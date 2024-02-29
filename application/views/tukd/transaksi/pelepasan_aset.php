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
            height: 450,
            width: 900,
            modal: true,
            autoOpen:false,
        });
        get_skpd(); 
        });    
     
  
    
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_lepas',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_po',
    		title:'Nomor',
    		width:50,
            align:"center"},
            {field:'tgl_po',
    		title:'Tanggal',
    		width:30},
            {field:'kd_skpd',
    		title:'S K P D',
    		width:30,
            align:"center"},
            {field:'kd_rek5',
    		title:'Rekening',
    		width:50,
            align:"center"},
            {field:'nilai',
    		title:'Nilai',
    		width:50,
            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor = rowData.no_po;
          tgl   = rowData.tgl_po;
          kode  = rowData.kd_skpd;
          lcket = rowData.keterangan;
          lcrek = rowData.kd_rek5;
          lcaset = rowData.kd_aset;
          lcnilai = rowData.nilai;
          lcidx = rowIndex;
          //alert(lcaset);
          get(nomor,tgl,kode,lcket,lcrek,lcaset,lcnilai);   
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Pemakaian'; 
           edit_data();   
        }
        
        });
        
         $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
    

                  
         $('#rek').combogrid({  
           panelWidth:700,  
           idField:'kd_rek5',  
           textField:'kd_rek5',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tagih/'+'137',             
           columns:[[  
               {field:'kd_rek5',title:'Kode Rekening',width:140},  
               {field:'nm_rek5',title:'Uraian',width:700},
              ]],              
               onSelect:function(rowIndex,rowData){
               kode_lo = rowData.kd_rek_lo;
               //alert(kode_lo);
               $("#nmrek").attr("value",rowData.nm_rek5.toUpperCase());
               $("#rek1").attr("value",rowData.kd_rek_lo);
              }    
            });
            
         $('#rek2').combogrid({  
           panelWidth:700,  
           idField:'kd_rek5',  
           textField:'kd_rek5',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tagih/'+'13',             
           columns:[[  
               {field:'kd_rek5',title:'Kode Rekening',width:140},  
               {field:'nm_rek5',title:'Uraian',width:700},
              ]],              
               onSelect:function(rowIndex,rowData){
               kode_lo = rowData.kd_rek_lo;
               //alert(kode_lo);
               $("#nmrek2").attr("value",rowData.nm_rek5.toUpperCase());
               $("#rek3").attr("value",rowData.kd_rek_lo);
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
        								$("#skpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
        								kode = data.kd_skpd;
                                        
        							  }                                     
        	});
             
        }      

     function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });   
     }

    
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
         });
     }
    
       
       
    function get(nomor,tgl,kode,lcket,lcrek,lcaset,lcnilai){
        $("#nomor").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        //$("#skpd").combogrid("setValue",kode);       
        //$('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tetap/'+kode});
        $("#rek").combogrid("setValue",lcrek);
        $("#rek2").combogrid("setValue",lcaset);
        $("#nilai").attr("value",lcnilai);
        $("#ket").attr("value",lcket);
        
                
    }
    
    function kosong(){
        $("#nomor").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        //$("#skpd").combogrid("setValue",'');
        $("#rek").combogrid("setValue",'');
        $("#rek2").combogrid("setValue",'');
        //$("#nmskpd").attr("value",'');
        $("#nmrek").attr("value",'');
        $("#nmrek2").attr("value",'');
        $("#nilai").attr("value",'');        
        $("#ket").attr("value",''); 
       
    }
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_pakai',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_lepas(){
        var cno = document.getElementById('nomor').value;
        var ctgl = $('#tanggal').datebox('getValue');
        var cskpd = document.getElementById('skpd').value;
        var lckdaset = $('#rek2').combogrid('getValue');
        var lcket = document.getElementById('ket').value;
        var lntotal = angka(document.getElementById('nilai').value);
            lctotal = number_format(lntotal,0,'.',',');
        var o       = document.getElementById('status1').checked; 
        var p       = document.getElementById('status2').checked; 
        var q       = document.getElementById('status3').checked; 
        
		if (o==false && p==false && q==false ){
            alert('Jenis Tidak Boleh Kosong');
            exit();
        } 
		
		if ((o==true && p==true) || (o==true && q==true) || (p==true && q==true) || (o==true && p==true && q==true)){
            alert('Jenis Tidak Boleh Lebih dari Satu');
            exit();
        } 
		
		if(o==true){
			jenis='1';
		}
		if(p==true){
			jenis='2';
		}
		if(q==true){
			jenis='3';
		}
		if (cno==''){
            alert('Nomor PO Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal PO Tidak Boleh Kosong');
            exit();
        }
        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        
        //$(document).ready(function(){
//            $.ajax({
//                type: "POST",
//                url: '<?php echo base_url(); ?>/index.php/tukd/simpan_tetap',
//                data: ({tabel:'tr_tetap',no:cno,tgl:ctgl,skpd:cskpd,ket:lcket,kdrek:lckdrek,nilai:lntotal}),
//                dataType:"json"
//            });
//        });
        
       // if(lcstatus=='tambah'){ 
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/tukd/simpan_pakai',
                    data: ({tabel:'tr_lepas',no:cno,tgl:ctgl,skpd:cskpd,ket:lcket,kdrek:lckdrek,kdaset:lckdaset,rek:rek_lo,nilai:lntotal}),
                    dataType:"json"
                });
            });    
            
            
        //$('#dg').datagrid('appendRow',{no_po:cno,tgl_po:ctgl,kd_skpd:cskpd,kd_rek5:lckdrek,nilai:lctotal,keterangan:lcket});
        //} else {
//            
//            lcquery = "UPDATE tr_pakai SET tgl_po='"+ctgl+"',kd_skpd='"+cskpd+"',kd_rek5='"+lckdrek+"',kd_rek_lo='"+rek_lo+"',nilai="+
//                       lntotal+",kd_aset='"+lckdaset+"',keterangan='"+lcket+"' where no_po='"+cno+"'";
//            
//            //alert(lcquery);
//            $(document).ready(function(){
//            $.ajax({
//                type: "POST",
//                url: '<?php echo base_url(); ?>/index.php/tukd/update_pakai',
//                data: ({st_query:lcquery}),
//                dataType:"json"
//            });
//            });
//            
//            
//            
//                $('#dg').datagrid('updateRow',{
//            	index: lcidx,
//            	row: {
//            		no_po: cno,
//            		tgl_po: ctgl,
//                    kd_skpd: cskpd,
//                    kd_rek5: lckdrek,
//                    nilai: lctotal,
//                    keterangan:lcket                    
//            	}
//            });
//        }
        
        
        alert("Data Berhasil disimpan");
        //$("#dialog-modal").dialog('close');
        //section1();
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Pelepasan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'INPUTAN PELEPASAN ASET';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
         $('#dg').edatagrid('reload');               

     }    
    
     function hapus(){
      //  var cnomor = document.getElementById('nomor').value;
//        var cskpd = $('#skpd').combogrid('getValue');
        
        
        //alert(cnomor+cskpd);
        var urll = '<?php echo base_url(); ?>index.php/tukd/hapus_pakai';
        $(document).ready(function(){
         $.post(urll,({no:nomor,skpd:kode}),function(data){
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
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN PELEPASAN BARANG</a></b></u></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="INPUTAN PELEPASAN BARANG" style="width:870px;height:450px;" >  
        </table>
 
    </p> 
    </div>   

</div>

</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td width="25%">No. PELEPASAN</td>
                <td width="1%"></td>
              <td width="74%"><input type="text" id="nomor" style="width: 200px;"/></td>  
            </tr>            
            <tr>
                <td>Tanggal </td>
                <td></td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>
            </tr>
            <tr>
                <td>S K P D</td>
                <td></td>
                <td><input id="skpd" name="skpd" style="width: 140px;" />  <input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
			 <tr>
                <td>Jenis</td>
                <td></td>
                <td><input type="checkbox" id="status1"/>Aset Lain &nbsp; &nbsp; &nbsp; <input type="checkbox" id="status2"/>Pemusnahan &nbsp; &nbsp; &nbsp; <input type="checkbox" id="status3"/>Penjualan</td>                            
            </tr>
            <tr>
                <td>Rekening Aset/Kas </td>
                <td></td>
                <td><input id="rek2" name="rek2" style="width: 140px;" />
                  <input type="hidden" id="rek3" style="width: 140px;" readonly="true"/>
                 <input type="text" id="nmrek2" style="border:0;width: 600px;" readonly="true"/></td>                
            </tr>            
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 200px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            <tr>
                <td>Rekening Akumulasi </td>
                <td></td>
                <td><input id="rek" name="rek" style="width: 140px;" /> <input type="hidden" id="rek1" style="width: 140px;" readonly="true"/>
                 <input type="text" id="nmrek" style="border:0;width: 600px;" readonly="true"/></td>                
            </tr> 
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea rows="2" cols="50" id="ket" style="width: 740px;"></textarea>
                </td> 
            </tr>
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_lepas();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>


  	
</body>

</html>