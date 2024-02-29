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
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
  
    <script type="text/javascript">
    
    var kode     = '';
    var giat     = '';
    var nomor    = '';
    var judul    = '';
    var cid      = 0;
    var lcidx    = 0;
    var lcstatus = '';
    var pidx     = 0;
                    
    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height   : 600,
            width    : 1000,
            modal    : true,
            autoOpen : false
        });
     });    

     
     $(function(){  
     $('#rek5').combogrid({  
       panelWidth : 500,  
       idField    : 'kd_rek5',  
       textField  : 'kd_rek5',  
       mode       : 'remote',
       url        : '<?php echo base_url(); ?>index.php/master/ambil_rekening5_ar',  
       columns    : [[  
           {field:'kd_rek5',title:'Kode Rekening',width:100},  
           {field:'nm_rek5',title:'Nama Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_rek5 = rowData.kd_rek5;
            $("#nm_u").attr("value",rowData.nm_rek5.toUpperCase());
       }  
     });     
        
        
     
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_ar',
        idField       : 'id',            
        rownumbers    : "true", 
        fitColumns    : "true",
        singleSelect  : "true",
        autoRowHeight : "false",
        loadMsg       : "Tunggu Sebentar....!!",
        pagination    : "true",
        nowrap        : "true",                       
        columns       : [[
            {field:'kd_rek5',
    		title:'Kode Rekening',
    		width:25,
            align:"left"},
            {field:'nm_rek5',
    		title:'Nama Rekening',
    		width:200,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_s  = rowData.kd_rek5;
          nm_s  = rowData.nm_rek5;
          get(kd_s,nm_s); 
          lcidx = rowIndex;  
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Urusan'; 
           edit_data();   
           load_detail();
        }
        });

        
    $('#dg2').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_detail_ar',
        idField       :'id',
        toolbar       :"#toolbar",              
        rownumbers    :"true", 
        fitColumns    :false,
        autoRowHeight :"false",
        singleSelect  :"true",
        nowrap        :"false",          
        columns       : [[{field:'id',        title:'id',           width:70, align:"left",hidden:"true"},
                          {field:'no_urut',   title:'No Urut',      width:70, align:"left",hidden:"true"},
                          {field:'kd_rek5',   title:'Rekening',     width:80, align:"left",hidden:"true"},
                          {field:'uraian',    title:'Uraian',       width:295,align:"left"},
                          {field:'merk',      title:'Merk',         width:100,align:"left"},
                          {field:'satuan',    title:'Satuan',       width:100,align:"left"},
                          {field:'harga',     title:'Harga',        width:150,align:"right"},
                          {field:'keterangan',title:'Keterangan',   width:200,align:"left"},
                          {field:'hapus',     title:'Hapus',        width:70, align:"center",
                            formatter:function(value,rec){ 
                            return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                          }
                          }
                         ]]
        });
    });        

    
    function get(kd_s,nm_s) {
        $("#rek5").combogrid("setValue",kd_s);
        $("#nm_u").attr("value",nm_s);  
        $("#rek5_hide").attr("value",kd_s);
    }
       
    
    function kosong(){
        $("#rek5").combogrid("setValue",'');
        $("#nm_u").attr("value",'');
        $("#rek5_hide").attr("value",'');
        load_detail();
    }
    
    
    function kosong_detail(){
        $("#uraian").attr('value','') ;
        $("#merk").attr('value','') ;
        $("#satuan").attr('value','') ;
        $("#harga").attr('value',0) ;
        $("#ket").attr('value','') ;
    }
    

    function muncul(){
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
		url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_ar',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    
    function simpan(){
       
        var crek5      = $('#rek5').combogrid('getValue');
        var cnmrek5    = document.getElementById('nm_u').value;
        var crek5_hide = document.getElementById('rek5_hide').value;
       
        if (crek5==''){
            alert('Kode Rekening Tidak Boleh Kosong');
            exit();
        } 

        if (cnmrek5==''){
            alert('Nama Rekening Tidak Boleh Kosong');
            exit();
        }

        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kd_rek5,nm_rek5)";
            lcvalues = "('"+crek5+"','"+cnmrek5+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type      : "POST",
                    url       : '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data      : ({tabel:'trhharga',kolom:lcinsert,nilai:lcvalues,cid:'kd_rek5',lcid:crek5}),
                    dataType  : "json",
                    success   : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else if(status=='1'){
                            alert('Data Sudah Ada..!!');
                            exit();
                        }else{
                            detsimpan();
                            alert('Data Tersimpan..!!');
                            exit();
                        }
                    }
                });
            });   
           
        } else {
            
            lcquery = "UPDATE trhharga SET kd_rek5='"+crek5+"', nm_rek5='"+cnmrek5+"' where kd_rek5='"+crek5_hide+"'";

            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/master/update_master_ar',
                data     : ({st_query:lcquery,tabel:'trhharga',cid:'kd_rek5',lcid:crek5,lcid_h:crek5_hide}),
                dataType : "json",
                success  : function(data){
                           status = data;
                        
                        if ( status=='1' ){
                            alert('Kode Rekening Sudah Terpakai...!!!');
                            exit();
                        }
                        
                        if ( status=='2' ){
                            detsimpan();
                            alert('Data Tersimpan...!!!');
                            lcstatus = 'edit';
                            load_detail();
                            exit();
                        }
                        
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                    }
            });
            });
        }
        $('#dg').edatagrid('reload'); 
    } 
    
    
    function edit_data(){
        lcstatus = 'edit';
        judul    = 'Edit Data Standar Harga';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        }    
    
    
    function tambah(){
        lcstatus = 'tambah';
        judul    = 'Input Data Standar Harga';
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
        
        var crek5 = $('#rek5').combogrid('getValue');
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
         $(document).ready(function(){
         $.post(urll,({tabel:'trhharga',cnid:crek5,cid:'kd_rek5'}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                hapus_detail_all();
                alert('Data Berhasil Dihapus..!!');
                kosong();
                $("#dialog-modal").dialog('close');
                exit();
            }
         });
        });    
    } 
    
    
    function hapus_detail_all(){
        
        var crek5 = $('#rek5').combogrid('getValue');
        var urll  = '<?php echo base_url(); ?>index.php/master/hapus_detail_all';
         $(document).ready(function(){
         $.post(urll,({tabel:'trdharga',cnid:crek5,cid:'kd_rek5'}),function(data){
            status = data;
            if ( status == '0'){
                alert("Gagal Hapus Detail...!!!")
            }
         });
         });    
    } 
    
    
    function hapus_detail(){
        
        var a    = $("#rek5").combogrid("getValue") ;
        var rows = $('#dg2').edatagrid('getSelected');
        
        bkdrek   = rows.kd_rek5 ;
        buraian  = rows.uraian ;
        bmerk    = rows.merk ;
        bharga   = rows.harga ;
        burut    = rows.no_urut ;
        
        var idx  = $('#dg2').edatagrid('getRowIndex',rows);
        var tny  = confirm('Yakin Ingin Menghapus Data, '+buraian+'  Merk :  '+bmerk+'  Harga  '+bharga+' ?');
        
        if ( tny == true ) {
            
            $('#dg2').datagrid('deleteRow',idx);     
            $('#dg2').datagrid('unselectAll');
              
             var urll = '<?php  echo base_url(); ?>index.php/master/hapus_detail';
             $(document).ready(function(){
             $.post(urll,({ckdrek:bkdrek,curaian:buraian,cmerk:bmerk,charga:bharga,curut:burut}),function(data){
             status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    alert('Data Telah Terhapus..!!');
                    detsimpan();
                    load_detail();
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
    
    
    function enter(ckey,_cid){
        if (ckey==13)
        	{    	       	       	    	   
            	document.getElementById(_cid).focus();
                if(_cid=='uraian'){       
                   append_save();
                }
        	}     
    }
    
    
    function append_save() {
            
            $('#dg2').datagrid('selectAll');
            var rows  = $('#dg2').datagrid('getSelections');
                jgrid = rows.length - 1 ;
                pidx  = jgrid + 1 ;
            
            var vkdrek  = $("#rek5").combogrid('getValue') ;
            var vurai   = document.getElementById('uraian').value ;
            var vmerk   = document.getElementById('merk').value ;
            var vsatuan = document.getElementById('satuan').value ;
            var vharga  = document.getElementById('harga').value ;
            var vket    = document.getElementById('ket').value ;
            
            if ( vkdrek=='' ){
                alert('Pilih Rekening Terlebih Dahulu...!!!');
                document.getElementById('rek5').focus ;
                exit();
            }

            $('#dg2').edatagrid('appendRow',{kd_rek5:vkdrek,uraian:vurai,merk:vmerk,satuan:vsatuan,harga:vharga,keterangan:vket,id:pidx,no_urut:pidx});
            $("#dg2").datagrid("unselectAll");
            kosong_detail();
            
       }
       
       
    function detsimpan() {
        
        var crek5_hide = document.getElementById('rek5_hide').value ;
        var crek5      = $("#rek5").combogrid("getValue") ;
        var csql       = '' ; 
        
        $('#dg2').datagrid('selectAll');
        var rows = $('#dg2').datagrid('getSelections');
        
        for(var i=0;i<rows.length;i++){            
            cidx    = rows[i].id;
            curut   = rows[i].no_urut;
            ckdrek  = rows[i].kd_rek5;
            curaian = rows[i].uraian;
            cmerk   = rows[i].merk;
            csatuan = rows[i].satuan;
            charga  = angka(rows[i].harga);
            cket    = rows[i].keterangan;
            
             if ( i > 0 ) {
                csql = csql+","+"('"+i+"','"+crek5+"','"+curaian+"','"+cmerk+"','"+csatuan+"','"+charga+"','"+cket+"')";
             } else {
                csql = "values('"+i+"','"+crek5+"','"+curaian+"','"+cmerk+"','"+csatuan+"','"+charga+"','"+cket+"')";                                            
             }
        }
        
        $(document).ready(function(){
        $.ajax({
              type     : "POST",   
              dataType : 'json',                 
              data     : ({tabel_detail:'trdharga',sql_detail:csql,proses:'detail',nomor:crek5_hide}),
              url      : '<?php echo base_url(); ?>/index.php/master/simpan_detail_standar_harga',
              success  : function(data){                        
                              status = data;   
                              if ( status=='0' ) {               
                                  alert('Data Detail Gagal Tersimpan');
                              } else if ( status=='1' ) {               
                                  alert('Data Detail Berhasil Tersimpan');
                              } 
                         }
            });
        });            

        $("#rek5_hide").attr("Value",crek5) ;
        $('#dg2').edatagrid('unselectAll');
    
    } 
    
    
    function load_detail() {
        
        var crekening = $("#rek5").combogrid("getValue") ;
        $('#dg2').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_detail',
        queryParams   : ({rekening:crekening}),
        idField       : 'id',
        toolbar       : "#toolbar",              
        rownumbers    : "true", 
        fitColumns    : "false",
        autoRowHeight : "false",
        singleSelect  : "true",
        nowrap        : "false",          
        columns       : [[{field:'id',        title:'id',           width:70, align:"left",hidden:"true"},
                          {field:'no_urut',   title:'No Urut',      width:70, align:"left",hidden:"true"},
                          {field:'kd_rek5',   title:'Rekening',     width:80, align:"left",hidden:"true"},
                          {field:'uraian',    title:'Uraian',       width:295,align:"left"},
                          {field:'merk',      title:'Merk',         width:100,align:"left"},
                          {field:'satuan',    title:'Satuan',       width:100,align:"left"},
                          {field:'harga',     title:'Harga',        width:150,align:"right"},
                          {field:'keterangan',title:'Keterangan',   width:200,align:"left"},
                          {field:'hapus',     title:'Hapus',        width:70, align:"center",
                          formatter:function(value,rec){ 
                          return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                          }
                          }
                         ]]
        });
    }
    
    
    function insert_row() {

        var rows     = $('#dg2').edatagrid('getSelected');
        var idx_ins  = $('#dg2').edatagrid('getRowIndex',rows);
        
        if ( idx_ins == -1){
            alert("Pilih Lokasi Insert Terlebih Dahulu...!!!") ;
            exit();
        }

        $('#dg2').datagrid('selectAll');
        var rows_grid = $('#dg2').datagrid('getSelections');
        for ( var i=idx_ins; i<rows_grid.length; i++ ) {            
              $('#dg2').edatagrid('updateRow',{index:i,row:{id:i+1,no_urut:i+1}});
        }
        $('#dg2').datagrid('unselectAll');
           
        var vkdrek  = $("#rek5").combogrid('getValue') ;
        var vurai   = document.getElementById('uraian').value ;
        var vmerk   = document.getElementById('merk').value ;
        var vsatuan = document.getElementById('satuan').value ;
        var vharga  = document.getElementById('harga').value ;
        var vket    = document.getElementById('ket').value ;
            
        if ( vkdrek=='' ){
             alert('Pilih Rekening Terlebih Dahulu...!!!');
             document.getElementById('rek5').focus ;
             exit();
        }

        $('#dg2').edatagrid('insertRow',{index:idx_ins,row:{kd_rek5:vkdrek,uraian:vurai,merk:vmerk,satuan:vsatuan,harga:vharga,keterangan:vket,id:idx_ins,no_urut:idx_ins}});
        $("#dg2").datagrid("unselectAll");
        kosong_detail();
            
    }

  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>INPUTAN MASTER STANDAR HARGA</a></b></u></h3>
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
        <table id="dg" title="LISTING DATA STANDAR HARGA" style="width:900px;height:440px;" >  
        </table>
        </td>
        </tr>
    </table>  
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">

    <!--<p class="validateTips">Semua Inputan Harus Di Isi.</p>--> 
    
    <table align="center" style="width:100%;" border="0">
       <tr>
           <td width="10%">REKENING</td>
           <td width="1%">:</td>
           <td><input type="text" id="rek5" style="width:100px;"/>&nbsp;&nbsp;<input type="text" id="nm_u" style="width:510px;border:0px;" /> <input type="hidden" id="rek5_hide" style="width:100px;"/></td>  
      </tr> 
    </table>       
    
    
    <table align="center" style="width:100%;" border="0">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="color:#004080;font-size:17px;font:bolder;" align='left'><B>INPUT DETAIL RINCIAN</B></td>
        </tr> 
    </table>
    
    <fieldset style="width:927px;">
        <table align="center" style="width:100%;" border="0">
                <tr>
                    <td width='10%'>Uraian</td>
                    <td width="1%">:</td>
                    <td>&nbsp;<input type="text" id="uraian" style="width:800px;" onkeypress="javascript:enter(event.keyCode,'merk');"/></td>  
                </tr> 
                <tr>
                    <td>Merk</td>
                    <td width="1%">:</td>
                    <td>&nbsp;<input type="text" id="merk" style="width:200px;" onkeypress="javascript:enter(event.keyCode,'satuan');"/></td>  
                </tr>
                <tr>
                    <td>Satuan</td>
                    <td width="1%">:</td>
                    <td>&nbsp;<input type="text" id="satuan" style="width:200px;" onkeypress="javascript:enter(event.keyCode,'harga');"/></td>  
                </tr>  
                <tr>
                    <td>Harga</td>
                    <td width="1%">:</td>
                    <td>&nbsp;<input type="text" id="harga" style="width:200px;text-align:right;" onkeypress="javascript:enter(event.keyCode,'ket');return(currencyFormat(this,',','.',event))"/></td>  
                </tr> 
                <tr>
                    <td>Keterangan</td>
                    <td width="1%">:</td>
                    <td>&nbsp;<input type="text" id="ket" style="width:800px;" onkeypress="javascript:enter(event.keyCode,'uraian');"/></td>  
                </tr> 
    
        </table>       
    </fieldset>
   
    <table style="width:950px;" border='0'>
            <tr>
                <td align="left">
                        <a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:insert_row();">Insert</a>
                </td>

                <td align="right">
                    <a class="easyui-linkbutton" iconCls="icon-add"    plain="true" onclick="javascript:tambah();">Baru</a>
                    <a class="easyui-linkbutton" iconCls="icon-save"   plain="true" onclick="javascript:simpan();">Simpan</a>
        		    <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                    <a class="easyui-linkbutton" iconCls="icon-undo"   plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
             </tr>
             <table id="dg2" title="Listing Data" style="width:950px;height:280px;" >  
             </table>
    </table>
    
</div>
</body>
</html>