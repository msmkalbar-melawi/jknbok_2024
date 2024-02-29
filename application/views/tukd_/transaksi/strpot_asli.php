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
            $('#ctrmpot').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_trmpot',  
                    idField:'no_bukti',                    
                    textField:'no_bukti',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_bukti',title:'Bukti',width:60},  
                        {field:'tgl_bukti',title:'Tanggal',align:'left',width:60},
                        {field:'no_sp2d',title:'SP2D',width:60} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kode = rowData.no_bukti;
                    dns = rowData.kd_skpd;
                    val_ttd(dns);
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
     $('#pot_out').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_pot_out',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_bukti',
    		title:'Nomor Bukti',
    		width:40},
            {field:'tgl_bukti',
    		title:'Tanggal Bukti',
    		width:40},            
            {field:'ket',
    		title:'Keterangan',
    		width:140,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          bukti = rowData.no_bukti;
          trm = rowData.no_terima          
          tgl  = rowData.tgl_bukti;
          st   = rowData.status;
          ket = rowData.ket;       
          getpot(bukti,trm,tgl,st,ket);                                                      
        },
        onDblClickRow:function(rowIndex,rowData){
            section2();   
        }
    });
    }); 
        
              
    $(function(){
            $('#trmpot').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/trmpot_',  
                    idField:'no_bukti',                    
                    textField:'no_bukti',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_bukti',title:'No',width:60},  
                        {field:'tgl_bukti',title:'Tanggal',align:'left',width:30} 
                          
                    ]],
                     onSelect:function(rowIndex,rowData){
                        no_terima = rowData.no_bukti;                                                                       
                        dns  = rowData.kd_skpd;                        
                        jns  = rowData.jns_spp;
                        nm  = rowData.nm_skpd;
                        npwp = rowData.npwp;
                        ket = rowData.ket;       
                        get(dns,nm,jns,npwp,ket);                      
                        pot();                                                              
                    }  
                });
           });

        $(function(){
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true"                                  
			});
		}); 
    
        $(function(){
			$('#pot1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true"
                                  
			});
		}); 
        
        
         function pot(){
         $(function(){	   	                    
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot_in',
                queryParams:({bukti:no_terima}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                 
                      pot_pilih();                                 
                      },
                 onClickRow:function(rowIndex, rowData){
								rk=rowData.kd_rek5;
                                nrek=rowData.nm_rek5;
                                nila=rowData.nilai;                                
								dsimpan(rk,nrek,nila);
                                pot_pilih();
							 },		                              			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
                     {field:'id',
    		          title:'ID',
                      hidden:true    		
                    },                    
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
                     align:'right'
                     }                      
				]]
			});
  	

		});
        }
        

        function pot1(){
        $(function(){
	   	    var bukti='';                              
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot_in',
                queryParams:({bukti:bukti}),
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
					{field:'kd_rek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:530
					},                    
                    {field:'nilai',
					 title:'Nilai',
					 width:100,
                     align:'right'
                     }                      
				]]	
			
			});
  	

		});
        }
        
        function pot_pilih(){
        $(function(){
	   	    var bukti = document.getElementById('no_bukti').value; 
               //alert(bukti) ;          
			$('#pot1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot_out',
                queryParams:({bukti:bukti}),
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
                                dsimpan(rekeing,nrekeing,nilai);       	                                  
							 },                			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
                     {field:'id',
    		          title:'ID',
                      hidden:true    		
                    },                    
					{field:'kd_rek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:560
					},                    
                    {field:'nilai',
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
              
        function get(kd_skpd,nm_skpd,jns_spp,npwp,ket){
        $("#dn").attr("value",kd_skpd);
        $("#npwp").attr("Value",npwp);
        $("#nmskpd").attr("Value",nm_skpd);
        $("#jns_beban").attr("Value",jns_spp);
        $("#ketentuan").attr("Value",ket);            
        }
                  
        function getpot(no_bukti,no_terima,tgl_bukti,status,ket){
            //alert(no_bukti+no_sp2d+tgl_bukti+status+ket);
        $("#no_bukti").attr("value",no_bukti);
        $("#trmpot").combogrid("setValue",no_terima);
        $("#dd").datebox("setValue",tgl_bukti);
        $("#ketentuan").attr("value",ket);       
        tombol(status);                   
        }
		
        function kosong(){
        $("#no_bukti").attr("value",'');
        $("#dd").datebox("setValue",'');
        $("#trmpot").combogrid("setValue",'');        
        $("#dn").attr("value",'');        
        $("#jns_beban").attr("Value",'');
        $("#npwp").attr("Value",'');        
        $("#nmskpd").attr("Value",'');
        $("#ketentuan").attr("value",'');   
        document.getElementById("p1").innerHTML="";        
        pot1();
        $("#trmpot").combogrid("clear");
        //tombolnew();      
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
        $("#dialog-modal").dialog('open');
    } 
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    }   
     function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#sp2d').edatagrid({
	       url: '<?php echo base_url(); ?>/index.php/tukd/load_sp2d',
         queryParams:({cari:kriteria})
        });        
     });
    }
        
        function hsimpan(){        
        var a = document.getElementById('no_bukti').value;
        var b = $('#dd').datebox('getValue');  
        var c = document.getElementById('jns_beban').value;       
        var d = document.getElementById('ketentuan').value;       
        var e = document.getElementById('nmskpd').value;
        var f = document.getElementById('dn').value;
        var g = document.getElementById('npwp').value;
        var h = document.getElementById('rektotal1').value;       
        // alert(no_terima+'/'+a+'/'+b+'/'+c+'/'+d+'/'+e+'/'+f+'/'+g+'/'+h);       
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({no_bukti:a,tgl_bukti:b,no_terima:no_terima,jns_spp:c,ket:d,kd_skpd:f,nm_skpd:e,npwp:g,nilai:h}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/simpan_strpot",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan');
                    $('#pot_out').edatagrid('reload');
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                }
            }
         });
        });
        }
        
        function dsimpan(rek,nama,nilai){		
		var bukti = document.getElementById('no_bukti').value;
        //alert(rek+nama+nilai);
        if(bukti !=''){
           $(function(){      
            $.ajax({
            type: 'POST',
            data: ({bukti:bukti,kd_rek5:rek,nm_rek5:nama,nilai:nilai}),
            dataType:"json",
            url:'<?php echo base_url(); ?>/index.php/tukd/psimpan_str'
            });
          });            
            } else {
                alert('Nomor Bukti Tidak Boleh kosong')
                document.getElementById('no_bukti').focus();
                exit();
            }

		} 
              

                  
         function hhapus(){				
            var nbukti = document.getElementById("no_bukti").value;            
                        
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hapus_strpot';             			    
         	if (nbukti !=''){
				var del=confirm('Anda yakin akan menghapus Setor Potongan NO  '+nbukti+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:nbukti}),function(data){
                      status = data;            
                    });
                    });
				
				}
				} 
		}
        
        
        
     
        
        function load_sum_pot(){                
		var no_bukti = document.getElementById('no_bukti').value;              
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({bukti:no_bukti}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_str_pot",
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
     
     
     function tombol(st){  
     if (st=='1'){
     $('#save').linkbutton('disable');
     $('#del').linkbutton('disable');
     $('#poto').linkbutton('disable');       
     document.getElementById("p1").innerHTML="Sudah di CAIRKAN!!";
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
    }
    
    function openWindow( url )
        {
      
        var no =kode.split("/").join("123456789");
       // alert(no);
        window.open(url+'/'+no+'/'+dns, '_blank');
        window.focus();
        }
    function cek(){
        var lcno = document.getElementById('no_bukti').value;
		var b = $('#dd').datebox('getValue');

        //alert(lcno);
            if(lcno !='' && b !=''){
               hsimpan();
               //detsimpan();               
            } else {
                alert('Nomor Kas atau Tanggal Tidak Boleh kosong')
                document.getElementById('no_bukti').focus();
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

<h3><a href="#" id="section1" onclick="javascript:$('#pot_out').edatagrid('reload')">List Setor Potongan</a></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>                       
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="pot_out" title="List " style="width:870px;height:450px;" >  
        </table>
                  
        
    </p> 
    </div>

<h3><a href="#" id="section2" onclick="javascript:$('#dg').edatagrid('reload')" >Input Setor Potongan</a></h3>
   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>
  <!-- <?php echo form_open('tukd/simpan', array('class' => 'basic')); ?> -->
               
<table border='0' style="font-size:11px" >
 
 <tr>
   <td >No Bukti </td>
   <td><input type="text" name="no_bukti" id="no_bukti" onclick="javascript:select();" /></td>
   <td>Tanggal </td>
   <td><input id="dd" name="dd" type="text" /></td>
 </tr>
 <tr>
   <td >No Terima </td>
   <td><input type="text" name="trmpot" id="trmpot"  /></td>
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
 </tr>
 
 <tr>
   <td width='8%'>SKPD</td>
   <td width="53%" >     
      <input id="dn" name="dn" style="width:130px" readonly="true"/></td> 
   <td width='8%'>NPWP</td>
   <td width="31%" ><input type="text" name="npwp" id="npwp" value="" /></td> 
 </tr>
 <tr>
   <td width='8%'>&nbsp;</td>
   <td width='53%'><textarea name="nmskpd" id="nmskpd" cols="40" rows="1" style="border: 0;"  readonly="true"></textarea></td>
   <td width='8%'>Keterangan</td>
   <td width='31%'><textarea name="ketentuan" id="ketentuan" cols="30" rows="2" ></textarea></td>
 </tr>       
 
    <tr>
                <td colspan="4" align="right">
                <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#pot1').edatagrid('addRow');javascript:$('#pot1').edatagrid('reload');javascript:cek();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section1();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>                
                </td> <!--<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:();">cetak</a>-->               
            </tr>
    </table>
    
        <table id="pot" title="List Potongan" style="width:850px;height:150px;" >  
        </table><br/>
       
    <!-- <?php echo form_close(); ?> -->
    
        <table id="pot1" title="Potongan" style="width:850px;height:150px;" >  
        </table>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal"  style="width:140px" align="right" readonly="true" >
        <input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:140px" align="right" readonly="true" >
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
            <td><input id="ctrmpot" name="ctrmpot" style="width: 170px;" /></td>
        </tr>
        <tr>
            <td width="110px">Penandatangan:</td>
            <td><input id="ttd" name="ttd" style="width: 170px;" /></td>
        </tr>
       
    </table>  
    </fieldset>
    <a href="<?php echo site_url(); ?>/tukd/cetak_sp2d" class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false;">Cetak</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>
 	
</body>

</html>