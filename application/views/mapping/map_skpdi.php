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
                height: 180,
                width: 500,
                modal: true,
                autoOpen:false                
            });                                                            
    });         
    var idx ='';    
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/mapping/load_skpd',
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
    	    {field:'kd_skpd',title:'Kode skpd',width:30},
            {field:'nm_skpd',title:'skpd',width:100},
            {field:'kd_skpd90',title:'Kode skpd 90',width:30},
            {field:'nm_skpd90',title:'skpd 90',width:100}
        ]],
        onSelect:function(rowIndex,rowData){    
           kd_skpd = rowData.kd_skpd;
           nm_skpd = rowData.nm_skpd;
           get(kd_skpd,nm_skpd);
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
        	    {field:'kd_skpd1',title:'Kode skpd',width:50,hidden:"true"},
                {field:'nm_skpd1',title:'Nama skpd',width:70},
                {field:'kd_skpd90',title:'Kode skpd 90',width:50,hidden:"true"},
                {field:'nm_skpd90',title:'Nama skpd 90',width:70}        
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

     $('#kd_skpd').combogrid({  
       panelWidth:700,  
       idField:'kd_skpd',  
       textField:'kd_skpd',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/ambil_skpd',  
       columns:[[  
           {field:'kd_skpd',title:'Kode skpd',width:100},  
           {field:'nm_skpd',title:'Nama skpd',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           // kd_prog = rowData.kd_program;
       
       //$("#kd_progr").attr("value",rowData.kd_program.toUpperCase());
           $("#nm_skpd").attr("value",rowData.nm_skpd.toUpperCase());
           $("#kd_skpd1").attr("value",rowData.kd_skpd.toUpperCase());
           $("#nm_skpd1").attr("value",rowData.nm_skpd.toUpperCase());

                                  
       }  
     }); 

     
     // $('#kd_skpds').combogrid({  
     //       panelWidth:700,  
     //       idField:'kd_skpd',  
     //       textField:'kd_skpd',  
     //       mode:'local',                      
     //       url:'<?php echo base_url(); ?>index.php/mapping/ambil_skpd',  
     //       columns:[[  
     //           {field:'kd_skpd',title:'Kode skpd',width:100},  
     //           {field:'nm_skpd',title:'Nama skpd',width:700}    
     //       ]],  
     //       onSelect:function(rowIndex,rowData){
     //           cskpd = rowData.kd_skpd;               
     //           $('#nm_skpd').attr('value',rowData.nm_skpd);                               
     //       } 
     // });

     $('#kd_skpd90').combogrid({  
           panelWidth:700,  
           idField:'kd_bidang_skpd',  
           textField:'kd_bidang_skpd',  
           mode:'remote',                      
           url:'<?php echo base_url(); ?>index.php/mapping/ambil_skpd90',  
           columns:[[  
               {field:'kd_bidang_skpd',title:'Kode skpd',width:100},  
               {field:'nm_bidang_skpd',title:'Nama skpd',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){              
               $('#nm_skpd90').attr('value',rowData.nm_bidang_skpd);                               
           } 
     });
               
     $('#jenis').combobox({           
        valueField:'value',  
        textField:'label',        
        data: [{label: '1 || Aktiva',value: '1'},
               {label: '2 || Hutang',value: '2'},
               {label: '3 || Pasiva',value: '3'},
               {label: '4 || Pendapatan',value: '4'},
               {label: '5 || Belanja',value: '5'},
               {label: '6 || Transfer',value: '6'},
               {label: '7 || Pembiayaan',value: '7'},
               {label: '8 || Pendapatan LO',value: '8'},
               {label: '9 || Beban LO',value: '9'},],
        onSelect:function(rec){            
            cjenis = rec.value;     
            cskpd = $('#skpd').combogrid('getValue');  
            frek = '';              
            $('#giat').combogrid('setValue','');
            $('#rek').combogrid('setValue','');
            $('#nmrek').attr('value','');
            $('#nmgiat').attr('value','');                     
            $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/akuntansi/load_ju_trskpd',queryParams:({kd:cskpd,jenis:cjenis})});
            var jj = 0;    
               $('#dg2').datagrid('selectAll');
               var rows = $('#dg2').datagrid('getSelections');     
		       for(var p=0;p<rows.length;p++){ 
                    cgiat   = rows[p].kd_kegiatan;
                    rek5    = rows[p].kd_rek5;                                       
                    if (cgiat==''){                        
                        if (jj>0){   
                            frek = frek+','+rek5;
                        } else {
                            frek = rek5;
                        }
                        jj++;
                    }                                                                                                                                                                                                  
            } 
            //alert(cjenis) ;     
            $('#dg2').edatagrid('unselectAll');     
            $('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/akuntansi/load_ju_rek',queryParams:({jenis:cjenis,giat:'',kd:cskpd,rek:frek})}) ;
                                 
        }
     });  
     
      $('#rk').combobox({           
        valueField:'value',  
        textField:'label',        
        data: [{label: 'Debet',value: 'D'},
               {label: 'Kredit',value: 'K'}]
      });
               
      $('#giat').combogrid({  
           panelWidth:700,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',                                 
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:140},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               cgiat = rowData.kd_kegiatan;  
               cjenis = $('#jenis').combobox('getValue');
               cskpd = $('#skpd').combogrid('getValue');
                frek = '';  
               $('#rek').combogrid('setValue','');                  
               $('#nmgiat').attr('value',rowData.nm_kegiatan);
               var jj = 0;    
               $('#dg2').datagrid('selectAll');
               var rows = $('#dg2').datagrid('getSelections');     
		       for(var p=0;p<rows.length;p++){ 
                    dgiat   = rows[p].kd_kegiatan;
                    rek5    = rows[p].kd_rek5;                                       
                    if (dgiat!=''){                        
                        if (jj>0){   
                            frek = frek+','+rek5;
                        } else {
                            frek = rek5;
                        }
                        jj++;
                    }                                                                                                                                                                                                  
               }   
               $('#dg2').edatagrid('unselectAll');            
               $('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/akuntansi/load_ju_rek',queryParams:({jenis:cjenis,giat:cgiat,kd:cskpd,rek:frek})}) ;                                                                                                                                                                           
           }  
        });
        
      $('#rek').combogrid({  
           panelWidth:700,  
           idField:'kd_rek5',  
           textField:'nm_rek5',  
           mode:'remote',                  
           columns:[[  
               {field:'kd_rek5',title:'Kode Rekening',width:140},  
               {field:'nm_rek5',title:'Nama Rekening',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){              
              $("#nmrek").attr("value",rowData.nm_rek5);                                                                                                                                                                       
           }  
      });
     
    });
    
    function kosong(){
        $("#kd_skpd").combogrid("setValue",'');
        $("#nm_skpd").attr("value",'');
        $("#kd_skpd1").attr("value",'');
        $("#nm_skpd1").attr("value",'');
    }
    
    function kosong2(){        
        $("#kd_skpd90").combogrid("setValue",'');
        $("#nm_skpd90").attr("value",'');
          
    }
    
    function set_grid(){
        $('#dg1').edatagrid({                                                                   
           columns:[[
        	    {field:'kd_skpd1',title:'Kode skpd',width:50},
              {field:'nm_skpd1',title:'Nama skpd',width:70},
              {field:'kd_skpd90',title:'Kode skpd 90',width:50},
              {field:'nm_skpd90',title:'Nama skpd 90',width:70}          
            ]]
        });                 
    }
    
    function set_grid2(){
        $('#dg2').edatagrid({                                                                   
           columns:[[
                {field:'hapus',title:'Hapus',width:19,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/assets/images/icon/cross.png' onclick='javascript:hapus_detail();'' />";}},
        	     {field:'kd_skpd90',title:'Kode skpd',width:50},
               {field:'nm_skpd90',title:'Nama skpd',width:70}          
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
        var cnm_skpd = document.getElementById('nm_skpd').value;
        var ckd_skpd = $('#kd_skpd').combogrid('getValue');
        $("#kd_skpd1").attr("value",ckd_skpd);
        $("#nm_skpd1").attr("value",cnm_skpd);

        kosong2();      
               
        if (ckd_skpd != '' && cnm_skpd != ''){            
            $("#dialog-modal").dialog('open'); 
            set_grid2();
            load_detail2();           
        } else {
            alert('Harap Isi Kode skpd, & Nama skpd ') ;         
        }
    }
    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg2').edatagrid('reload');
        kosong2();                        
    }   
    
    function load_detail(){
        var i = 0;
        var kk = $('#kd_skpd').combogrid('getValue');             
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/mapping/load_detail_skpd',
                data: ({no:kk}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    
                    if (n['kd_skpd90']=='' || n['kd_skpd90'] ==null){
                      ckd_skpd1    = '';
                      cnm_skpd1    = '';
                      ctotall  = 0;  
                    }else{
                      ckd_skpd1    = n['kd_skpd1'];
                      cnm_skpd1    = n['nm_skpd1'];
                      ckd_skpd90   = n['kd_skpd90'];
                    cnm_skpd90   = n['nm_skpd90'];
                                       
                    ctotall  = n['jml'];

                     $('#dg1').edatagrid('appendRow',{kd_skpd1:ckd_skpd1,nm_skpd1:cnm_skpd1,kd_skpd90:ckd_skpd90,nm_skpd90:cnm_skpd90});
                    

                    }

                    $("#totall").attr("value",ctotall);

                                                                                    
                                                                                                                                                                                                                                                                                                                                                                           
                    });                                                                           
                }
            });
           });   
           set_grid();
    }
    
    function load_detail2(){           
       $('#dg1').datagrid('selectAll');
       var rows = $('#dg1').datagrid('getSelections');             
       if (rows.length==0){
            set_grid2();
            exit();
       }                     
		for(var p=0;p<rows.length;p++){
            ckd_skpd90     = rows[p].kd_skpd90;
            cnm_skpd90    = rows[p].nm_skpd90;
            $('#dg2').edatagrid('appendRow',{kd_skpd90:ckd_skpd90,nm_skpd90:cnm_skpd90});            
        }
        $('#dg1').edatagrid('unselectAll');
    } 
    
    function get(kd_skpd,nm_skpd){
        $('#nm_skpd').attr('value',nm_skpd);
        $("#kd_skpd").combogrid("setValue",kd_skpd);
        $('#nm_skpd1').attr('value',nm_skpd);
        $("#kd_skpd1").attr("value",kd_skpd);
    }
    
    function hapus_giat(){         
         var rows = $('#dg1').edatagrid('getSelected'); 
         var ckd_skpd90 = rows.kd_skpd90;        
         var cnm_skpd90 =  rows.nm_skpd90;         
         
         var tny = confirm('Yakin Ingin Menghapus Data, skpd : '+cnm_skpd90);
         if (tny==true){                                      
             $('#dg1').edatagrid('deleteRow',idx);
         }              
    }
    
    function hapus_detail(){
        var rows = $('#dg2').edatagrid('getSelected');
        ckd_skpd90 = rows.ckd_skpd90;
        cnm_skpd90 = rows.nm_skpd90;
        

        
        var idx = $('#dg2').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, skpd : '+ckd_skpd90+' Nama : '+cnm_skpd90);
        if (tny==true){
            $('#dg2').edatagrid('deleteRow',idx);
            $('#dg1').edatagrid('deleteRow',idx);
            kosong2();
        }                     
    }
    
    function hapus(){
        var cnm_skpd = document.getElementById('nm_skpd').value;
        var ckd_skpd  = $('#kd_skpd').combogrid('getValue');
        var urll = '<?php echo base_url(); ?>index.php/mapping/hapus_mapping_skpd';
        var tny = confirm('Yakin Ingin Menghapus Data, skpd : '+ckd_skpd+'-'+cnm_skpd);        
        if (tny==true){
        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({kode:ckd_skpd}),
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
        }     
    }
    
    function append_save(){
        var ckd_skpd90  = $('#kd_skpd90').combogrid('getValue');
        var cnm_skpd90  = document.getElementById('nm_skpd90').value;
        var ckd_skpd1   = document.getElementById('kd_skpd1').value;
        var cnm_skpd1   = document.getElementById('nm_skpd1').value;
        var nil           = 1;  
        var ctotal        = document.getElementById('total').value;
        if (ckd_skpd90 != '' || cnm_skpd90 != ''){

            //hitung jumlah mappingan
            if (ctotal==''){
              ctotal=0;
            }
                ctotal = parseInt(ctotal)+parseInt(nil);
                $('#totall').attr('value',ctotal);
                $('#total').attr('value',ctotal);  

            
            $('#dg1').edatagrid('appendRow',{kd_skpd1:ckd_skpd1,nm_skpd1:cnm_skpd1,kd_skpd90:ckd_skpd90,nm_skpd90:cnm_skpd90});

            $('#dg2').edatagrid('appendRow',{kd_skpd90:ckd_skpd90,nm_skpd90:cnm_skpd90});
            
            kosong2();
       }else {
                alert('Kode dan Nama skpd tidak boleh kosong');
                exit();
        }
    }


    function cetak(){
        $("#dialog-modal-cetak").dialog('open');
    }
    function cetak1(){
        var url ="<?php echo site_url(); ?>/mapping/cetak_skpd/0";
        window.open(url);
        window.focus();
    }
    function cetak2(){
        var url ="<?php echo site_url(); ?>/mapping/cetak_skpd/1";
        window.open(url);
        window.focus();
    }
    function cetak3(){
        var url ="<?php echo site_url(); ?>/mapping/cetak_skpd/2";
        window.open(url);
        window.focus();
    }
                

    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#dg').edatagrid({
         url: '<?php echo base_url(); ?>/index.php/mapping/load_skpd',
         queryParams:({cari:kriteria})
        });        
     });
    }
    
    function simpan(){
        var cnm_skpd  = document.getElementById('nm_skpd').value;
        var ckd_skpd  = $('#kd_skpd').combogrid('getValue');
        var ctotall = document.getElementById('totall').value;
             
        if (ctotall=='' || ctotall==0){
            alert('Anda harus melakukan mapping skpd terlebih dahulu');
            exit();
        } 
        if (ckd_skpd==''){
            alert('Kode dan nama skpd Asal Tidak Boleh Kosong');
            exit();
        }      
                        
                                                      
                $('#dg1').datagrid('selectAll');
                var dgrid = $('#dg1').datagrid('getSelections');
 			           for(var w=0;w<dgrid.length;w++){
            				        ckd_skpd90 = dgrid[w].kd_skpd90;                                            
                            ckd_skpd1  = dgrid[w].kd_skpd1;
                            cnm_skpd90 = dgrid[w].nm_skpd90;
                            cnm_skpd1  = dgrid[w].nm_skpd1;
                                                                            
                            if (w>0) {
                                csql = csql+",('"+ckd_skpd1+"','"+cnm_skpd1+"','"+ckd_skpd90+"','"+cnm_skpd90+"')";
                            } else {
                                csql = " values('"+ckd_skpd1+"','"+cnm_skpd1+"','"+ckd_skpd90+"','"+cnm_skpd90+"')";                                            
                            }
                                                                                          
             			}                                                    
                        $(document).ready(function(){     
                            $.ajax({
                                type: "POST",   
                                dataType : 'json',                 
                                data: ({tabel:'mapping_skpd',sql:csql,kode:ckd_skpd}),
                                url: '<?php echo base_url(); ?>/index.php/mapping/simpan_skpd',
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
<h3><a href="#" id="section1" >List skpd</a></h3>
    <div>
      <p>Keterangan Warna:</p>
      <p>
        <button class="button button2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> Sudah dilakukan mapping<br />
        <button class="button button1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> Belum dilakukan mapping</p>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak Hasil Maping</a>               
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();load_detail();">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="List skpd" style="width:870px;height:600px;" >  
        </table>                          
    </p> 
    </div>   

<h3><a href="#" id="section2">MAPPING skpd</a></h3>
   <div  style="height: 350px;">
   <p>         
        <table align="center" style="width:100%;">                        
            <tr>
                <td>Kode skpd</td>
                <td><input id="kd_skpd" name="kd_skpd" style="width: 140px;" /></td>
                <td></td>
                <td>Nama skpd :</td> 
                <td><input type="text" id="nm_skpd" style="border:0;width: 400px;" readonly="true"/></td>                                
            </tr>                                                   
           <tr>
                <td colspan="5" align="right"><a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();load_detail();">Tambah</a>
                    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
		            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
  		            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>                                   
                </td>
           </tr>
        </table>          
        <table id="dg1" title="Kode skpd Permen 90" style="width:870px;height:350px;" >  
        </table>  
        <div id="toolbar" align="right">
    		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah skpd Permen 90</a>
   		    <!--<input type="checkbox" id="semua" value="1" /><a onclick="">Semua skpd Permen 90</a>-->
            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_giat();">Hapus skpd Permen 90</a>               		
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
            <td >Kode skpd</td>
            <td>:</td>
            <td><input id="kd_skpd1" name="kd_skpd1" style="width: 200px;" readonly="true" /></td>
            <td >Nama skpd</td>
            <td>:</td>
            <td><input type="text" id="nm_skpd1" name="nm_skpd1" readonly="true" style="border:0;width: 400px;" readonly="true"/></td>
        </tr>
        <tr>
            <td >Kode skpd 90</td>
            <td>:</td>
            <td><input id="kd_urusan1" name="kd_urusan1" style="width: 200px;" readonly="true" /></td>
            <td >Nama skpd</td>
            <td>:</td>
            <td><input type="text" id="nm_urusan1" name="nm_urusan1" readonly="true" style="border:0;width: 400px;" readonly="true"/></td>
        </tr>  

         <tr>
            <td >Kode skpd Permen 90</td>
            <td>:</td>
            <td><input id="kd_skpd90" name="kd_skpd90" style="width: 200px;" /></td>
            <td >Nama skpd Permen 90</td>
            <td>:</td>
            <td><input type="text" id="nm_skpd90" readonly="true" style="border:0;width: 400px;"/></td>
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
        <table id="dg2" title="Input skpd Permen 90" style="width:950px;height:270px;"  >  
        </table>  
     
    </fieldset>  
</div>


<div id="dialog-modal-cetak" title="Cetak skpd">
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