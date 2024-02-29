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
    
	<style>    
    #tagih {
        position: relative;
        width: 500px;
        height: 70px;
        padding: 0.4em;
    }  
    </style>
    <script type="text/javascript">
    
     var  zno_upload = ''; 
     var  kodesk = '';  
    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 450,
                width: 920,
                modal: true,
                autoOpen:false                
            });              
           load_sisa_bank(); 
           get_nourut();     
           get_nourutbku();        
           get_skpd();  
        });    
        
    $(function(){                
      $('#dg').edatagrid({
		rowStyler:function(index,row){        
        if ((row.status_validasix==1)){
		   return 'background-color:#B0E0E6';
        }        
		},
		url: '<?php echo base_url(); ?>/index.php/cms/load_list_belum_validasi_perbidang',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        columns:[[    	    
			{field:'no_bukti',
    		title:'No.',
    		width:8},
            {field:'tgl_bukti',
    		title:'TGL Trans.',
    		width:15},
            {field:'tgl_validasi',
    		title:'TGL Validasi',
    		width:15},
            {field:'kd_skpd',
    		title:'SKPD',
    		width:13,
            align:"center"},
            {field:'ket',
    		title:'Keterangan',
    		width:46,
            align:"left"},
			{field:'total',
    		title:'Nilai Pengeluaran',            
    		width:25,
            align:"right"},            
            {field:'no_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},
			{field:'status_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},            
			{field:'tgl_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},
			{field:'status_validasi',
    		title:'STT',
    		width:5,
            align:"center"},
            {field:'ck',
    		checkbox:'true'},
            {field:'rekening_awal',
    		title:'Rek Bend',
    		width:10,
            align:"left",hidden:true},
            {field:'nm_rekening_tujuan',
    		title:'Nama Rek',
    		width:10,
            align:"left",hidden:true},
            {field:'rekening_tujuan',
    		title:'Rek Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'bank_tujuan',
    		title:'Bank Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'ket_tujuan',
    		title:'Ket. Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'status_pot',
    		title:'POT',
    		width:10,
            align:"left",hidden:true}
        ]],
        onSelect:function(rowIndex,rowData){                                                      
          skdvoucher    = rowData.no_voucher; 
          stglvoucher   = rowData.tgl_voucher;         
          skdskpd       = rowData.kd_skpd;
          stotal        = rowData.total;        
          //get(skdvoucher,stglvoucher,skdskpd,stotal);
          
          if(rowData.status_validasix==1){
            alert('sudah di validasi');
            bersih_list();
            exit();
          }          
          load_total_sub();
        },
        onDblClickRow:function(rowIndex,rowData){                                       
        }
    }); });
    
    $(function(){
    $('#dg2').edatagrid({
		idField:'id',            
        toolbar:'#toolbar',
            rownumbers:"true", 
            fitColumns:"true",            
            autoRowHeight:"false",
            singleSelect:"true",
            nowrap:"true",
            loadMsg:"Tunggu Sebentar....!!",                               
        columns:[[    	    
			{field:'no_bukti',
    		title:'No.',
    		width:8,hidden:true},
            {field:'tgl_bukti',
    		title:'TGL Trans.',
    		width:15,hidden:true},
            {field:'no_bku',
    		title:'No. BKU',
    		width:10},
            {field:'tgl_validasi',
    		title:'TGL BKU',
    		width:13},
            {field:'kd_skpd',
    		title:'SKPD',
    		width:13,
            align:"center"},
            {field:'ket',
    		title:'Keterangan',
    		width:60,
            align:"left"},
			{field:'total',
    		title:'Nilai Pengeluaran',            
    		width:20,
            align:"right"},            
            {field:'no_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},
			{field:'status_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},            
			{field:'tgl_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},
			{field:'status_validasi',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},            
            {field:'rekening_awal',
    		title:'Rek Bend',
    		width:10,
            align:"left",hidden:true},
            {field:'nm_rekening_tujuan',
    		title:'Nama Rek',
    		width:10,
            align:"left",hidden:true},
            {field:'rekening_tujuan',
    		title:'Rek Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'bank_tujuan',
    		title:'Bank Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'ket_tujuan',
    		title:'Ket. Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'status_pot',
    		title:'POT',
    		width:10,
            align:"left",hidden:true}                     
            ]],
        onSelect:function(rowIndex,rowData){                                                                       
        },
        onDblClickRow:function(rowIndex,rowData){                                       
        }
    }); 
    
    $(function(){
    $('#tglvoucher').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();    
            	return y+'-'+m+'-'+d;
            }
        });
    });
    
     $(function(){    
    $('#tglvalidasi').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();    
            	return y+'-'+m+'-'+d;
            }
        });
    });
       
    });
        
    
function cari(){
    var kriteria = $('#tglvoucher').datebox('getValue');
    
    if(kriteria=='' || kriteria==null){
        alert('Tanggal Transaksi Belum dipilih !');
        exit();
    }
    
        $(function(){ 
        $('#dg').edatagrid({
		    url: '<?php echo base_url(); ?>/index.php/cms/load_list_validasi_perbidang',
            queryParams:({cari:kriteria})
            });        
        });
    }		
    
function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/cms/no_urut_validasicms',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#no_validasi").attr("value",data.no_urut);
        							  }                                     
        	});  
        }    

function get_nourutbku()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/no_urut',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#no_bku").attr("value",data.no_urut);
        							  }                                     
        	});  
        }    

function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka_penetapan/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								//$("#skpd").attr("value",data.kd_skpd);
        								//$("#nmskpd").attr("value",data.nm_skpd);         
        								kodesk = data.kd_skpd;
                                        //kegia();              
        							  }                                     
        	});  
        }
    
function load_sisa_bank(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/cms/load_sisa_bank",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#sisa_bank").attr("value",n['sisa']);                   
                });
            }
         });
        });
    }    
    
function bersih_list(){
    $('#dg').edatagrid('unselectAll');
    $("#total_trans").attr("value",number_format(0,2,'.',','));
    $('#dg').edatagrid('reload');
    
    load_sisa_bank(); 
    get_nourut();     
    get_nourutbku();
}    

function load_total_sub(){
    var hasil=0;
    var rows = $('#dg').datagrid('getSelections');     
		      for(var p=0;p<rows.length;p++){ 
                    hasil = hasil+angka(rows[p].total);                                        
                   }
    $("#total_trans").attr("value",number_format(hasil,2,'.',','));                             
}
  
function proses_validasi_db(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();
    if(dd<10){
    dd='0'+dd;
    } 
    if(mm<10){
        mm='0'+mm;
    } 
    var harini = yyyy+'-'+mm+'-'+dd;
             
     var tot_transval = 0;     
     
     var n_bku = angka(document.getElementById('no_bku').value);
     var n_validasi = angka(document.getElementById('no_validasi').value);
     
    var x = $('#dg').datagrid('getSelected');     
    if(x==null){
        alert('List Data belum dipilih');
        exit();
    }
     
     if(n_bku==''){alert('Refresh App'); exit();}
     if(n_validasi==''){alert('Refresh App'); exit();}
     
     var sis_bank = angka(document.getElementById('sisa_bank').value);
               
     var rows = $('#dg').datagrid('getSelections');                                        
            for(var p=0;p<rows.length;p++){			 
                    tot_transval   = tot_transval+ angka(rows[p].total);
            }              
      
                         
     if(tot_transval > sis_bank){
        alert('Total Transaksi melebihi Saldo Bank');
        exit();
     }                            
                         
     var r = confirm("Apakah data yang akan di-Validasi sudah benar ?");
     if (r == true) {
     
        var dskpd = '';                            
        var csql = '';
        var p=0; var nomorbku=0;
        var i=0;
        var j=1;
            //$('#dg').edatagrid('selectAll');        
            var rows = $('#dg').datagrid('getSelections'); 
            for(var p=0;p<rows.length;p++){			                                              
                    nomorbku = n_bku+i;                     
                    if(rows[p].status_pot==1){                        
                        i=i+2; 
                    }else{
                        i=i+1;
                    }                                                                                                                                          
                    cno_bukti     = rows[p].no_bukti;
                    ctgl_bukti    = rows[p].tgl_bukti;
                    cno_upload    = rows[p].no_upload;
                    cstt_upload   = rows[p].status_upload;                 
                    cskpd         = rows[p].kd_skpd;
                    cket          = rows[p].ket;                    
                    ctotal        = angka(rows[p].total);                    
                    crekening_awal     = rows[p].rekening_awal;                   
                    cnm_rekening_tujuan  = rows[p].nm_rekening_tujuan;
                    crekening_tujuan   = rows[p].rekening_tujuan;
                    cbank_tujuan  = rows[p].bank_tujuan;
                    cket_tujuan   = rows[p].ket_tujuan;                                 
                    dskpd = kodesk;//cskpd.substr(0,7)+'.00';  
              
                if (p>0) {
                csql = csql+","+"('"+cno_bukti+"','"+ctgl_bukti+"','"+cno_upload+"','"+crekening_awal+"','"+cnm_rekening_tujuan+"','"+crekening_tujuan+"','"+cbank_tujuan+"','"+cket_tujuan+"','"+ctotal+"','"+cskpd+"','"+dskpd+"','"+cstt_upload+"','"+harini+"','1','"+n_validasi+"')";
                } else {
                csql = "values('"+cno_bukti+"','"+ctgl_bukti+"','"+cno_upload+"','"+crekening_awal+"','"+cnm_rekening_tujuan+"','"+crekening_tujuan+"','"+cbank_tujuan+"','"+cket_tujuan+"','"+ctotal+"','"+cskpd+"','"+dskpd+"','"+cstt_upload+"','"+harini+"','1','"+n_validasi+"')";                                            
                }                                                            
			}
            //alert(csql);            
            $(document).ready(function(){               
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({tabel:'trvalidasi_cmsbank_bidang',no:n_validasi,sql:csql,skpd:dskpd}),
                    url: '<?php echo base_url(); ?>/index.php/cms/simpan_validasicms_bidang',
                    success:function(data){                        
                        status = data.pesan;   
                         if (status=='1'){               
                            alert('Data Berhasil diproses...!!!');		                 
                            bersih_list();                                                                  					
                        } else{ 
                            alert('Data Gagal diproses...!!!');
                        }                                             
                    }
                });
                });           
                                  
            } else {
                alert('Silahkan Cek lagi, Pastikan Data Sudah Benar...');
            }            
        }                 
        
        function batal_open(){
            $("#dialog-modal").dialog('open');
            
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1;
            var yyyy = today.getFullYear();
                if(dd<10){
                    dd='0'+dd;
                } 
                if(mm<10){
                    mm='0'+mm;
                } 
            var today = yyyy+'-'+mm+'-'+dd; 
            $('#tglvalidasi').datebox('setValue',today);                           
    
        $(function(){ 
        $('#dg2').edatagrid({
		    url: '<?php echo base_url(); ?>/index.php/cms/load_list_telahvalidasi',
            queryParams:({cari:today})
            });        
        });             
            
            
        }
        
        function batal_close(){
            var today = '';
            
            $(function(){ 
            $('#dg').edatagrid({
		    url: '<?php echo base_url(); ?>/index.php/cms/load_list_validasi',
            queryParams:({cari:today})
            });        
            });
            
            $("#dialog-modal").dialog('close');
               
        }
        
        function proses_batal(){
            
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1;
            var yyyy = today.getFullYear();
                if(dd<10){
                    dd='0'+dd;
                } 
                if(mm<10){
                    mm='0'+mm;
                } 
            var today = yyyy+'-'+mm+'-'+dd;
            var paramtoday = $('#tglvalidasi').datebox('getValue');  
            
            var x = $('#dg2').datagrid('getSelected');     
            if(x==null){
                alert('List Data belum dipilih');
                exit();
            }
            
            var r = confirm("Apakah data yang akan di-Batalkan sudah benar ?");
            if (r == true) {
                 
            if(today==paramtoday){
            
            var rows = $('#dg2').datagrid('getSelections'); 
            for(var p=0;p<rows.length;p++){			                                              
                    hno_voucher = rows[p].no_voucher;
                    htgl_valid  = rows[p].tgl_validasi;
                    hno_bukti   = rows[p].no_bku;
                    cskpd       = rows[p].kd_skpd;                     
            }
            
            $(document).ready(function(){               
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({tabel:'trvalidasi_cmsbank',nobukti:hno_bukti,novoucher:hno_voucher,skpd:cskpd,tglvalid:htgl_valid}),
                    url: '<?php echo base_url(); ?>/index.php/cms/batal_validasicms',
                    success:function(data){                        
                        status = data.pesan;   
                         if (status=='1'){               
                            alert('Data Berhasil diproses...!!! Refresh Halaman');		                 
                            batal_close();                                                                  					
                        } else{ 
                            alert('Data Gagal diproses...!!!');
                        }                                             
                    }
                });
                }); 
            
             
            
            }else{
                alert('Tanggal harus hari ini...');
                exit();
            }
            }else{
                alert('Silahkan cek Kembali...');
                exit();
            }
        }
    </script>

</head>
<body>

<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1" >VALIDASI - DAFTAR SETOR SIMPANAN NON TUNAI</a></h3>
    <div>
    <p align="center">         
    <table width="100%">
        <tr>
            <td><label><b><i>No Bukti</i></b></label> : 
            <input name="no_bku" type="text" id="no_bku" style="width:100px; border: 0;" readonly="true"/>            
            <input name="no_validasi" type="hidden" id="no_validasi" style="width:100px; border: 0;"/>
            </td>
            <td>
            </td>
        </tr>  
        <tr>
            <td><label><b>Tanggal Upload</b></label> : 
            <input name="tglvoucher" type="text" id="tglvoucher" style="width:100px; border: 0;"/>            
            &nbsp; 
            <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
            </td>
            <td align="right"><label><b>Aksi</b></label> :            
            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:bersih_list();">Bersihkan List</a> &nbsp;
            <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:proses_validasi_db();">Proses Validasi</a>  &nbsp;                                   
            <!--<a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:batal_open();">Batal Validasi</a> &nbsp;-->            
            </td>
        </tr>        
        </table>
        <table id="dg" title="List Data Transaksi" style="width:860px;height:390px;"  >  
        </table>
        <table width="100%" style="text-align: right;">
            <tr>
            <td><label><b>Total Transaksi</b></label> : 
            <input name="total_trans" type="text" id="total_trans" style="text-align:right; width:200px; border: 0;" readonly="true"/>            
            </td>
            </tr>
            <tr>
            <td><label><b>Sisa Saldo Bank</b></label> : 
            <input name="sisa_bank" type="text" id="sisa_bank" style="text-align:right; width:200px; border: 0;" readonly="true"/>            
            </td>
            </tr>        
        </table>
        <font><b>Note : Warna Biru sudah divalidasi</b></font>
    </p> 
    </div>      
</div>
</div>

<div id="dialog-modal" title="List Data Validasi">
    <fieldset>
        <p>Tanggal Validasi : <input name="tglvalidasi" type="text" id="tglvalidasi" style="width:100px; border: 0;"/> </p>
        <table id="dg2" title="Data Transaksi - Telah Validasi" style="width:870px;height:280px;"  >  
        </table>
        <table width="100%" >
            <tr style="text-align: center;">
            <td>
            <a class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:batal_close();">Kembali</a> &nbsp;&nbsp;
            <a class="easyui-linkbutton" id="proses" iconCls="icon-add" plain="true" onclick="javascript:proses_batal();">Proses Batal</a> &nbsp;&nbsp;
            </td>
            </tr>
        </table>           
    </fieldset>  
</div>


</body>

</html>