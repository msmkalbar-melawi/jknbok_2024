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
    
    var nl =0;
	var tnl =0;
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var rek=0;
    
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
   	});

        $(function(){
            $('#cspm').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_spm',  
                    idField:'no_spm',                    
                    textField:'no_spm',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spm',title:'SPM',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:60},
                        {field:'no_spp',title:'SPP',width:60} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kode = rowData.no_spm;
                    skpd = rowData.kd_skpd;
                    val_ttd(skpd);
                    }   
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
    $(function(){ 
     $('#spm').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_spm',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_spm',
    		title:'Nomor SPM',
    		width:40},
            {field:'no_spp',
    		title:'Nomor SPP',
    		width:40},
            {field:'tgl_spm',
    		title:'Tanggal',
    		width:25},
            {field:'kd_skpd',
    		title:' SKPD',
    		width:25,
            align:"left"},
            {field:'keperluan',
    		title:'Keterangan',
    		width:140,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          no_spm = rowData.no_spm;
          no_spp = rowData.no_spp;
          skpd = rowData.kd_skpd;         
          tgs  = rowData.tgl_spm;
          st =  rowData.status;
          jns= rowData.jns_spp;
          getspm(no_spm,no_spp,tgs,st,jns);                                                      
        },
        onDblClickRow:function(rowIndex,rowData,st){
            section2();   
        }
    });
    }); 
        
              
    $(function(){
            $('#nospp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/nospp',  
                    idField:'no_spp',                    
                    textField:'no_spp',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spp',title:'No',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:80} 
                          
                    ]],
                     onSelect:function(rowIndex,rowData){
                        no_spp = rowData.no_spp;         
                        skpd  = rowData.kd_skpd;
                        sp  = rowData.no_spd;          
                        bl  = rowData.bulan;
                        tg  = rowData.tgl_spp;
                        jns  = rowData.jns_spp;
                        kep  = rowData.keperluan;
                        np  = rowData.npwp;
                        rekan  = rowData.nmrekan;
                        bk  = rowData.bank;
                        ning  = rowData.no_rek;
                        nm  = rowData.nm_skpd;        
                        get(no_spp,skpd,sp,tg,bl,jns,kep,np,rekan,bk,ning,nm);
                        detail();                                                                
                    }  
                });
           });
    

           
 
             
        $(function(){
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true"
                                  
			});
		}); 
        
        $(function(){
			$('#rekpot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/rek_pot',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
                                  
			});
		}); 
        
        $(function(){
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/get_pot',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
                                  
			});
		}); 
    
  
        
        function detail(){
        $(function(){
			//var no_spp = document.getElementById('nospp1').value;            
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({spp:no_spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_spm();
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
					 width:400					 
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
        
        function detail1(){
        $(function(){
            var no_spp='';
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({spp:no_spp}),
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
					 width:400					 
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
        
        function rk_pot(){
        $(function(){	   	               
			$('#rekpot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/rek_pot',                
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){
                      pot();                                           
                    },                      
                 onClickRow:function(rowIndex, rowData){							
								rk_pot=rowData.kd_rek5;                                
                                nrek=rowData.nm_rek5;                                
								simpan(rk_pot,nrek);
                                pot();
							 },				 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
                     {field:'pilih',
					 title:'pilih',
					 width:20,
                     align:'center',
					 checkbox:true,
                     hidden:true
                     },
                     {field:'kd_rek5',
					 title:'Rekening',
					 width:150,
					 align:'left'
					},			
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:620
					}
				]]	
			
			});
  	

		});
        }
        
         function pot(){
        $(function(){
	   	    var spm = document.getElementById('no_spm').value;
               //alert(spm)            
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot',
                queryParams:({spm:spm}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_pot();
                      },                    
                 onAfterEdit:function(rowIndex, rowData, changes){						
								rekeing=rowData.kd_rek5;
                                nrekeing=rowData.nm_rek5;
                                nilai=rowData.nilai;
                                poto=rowData.pot;                                
								psimpan(rekeing,nrekeing,nilai,poto);       	                                      
							 },                			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                    
					{field:'kd_rek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:550
					},                    
                    {field:'nilai',
					 title:'Nilai',
					 width:100,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
                     {field:'pot',
					 title:'ket',
					 width:30,
                     align:'center',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     }
                      
				]]	
			
			});
  	

		});
        }
              
        function get(no_spp,kd_skpd,no_spd,tgl_spp,bulan,jns_spp,keperluan,npwp,rekanan,bank,rekening,nm_skpd){
        $("#nospp").attr("value",no_spp);
		$("#nospp1").attr("value",no_spp);
        $("#dn").attr("value",kd_skpd);
        $("#sp").attr("value",no_spd);        
        $("#tgl_spp").attr("value",tgl_spp);
        $("#kebutuhan_bulan").attr("Value",bulan);
        $("#ketentuan").attr("Value",keperluan);
        $("#jns_beban").attr("Value",jns_spp);
        $("#npwp").attr("Value",npwp);
        $("#rekanan").attr("Value",rekanan);
        $("#bank1").attr("Value",bank);
        $("#rekening").attr("Value",rekening);
        $("#nmskpd").attr("Value",nm_skpd);
                    
        }
                  
        function getspm(no_spm,no_spp,tgl_spm,status,jns_spp){
        $("#no_spm").attr("value",no_spm);
        $("#nospp").combogrid("setValue",no_spp);
        $("#dd").datebox("setValue",tgl_spm);
        $("#jns_beban").attr("Value",jns_spp);
         tombol(status);                   
        }
		
        function kosong(){
        cdate = '<?php echo date("Y-m-d"); ?>';
        $("#no_spm").attr("value",'');
        $("#dd").datebox("setValue",cdate);
        $("#nospp").combogrid("setValue",'');       
        $("#dn").attr("value",'');
        $("#sp").attr("value",'');        
        $("#tgl_spp").attr("value",'');
        $("#kebutuhan_bulan").attr("Value",'');
        $("#ketentuan").attr("Value",'');
        $("#jns_beban").attr("Value",'');
        $("#npwp").attr("Value",'');
        $("#rekanan").attr("Value",'');
        $("#bank1").attr("Value",'');
        $("#rekening").attr("Value",'');
        $("#nmskpd").attr("Value",'');
        document.getElementById("p1").innerHTML="";
        detail1();
        $("#nospp").combogrid("clear");
        tombolnew();
        }
        
        $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $("#dialog-modal").dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
        });
       
     function cetak(){
        var nom=document.getElementById("no_spm").value;
        $("#cspm").combogrid("setValue",nom);
        $("#dialog-modal").dialog('open');
    } 
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    }   
     function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#spm').edatagrid({
	       url: '<?php echo base_url(); ?>/index.php/tukd/load_spm',
         queryParams:({cari:kriteria})
        });        
     });
    }
        
        function simpan_spm(){        
        var a1 = document.getElementById('no_spm').value;
        var b1 = $('#dd').datebox('getValue'); 
        //var a = document.getElementById('no_spp').value;
        var b = document.getElementById('tgl_spp').value;      
        var c = document.getElementById('jns_beban').value; 
        var d = document.getElementById('kebutuhan_bulan').value;
        var e = document.getElementById('ketentuan').value;
        var f = document.getElementById('rekanan').value;
        var g = document.getElementById('bank1').value;
        var h = document.getElementById('npwp').value;
        var i = document.getElementById('rekening').value;
        var j = document.getElementById('nmskpd').value;
        var k = document.getElementById('dn').value;
        var l = document.getElementById('sp').value;
        var m = document.getElementById('rekspm1').value; 
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cskpd:k,cspd:l,no_spm:a1,tgl_spm:b1,no_spp:no_spp,tgl_spp:b,jns_spp:c,bulan:d,keperluan:e,nmskpd:j,rekanan:f,bank:g,npwp:h,rekening:i,nilai:m}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/simpan_spm",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan');
                    $('#spp').edatagrid('reload');
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                }
            }
         });
        });
        }
    
        function simpan(reke,nrek){		
		var spm = document.getElementById('no_spm').value;
		var cskpd =document.getElementById('dn').value;
        
        $(function(){      
            $.ajax({
            type: 'POST',
            data: ({cskpd:cskpd,spm:spm,kd_rek5:reke,nmrek:nrek}),
            dataType:"json",
            url:'<?php echo base_url(); ?>/index.php/tukd/pot_simpan'
         });
        });
		}
        
        function psimpan(reke,nrek,nilai,ket){		
		var spm = document.getElementById('no_spm').value;
		var cskpd =document.getElementById('dn').value;
        //alert(spm+cskpd+reke+nrek+nilai+ket);
        $(function(){      
            $.ajax({
            type: 'POST',
            data: ({cskpd:cskpd,spm:spm,kd_rek5:reke,nmrek:nrek,nilai:nilai,ket:ket}),
            dataType:"json",
            url:'<?php echo base_url(); ?>/index.php/tukd/potsimpan'
         });
        });
		}
     
          
         function hhapus(){				
            var spm = document.getElementById("no_spm").value;
            //var spp = document.getElementById("no_spp").value; 
            //alert(spm+no_spp);             
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hapus_spm';             			    
         	if (spm !=''){
				var del=confirm('Anda yakin akan menghapus SPM '+spm+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:spm,spp:no_spp}),function(data){
                    status = data;
                        
                    });
                    });
				
				}
				} 
		}
        
         function phapus(){				
            var spm = document.getElementById("no_spm").value;
            var rek=getSelections();                       
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hapus_pot';             			    
         	if (spm !=''){
				var del=confirm('Anda yakin akan menghapus rek '+rek+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:spm,rek:rek}),function(data){
                    status = data;
                        
                    });
                    });
				
				}
				} 
		}  
         
        function getSelections(idx){
			//alert(idx);
			var ids = [];
			var rows = $('#pot').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kd_rek5);
			}
			return ids.join(':');
		}
        
        function load_sum_spm(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spp:no_spp}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_spm",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rekspm").attr("value",n['rekspm']);
                    $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
        });
    }         
        
        function load_sum_pot(){                
		var spm = document.getElementById('no_spm').value;              
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spm:spm}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_pot",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal']);
                });
            }
         });
        });
    }
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
     }
     function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });
     }
     function section3(){
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
     }
     
    function tombol(st){  
    if (st=='1'){
    $('#save').linkbutton('disable');
    $('#del').linkbutton('disable');
    $('#poto').linkbutton('disable');       
    document.getElementById("p1").innerHTML="Sudah di Buat SP2D!!";
     } else {
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
     $('#poto').linkbutton('enable');     
    document.getElementById("p1").innerHTML="";
     }
    }
    
    function tombolnew(){  
    
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
     $('#poto').linkbutton('enable');     
    
    }
     
    function openWindow( url )
        {
      
        var no =kode.split("/").join("123456789");
       // alert(no);
        window.open(url+'/'+no+'/'+skpd+'/'+jns, '_blank');
        window.focus();
        }
        
    function cek(){
        var lcno = document.getElementById('no_spm').value;
            if(lcno !=''){
               section3();               
            } else {
                alert('Nomor SPM Tidak Boleh kosong')
                document.getElementById('no_spm').focus();
                exit();
            }
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

<h3><a href="#" id="section1" onclick="javascript:$('#spm').edatagrid('reload')">List SPM</a></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();section2();">Tambah</a>
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="spm" title="List SPM" style="width:870px;height:450px;" >  
        </table>
                  
        
    </p> 
    </div>

<h3><a href="#" id="section2" onclick="javascript:$('#dg').edatagrid('reload')" >Input SPM</a></h3>
   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>
  <!-- <?php echo form_open('tukd/simpan', array('class' => 'basic')); ?> -->
               
<table border='0' style="font-size:11px" >
 
 <tr>
   <td >No SPM</td>
   <td><input type="text" name="no_spm" id="no_spm" onclick="javascript:select();" /></td>
   <td>Tgl SPM </td>
   <td><input id="dd" name="dd" type="text" /></td>
 </tr>
 <tr>   
   <td width="8%" >No SPP</td>
   <td><input id="nospp" name="nospp" style="width:130px" />
     <input type="hidden" name="nospp1" id="nospp1" /></td>
   <td>Tgl SPP </td>
   <td><input id="tgl_spp" name="tgl_spp" type="text" readonly="true" /></td>   
    </tr>
 <tr>
   <td width='8%'>SKPD</td>
   <td width="53%" >     
      <input id="dn" name="dn" style="width:130px" readonly="true"/></td> 
   <td width='8%'>Bulan</td>
   <td width="31%" ><select  name="kebutuhan_bulan" id="kebutuhan_bulan"  >
     <option value="">...Pilih Kebutuhan Bulan... </option>
     <option value="1" >1 | Januari</option>
     <option value="2">2 | Februari</option>
     <option value="3">3 | Maret</option>
     <option value="4">4 | April</option>
     <option value="5">5 | Mei</option>
     <option value="6">6 | Juni</option>
     <option value="7">7 | Juli</option>
     <option value="8">8 | Agustus</option>
     <option value="9">9 | September</option>
     <option value="10">10 | Oktober</option>
     <option value="11">11 | November</option>
     <option value="12">12 | Desember</option>
   </select></td> 
 </tr>
 <tr>
   <td width='8%'>&nbsp;</td>
   <td width='53%'><textarea name="nmskpd" id="nmskpd" cols="40" rows="1" style="border: 0;"  readonly="true"></textarea></td>
   <td width='8%'>Keperluan</td>
   <td width='31%'><textarea name="ketentuan" id="ketentuan" cols="30" rows="2" readonly="true"></textarea></td>
 </tr>
 <tr>
   <td width='8%'>No SPD</td>
   <td><input id="sp" name="sp" style="width:150px" readonly="true"/></td>
   <td width='8%'>Rekanan</td>
   <td><textarea id="rekanan" name="rekanan" cols="30" rows="1" readonly="true" > </textarea></td>
 </tr>
 
 <tr>
   <td>Beban</td>
   <td><select name="jns_beban" id="jns_beban" >
     <option value="">...Pilih Jenis Beban... </option>
     <option value="1">UP</option>
     <option value="2">GU</option>
     <option value="3">TU</option>
     <option value="4">LS GAJI</option>
     <option value="5">LS PPKD</option>
     <option value="6">LS Barang Jasa</option>
   </select></td>
   <td>Bank</td>
   <td><?php
								  		$bank1="select * from ms_bank ";
                                        $pagingquery1 = $bank1; //echo "edit  $pagingquery1<br />";
                                        $res = mysql_query($pagingquery1)or die("pagingquery gagal".mysql_error());
								?>
     <select name="bank1" id="bank1" style="height: 27px; width: 100px;">
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
 <tr>
   <td width='8%'>NPWP</td>
   <td width='53%'><input type="text" name="npwp" id="npwp" value="" /></td>
   <td width='8%'>Rekening</td>
   <td width='31%'><input type="text" name="rekening" id="rekening"  value="" /></td>
 </tr>       
 
    <tr>
                <td colspan="4" align="right">
                <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_spm();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section1();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>
                <a id="poto" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:cek();javascript:rk_pot();">Potongan</a>
                <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a></td>                
            </tr>
    </table>
     <table id="dg" title=" Detail SPM" style="width:850%;height:250%;" >  
        
        </table>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rekspm" id="rekspm"  style="width:140px" align="right" readonly="true" >
        <input class="right" type="hidden" name="rekspm1" id="rekspm1"  style="width:100px" align="right" readonly="true" >
        
    <!-- <?php echo form_close(); ?> -->
    

   </p>
    </div>
    
<h3><a href="#" id="section3" >Potongan</a></h3>
    <div>
    <p align="right">      
        <table id="rekpot" title="Pilih Rekening Potongan" style="width:870px;height:200px;" >  
        </table><br /><br />
        <table id="pot" title="List Potongan" style="width:870px;height:200px;" >  
        </table>          
        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:phapus();javascript:$('#pot').edatagrid('reload');">Hapus</a>
       <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#pot').edatagrid('addRow');javascript:$('#pot').edatagrid('reload');simpan_spm();">SIMPAN</a>
       <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section2();">kembali</a>
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal"  style="width:100px" align="right" readonly="true" >
        
    </p> 
    </div>
   
  
</div>

</div> 

<div id="dialog-modal" title="CETAK SPP">
    <p class="validateTips">SILAHKAN PILIH SPP</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">NO SPP:</td>
            <td><input id="cspm" name="cspm" style="width: 170px;" /></td>
        </tr>
        <tr>
            <td width="110px">Penandatangan:</td>
            <td><input id="ttd" name="ttd" style="width: 170px;" /></td>
        </tr>
       
    </table>  
    </fieldset>
    <a href="<?php echo site_url(); ?>/tukd/cetak_spm" class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false;">Cetak</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>
 	
</body>

</html>