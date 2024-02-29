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
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
    

     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 300,
            width: 800,
            modal: true,
            autoOpen:false
        });
        get_skpd();
        });    
     
     $(function(){        
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_rek_bank',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"false",
        nowrap:"true",                       
        columns:[[
    	    {field:'rekening',
    		title:'Rekening',
    		width:15,
            align:"center"},
            {field:'nm_rekening',
    		title:'A.N Rekening',
    		width:40},
            {field:'nm_jenis',
    		title:'Jenis Rekening',
    		width:20,
            align:"left"},
            {field:'ket',
    		title:'Keterangan',
    		width:20,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          kd = rowData.rekening;
          nm = rowData.nm_rekening;
          bnk = rowData.bank;
          jns = rowData.jenis;
          ket = rowData.ket;
          lcidx = rowIndex; 
          get(kd,nm,bnk,jns,ket);  
                                                   
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data'; 
           edit_data();              
        }
        
        });
       
       $('#srekening_bank').combogrid({  
           panelWidth:700,  
           idField:'kode',  
           textField:'kode',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/load_bank',
           columns:[[  
               {field:'kode',title:'Kode',width:150},  
               {field:'nama',title:'Nama',width:500}    
           ]],  onSelect:function(rowIndex,rowData){
                    $("#srekening_nmbank").attr("value",rowData.nama);
                    }   
           });
       
    });        

 
    function get_skpd()
        {
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                                        kd_skpd = data.kd_skpd;
                                        $("#nmskpd").attr("value",data.nm_skpd.toUpperCase());
                                        $("#sskpd").attr("value",data.kd_skpd);
                                        
                                      }                                     
            });
        }

    function get(kd,nm,bnk,jns,ket) {
        
        $("#srekening").attr("value",kd);
        $("#srekening_nm").attr("value",nm);     
        $("#srekening_bank").combogrid("setValue",bnk);
        $("#kdjenis").combobox("setValue",jns);
        $("#sket").attr("value",ket);                                        
    }
       
    function kosong(){
        $("#srekening").attr("value",'');
        $("#srekening_nm").attr("value",'');
        $("#srekening_bank").combogrid("setValue",'');
        $("#srekening_nmbank").attr("value",'');
        $("#kdjenis").combobox("setValue",''); 
        $("#sket").attr("value",'');        
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_rek_bank',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_rekening(){
       var cskpd = document.getElementById('sskpd').value;
        var crek = document.getElementById('srekening').value;
        var crek_nm2 = document.getElementById('srekening_nm').value;        
        var crek_bank = $("#srekening_bank").combogrid("getValue");    
        var cket = document.getElementById('sket').value;    
        var cjenis = $("#kdjenis").combobox("getValue");

        var crek_nm = crek_nm2.replace("'", "''");  
        
        if (crek==''){
            alert('Rekening Tidak Boleh Kosong');
            exit();
        } 
        if (crek_nm==''){
            alert('Nama Rekeninig Tidak Boleh Kosong');
            exit();
        }
        if (crek_bank==''){
            alert('Rekening Bank Tidak Boleh Kosong');
            exit();
        }
        if (cjenis==''){
            alert('Jenis Rekening Tidak Boleh Kosong');
            exit();
        }        
        
        if(lcstatus=='tambah'){        
            
            lcinsert = "(rekening,nm_rekening,bank,kd_skpd,jenis,keterangan)";
            lcvalues = "('"+crek+"','"+crek_nm+"','"+crek_bank+"','"+cskpd+"','"+cjenis+"','"+cket+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master_cek',
                    data: ({tabel:'ms_rekening_bank',kolom:lcinsert,nilai:lcvalues,cid:'rekening',lcid:crek}),
                    dataType:"json",
                    
                    success  : function(data){                        
                        status = data.pesan; 
                         if (status=='2'){               
                            alert('Data Berhasil Tersimpan...!!!');
                        }else if (status=='1'){               
                            alert('Data Sudah Ada...!!!');
                        } else{ 
                            alert('Data Gagal Tersimpan...!!!');
                        }                                             
                    }

                });
            });              
           
        } else{
            
            lcquery = "UPDATE ms_rekening_bank SET nm_rekening='"+crek_nm+"', bank='"+crek_bank+"', jenis='"+cjenis+"', keterangan='"+cket+"' where rekening='"+crek+"' and kd_skpd='"+cskpd+"'";
            
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master',
                data: ({st_query:lcquery}),
                dataType:"json"
            });
            });
            
            
        }
        
        
       // alert("Data Berhasil disimpan");
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 

    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("srekening").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data';
        $("#dialog-modal").dialog({ title: judul });        
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("srekening").disabled=false;
        document.getElementById("srekening").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        var ckode = document.getElementById('srekening').value;
        
        var del=confirm('Anda yakin akan menghapus data rekening '+ckode+'?');
                
                if  (del==true){
        var ckode = document.getElementById('srekening').value;
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master_rek';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_rekening_bank',cnid:ckode,cid:'rekening'}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                keluar();
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
    
    function cek_huruf(b){
        b.value = b.value.toUpperCase();                   
    }
    
    function cek_angka(a){
        if(!/^[0-9.]+$/.test(a.value))
	   {
	       a.value = a.value.substring(0,a.value.length-1000);
	   } 
    }
  
   </script>

</head>
<body>

<div id="content"> 

<h3 align="center"><u><b><a>INPUTAN MASTER REKENING BANK</a></b></u></h3>
    <div align="center"> 
    <table style="width:400px;" border="0">
        <tr>
            <!--<td width="10%"></td>--> 
            <td width="5%" colspan="2"><a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah</a></td>
            <td width="5%" colspan="2"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
            <input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA" style="width:900px;height:440px;" >  
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
                <td width="30%">SKPD</td>
                <td width="1%">:</td>
                <td><input type="text" id="sskpd" readonly="true" style="width:100px;border: 0;"/><input type="text" id="nmskpd" readonly="true" style="width:350px;border: 0;"/></td>  
            </tr> 
           <tr>
                <td width="30%">REKENING</td>
                <td width="1%">:</td>
                <td><input type="text" id="srekening" onkeyup="javascript:cek_angka(this);" style="width:200px;"/></td>  
            </tr> 
           <tr>
                <td width="30%">A.N REKENING</td>
                <td width="1%">:</td>
                <td><input type="text" id="srekening_nm" style="width:400px;"/></td>  
            </tr>            
            <tr>
                <td width="30%">BANK</td>
                <td width="1%">:</td>
                <td><input type="text" id="srekening_bank" style="width:100px;"/>&nbsp;<input type="text" readonly="true" id="srekening_nmbank" style="width:200px;border: 0;"/></td>  
            </tr>
            <tr>
                <td width="30%">JENIS</td>
                <td witdh="1%">:</td>
                <td><input id="kdjenis" style="width:250px;" class="easyui-combobox" data-options="
            		valueField: 'value',
            		textField: 'label',
            		data: [{
            			label: '',
            			value: ''
            		},{
            			label: 'Rekening Pegawai',
            			value: '1'
            		},{
            			label: 'Rekening Rekanan Pihak Ketiga',
            			value: '2'
            		},{
            			label: 'Rekening Penampung Pajak',
            			value: '3'
            		}]"/></td>
            </tr>
            <tr>
                <td width="30%">KETERANGAN TAMBAHAN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Pihak Ketiga, dll)</td>
                <td width="1%">:</td>
                <td><input type="text" id="sket" onkeyup="javascript:cek_huruf(this);" style="width:400px;"/></td>  
            </tr>
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_rekening();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>