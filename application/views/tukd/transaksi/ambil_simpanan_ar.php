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
    var cid      = 0 ;
    var lcidx    = 0 ;
    var lcstatus = '';
    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 500,
            width: 900,
            modal: true,
            autoOpen:false,
        });
        get_skpd();
		get_tahun();
        });    
     

     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tunai/load_ambilsimpanan',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_kas',
    		title:'Nomor Kas',
    		width:50,
            align:"center"},
            {field:'tgl_kas',
    		title:'Tanggal Kas',
    		width:30,
            align:"center"},
            {field:'kd_skpd',
    		title:'S K P D',
    		width:30,
            align:"center"},
            {field:'nilai',
    		title:'N I L A I',
    		width:50,
            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor   = rowData.no_kas;
          tgl     = rowData.tgl_kas;
          no_bukti   = rowData.no_bukti;
          tgl_bukti     = rowData.tgl_bukti;
          kode    = rowData.kd_skpd;
          lnnilai = rowData.nilai;
          lcbank  = rowData.bank;
          lcnmrek = rowData.nm_rekening;
          lcket   = rowData.keterangan;
          lcidx   = rowIndex;
          get(nomor,tgl,no_bukti,tgl_bukti,kode,lnnilai,lcbank,lcnmrek,lcket);   
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Penetapan'; 
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
            },
           onSelect: function(date){
				var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
        $("#tgl_bukti").datebox("setValue",y+'-'+m+'-'+d);
	       }
        });
        
        $('#tgl_bukti').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
		      jaka1 = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
	       }
        });
        
        $('#skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tunai/skpd_2',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd;               
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
           }  
        });
   
        
        $('#bank').combogrid({  
           panelWidth:700,  
           idField:'kode',  
           textField:'kode',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tunai/config_bank_simpanan',  
           columns:[[  
               {field:'kode',title:'Kode Bank',width:100},  
               {field:'nama',title:'Nama Bank',width:700}    
           ]],  
        //    onSelect:function(rowIndex,rowData){
        //        kode = rowData.kode;               
        //        $("#nmbank").attr("value",rowData.nama.toLowerCase());
        //    }  
        });

    });        

    
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
     
     function get_skpd()
     {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka_penetapan/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        		                        $("#skpd").combogrid("setValue",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
        							  }                                     
     });
     }
	  function get_tahun() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tunai/config_tahun',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
        	});
             
        }
       
    function get(nomor,tgl,no_bukti,tgl_bukti,kode,lnnilai,lcbank,lcnmrek,lcket){
        $("#nomor").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#no_bukti").attr("value",no_bukti);
        $("#tgl_bukti").datebox("setValue",tgl_bukti);
        $("#skpd").combogrid("setValue",kode); 
        $("#nilai").attr("value",lnnilai);
        $("#bank").combogrid("setValue",lcbank);
        $("#nmrek").attr("value",lcnmrek);
        $("#ket").attr("value",lcket);
        $("#skpd").combogrid('disable');
		load_sisa();
    }
    
    function kosong(){
        $("#nomor").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#no_bukti").attr("value",'');
        $("#tgl_bukti").datebox("setValue",'');
        $("#nilai").attr("value",'');
        $("#bank").combogrid("setValue",'');
        $("#nmbank").attr("value",'');
        $("#nmrek").attr("value",'');
        $("#ket").attr("value",'');
        $("#skpd").combogrid('disable');
		load_sisa();
		get_nourut();

    }
    
    function load_sisa(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tunai/load_sisa_bank",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#sisa_uang").attr("value",n['sisa']);
                   // $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
        });
    }
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tunai/cari_ambilsimpanan',
        queryParams:({cari:kriteria})
        });        
     });
     }
    
    
    
     function simpan_ambilsmp(){
        
        var cno     = document.getElementById('nomor').value;
        var cno_bukti     = document.getElementById('no_bukti').value;
        var ctgl    = $('#tanggal').datebox('getValue');
        var ctgl_bukti    = $('#tgl_bukti').datebox('getValue');
        var cskpd   = $('#skpd').combogrid('getValue');
        var lnnilai = angka(document.getElementById('nilai').value);
		var sisa_bank = angka(document.getElementById('sisa_uang').value);
        var cbank   = $('#bank').combogrid('getValue');
        var cnmrek  = document.getElementById('nmrek').value;
        var lcket   = document.getElementById('ket').value;
        var tahun_input = ctgl.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			alert('Tahun tidak sama dengan tahun Anggaran');
			exit();
		}
		if (lnnilai>sisa_bank){
            alert('Nilai Lebih Besar dari Sisa Bank');
            exit();
        }
		
        if (cno==''){
            alert('Nomor  Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal  Tidak Boleh Kosong');
            exit();
        }
        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        
        if (lcstatus=='tambah'){ 
                    
                    lcinsert = "(no_kas,tgl_kas,no_bukti,tgl_bukti,kd_skpd,nilai,bank,nm_rekening,keterangan)";
                    lcvalues = "('"+cno+"','"+ctgl+"','"+cno_bukti+"','"+ctgl_bukti+"','"+cskpd+"','"+lnnilai+"','"+cbank+"','"+cnmrek+"','"+lcket+"')";
        
                    $(document).ready(function(){
                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url(); ?>/index.php/tunai/simpan_ambil_simpanan',
                            data: ({tabel:'tr_ambilsimpanan',kolom:lcinsert,nilai:lcvalues,cid:'no_kas',lcid:cno}),
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
                 
                  } else {
                    
                    lcquery = "UPDATE tr_ambilsimpanan SET tgl_kas='"+ctgl+"',no_bukti='"+cno_bukti+"',tgl_bukti='"+ctgl_bukti+"', kd_skpd='"+cskpd+"', nilai='"+lnnilai+"', bank='"+cbank+"', nm_rekening='"+cnmrek+"', keterangan='"+lcket+"' where no_kas='"+cno+"' AND kd_skpd='"+cskpd+"'";
                   
                    $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/tunai/update_ambilsimpanan',
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
        judul = 'Edit Data Ambil Simpanan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=true;
        }    
        
    
    function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Ambil Simpanan';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();

        } 
     
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
		var nomor     = document.getElementById('nomor').value;
        var kode   = $('#skpd').combogrid('getValue');
		alert(nomor)
        var urll = '<?php echo base_url(); ?>index.php/tunai/hapus_ambilsimpanan';
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
  
    function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tunai/no_urut',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#no_bukti").attr("value",data.no_urut);
        								$("#nomor").attr("value",data.no_urut);
        							  }                                     
        	});  
        }
  
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN AMBIL SIMPANAN</a></b></u></h3>
    <div>
    <p align="right">         
        <button type="submit" class="easyui-linkbutton"  plain="true" onclick="javascript:tambah()"><i class="fa fa-plus-square"></i> Tambah</button>               
        <button type="delete" class="easyui-linkbutton"  plain="true" onclick="javascript:hapus();"><i class="fa fa-trash"></i> Hapus</button>
        
        <input type="text" value="" id="txtcari"/><button type="primary" class="easyui-linkbutton"  plain="true" onclick="javascript:cari();"><i class="fa fa-search"></i></button>
        <table id="dg" title="Listing data ambil simpanan" style="width:870px;height:450px;" >  
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
                <td>S K P D</td>
                <td></td>
                <td><input id="skpd" name="skpd" style="width: 140px;" />  <input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>

            <tr>
                <td>No. Kas</td>
                <td></td>
                <td><input type="text" id="nomor" style="width: 200px;"/></td>  
            </tr>            
            <tr>
                <td>Tgl Kas </td>
                <td></td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>
            </tr>
             <tr>
                <td>No. Bukti</td>
                <td></td>
                <td><input type="text" id="no_bukti" style="width: 200px;"/></td>  
            </tr>            
            <tr>
                <td>Tgl Bukti </td>
                <td></td>
                <td><input type="text" id="tgl_bukti" style="width: 140px;" /></td>
            </tr>
			
			 <tr>
                <td>Sisa Bank</td>
                <td></td>
                <td><input type="text" id="sisa_uang" style="width: 200px; text-align: right;" /></td> 
            </tr>
			
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 200px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            <!---onkeypress="return(currencyFormat(this,',','.',event))--->
            <tr>
                <td>Bank</td>
                <td></td>
                <td><input type="text" id="bank" style="width: 140px;" /> <input type="text" id="nmbank" style="border:0;width: 600px;" readonly="true"/> </td>                
            </tr> 
            
            
            <tr>
                <td>Nama Rekening</td>
                <td colspan="2"><textarea rows="1" cols="50" id="nmrek" style="width: 740px;"></textarea>
                </td> 
            </tr>
           
            
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea rows="2" cols="50" id="ket" style="width: 740px;"></textarea>
                </td> 
            </tr>
            
            <tr>
                <td colspan="3" align="center">
                      <button type="submit" class="easyui-linkbutton"  plain="true" onclick="javascript:simpan_ambilsmp();"><i class="fa fa-save"> </i> Simpan</button>
		              <button type="edit" class="easyui-linkbutton"  plain="true" onclick="javascript:keluar();"><i class="fa fa-arrow-left"> </i> Kembali</button>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
  	
</body>

</html>