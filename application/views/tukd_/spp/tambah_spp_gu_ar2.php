<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
   
    <script type="text/javascript"> 
   
    var no_spp = '';
    var kode   = '';
    var spd    = '';
    var st_12  = 'edit';
    
     $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal").dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
        $( "#dialog-modal-tr").dialog({
            height: 320,
            width: 500,
            modal: true,
            autoOpen:false
        });
        $( "#dialog-modal-tr2").dialog({
            height: 600,
            width: 500,
            modal: true,
            autoOpen:false
        });
        get_skpd();
        });
   
    
    $(function(){
   	     $('#dd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
       
                
          $('#spp').edatagrid({
    		url: '<?php echo base_url(); ?>/index.php/tukd/load_spp_gu',
            idField:'id',            
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",
            pagination:"true",
            nowrap:"true",                       
            columns:[[
        	    {field:'no_spp',
        		title:'NO SPP',
        		width:60},
                {field:'tgl_spp',
        		title:'Tanggal',
        		width:40},
                {field:'nm_skpd',
        		title:'Nama SKPD',
        		width:170,
                align:"left"},
                {field:'keperluan',
        		title:'Keterangan',
        		width:110,
                align:"left"}
            ]],
            onSelect:function(rowIndex,rowData){
              
              nomer     = rowData.no_spp;         
              kode      = rowData.kd_skpd;
              spd       = rowData.no_spd;
              tg        = rowData.tgl_spp;
              jn        = rowData.jns_spp;
              kep       = rowData.keperluan;
              np        = rowData.npwp;          
              bk        = rowData.bank;
              ning      = rowData.no_rek;
              status    = rowData.status;
              no_bukti  = rowData.no_bukti; 
              no_bukti2 = rowData.no_bukti2;          
              no_bukti3 = rowData.no_bukti3;          
              no_bukti4 = rowData.no_bukti4;          
              no_bukti5 = rowData.no_bukti5;          
              get(nomer,kode,spd,tg,jn,kep,np,bk,ning,status,no_bukti,no_bukti2,no_bukti3,no_bukti4,no_bukti5);
              detail_trans_2()                                        
            },
            onDblClickRow:function(rowIndex,rowData){
                section1();
            }
        });
        
                
            $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1',                
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true,                                        
                    columns:[[  
                        {field:'no_spd',title:'No SPD',width:50},  
                        {field:'tgl_spd',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd = rowData.no_spd;                                                                  
                    }    
                });
                
           
            /*
            $('#tr').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/trans',                
                    idField:'no_bukti',  
                    textField:'no_bukti',
                    mode:'remote',  
                    fitColumns:true,                                                          
                    columns:[[  
                        {field:'no_bukti',title:'No BUKTI',width:30},  
                        {field:'tgl_bukti',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    no_bukti = rowData.no_bukti;
                    detail_trans()                                  
                    }    
                }); 
                */
                
            
            $('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
			});
   	});
        
           
        
        function val_ttd(dns){
           $(function(){
            $('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd/'+dns,  
                    idField:'nip',                    
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nip',title:'NIP',width:60},  
                        {field:'nama',title:'NAMA',align:'left',width:100}
                            ]],
                    onSelect:function(rowIndex,rowData){
                    nip = rowData.nip;
                    }   
                });
           });              
         }
         

    
    function get_skpd()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#dn").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
        								kode = data.kd_skpd;
                                        validate_spd(kode);
                                        //validate_tran(kode);              
        							  }                                     
        	});  
        }         
    
    
    
    function validate_spd(kode){
           $(function(){
            $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1/'+kode,  
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true
                });
           });
        }
        
    
    /*
    function validate_tran(kode){
           $(function(){
            $('#tr').combogrid({  
                panelWidth:500,  
                url: '<?php //echo base_url(); ?>/index.php/tukd/trans/'+kode,  
                    idField:'no_bukti',  
                    textField:'no_bukti',
                    mode:'remote',  
                    fitColumns:true
                });
           });
        }
    */
      
           
    function detail_trans(){
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data_tran',
                queryParams:({no_bukti:no_bukti}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_tran();                        
                    },
                onSelect:function(rowIndex,rowData){
                kd = rowIndex;                                               
                },
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                                          
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					},
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:420
					},
                    {field:'nilai1',
					 title:'Nilai',
					 width:100,
                     align:'right'
                     }
				]]	
			});
		});
        }
        

        function detail(){
        $(function(){
            var no_bukti='';
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data_tran',
                queryParams:({no_bukti:no_bukti}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,                 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                                          
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					},
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:420
					},
                    {field:'nilai1',
					 title:'Nilai',
					 width:100,
                     align:'right'
                     }
				]]	
			});
		});
        }
                
                
        function get(no_spp,kd_skpd,no_spd,tgl_spp,jns_spp,keperluan,npwp,bank,rekening,status,no_bukti,no_bukti2,no_bukti3,no_bukti4,no_bukti5){
        $("#no_spp").attr("value",no_spp);
        $("#dn").attr("Value",kd_skpd);
        $("#sp").combogrid("setValue",no_spd);
        $("#dd").datebox("setValue",tgl_spp);        
        $("#ketentuan").attr("Value",keperluan);
        $("#jns_beban").attr("Value",jns_spp);
        $("#npwp").attr("Value",npwp);       
        $("#bank1").attr("Value",bank);
        $("#rekening").attr("Value",rekening);
        
        $("#no1").attr("Value",no_bukti);
        $("#no2").attr("Value",no_bukti2);
        $("#no3").attr("Value",no_bukti3);
        $("#no4").attr("Value",no_bukti4);
        $("#no5").attr("Value",no_bukti5);
        tombol(status);           
        }
        
		
        function kosong(){
        $("#no_spp").attr("value",'');
        $("#sp").combogrid("setValue",'');
        $("#dd").datebox("setValue",'');        
        $("#ketentuan").attr("Value",'');
        $("#jns_beban").attr("Value",'');
        $("#npwp").attr("Value",'');        
        $("#bank1").attr("Value",'');
        $("#rekening").attr("Value",'');
        document.getElementById("p1").innerHTML="";
        document.getElementById("no_spp").focus();
        $("#sp").combogrid("clear");
        
        $("#no1").attr("Value",'');
        $("#no2").attr("Value",'');
        $("#no3").attr("Value",'');
        $("#no4").attr("Value",'');
        $("#no5").attr("Value",'');
        
        $('#save').linkbutton('enable');
        detail_trans_2();
        st_12 = 'baru';
        }

		
    function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		} 
       
    function cetak(){
        var nom=document.getElementById("no_spp").value;
        $("#cspp").combogrid("setValue",nom);
        $("#dialog-modal").dialog('open');
    } 
    
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    } 
    
    
    function keluar_no(){
        $("#dialog-modal-tr").dialog('close');
    }
    
    function keluar_no2(){
        $("#dialog-modal-tr2").dialog('close');
    }      
      
    
    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#spp').edatagrid({
	       url: '<?php echo base_url(); ?>/index.php/tukd/load_spp',
         queryParams:({cari:kriteria})
        });        
     });
    }
    
     
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();
         });
     }
     
    
    function section4(){
         $(document).ready(function(){    
             $('#section4').click();                                               
         });
     }
     
     
     function section5(){
         $(document).ready(function(){    
             $("#dialog-modal-tr").click();                                               
         });
     }
     
    function tambah_no(){
        judul = 'Input Data No Transaksi';
        $("#dialog-modal-tr").dialog({ title: judul });
        $("#dialog-modal-tr").dialog('open');
        
        document.getElementById("no_spp").focus();
        
        if ( st_12 == 'baru' ){
        $("#no1").attr("value",'');
        $("#no2").attr("value",'');
        $("#no3").attr("value",'');
        $("#no4").attr("value",'');
        $("#no5").attr("value",'');
        }
     }
     
     function tambah_no2(){
        judul = 'Input Data No Transaksi';
        $("#dialog-modal-tr").dialog({ title: judul });
        $("#dialog-modal-tr").dialog('open');
        
        document.getElementById("no_spp").focus();
        
        if ( st_12 == 'baru' ){
        $("#no1").attr("value",'');
        $("#no2").attr("value",'');
        $("#no3").attr("value",'');
        $("#no4").attr("value",'');
        $("#no5").attr("value",'');
        }
     } 
     
     function tambah_no3(){
        judul = '';
        $("#dialog-modal-tr2").dialog({ title: judul });
        $("#dialog-modal-tr2").dialog('open');
        document.getElementById("no_spp").focus();
        detail_trans_3();
        
        if ( st_12 == 'baru' ){
        }
     }  
     
     function hsimpan(){        

        var a    = document.getElementById('no_spp').value;
        var b    = $('#dd').datebox('getValue');      
        var c    = document.getElementById('jns_beban').value;               
        var e    = document.getElementById('ketentuan').value;       
        var g    = document.getElementById('bank1').value;
        var h    = document.getElementById('npwp').value;
        var i    = document.getElementById('rekening').value;
        var j    = document.getElementById('nmskpd').value;         
        var k    = document.getElementById('rektotal1').value;
        var nno1 = document.getElementById('no1').value;
        var nno2 = document.getElementById('no2').value;
        var nno3 = document.getElementById('no3').value;
        var nno4 = document.getElementById('no4').value;
        var nno5 = document.getElementById('no5').value;
        
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cskpd:kode,cspd:spd,no_spp:a,bukti1:nno1,bukti2:nno2,bukti3:nno3,bukti4:nno4,bukti5:nno5,tgl_spp:b,jns_spp:c,keperluan:e,nmskpd:j,bank:g,npwp:h,rekening:i,nilai:k}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/simpan",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan...!!!');
                }else{
                    alert('Data Gagal Berhasil Tersimpan...!!!');
                }
            }
         });
        });
    }
    
    
    function dsimpan(kd_rek5,nm_rek5,nilai,kd){
        var a = document.getElementById('no_spp').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cno_spp:a,cskpd:kode,crek:kd_rek5,nrek:nm_rek5,nilai:nilai,kd:kd}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/dsimpan"            
         });
        });
    } 
    
    
    function detsimpan(){
        var a = document.getElementById('no_spp').value;        
        $('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');
        for(var i=0;i<rows.length;i++){            
            
            ckdgiat  = rows[i].kdkegiatan;
            cnmgiat  = rows[i].nmkegiatan;
            ckdrek   = rows[i].kdrek5;
            cnmrek   = rows[i].nmrek5;
            cnilai   = rows[i].nilai1;
                       
            no = i+1;      
            $(document).ready(function(){      
            $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/dsimpan" ,
            data: ({cno_spp:a,cskpd:kode,cgiat:ckdgiat,crek:ckdrek,ngiat:cnmgiat,nrek:cnmrek,nilai:cnilai,kd:no}),
            dataType:"json"            
         });
        });
        }
    } 
    
    
    function hhapus(){				
            var spp = document.getElementById("no_spp").value;              
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hhapus';             			    
         	if (spp !=''){
				var del=confirm('Anda yakin akan menghapus SPP '+spp+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:spp}),function(data){
                    status = data;                        
                    });
                    });				
				}
				} 
	}
        
    
    function getSelections(idx){
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kdkegiatan);
			}
			return ids.join(':');
	}
        
    function getSelections1(idx){
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kdrek5);
			}
			return ids.join(':');
	}
    
    
    function kembali(){
        $('#kem').click();
    }                
    
    
    function load_sum_spp(){                
		var spp   = document.getElementById('no_spp').value;
        var nospp = spp.split("/").join("123456789");       
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spp:nospp}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_spp",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal']);
                    $("#rektotal1").attr("value",n['rektotal1']);
                });
            }
         });
        });
    }
    
    
    function load_sum_tran(){                
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({no_bukti:no_bukti}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_tran",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal']);
                    $("#rektotal1").attr("value",n['rektotal1']);
                });
            }
         });
        });
    }
   
   
    function tombol(st){  
    if (st=='1'){
    $('#save').linkbutton('disable');
    $('#del').linkbutton('disable');
    $('#sav').linkbutton('disable');
    $('#dele').linkbutton('disable');    
    document.getElementById("p1").innerHTML="Sudah di Buat SPM...!!!";
     } else {
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
     $('#sav').linkbutton('enable');
     $('#dele').linkbutton('enable');
    document.getElementById("p1").innerHTML="";
     }
    }	
    
        
    function openWindow( url )
    {
        var no =nomer.split("/").join("123456789");
        window.open(url+'/'+no+'/'+kode+'/'+jns, '_blank');
        window.focus();
    }
    
    $(function(){
    
        var lno1 = document.getElementById('no1').value;
            
        var data1 =[""];
        $("#no1").autocomplete({
            source    : data1, 
            minLength : 0,
            autoFocus : true
            }).on("focus", function () {
            $(this).autocomplete("search", '');}); 
         
         
                    $.ajax({url:'<?php echo base_url(); ?>index.php/tukd/ambil_no_transaksi',
                    type    : "POST",                             
                    data    : ({no_1:lno1}),                           
                    success : function(data){
                    data1   = eval(data);
                                                                        
                       $("#no1").autocomplete({
                       source    : data1, 
                       minLength : 0,
                       select    : function(event,ui){}
                       }).on("focus", function () {
                       $(this).autocomplete("search", '');});
                       }                                     
                       });  
                                
    });
    

    $(function(){
    
        var lno1 = document.getElementById('no2').value;
            
        var data1 =[""];
        $("#no2").autocomplete({
            source    : data1, 
            minLength : 0,
            autoFocus : true
            }).on("focus", function () {
            $(this).autocomplete("search", '');}); 
         
                    $.ajax({url:'<?php echo base_url(); ?>index.php/tukd/ambil_no_transaksi',
                    type    : "POST",                             
                    data    : ({no_1:lno1}),                           
                    success : function(data){
                    data1   = eval(data);
                                                                        
                       $("#no2").autocomplete({
                       source    : data1, 
                       minLength : 0,
                       select    : function(event,ui){}
                       }).on("focus", function () {
                       $(this).autocomplete("search", '');});
                       }                                     
                       });   
    });

    
    $(function(){
    
        var lno1 = document.getElementById('no3').value;
            
        var data1 =[""];
        $("#no3").autocomplete({
            source    : data1, 
            minLength : 0,
            autoFocus : true
            }).on("focus", function () {
            $(this).autocomplete("search", '');}); 
         
                    $.ajax({url:'<?php echo base_url(); ?>index.php/tukd/ambil_no_transaksi',
                    type    : "POST",                             
                    data    : ({no_1:lno1}),                           
                    success : function(data){
                    data1   = eval(data);
                                                                        
                       $("#no3").autocomplete({
                       source    : data1, 
                       minLength : 0,
                       select    : function(event,ui){}
                       }).on("focus", function () {
                       $(this).autocomplete("search", '');});
                       }                                     
                       });   
    });

    
    $(function(){
    
        var lno1 = document.getElementById('no4').value;
            
        var data1 =[""];
        $("#no4").autocomplete({
            source    : data1, 
            minLength : 0,
            autoFocus : true
            }).on("focus", function () {
            $(this).autocomplete("search", '');}); 
         
                    $.ajax({url:'<?php echo base_url(); ?>index.php/tukd/ambil_no_transaksi',
                    type    : "POST",                             
                    data    : ({no_1:lno1}),                           
                    success : function(data){
                    data1   = eval(data);
                                                                        
                       $("#no4").autocomplete({
                       source    : data1, 
                       minLength : 0,
                       select    : function(event,ui){}
                       }).on("focus", function () {
                       $(this).autocomplete("search", '');});
                       }                                     
                       });   
    });

    
    $(function(){
    
        var lno1 = document.getElementById('no5').value;
            
        var data1 =[""];
        $("#no5").autocomplete({
            source    : data1, 
            minLength : 0,
            autoFocus : true
            }).on("focus", function () {
            $(this).autocomplete("search", '');}); 
         
                    $.ajax({url:'<?php echo base_url(); ?>index.php/tukd/ambil_no_transaksi',
                    type    : "POST",                             
                    data    : ({no_1:lno1}),                           
                    success : function(data){
                    data1   = eval(data);
                                                                        
                       $("#no5").autocomplete({
                       source    : data1, 
                       minLength : 0,
                       select    : function(event,ui){}
                       }).on("focus", function () {
                       $(this).autocomplete("search", '');});
                       }                                     
                       });   
    });
    
    
    function detail_trans_2(){
        
        var n1 = document.getElementById('no1').value;
        var n2 = document.getElementById('no2').value;               
        var n3 = document.getElementById('no3').value;       
        var n4 = document.getElementById('no4').value;
        var n5 = document.getElementById('no5').value;
        
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data_tran_2',
                queryParams:({ no_bukti1:n1, no_bukti2:n2, no_bukti3:n3, no_bukti4:n4, no_bukti5:n5 }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_tran();                        
                    },
                onSelect:function(rowIndex,rowData){
                kd = rowIndex;                                               
                },
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
                     {field:'no_bukti',
					 title:'No Bukti',
					 width:100,
					 align:'left'
					 },                                          
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					 },
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					 },
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:320
					 },
                    {field:'nilai1',
					 title:'Nilai',
					 width:100,
                     align:'right'
                     }
				]]	
			});
		});
        }
       
     
       function detail_trans_3(){
        
        $(function(){
			$('#dg2').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data_tran_3',
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 autoRowHeight:"true",
                 singleSelect:"true",
                 checkOnSelect:false,
                 selectOnCheck:false,
                 onLoadSuccess:function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                },
                columns:[[
	                 {field:'no_bukti',
					 title:'No Bukti',
					 width:70,
					 align:'left'
					 }
                     ]],
                onDblClickRow:function(rowIndex,rowData){
                    vnobukti = rowData.no_bukti;
                    masuk_grid();
                    filter_nobukti();
                }
			});
		});
        }
        
        
    function masuk_grid(){
        var i = 0;
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/select_data_tran_4',
                data: ({no_bukti1:vnobukti}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    xnobukti = n['no_bukti'];                                                                                        
                    xgiat    = n['kdkegiatan'];
                    xkdrek5  = n['kdrek5'];
                    xnmrek5  = n['nmrek5'];
                    xnilai   = n['nilai1'];
                    $('#dg1').edatagrid('appendRow',{no_bukti:xnobukti,kdkegiatan:xgiat,kdrek5:xkdrek5,nmrek5:xnmrek5,nilai1:xnilai});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                 }
            });
           });   
    }
    
    
    function filter_nobukti(){
        var vvnobukti='';
         $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
			for(var i=0;i<rows.length;i++){
				vvnobukti   = vvnobukti+"A"+rows[i].no_bukti+"A";
                if (i<rows.length && i!=rows.length-1){
                    vvnobukti = vvnobukti+'B';
                }
          }
          $('#dg1').datagrid('unselectAll');
          
        alert('filter');
        alert(vvnobukti);
        detail_trans_5(vvnobukti);a
    }
    
    function detail_trans_5(vvnobukti){
        
        $(function(){
			$('#dg2').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data_tran_5/'+vvnobukti,
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 autoRowHeight:"true",
                 singleSelect:"true",
                 checkOnSelect:false,
                 selectOnCheck:false,
                 onLoadSuccess:function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                },
                columns:[[
	                 {field:'no_bukti',
					 title:'No Bukti',
					 width:70,
					 align:'left'
					 }
                     ]]
		});
        });
        }

    </script>
    
    <STYLE TYPE="text/css"> 
         input.right{ 
         text-align:right; 
         } 
    </STYLE> 

</head>
<body>

<div id="content">
<div id="accordion">

<h3><a href="#" id="section4" onclick="javascript:$('#spp').edatagrid('reload')">List SPP</a></h3>
   
<div>
    <p align="right">  
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section1();kosong();">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="spp" title="List SPP" style="width:870px;height:450px;" >  
        </table>
    </p> 
</div>


<h3><a href="#" id="section1">Input SPP</a></h3>

   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>

               
<table border='0' style="font-size:11px" >
 
 <tr style="height: 10px;">   
   <td width="10%" >No SPP</td>
   <td><input type="text" name="no_spp" id="no_spp" onclick="javascript:select();" style="width:200px" /></td>
   <td>Tanggal</td>
   <td><input id="dd" name="dd" type="text" /></td>   
 </tr>
 
 <tr style="height: 20px;">
 
   <td width='10%'>SKPD</td>
   <td width="35%">     
        <input id="dn" name="dn"  readonly="true" style="width:130px; border: 0; " />
        <input name="nmskpd" type="text" id="nmskpd" size="60" readonly="true" style="border: 0;"/>
        </td> 
   <td width='15%'>Beban</td>
   <td width="40%" ><select name="jns_beban" id="jns_beban">
     <option value="2">GU</option>
   </select></td>
 </tr>
 
 <tr style="height: 10px;">
   <td width='10%'>No SPD</td>
   <td width='35%'><input id="sp" name="sp" style="width:200px" /></td>
   <td width='15%'>Keperluan</td>
   <td width='40%'><textarea name="ketentuan" id="ketentuan" cols="40" rows="1" ></textarea></td>
 </tr>
 
 <tr style="height: 10px;">
   
   <td width='10%'>NPWP</td>
   <td width='35%'><input type="text" name="npwp" id="npwp" value="" style="width:200px" /></td>
   
   <td width='15%'>Bank</td>
   <td><?php
								  		$bank1="select * from ms_bank ";
                                        $pagingquery1 = $bank1; //echo "edit  $pagingquery1<br />";
                                        $res = mysql_query($pagingquery1)or die("pagingquery gagal".mysql_error());
								?>
     <select name="bank1" id="bank1" style="height: 27px; width: 200px;">
       <option value="">...Bank.. </option>
       <?php
		 if($res)
          {
           while ($result = mysql_fetch_row($res)) 
             {
  		?>
       <option value="<?php echo $result[0]; ?>" <?php if($result[0]==$bank1){echo "selected";}?>> <?php echo $result[0]."-".$result[1]; ?> </option>
       <?php 
             }
           }
        ?>
     </select></td>
 </tr>
 
 <tr style="height: 10px;">
   
   <td></td>
   <td></td>
   <td width='10%'>Rekening</td>
   <td width='40%'><input type="text" name="rekening" id="rekening"  value="" style="width:200px" /></td>

 </tr>

        <tr>
        
            <td colspan="2">
                  <div align="left">
                     <a class="easyui-linkbutton" onclick="javascript:tambah_no3();">Pilih Nomor Transaksi</a>
                  </div>
            </td>                      
        
            <td colspan="2">
                  <div align="right"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true"  onclick="javascript:hsimpan();detsimpan();">Simpan</a>
                        <a id="del"class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section4();">Hapus</a>
                        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section4();">Kembali</a>
                        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>
                  </div>
            </td>                
        
        </tr>
    
    </table>
   
        <table id="dg1" title="Input Detail SPP" style="width:850%;height:300%;" >  
        </table>
       
        <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;-->
         
        <table border='0' style="width:100%;height:5%;"> 
             <td width='30%'></td>
             <td width='40%'><input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:140px" align="right" readonly="true" ></td>
             <td width='15%'><B>Total</B></td>
             <td width='15%'><input class="right" type="text" name="rektotal" id="rektotal"  style="width:140px" align="right" readonly="true" ></td>
        </table>
            
        <!-- <?php echo form_close(); ?> -->

   </p>
   </div>
</div>
</div> 

<div id="dialog-modal" title="CETAK SPP">
    
    <p class="validateTips">SILAHKAN PILIH SPP</p>  
    
    <fieldset>

        <table>
            
            <tr>            
                <td width="110px" >NO SPP:</td>
                <td><input id="cspp" name="cspp" style="width: 210px; " /></td>
            </tr>
            
            <tr>
                <td width="110px">Penandatangan:</td>
                <td><input id="ttd" name="ttd" style="width: 170px;" /></td>
            </tr>
           
        </table>  
    
    </fieldset>

    <div>
    </div>     

    <a href="<?php echo site_url(); ?>/tukd/cetakspp1" class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false;">SPP1</a>
    <a href="<?php echo site_url(); ?>/tukd/cetakspp2" class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false;">SPP2</a>
    <a href="<?php echo site_url(); ?>/tukd/cetakspp3" class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false;">SPP3</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  

</div>
 	
<div id="dialog-modal-tr" title="">
    <p class="validateTips">Pilih Nomor Transaksi</p> 
    <fieldset>
    <table align="center" style="width:100%;" border="0">
            
            <tr>
                <td>1. No Transaksi</td>
                <td></td>
                <td><input id="no1" name="no1" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>2. No Transaksi</td>
                <td></td>
                <td><input id="no2" name="no2" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>3. No Transaksi</td>
                <td></td>
                <td><input id="no3" name="no3" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>4. No Transaksi</td>
                <td></td>
                <td><input id="no4" name="no4" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>5. No Transaksi</td>
                <td></td>
                <td><input id="no5" name="no5" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                            
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                            
            </tr>
            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:detail_trans_2();">Pilih</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_no();">Kembali</a>
                </td>                
            </tr>
        
    </table>       
    
    </fieldset>

</div>

<div id="dialog-modal-tr2" title="">
    <p class="validateTips"></p> 
    <fieldset>
    <table align="center" style="width:100%;" border="0">
    
       <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
       </tr> 

       <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
       </tr>
            
        <table id="dg2" title="Pilih Nomor Bukti" style="width:460%;height:430%;" >  
        </table>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                            
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                            
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                            
            </tr>

            <table align="center" style="width:100%;" border="0">
                <tr>
                    <td align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:detail_trans_3();">Pilih</a>
    		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_no2();">Kembali</a>
                    </td>
                </tr>
            </table>
    </table>       
    </fieldset>
</div>
</body>
</html>