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
    
    <script>
    $(document).ready(function() {
      $("#accordion").accordion();            
      $( "#dialog-modal" ).dialog({
        height: 650,
        width: 1000,
        modal: true,
        autoOpen:false                
      }); 

       $( "#dialog-modal-cetak" ).dialog({
                height: 100,
                width: 500,
                modal: true,
                autoOpen:false                
            });                                                             
    });         
    var idx ='';    
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/mapping/load_program',
        idField:'no_voucher',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",rowStyler: function(index,row){
                    if (row.jml>0){
                      return 'background-color:#008CBA;';
                    }
                  },                       
        columns:[[
    	    {field:'kd_program',title:'Kode program',width:30},
            {field:'nm_program',title:'program',width:100}
        ]],
        onSelect:function(rowIndex,rowData){    
           kd_program = rowData.kd_program;
           nm_program = rowData.nm_program;
           get(kd_program,nm_program);
           load_detail();
        },
        onDblClickRow:function(rowIndex,rowData){         
            section2();
            load_detail();          
        }
     });   
     
     $('#dg1').edatagrid({		
        toolbar:'#toolbar',
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",            
        nowrap:"true",
        onSelect:function(rowIndex,rowData){
            idx = rowIndex;           
        }
     }); 
     
     $('#dg2').edatagrid({		       
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"true",
        loadMsg:"Tunggu Sebentar....!!",              
        nowrap:"false",
        columns:[[
                {field:'hapus',title:'Hapus',width:11,align:"center",
                formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';
                    }
                },
        	    {field:'kd_program1',title:'Kode program',width:50,hidden:"true"},
                {field:'nm_program1',title:'Nama program',width:70},
                {field:'kd_program90',title:'Kode program 90',width:50,hidden:"true"},
                {field:'nm_program90',title:'Nama program 90',width:70}        
            ]]
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

     $('#kd_program').combogrid({  
       panelWidth:700,  
       idField:'kd_program',  
       textField:'kd_program',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/ambil_program',  
       columns:[[  
           {field:'kd_program',title:'Kode program',width:100},  
           {field:'nm_program',title:'Nama program',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           // kd_prog = rowData.kd_program;
       
       //$("#kd_progr").attr("value",rowData.kd_program.toUpperCase());
           $("#nm_program").attr("value",rowData.nm_program.toUpperCase());
           $("#kd_program1").attr("value",rowData.kd_program.toUpperCase());
           $("#nm_program1").attr("value",rowData.nm_program.toUpperCase());

                                  
       }  
     }); 

     
     // $('#kd_programs').combogrid({  
     //       panelWidth:700,  
     //       idField:'kd_program',  
     //       textField:'kd_program',  
     //       mode:'local',                      
     //       url:'<?php echo base_url(); ?>index.php/mapping/ambil_program',  
     //       columns:[[  
     //           {field:'kd_program',title:'Kode program',width:100},  
     //           {field:'nm_program',title:'Nama program',width:700}    
     //       ]],  
     //       onSelect:function(rowIndex,rowData){
     //           cskpd = rowData.kd_skpd;               
     //           $('#nm_program').attr('value',rowData.nm_program);                               
     //       } 
     // });

     $('#kd_program90').combogrid({  
           panelWidth:700,  
           idField:'kd_program',  
           textField:'kd_program',  
           mode:'remote',                      
           url:'<?php echo base_url(); ?>index.php/mapping/ambil_program90',  
           columns:[[  
               {field:'kd_program',title:'Kode program',width:100},  
               {field:'nm_program',title:'Nama program',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){              
               $('#nm_program90').attr('value',rowData.nm_program);                               
           } 
     });
               

     
    });
    
    function kosong(){
        $("#kd_program").combogrid("setValue",'');
        $("#nm_program").attr("value",'');
        $("#kd_program1").attr("value",'');
        $("#nm_program1").attr("value",'');
        $('#kd_program').combogrid('enable');
    }
    
    function kosong2(){        
        $("#kd_program90").combogrid("setValue",'');
        $("#nm_program90").attr("value",'');
          
    }
    
    function set_grid(){
        $('#dg1').edatagrid({                                                                   
           columns:[[
        	    {field:'kd_program1',title:'Kode program',width:50},
              {field:'nm_program1',title:'Nama program',width:70},
              {field:'kd_program90',title:'Kode program 90',width:50},
              {field:'nm_program90',title:'Nama program 90',width:70}          
            ]]
        });               
    }
    
    function set_grid2(){
        $('#dg2').edatagrid({                                                                   
           columns:[[
                {field:'hapus',title:'Hapus',width:19,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/assets/images/icon/cross.png' onclick='javascript:hapus_detail();'' />";}},
        	     {field:'kd_program90',title:'Kode program',width:50},
               {field:'nm_program90',title:'Nama program',width:70}          
            ]]
        });                 
    }
    
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });    
         set_grid();              
     }
     
     function section2(){
         $(document).ready(function(){                
             $('#section2').click(); 
         });        
         set_grid();                
     }
     
     function tambah(){
        var cnm_program = document.getElementById('nm_program').value;
        var ckd_program = $('#kd_program').combogrid('getValue');
        $("#kd_program1").attr("value",ckd_program);
        $("#nm_program1").attr("value",cnm_program);
        kosong2();      
               
        if (ckd_program != '' && cnm_program != ''){            
            $("#dialog-modal").dialog('open'); 
            set_grid2();
            load_detail2();           
        } else {
            alert('Harap Isi Kode program, & Nama program ') ;         
        }
    }
    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg2').edatagrid('reload');
        kosong2();                        
    }   
    
    function load_detail(){
        var i = 0;
        var kk = $('#kd_program').combogrid('getValue');             
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/mapping/load_detail_program',
                data: ({no:kk}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    
                    if (n['kd_program90']=='' || n['kd_program90'] ==null){
                      ckd_program1    = '';
                      cnm_program1    = '';
                      ctotall  = 0;  
                    }else{
                      ckd_program1    = n['kd_program1'];
                      cnm_program1    = n['nm_program1'];
                      ckd_program90   = n['kd_program90'];
                    cnm_program90   = n['nm_program90'];
                                       
                    ctotall  = n['jml'];

                     $('#dg1').edatagrid('appendRow',{kd_program1:ckd_program1,nm_program1:cnm_program1,kd_program90:ckd_program90,nm_program90:cnm_program90});
                    

                    }

                    $("#totall").attr("value",ctotall);

                                                                                    
                                                                                                                                                                                                                                                                                                                                                                           
                    });                                                                           
                }
            });
           });   
           set_grid();

           $('#kd_program').combogrid('disable');
    }
    
    function load_detail2(){           
       $('#dg1').datagrid('selectAll');
       var rows = $('#dg1').datagrid('getSelections');             
       if (rows.length==0){
            set_grid2();
            exit();
       }                     
		for(var p=0;p<rows.length;p++){
            ckd_program90     = rows[p].kd_program90;
            cnm_program90    = rows[p].nm_program90;
            $('#dg2').edatagrid('appendRow',{kd_program90:ckd_program90,nm_program90:cnm_program90});            
        }
        $('#dg1').edatagrid('unselectAll');
    } 
    
    function get(kd_program,nm_program){
        $('#nm_program').attr('value',nm_program);
        $("#kd_program").combogrid("setValue",kd_program);
        $('#nm_program1').attr('value',nm_program);
        $("#kd_program1").attr("value",kd_program);
    }
    
    function hapus_giat(){         
         var rows = $('#dg1').edatagrid('getSelected'); 
         var ckd_program90 = rows.kd_program90;        
         var cnm_program90 =  rows.nm_program90;         
         
         var tny = confirm('Yakin Ingin Menghapus Data, program : '+cnm_program90);
         if (tny==true){                                      
             $('#dg1').edatagrid('deleteRow',idx);
         }              
    }
    
    function hapus_detail(){
        var rows = $('#dg2').edatagrid('getSelected');
        ckd_program90 = rows.ckd_program90;
        cnm_program90 = rows.nm_program90;
        

        
        var idx = $('#dg2').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, program : '+ckd_program90+' Nama : '+cnm_program90);
        if (tny==true){
            $('#dg2').edatagrid('deleteRow',idx);
            $('#dg1').edatagrid('deleteRow',idx);
            kosong2();
        }                     
    }
    
    function hapus(){
        var cnm_program = document.getElementById('nm_program').value;
        var ckd_program  = $('#kd_program').combogrid('getValue');
        var urll = '<?php echo base_url(); ?>index.php/mapping/hapus_mapping_program';
        var tny = confirm('Yakin Ingin Menghapus Data, program : '+ckd_program+'-'+cnm_program);        
        if (tny==true){
        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({kode:ckd_program}),
                 success:function(data){
                        status = data.pesan;
                        if (status=='1'){
                            alert('Data Berhasil Terhapus');         
                        } else {
                            alert('Gagal Hapus');
                        }        
                 }
                 
                });           
        });
        $('#dg').edatagrid('reload');
        }     
    }
    
    function append_save(){
        var ckd_program90  = $('#kd_program90').combogrid('getValue');
        var cnm_program90  = document.getElementById('nm_program90').value;
        var ckd_program1   = document.getElementById('kd_program1').value;
        var cnm_program1   = document.getElementById('nm_program1').value;
        var nil           = 1;  
        var ctotal        = document.getElementById('total').value;
        if (ckd_program90 != '' || cnm_program90 != ''){

            //hitung jumlah mappingan
            if (ctotal==''){
              ctotal=0;
            }
                ctotal = parseInt(ctotal)+parseInt(nil);
                $('#totall').attr('value',ctotal);
                $('#total').attr('value',ctotal);  

            
            $('#dg1').edatagrid('appendRow',{kd_program1:ckd_program1,nm_program1:cnm_program1,kd_program90:ckd_program90,nm_program90:cnm_program90});

            $('#dg2').edatagrid('appendRow',{kd_program90:ckd_program90,nm_program90:cnm_program90});
            
            kosong2();
       }else {
                alert('Kode dan Nama program tidak boleh kosong');
                exit();
        }
    }

    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#dg').edatagrid({
         url: '<?php echo base_url(); ?>/index.php/mapping/load_program',
         queryParams:({cari:kriteria})
        });        
     });
    }

     function cetak(){
        $("#dialog-modal-cetak").dialog('open');
    }
    function cetak1(){
        var url ="<?php echo site_url(); ?>/mapping/cetak_program/0";
        window.open(url);
        window.focus();
    }
    function cetak2(){
        var url ="<?php echo site_url(); ?>/mapping/cetak_program/1";
        window.open(url);
        window.focus();
    }
    function cetak3(){
        var url ="<?php echo site_url(); ?>/mapping/cetak_program/2";
        window.open(url);
        window.focus();
    }
      
    
    function simpan(){
        var cnm_program  = document.getElementById('nm_program').value;
        var ckd_program  = $('#kd_program').combogrid('getValue');
        var ctotall = document.getElementById('totall').value;
             
        if (ctotall=='' || ctotall==0){
            alert('Anda harus melakukan mapping program terlebih dahulu');
            exit();
        } 
        if (ckd_program==''){
            alert('Kode dan nama program Asal Tidak Boleh Kosong');
            exit();
        }      
                        
                                                      
                $('#dg1').datagrid('selectAll');
                var dgrid = $('#dg1').datagrid('getSelections');
 			           for(var w=0;w<dgrid.length;w++){
            				        ckd_program90 = dgrid[w].kd_program90;                                            
                            ckd_program1  = dgrid[w].kd_program1;
                            cnm_program90 = dgrid[w].nm_program90;
                            cnm_program1  = dgrid[w].nm_program1;
                                                                            
                            if (w>0) {
                                csql = csql+",('"+ckd_program1+"','"+cnm_program1+"','"+ckd_program90+"','"+cnm_program90+"')";
                            } else {
                                csql = " values('"+ckd_program1+"','"+cnm_program1+"','"+ckd_program90+"','"+cnm_program90+"')";                                            
                            }
                                                                                          
             			}                                                    
                        $(document).ready(function(){     
                            $.ajax({
                                type: "POST",   
                                dataType : 'json',                 
                                data: ({tabel:'mapping_program',sql:csql,kode:ckd_program}),
                                url: '<?php echo base_url(); ?>/index.php/mapping/simpan_program',
                                success:function(data){                        
                                    status = data.pesan;   
                                    if (status=='1'){               
                                        alert('Data Berhasil Tersimpan');
                                    } else{ 
                                        alert('Data Gagal Tersimpan');
                                    }                                             
                                }
                            });
                        });

                        section1();
                        $('#dg').edatagrid('reload');
                                     
    }  



    </script>
    <style type="text/css">
      .button2 {background-color: #008CBA;}
      .button1 {background-color: #fff;}
    </style>
 </head>
    
<body>

<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1" >List program</a></h3>
    <div>
      <p>Keterangan Warna:</p>
      <p>
        <button class="button button2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> Sudah dilakukan mapping<br />
        <button class="button button1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> Belum dilakukan mapping</p>
    <p align="right">
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak Hasil Maping</a>         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="List program" style="width:870px;height:600px;" >  
        </table>                          
    </p> 
    </div>   

<h3><a href="#" id="section2">MAPPING program</a></h3>
   <div  style="height: 350px;">
   <p>         
        <table align="center" style="width:100%;">                        
            <tr>
                <td>Kode program</td>
                <td><input id="kd_program" name="kd_program" style="width: 140px;" /></td>
                <td></td>
                <td>Nama program :</td> 
                <td><input type="text" id="nm_program" style="border:0;width: 400px;" readonly="true"/></td>                                
            </tr> 


            <tr>
                <td colspan="5"><font color="red">Catatan: Jika program yang akan di mapping tidak tersedia, silahkan input urusan dari program tersebut terlebih dahulu.</font></td>
            </tr>                                                   
           <tr>
                <td colspan="5" align="right"><a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Tambah</a>
                    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
		            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
  		            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>                                   
                </td>
           </tr>
        </table>          
        <table id="dg1" title="Kode program Permen 90" style="width:870px;height:350px;" >  
        </table>  
        <div id="toolbar" align="right">
    		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah program Permen 90</a>
   		    <!--<input type="checkbox" id="semua" value="1" /><a onclick="">Semua program Permen 90</a>-->
            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_giat();">Hapus program Permen 90</a>               		
        </div>
        <table align="center" style="width:100%;">
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td ></td>
            <td align="right">Total Mappingan: <input type="text" id="totall" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></td>
        </tr>
        </table>
                
   </p>
   </div>
   
</div>
</div>


<div id="dialog-modal" title="Input ">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table>
        <tr>
            <td >Kode program</td>
            <td>:</td>
            <td><input id="kd_program1" name="kd_program1" style="width: 200px;" readonly="true" /></td>
            <td >Nama program</td>
            <td>:</td>
            <td><input type="text" id="nm_program1" name="nm_program1" readonly="true" style="border:0;width: 400px;" readonly="true"/></td>
        </tr> 

         <tr>
            <td >Kode program Permen 90</td>
            <td>:</td>
            <td><input id="kd_program90" name="kd_program90" style="width: 200px;" /></td>
            <td >Nama program Permen 90</td>
            <td>:</td>
            <td><input type="text" id="nm_program90" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>                
      
    </table>  
    </fieldset>
    <fieldset>
    <table align="center">
        <tr>
            <td><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>                               
            </td>
        </tr>
    </table>   
    </fieldset>
    <fieldset>
        <table align="right">           
            <tr>
                <td>Jumlah Mapping</td>
                <td>:</td>
                <td><input type="text" id="total" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;"/></td>
            </tr>
        </table>
        <table id="dg2" title="Input program Permen 90" style="width:950px;height:270px;"  >  
        </table>  
     
    </fieldset>  
</div>

<div id="dialog-modal-cetak" title="Cetak Program">
    <fieldset>
    <table align="center" >
        
        <tr>
            <td align="center"><br>
                <a class="easyui-linkbutton" iconCls="icon-print" plain="false" onclick="javascript:cetak2();">Layar</a>
                <a class="easyui-linkbutton" iconCls="icon-print" plain="false" onclick="javascript:cetak1();">PDF</a>
                <a class="easyui-linkbutton" iconCls="icon-excel" plain="false" onclick="javascript:cetak3();">Export</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="false" onclick="javascript:keluar();">Kembali</a>
            </td>
        </tr>
    </table>   
    </fieldset>
    </div>

</body>

</html>