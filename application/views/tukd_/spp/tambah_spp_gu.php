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
        $( "#dialog-modal-tr" ).dialog({
            height: 200,
            width: 700,
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
              nomer = rowData.no_spp;         
              kode  = rowData.kd_skpd;
              spd  = rowData.no_spd;
              tg  = rowData.tgl_spp;
              jn  = rowData.jns_spp;
              kep  = rowData.keperluan;
              np  = rowData.npwp;          
              bk  = rowData.bank;
              ning  = rowData.no_rek;
              status  = rowData.status;
              no_bukti= rowData.no_bukti;          
              get(nomer,kode,spd,tg,jn,kep,np,bk,ning,status,no_bukti);
              detail_trans()                                        
            },
            onDblClickRow:function(rowIndex,rowData){
                section1();
            }
        });
        
      //  $('#dn').combogrid({  
//                panelWidth:500,  
//                url: '<?php echo base_url(); ?>/index.php/tukd/skpd',  
//                    idField:'kd_skpd',                    
//                    textField:'kd_skpd',
//                    mode:'remote',  
//                    fitColumns:true,  
//                    columns:[[  
//                        {field:'kd_skpd',title:'kode',width:60},  
//                        {field:'nm_skpd',title:'nama',align:'left',width:80} 
//                          
//                    ]],
//                    onSelect:function(rowIndex,rowData){
//                    kode = rowData.kd_skpd;
//                    $("#nmskpd").attr("value",rowData.nm_skpd);
//                    validate_spd(kode);
//                    validate_tran(kode);
//                    }   
//                });
                
            $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1',                
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true,                                        
                    columns:[[  
                        {field:'no_spd',title:'No SPD',width:30},  
                        {field:'tgl_spd',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd = rowData.no_spd;                                                                  
                    }    
                });
                
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
                    //validate_tran();
                    detail_trans()                                  
                    }    
                });
                
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
                                        validate_tran(kode);              
        							  }                                     
        	});  
        }         
    
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
        
    function validate_tran(kode){
        //alert(kode);
           $(function(){
            $('#tr').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/trans/'+kode,  
                    idField:'no_bukti',  
                    textField:'no_bukti',
                    mode:'remote',  
                    fitColumns:true
                });
           });
        }
           
        function detail_trans(){
        $(function(){
            //alert(no_bukti);
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
        
        
        
        function get(no_spp,kd_skpd,no_spd,tgl_spp,jns_spp,keperluan,npwp,bank,rekening,status,no_bukti){
        $("#no_spp").attr("value",no_spp);
        $("#dn").attr("Value",kd_skpd);
        $("#sp").combogrid("setValue",no_spd);
        $("#tr").combogrid("setValue",no_bukti);
        $("#dd").datebox("setValue",tgl_spp);        
        $("#ketentuan").attr("Value",keperluan);
        $("#jns_beban").attr("Value",jns_spp);
        $("#npwp").attr("Value",npwp);       
        $("#bank1").attr("Value",bank);
        $("#rekening").attr("Value",rekening);
         tombol(status);           
        }
		
        function kosong(){
        
        $("#no_spp").attr("value",'');
        //$("#dn").combogrid("setValue",'');
        //$("#nmskpd").attr("value",'');
        $("#sp").combogrid("setValue",'');
        $("#tr").combogrid("setValue",'');
        $("#dd").datebox("setValue",'');        
        $("#ketentuan").attr("Value",'');
        $("#jns_beban").attr("Value",'');
        $("#npwp").attr("Value",'');        
        $("#bank1").attr("Value",'');
        $("#rekening").attr("Value",'');
        document.getElementById("p1").innerHTML="";
        document.getElementById("no_spp").focus();
        //$("#dn").combogrid("clear");
        $("#sp").combogrid("clear");
        $("#tr").combogrid("clear");        
        
        detail();
        
          
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
        var b = $('#dd').datebox('getValue');      
        var c = document.getElementById('jns_beban').value;               
        var e = document.getElementById('ketentuan').value;       
        var g = document.getElementById('bank1').value;
        var h = document.getElementById('npwp').value;
        var i = document.getElementById('rekening').value;
        var j = document.getElementById('nmskpd').value;         
        var k = document.getElementById('rektotal1').value;
        //alert(no_bukti+kode+spd);
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cskpd:kode,cspd:spd,no_spp:a,bukti:no_bukti,tgl_spp:b,jns_spp:c,keperluan:e,nmskpd:j,bank:g,npwp:h,rekening:i,nilai:k}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/simpan",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan');
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
                       
            no=i+1;      
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
    document.getElementById("p1").innerHTML="Sudah di Buat SPM!!";
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
       // alert(no);
        window.open(url+'/'+no+'/'+kode+'/'+jns, '_blank');
        window.focus();
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
 
 <tr>   
   <td width="6%" >No SPP</td>
   <td><input type="text" name="no_spp" id="no_spp" onclick="javascript:select();" /></td>
   <td>Tanggal</td>
   <td><input id="dd" name="dd" type="text" /></td>   
    </tr>
 <tr>
   <td width='6%'>SKPD</td>
   <td width="54%" >     
      <p>
        <input id="dn" name="dn"  readonly="true" style="width:130px; border: 0; " />
 </p>
      <p>       
        <input name="nmskpd" type="text" id="nmskpd" size="60" readonly="true" style="border: 0;"/>
      </p></td> 
   <td width='9%'>Beban</td>
   <td width="31%" ><select name="jns_beban" id="jns_beban">
     <option value="2">GU</option>
   </select></td>
 </tr>
 <tr>
   <td width='6%'>No SPD</td>
   <td width='54%'><input id="sp" name="sp" style="width:150px" /></td>
   <td width='9%'>Keperluan</td>
   <td width='31%'><textarea name="ketentuan" id="ketentuan" cols="30" rows="2" ></textarea></td>
 </tr>
 <tr>
   <td width='6%'>No Transaksi </td>
   <td><input id="tr" name="tr" style="width:150px" /></td>
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
   <td width='6%'>NPWP</td>
   <td width='54%'><input type="text" name="npwp" id="npwp" value="" /></td>
   <td width='9%'>Rekening</td>
   <td width='31%'><input type="text" name="rekening" id="rekening"  value="" /></td>
 </tr>

        

    <tr>
                <td colspan="4">
                  <div align="right"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true"  onclick="javascript:hsimpan();detsimpan();">Simpan</a>
                        <a id="del"class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section4();">Hapus</a>
                        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section4();">Kembali</a>
                        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a></div></td>                
            </tr>
    </table>
   
         <table id="dg1" title="Input Detail SPP" style="width:850%;height:300%;" >  
        
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