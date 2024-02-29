<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>   
   
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.min.css"/>
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
    var cekhapus = '0';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 750,
            width: 550,
            modal: true,
            autoOpen:false
        });
            get_skpd();
        });    
     
     $(function(){ 

      // get_max_no();
        
     // $('#dinas').combogrid({  
     //   panelWidth:500,  
     //   idField:'kd_skpd',  
     //   textField:'kd_skpd',  
     //   mode:'remote',
     //   url:'<?php echo base_url(); ?>index.php/master/ambil_skpd',  
     //   columns:[[  
     //       {field:'kd_skpd',title:'Kode SKPD',width:100},  
     //       {field:'nm_skpd',title:'Nama SKPD',width:400}    
     //   ]],  
     //   onSelect:function(rowIndex,rowData){
     //       $("#nama_dinas").attr("value",rowData.nm_skpd.toUpperCase());                
     //   }  
     // });


     

     $('#kode_bank').combogrid({  
       panelWidth:500,  
       idField:'kode',  
       textField:'kode',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_bank',  
       columns:[[  
           {field:'kode',title:'Kode Bank',width:100},  
           {field:'nama',title:'Nama Bank',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nama_bank").attr("value",rowData.nama.toUpperCase());                
       }  
     });   
     
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/master/load_perusahaan',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"false",
        singleSelect:"true",
        autoRowHeight:"true",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'nama',
            title:'Nama Perusahaan',
            width:10,
            align:"left"},
            {field:'alamat',
            title:'Alamat Perusahaan',
            width:30,
            align:"left"},
            {field:'npwp',
            title:'NPWP Perusahaan',
            width:10,
            align:"left"},
            {field:'pimpinan',
            title:'Pimpinan',
            width:10,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          kode    = rowData.kode;
          nama    = rowData.nama;
          bentuk  = rowData.bentuk;
          alamat  = rowData.alamat;
          pimpinan= rowData.pimpinan;
          id_bank = rowData.id_bank;
          bank    = rowData.bank;
          rekening= rowData.rekening;
          npwp    = rowData.npwp;
          kd_skpd = rowData.kd_skpd;
          nm_skpd = rowData.nm_skpd;
          // alert(npwp);
          get(kode,nama,bentuk,alamat,pimpinan,id_bank,bank,rekening,npwp,kd_skpd,nm_skpd); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           cekhapus = rowData.cek;
           judul = 'Edit Data Perusahaan'; 
           edit_data();   
        }
        
        });
       
    });     

    function get(kode,nama,bentuk,alamat,pimpinan,id_bank,bank,rekening,npwp,kd_skpd,nm_skpd) {
         $("#nomor").attr("value",kode);
         $("#nama").attr("value",nama);
         $("#alamat").attr("value",alamat);
         $("#nama_bank").attr("value",bank);
         $("#pimpinan").attr("value",pimpinan);
         $("#nama_bank").attr("value",bank);
         $("#kode_bank").combogrid("setValue",id_bank);
         // $("#").combobox("setValue",id_bank);
         $("#rekening").attr("value",rekening);
         // $("#dinas").combobox("setValue",kd_skpd);
         $("#dinas").attr("value",kd_skpd);
         $("#npwp").attr("value",npwp); 

         $("#nama_dinas").attr("value",nm_skpd);                       
    }
       
    function kosong(){
        // $("#nomor").attr("value",'');
         $("#nama").attr("value",'');
         $("#alamat").attr("value",'');
         $("#nama_bank").attr("value",'');
         $("#pimpinan").attr("value",'');
         $("#nama_bank").attr("value",'');
         $("#kode_bank").combogrid("setValue",'');
         // $("#").combobox("setValue",'');
         $("#rekening").attr("value",'');
         // $("#dinas").combobox("setValue",'');
         // $("#dinas").attr("value",'');
         $("#npwp").attr("value",''); 

         // $("#nama_dinas").attr("value",nm_skpd);    
        
    }

    function get_max_no() {
      $.ajax({
        url:'<?php echo base_url(); ?>index.php/master/no_perusahaan',
        type: "POST",
        dataType:"json",                         
        success:function(data){
          $("#nomor").attr("value",data);
          // alert(data);
        }                                     
      });     
    }
    
    function get_skpd() {
            
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/rka_penetapan/config_skpd',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                $("#dinas").attr("value",data.kd_skpd);
                $("#nama_dinas").attr("value",data.nm_skpd);
                 }  
            });  
        }
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_perusahaan',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_perusahaan(){

        var cno = document.getElementById('nomor').value;
        var cnama = document.getElementById('nama').value;
        var calamat = document.getElementById('alamat').value;
        var ckodebank =  $('#kode_bank').combogrid('getValue');
        var cnama_bank = document.getElementById('nama_bank').value;
        var crekening = document.getElementById('rekening').value;
        var cnama_dinas = document.getElementById('nama_dinas').value;
        var cnpwp = document.getElementById('npwp').value;
        var cpimpinan = document.getElementById('pimpinan').value;
        var cdinas =  document.getElementById('dinas').value;
       
       // alert(cnip+'/'+cnama+'/'+cdinas+'/'+cjabat+'/'+cpang+'/'+ckode);
                
        if (cnama==''){
            alert('Nama Perusahaan Tidak Boleh Kosong ');
            return;
        } 
        if (calamat==''){
            alert('Alamat Perusahaan Tidak Boleh Kosong');
            exit();
        }
        if (ckodebank==''){
            alert('Isi Kode Bank Tidak Boleh Kosong');
            exit();
        }
        if (cdinas==''){
            alert('Silahkan Pilih Dinas');
            exit();
        }
        if (crekening==''){
            alert('No Rekening Bendahara atau Rekanan Wajib Di isi');
            exit();
        }
        if (cnpwp==''){
            alert('No NPWP Bendahara atau Rekanan Wajib di isi');
            exit();
        }
        if (cpimpinan==''){
            alert('Isi Nama Pimpinan Wajib di isi');
            exit();
        }

         
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(nama,alamat,bank,rekening,npwp,kd_skpd,id_bank,pimpinan)";
            lcvalues = "('"+cnama+"','"+calamat+"','"+cnama_bank+"','"+crekening+"','"+cnpwp+"','"+cdinas+"','"+ckodebank+"','"+cpimpinan+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master_perusahaan',
                    data: ({tabel:'ms_perusahaan',kolom:lcinsert,nilai:lcvalues,cno:'kode',kode:cno}),
                    dataType:"json",
                    success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            return;
                        }else if(status=='1'){
                            alert('Data Sudah Ada. Data Gagal Disimpan!!');
                            return;
                        }else{
                            alert('Data Tersimpan..!!');
                            $("#dialog-modal").dialog('close');
                            $('#dg').edatagrid('reload');                         
                        }
                    }
                });
            });   
           
        } else{
            
            lcquery = "UPDATE ms_perusahaan SET nama='"+cnama+"',alamat='"+calamat+"',pimpinan='"+cpimpinan+"',bank='"+cnama_bank+"',rekening='"+crekening+"',npwp='"+cnpwp+"',kd_skpd='"+cdinas+"',id_bank='"+ckodebank+"' where kode='"+cno+"'" ;

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master_perusahaan',
                data: ({st_query:lcquery,st_query1:lcquery}),
                dataType:"json",
                success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            return;
                        }else{
                            alert('Data Tersimpan..!!');
                            $("#dialog-modal").dialog('close');
                            $('#dg').edatagrid('reload');                         

                        }
                    }
            });
            });

        }
        
        
        //alert("Data Berhasil disimpan");

    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Perusahaan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Perusahaan';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
        get_max_no();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        var cnomor = document.getElementById('nomor').value;
        var cbidang = '1';
        if(cekhapus=='1'){
            alert('Gagal Hapus. nomor sudah dipakai di Inputan Penagihan.');
            return;
        }
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_perusahaan';
        $(document).ready(function(){
         $.post(urll,({cnid:cnomor}),
          function(data){
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
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 

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
<h3 align="center"><u><b><a>INPUTAN MASTER PERUSAHAAN</a></b></u></h3>
    <div align="center">
      <p align="left"><font color="red" style="font-size:20px">Perhatian!!!</font><br>
        <font color="red" style="font-size:15px"><ul align="left">
          <li>Pihak ketiga hanya diinput 1 kali saja, jadi silahkan cek terlebih dahulu apakah pihak ketiga sudah pernah diinput sebelumnya atau belum.</li>
        <li>Jika pihak ketiga terdapat beberapa kontrak, silahkan input data pihak ketiga 1 kali dan langsung input data kontrak di menu master kontrak.</li></ul></font>
      </p>
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
        <table id="dg" title="LISTING DATA PERUSAHAAN" style="width:900px;height:450px;" >  
        </table>
        </td>
        </tr>
    </table>    
    
        
 
    </p> 
    </div>   
</div>

<div id="dialog-modal" style="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
      <div class="row" style="width: 550px">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>No</label>  
              <input type="text" class="form-control" id="nomor" style="width:50px;"/ readonly="readonly">
            </div>
            <div class="form-group">
              <label>Kode SKPD/Unit</label>  
              <input type="text" class="form-control" id="dinas" style="width:500px;" readonly="true" />
            </div>
            <div class="form-group">
              <label>Nama SKPD/Unit</label>  
              <input type="text" class="form-control" id="nama_dinas" style="width:500px;"/ readonly="readonly">
            </div>
            <div class="form-group">
              <label>Nama Rekanan</label>  
              <input type="text" class="form-control" id="nama" name="nama" style="width:500px;"/>
            </div>
            <div class="form-group">
              <label>Alamat</label>  
              <textarea name="alamat" class="form-control" id="alamat" style="width:500px;" rows="3" ></textarea>
            </div>
            <div class="form-group">
              <label>Pimpinan</label>  
              <input type="text" id="pimpinan" class="form-control" name="pimpinan" style="width:500px;"/>
            </div>
            <div class="form-group">
              <label>Bank</label> <br> 
              <input type="text" id="kode_bank" style="width:50px;"/>&nbsp;
              <input type="text" id="nama_bank" readonly="true" style="width:400px;border:none"/>
            </div>
            <div class="form-group">
              <label>No. Rekening Bank</label>
              <input type="text" id="rekening" class="form-control" style="width:500px;"/>
            </div>
            <div class="form-group">
              <label>NPWP</label>  
              <input type="text" id="npwp" class="form-control" style="width:500px;"/>
              <input type="hidden" id="bentuk" name="bentuk" style="width:300px;"/>
            </div>
          </div>
          <div class="card-footer" align="center">
                <button class="btn btn-info"  plain="true" onclick="javascript:simpan_perusahaan();">Simpan</button>
                <button id="hapus" class="btn btn-danger" plain="true" onclick="javascript:hapus();">Hapus</button>
                <button class="btn btn-warning" plain="true" onclick="javascript:keluar();">Kembali</button>
          </div>
        </div>
      </div>
           
    </fieldset>
</div>

</body>

</html>