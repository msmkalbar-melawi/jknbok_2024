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
   
    var no_spp = '';
    var kode = '';
    //var cid = 0;
   
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
   	     $('#ddspm').datebox({  
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
   	     $('#ddspp').datebox({  
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
            $('#cspp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/load_spp_up',  
                    idField:'no_spp',                    
                    textField:'no_spp',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spp',title:'SPP',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:60},
                        {field:'tgl_spp',title:'Tanggal',width:60} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nomer = rowData.no_spp;
                    kode = rowData.kd_skpd;
                    jns = rowData.jns_spp;
                    val_ttd(kode);
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
        var jenis='1';
    $('#spp').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_sp2d_pkd/'+jenis,
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        //loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_sp2d',
    		title:'Nomor SP2D',
    		width:40},
            {field:'tgl_sp2d',
    		title:'Tanggal',
    		width:40},
            {field:'no_spm',
    		title:'Nomor SPM',
    		width:40},
            {field:'no_spp',
    		title:'Nomor SPP',
    		width:40},
            {field:'kd_skpd',
    		title:'SKPD',
    		width:40,
            align:"left"},
            {field:'keperluan',
    		title:'Keterangan',
    		width:140,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          no_spp = rowData.no_spp;
          no_spm = rowData.no_spm;
          no_sp2d = rowData.no_sp2d;         
          kode  = rowData.kd_skpd;
          spd  = rowData.no_spd;
          tg  = rowData.tgl_spp;
          tgspm  = rowData.tgl_spm;
          tgsp2d  = rowData.tgl_sp2d;
          jn  = rowData.jns_spp;
          kep  = rowData.keperluan;
          np  = rowData.npwp;          
          bk  = rowData.bank;
          ning  = rowData.no_rek;
          status  = rowData.status;          
          get(no_spp,no_spm,no_sp2d,kode,spd,tg,tgspm,tgsp2d,jn,kep,np,bk,ning,status);                                            
        },
        onDblClickRow:function(rowIndex,rowData){
            section1();
        }
    });
    }); 
    $(function(){
            $('#dn').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/skpd_2',  
                    idField:'kd_skpd',                    
                    textField:'kd_skpd',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'kd_skpd',title:'kode',width:60},  
                        {field:'nm_skpd',title:'nama',align:'left',width:80} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kode = rowData.kd_skpd;
                    $("#nmskpd").attr("value",rowData.nm_skpd);
                    validate_spd(kode);
                    }   
                });
           });           
              
            $(function(){
            $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1',                
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true,
                    onLoadSuccess:function(data){
                      detail1();                                           
                    },                    
                    columns:[[  
                        {field:'no_spd',title:'No SPD',width:30},  
                        {field:'tgl_spd',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd = rowData.no_spd;                                  
                    append_jak()                                                       
                    }    
                });
           });
           
   
  $(function(){
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
             
   
        
    function validate_spd(kode){
        //alert(kode);
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
           
        function detail1(){
        $(function(){
	   	    var no_spp = document.getElementById('no_spp').value;            
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({spp:no_spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_spp();                        
                    },
                onSelect:function(rowIndex,rowData){
                kd = rowIndex;                                               
                },   
                 onAfterEdit:function(rowIndex, rowData, changes){
								kd_rek5=rowData.kdrek5;
                                nm_rek5=rowData.nmrek5;
                                nilai=rowData.nilai1;
                                kd=rowIndex;
								dsimpan(kd_rek5,nm_rek5,nilai,kd);       	                                  
							 },                			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kdrek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:570
					},
                    {field:'nilai1',
					 title:'Nilai',
					 width:100,
                     align:'right',
					 editor:{type:"numberbox"					     
							} 
                     }
                      
				]]	
			
			});
  	

		});
        }
        
        function detail(){
        $(function(){
	   	    var no_spp = '';            
			$('#dg1').edatagrid({
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
					{field:'kdrek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:570
					},
                    {field:'nilai1',
					 title:'Nilai',
					 width:100,
                     align:'right',
					 editor:{type:"numberbox"					     
							} 
                     }
                      
				]]	
			
			});
  	

		});
        }
        
        function delete_jak(){                     
            $('#dg1').datagrid('deleteRow');
        } 
        function append_jak(){            
            $('#dg1').datagrid('appendRow',{kdrek5:'1110302',nmrek5:'Uang Persediaan',nilai1:'0'});
        } 
       
        function get(no_spp,no_spm,no_sp2d,kd_skpd,no_spd,tgl_spp,tgl_spm,tgl_sp2d,jns_spp,keperluan,npwp,bank,rekening,status){
        $("#no_spp").attr("value",no_spp);
        $("#no_spm").attr("value",no_spm);
        $("#no_sp2d").attr("value",no_sp2d);
        $("#dn").combogrid("setValue",kd_skpd);
        $("#sp").combogrid("setValue",no_spd);
        $("#ddspp").datebox("setValue",tgl_spp);
        $("#ddspm").datebox("setValue",tgl_spm);
        $("#dd").datebox("setValue",tgl_sp2d);        
        $("#ketentuan").attr("Value",keperluan);
        $("#jns_beban").attr("Value",jns_spp);
        $("#npwp").attr("Value",npwp);       
        $("#bank1").attr("Value",bank);
        $("#rekening").attr("Value",rekening);
         tombol(status);           
        }
		
        function kosong(){
        cdate = '<?php echo date("d-m-y"); ?>';
        $("#no_spp").attr("value",'');
        $("#no_spm").attr("value",'');
        $("#no_sp2d").attr("value",'');
        $("#dn").combogrid("setValue",'');
        $("#nmskpd").attr("value",'');
        $("#sp").combogrid("setValue",'');
        $("#ddspp").datebox("setValue",cdate);
        $("#ddspm").datebox("setValue",cdate);
        $("#dd").datebox("setValue",cdate);        
        $("#ketentuan").attr("Value",'');
        $("#jns_beban").attr("Value",'');
        $("#npwp").attr("Value",'');        
        $("#bank1").attr("Value",'');
        $("#rekening").attr("Value",'');
        document.getElementById("p1").innerHTML="";
        document.getElementById("no_spp").focus();
        $("#dn").combogrid("clear");
        $("#sp").combogrid("clear");
        detail();
        tombolnew()
                    
        }

		function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		} 
              
        $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal" ).dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
        });
       
     function cetak(){
        var nom=document.getElementById("no_spp").value;
        $("#cspp").combogrid("setValue",nom);
        $("#dialog-modal").dialog('open');
    } 
    
    function keluar(){
        $("#dialog-modal").dialog('close');
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
     function setgrid(){
       $('#dg1').edatagrid({			  			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kdrek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:570
					},
                    {field:'nilai1',
					 title:'Nilai',
					 width:100,
                     align:'right',
					 editor:{type:"numberbox"					     
							} 
                     }
                      
				]]
                });
     }  
      
     
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();
             // setgrid()                                              
         });
     }
     
     function section4(){
         $(document).ready(function(){    
             $('#section4').click();                                               
         });
     }
     
     
     function hsimpan(){
        var a = document.getElementById('no_spp').value;
        var b = $('#ddspp').datebox('getValue');
        var a1 = document.getElementById('no_spm').value;
        var b1 = $('#ddspm').datebox('getValue');
        var a2 = document.getElementById('no_sp2d').value;
        var b2 = $('#dd').datebox('getValue');       
        var c = document.getElementById('jns_beban').value; 
        var d = '';
        var e = document.getElementById('ketentuan').value;
        var f = '';
        var f1 = '';
        var g = document.getElementById('bank1').value;
        var h = document.getElementById('npwp').value;
        var i = document.getElementById('rekening').value;
        var j = document.getElementById('nmskpd').value;
        var k = document.getElementById('rektotal1').value;
        var l = '';
        var m = '';
        var n = '';
        var o = '';
        //alert(kode+'/'+spd+'/'+a+'/'+a1+'/'+a2+'/'+b+'/'+b1+'/'+b2);
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cskpd:kode,cspd:spd,no_spp:a,no_spm:a1,no_sp2d:a2,tgl_spp:b,tgl_spm:b1,tgl_sp2d:b2,jns_spp:c,bulan:d,keperluan:e,nmskpd:j,rekanan:f,bank:g,npwp:h,rekening:i,nilai:k,kegi:o,nmkegi:l,prog:m,nmprog:n,dir:f1}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/simpan_spp",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan, Simpan sekali lagi untuk memsatikan');
                    //$('#spp').edatagrid('reload');
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                }
            }
         });
        });
    }
    
    function dsimpan(kd_rek5,nm_rek5,nilai,kd){
        var a = document.getElementById('no_spp').value;
         //alert(a);    
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
         //alert(rows); 
        for(var i=0;i<rows.length;i++){            
            ckdgiat  = rows[i].kdkegiatan;
            cnmgiat  = rows[i].nmkegiatan;
            ckdrek  = rows[i].kdrek5;
            cnmrek  = rows[i].nmrek5;
            cnilai   = rows[i].nilai1;
            cnilai_s   = rows[i].sis;           
            no=i+1;      
            $(document).ready(function(){      
            $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/dsimpan" ,
            data: ({cno_spp:a,cskpd:kode,cgiat:ckdgiat,crek:ckdrek,ngiat:cnmgiat,nrek:cnmrek,nilai:cnilai,sis:cnilai_s,kd:no}),
            dataType:"json"            
         });
        });
        }
    } 
    
        function hhapus(){				
            var spp = document.getElementById("no_spp").value;
            var spm = document.getElementById("no_spm").value;
            var sp2d = document.getElementById("no_sp2d").value;              
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hhapus_pkd';             			    
         	if (spp !=''){
				var del=confirm('Anda yakin akan menghapus SPP '+spp+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({spp:spp,spm:spm,sp2d:sp2d}),function(data){
                    status = data;                        
                    });
                    });				
				}
				} 
		}
        
          function getSelections(idx){
			//alert(idx);
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kdkegiatan);
			}
			return ids.join(':');
		}
        
        function getSelections1(idx){
			//alert(idx);
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
		var spp = document.getElementById('no_spp').value;
        var nospp =spp.split("/").join("123456789");       
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
    function tombol(st){  
    if (st=='1'){
    $('#save').linkbutton('disable');
    $('#del').linkbutton('disable');
      
    document.getElementById("p1").innerHTML="Sudah di Buat SPM!!";
     } else {
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
    
    document.getElementById("p1").innerHTML="";
     }
    }	
    function tombolnew(){  
    
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
    
    } 
        
    function openWindow( url )
        {
       
        var no =nomer.split("/").join("123456789");
       // alert(no);
        window.open(url+'/'+no+'/'+kode+'/'+jns, '_blank');
        window.focus();
        }
    function cek(){
        var lcno = document.getElementById('no_spp').value;       
            if(lcno !=''){
               cek1();               
            } else {
                alert('Nomor SPP Tidak Boleh kosong')
                document.getElementById('no_spp').focus();
                exit();
            }
    }
    function cek1(){        
        var lcnospm = document.getElementById('no_spm').value;
            if(lcnospm !=''){
               cek2();               
            } else {
                alert('Nomor SPM Tidak Boleh kosong')
                document.getElementById('no_spm').focus();
                exit();
            }
    }    
    function cek2(){        
        var lcnosp2d = document.getElementById('no_sp2d').value;        
            if(lcnosp2d !=''){              
               hsimpan();               
            } else {
                alert('Nomor SP2D Tidak Boleh kosong')
                document.getElementById('no_sp2d').focus();
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
  <!-- <?php echo form_open('tukd/simpan', array('class' => 'basic')); ?> -->
               
<table border='0' style="font-size:11px" >
 
 <tr>
   <td >No SP2D</td>
   <td><input type="text" name="no_sp2d" id="no_sp2d" onclick="javascript:select();" /></td>
   <td>Tgl SP2D </td>
   <td><input id="dd" name="dd" type="text" /></td>
 </tr>
 <tr>
   <td >No SPM </td>
   <td><input type="text" name="no_spm" id="no_spm" onclick="javascript:select();" /></td>
   <td>Tgl SPM </td>
   <td><input id="ddspm" name="ddspm" type="text" /></td>
 </tr>
 <tr>   
   <td width="8%" >No SPP</td>
   <td><input type="text" name="no_spp" id="no_spp" onclick="javascript:select();" /></td>
   <td>Tgl SPP </td>
   <td><input id="ddspp" name="ddspp" type="text" /></td>   
    </tr>
 <tr>
   <td width='8%'>SKPD</td>
   <td width="47%" >     
      <input id="dn" name="dn" style="width:130px" /></td> 
   <td width='9%'>Beban</td>
   <td width="36%" ><select name="jns_beban" id="jns_beban">
     <option value="1">UP</option>
   </select></td>
 </tr>
 <tr>
   <td width='8%'>&nbsp;</td>
   <td width='47%'><textarea name="nmskpd" id="nmskpd" cols="40" rows="1" style="border: 0;"  readonly="true"></textarea></td>
   <td width='9%'>Keperluan</td>
   <td width='36%'><textarea name="ketentuan" id="ketentuan" cols="30" rows="2" ></textarea></td>
 </tr>
 <tr>
   <td width='8%'>No SPD</td>
   <td><input id="sp" name="sp" style="width:150px" /></td>
   <td width='9%'>Bank</td>
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
   <td width='47%'><input type="text" name="npwp" id="npwp" value="" /></td>
   <td width='9%'>Rekening</td>
   <td width='36%'><input type="text" name="rekening" id="rekening"  value="" /></td>
 </tr>

        

    <tr>
                <td colspan="4">
                  <div align="right"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true"  onclick="javascript:cek();javascript:$('#dg1').edatagrid('addRow');javascript:$('#dg1').edatagrid('reload');">Simpan</a>
                        <a id="del"class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section4();">Hapus</a>
                        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section4();">Kembali</a>
                        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a></div></td>                
            </tr>
    </table>
 
         <table id="dg1" title="Input Detail SPP" style="width:850%;height:150%;" >  
        
        </table>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal"  style="width:90px" align="rigth" readonly="true" >
            <input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:90px" align="rigth" readonly="true" >  
    <!-- <?php echo form_close(); ?> -->
    

   </p>
    </div>
    
<!--javascript:$('#dg1').edatagrid('addRow');javascript:$('#dg1').edatagrid('reload');-->
   

</div>

</div> 

<div id="dialog-modal" title="CETAK SPP">
    
    <p class="validateTips">SILAHKAN PILIH SPP</p>  
    
    <fieldset>
    <table>
        <tr>            
            <td width="110px">NO SPP:</td>
            <td><input id="cspp" name="cspp" style="width: 170px;" /></td>
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
 	
</body>

</html>