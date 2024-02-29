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

            $("#kode_org").hide();                                                           
    });         
    var idx ='';    
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/mapping/load_mapping',
        idField:'no_voucher',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",rowStyler: function(index,row){
                    if (row.status==1){
                      return 'background-color:#3be8ff;';
                    }else{
                      return 'background-color:#ffffff;';
                    }
                  },                       
        columns:[[
    	    {field:'kd_kegiatan',title:'Kode kegiatan',width:80},
            {field:'nm_kegiatan',title:'kegiatan',width:300},
            {field:'nm_skpd',title:'SKPD',width:300}
        ]],
        onSelect:function(rowIndex,rowData){    
           kd_kegiatan  = rowData.kd_kegiatan;
           nm_kegiatan  = rowData.nm_kegiatan;
           indikator    = rowData.indikator;
           kd_skpd      = rowData.kd_skpd;
           nm_skpd      = rowData.nm_skpd;
           get(kd_skpd,nm_skpd,kd_kegiatan,nm_kegiatan,indikator);
        },
        onDblClickRow:function(rowIndex,rowData){         
            section2();
          // $("#waktu_giat").attr("value",rowData.waktu_giat.toUpperCase());
          //  $("#sasaran_giat").attr("value",rowData.sasaran_giat.toUpperCase());
          //  $("#tu_mas").attr("value",rowData.tu_mas.toUpperCase());
          //  $("#tu_capai").attr("value",rowData.tu_capai.toUpperCase());
          //  $("#tu_kel").attr("value",rowData.tu_kel.toUpperCase());
          //  $("#tu_has").attr("value",rowData.tu_has.toUpperCase());
          //  $("#tk_capai").attr("value",rowData.tk_capai.toUpperCase());
          //  $("#tk_kel").attr("value",rowData.tk_kel.toUpperCase());
          //  $("#tk_has").attr("value",rowData.tk_has.toUpperCase());
          //  $("#lokasi").attr("value",rowData.lokasi.toUpperCase());
          load_detail();
          // if (rowData.status==1){
          //   $('#hapusbutton').linkbutton('disable');
          //   $('#tambah90button').linkbutton('disable');
          //   $('#hapus90button').linkbutton('disable');
          // }else{

          //   $('#hapusbutton').linkbutton('enable');
          //   $('#tambah90button').linkbutton('enable');
          //   $('#hapus90button').linkbutton('enable');

          // }
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
        	    {field:'kd_kegiatan1',title:'Kode kegiatan',width:30},
                {field:'nm_kegiatan1',title:'Nama kegiatan',width:30},
                {field:'kd_sub_kegiatan90',title:'Kode Subkegiatan 90',width:30},
                {field:'nm_kegiatan90',title:'Nama Subkegiatan 90',width:10,hidden:'true'},
                {field:'kd_kegiatan90',title:'Kode kegiatan 90',width:30},
                {field:'nm_kegiatan90',title:'Nama kegiatan 90',width:10,hidden:'true'},
                {field:'kd_program90',title:'Kode Program 90',width:30},
                {field:'nm_program90',title:'Nama Program 90',width:1,hidden:'true'}
                
            ]]
     });


     $('#kd_skpd').combogrid({  
       panelWidth:700,  
       idField:'kd_skpd',
       textField:'kd_skpd',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/ambil_skpd_mapping',  
       columns:[[  
           {field:'kd_skpd',title:'Kode skpd',width:100},  
           {field:'nm_skpd',title:'Nama skpd',width:400}
       ]],  
       onSelect:function(rowIndex,rowData){
           // kd_prog = rowData.kd_skpd;
       
       //$("#kd_progr").attr("value",rowData.kd_skpd.toUpperCase());
           $("#nm_skpd").attr("value",rowData.nm_skpd.toUpperCase());
           $("#kd_skpd1").attr("value",rowData.kd_skpd.toUpperCase());

           if(rowData.kd_skpd90=='' || rowData.kd_skpd90==null){
              alert('Silahkan lakukan mapping Kode SKPD / UPTD terlebih dahulu');
              exit();
           }else{
            $("#nm_skpd90").attr("value",rowData.nm_skpd90.toUpperCase());
            $("#kd_skpd90").attr("value",rowData.kd_skpd90.toUpperCase());
            get_kegiatan();
            get_kegiatan90();
            get_sub_kegiatan90();
            get_program90();                
           }
           
                                  
       }  
     });


     $('#kd_skpdcetak').combogrid({  
       panelWidth:700,  
       idField:'kd_skpd',
       textField:'kd_skpd',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/ambil_skpd_mapping',  
       columns:[[  
           {field:'kd_skpd',title:'Kode skpd',width:100},  
           {field:'nm_skpd',title:'Nama skpd',width:400}
       ]],  
       onSelect:function(rowIndex,rowData){
           // kd_prog = rowData.kd_skpd;
       
       //$("#kd_progr").attr("value",rowData.kd_skpd.toUpperCase());
           $("#nm_skpdcetak").attr("value",rowData.nm_skpd.toUpperCase());
           
           if(rowData.kd_skpd90=='' || rowData.kd_skpd90==null){
              alert('Silahkan lakukan mapping Kode SKPD / UPTD terlebih dahulu');
              exit();
           }else{
            $("#nm_skpd90cetak").attr("value",rowData.nm_skpd90.toUpperCase());
            $("#kd_skpd90cetak").attr("value",rowData.kd_skpd90.toUpperCase());                
           }
           
                                  
       }  
     });

     function get_kegiatan(){
        var ckd_skpd = $('#kd_skpd').combogrid('getValue');
        $('#kd_kegiatan').combogrid({  
       panelWidth:700,  
       idField:'kd_kegiatan',  
       textField:'kd_kegiatan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/ambil_kegiatan',
        queryParams:({skpd:ckd_skpd}), 
       columns:[[  
           {field:'kd_kegiatan',title:'Kode Kegiatan',width:100},  
           {field:'nm_kegiatan',title:'Nama Kegiatan',width:400}   
       ]],  
       onSelect:function(rowIndex,rowData){
           // kd_prog = rowData.kd_kegiatan;
       
       //$("#kd_progr").attr("value",rowData.kd_kegiatan.toUpperCase());
           $("#nm_kegiatan").attr("value",rowData.nm_kegiatan.toUpperCase());
           $("#indikator").attr("value",rowData.indikator);
           // $("#totala1").attr("value",rowData.pagu.toUpperCase());
           // $("#totala").attr("value",rowData.pagu1.toUpperCase());
           // $("#totallalua").attr("value",rowData.angg_lalu1.toUpperCase());
           // $("#nilai1").attr("value",rowData.pagu1.toUpperCase());
           // $("#nilai1a").attr("value",rowData.pagu.toUpperCase());
           // $("#nilailalu1").attr("value",rowData.angg_lalu1.toUpperCase());

           // $("#waktu_giat").attr("value",rowData.waktu_giat.toUpperCase());
           // $("#sasaran_giat").attr("value",rowData.sasaran_giat.toUpperCase());
           // $("#tu_mas").attr("value",rowData.tu_mas.toUpperCase());
           // $("#tu_capai").attr("value",rowData.tu_capai.toUpperCase());
           // $("#tu_kel").attr("value",rowData.tu_kel.toUpperCase());
           // $("#tu_has").attr("value",rowData.tu_has.toUpperCase());
           // $("#tk_capai").attr("value",rowData.tk_capai.toUpperCase());
           // $("#tk_kel").attr("value",rowData.tk_kel.toUpperCase());
           // $("#tk_has").attr("value",rowData.tk_has.toUpperCase());
           // $("#lokasi").attr("value",rowData.lokasi.toUpperCase());

           $("#kd_kegiatan1").attr("value",rowData.kd_kegiatan.toUpperCase());
           $("#nm_kegiatan1").attr("value",rowData.nm_kegiatan.toUpperCase());

                                  
       }  
     }); 
     }
     

     

function get_sub_kegiatan90(){
      var ckd_skpd =  document.getElementById('kd_skpd90').value;
        $('#kd_sub_kegiatan90').combogrid({  
           panelWidth:700,  
           idField:'kd_sub_kegiatan',  
           textField:'kd_sub_kegiatan',  
           mode:'remote',                      
           url:'<?php echo base_url(); ?>index.php/mapping/ambil_sub_kegiatan90',
           queryParams:({skpd:ckd_skpd}), 
           columns:[[  
               {field:'kd_sub_kegiatan',title:'Kode Sub kegiatan',width:100},  
               {field:'nm_sub_kegiatan',title:'Nama Sub kegiatan',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){              
               $('#nm_sub_kegiatan90').attr('value',rowData.nm_sub_kegiatan);
               $("#kd_kegiatan90").combogrid("setValue",rowData.kd_kegiatan90);
               $("#kd_program90").combogrid("setValue",rowData.kd_program90);

           } 
     });
}


function get_kegiatan90(){
         var ckd_skpd =  document.getElementById('kd_skpd90').value;
        $('#kd_kegiatan90').combogrid({  
           panelWidth:700,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',                      
           url:'<?php echo base_url(); ?>index.php/mapping/ambil_kegiatan90',
           queryParams:({kode:ckd_skpd}), 
           columns:[[  
               {field:'kd_kegiatan',title:'Kode kegiatan',width:100},  
               {field:'nm_kegiatan',title:'Nama kegiatan',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){              
               $('#nm_kegiatan90').attr('value',rowData.nm_kegiatan);
               $("#kd_program90").combogrid("setValue",rowData.kd_program90);                       
           } 
     });
}


function get_program90(){
         var ckd_skpd = document.getElementById('kd_skpd90').value;
        $('#kd_program90').combogrid({  

           panelWidth:700,  
           idField:'kd_program',  
           textField:'kd_program',  
           mode:'remote',                      
           url:'<?php echo base_url(); ?>index.php/mapping/ambil_programp90',
           queryParams:({kode:ckd_skpd}), 
           columns:[[  
               {field:'kd_program',title:'Kode Program',width:100},  
               {field:'nm_program',title:'Nama Program',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){              
               $('#nm_program90').attr('value',rowData.nm_program);                               
           } 
     });
}
    
               

     
    });
    
    function kosong(){
        $("#kd_kegiatan").combogrid("setValue",'');
        $("#nm_kegiatan").attr("value",'');
        $("#kd_skpd").combogrid("setValue",'');
        $("#nm_skpd").attr("value",'');
        $("#nm_skpd90").attr("value",'');
        $("#kd_skpd90").attr("value",'');
        $("#kd_kegiatan1").attr("value",'');
        $("#nm_kegiatan1").attr("value",'');
        $("#totala").attr("value",'');
        $("#totalb").attr("value",'');
        $("#totallalua").attr("value",'');
        $("#totallalub").attr("value",'');
        $("#totala1").attr("value",'');
        $('#kd_kegiatan').combogrid('enable');
        $('#kd_skpd').combogrid('enable');

        $('#dg1').edatagrid('reload');
        $("#waktu_giat").attr("value",'');
        $("#sasaran_giat").attr("value",'');
        $("#tu_mas").attr("value",'');
        $("#tu_capai").attr("value",'');
        $("#tu_kel").attr("value",'');
        $("#tu_has").attr("value",'');
        $("#tk_capai").attr("value",'');
        $("#tk_kel").attr("value",'');
        $("#tk_has").attr("value",'');
        $("#lokasi").attr("value",'');

        $('#hapusbutton').linkbutton('enable');
        $('#tambah90button').linkbutton('enable');
        $('#hapus90button').linkbutton('enable');
    }
    
    function kosong2(){        
        $("#kd_kegiatan90").combogrid("setValue",'');
        $("#nm_kegiatan90").attr("value",'');
        $("#kd_sub_kegiatan90").combogrid("setValue",'');
        $("#nm_sub_kegiatan90").attr("value",'');
        $("#kd_program90").combogrid("setValue",'');
        $("#nm_program90").attr("value",'');
        $("#nilai90").attr("value",'');
        $("#sisa").attr("value",'');
        $("#total90").attr("value",'');
        $("#total").attr("value",'');
        $("#nilailalu90").attr("value",'');
        $("#sisalalu").attr("value",'');
        $("#totallalu90").attr("value",'');
          
    }


    function kosong3(){        
        $("#kd_kegiatan90").combogrid("setValue",'');
        $("#nm_kegiatan90").attr("value",'');
        $("#kd_sub_kegiatan90").combogrid("setValue",'');
        $("#nm_sub_kegiatan90").attr("value",'');
        $("#kd_program90").combogrid("setValue",'');
        $("#nm_program90").attr("value",'');
        // $("#tahapan").attr("value",'');
        $("#nilai90").attr("value",'');
        $("#sisa").attr("value",'');
        $("#nilailalu90").attr("value",'');
        $("#sisalalu").attr("value",'');
          
    }

    function disablecombo(){
        $('#kd_kegiatan').combogrid('disable');
        $('#kd_skpd').combogrid('disable');
    }
    
    function set_grid(){
        $('#dg1').edatagrid({                                                                   
           columns:[[
        	   {field:'kd_kegiatan1',title:'Kode kegiatan',width:50},
                {field:'nm_kegiatan1',title:'Nama kegiatan',width:150},
               {field:'kd_sub_kegiatan90',title:'Kode Subkegiatan 90',width:50},
               {field:'nm_sub_kegiatan90',title:'Nama Subkegiatan 90',width:150},
               {field:'kd_kegiatan90',title:'Kode kegiatan 90',width:50},
               {field:'nm_kegiatan90',title:'Nama kegiatan 90',width:150},
               {field:'kd_program90',title:'Kode Program 90',width:50},
               {field:'nm_program90',title:'Nama Program 90',width:150}   
            ]]
        });               
    }
      
 


    function set_grid2(){
        $('#dg2').edatagrid({                                                                   
           columns:[[
                {field:'hapus',title:'Hapus',width:50,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/assets/images/icon/cross.png' onclick='javascript:hapus_detail();'' />";}},
        	     {field:'kd_kegiatan1',title:'Kode kegiatan',width:50},
                {field:'nm_kegiatan1',title:'Nama kegiatan',width:150},
               {field:'kd_sub_kegiatan90',title:'Kode Subkegiatan 90',width:50},
               {field:'nm_sub_kegiatan90',title:'Nama Subkegiatan 90',width:150},
               {field:'kd_kegiatan90',title:'Kode kegiatan 90',width:50},
               {field:'nm_kegiatan90',title:'Nama kegiatan 90',width:150},
               {field:'kd_program90',title:'Kode Program 90',width:50},
               {field:'nm_program90',title:'Nama Program 90',width:150}
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
        var cnm_kegiatan = document.getElementById('nm_kegiatan').value;
        var ckd_kegiatan = $('#kd_kegiatan').combogrid('getValue');
        $("#kd_kegiatan1").attr("value",ckd_kegiatan);
        $("#nm_kegiatan1").attr("value",cnm_kegiatan);
        
        var cek = document.getElementById('total').value;    

        if(cek=='' || cek==0){
            kosong2();      
        }else{
            kosong3();
        }

               
        if (ckd_kegiatan != '' && cnm_kegiatan != ''){            
            $("#dialog-modal").dialog('open'); 
            set_grid2();
            load_detail2();           
        } else {
            alert('Harap Isi Kode kegiatan, & Nama kegiatan ') ;         
        }
    }
    
    function keluar(){

        $("#dialog-modal").dialog('close');
        $('#dg2').edatagrid('reload');
        kosong2();                        
    }   
    
    function load_detail(){
        var i = 0;
        tstatus   = 0;
        var kk = $('#kd_kegiatan').combogrid('getValue');             
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/mapping/load_detail_mapping',
                data: ({no:kk}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    
                    if (n['kd_kegiatan90']=='' || n['kd_kegiatan90'] ==null){
                      ckd_kegiatan1    = '';
                      cnm_kegiatan1    = '';
                      ctotall   = 0;
                      
                    }else{
                        ckd_kegiatan1     = n['kd_kegiatan1'];
                        cnm_kegiatan1     = n['nm_kegiatan1'];
                        ckd_sub_kegiatan90= n['kd_sub_kegiatan90'];
                        cnm_sub_kegiatan90= n['nm_sub_kegiatan90'];
                        ckd_kegiatan90    = n['kd_kegiatan90'];
                        cnm_kegiatan90    = n['nm_kegiatan90'];
                        ckd_program90     = n['kd_program90'];
                        cnm_program90     = n['nm_program90'];
                        ctotall           = n['jml'];
                        cstatus           = n['status'];

                        tstatus=tstatus+angka(cstatus);

                        

                        if (tstatus>=1){
                          $('#hapusbutton').linkbutton('disable');
                          $('#tambah90button').linkbutton('disable');
                          $('#hapus90button').linkbutton('disable');
                          
                        }else{

                          $('#hapusbutton').linkbutton('enable');
                          $('#tambah90button').linkbutton('enable');
                          $('#hapus90button').linkbutton('enable');

                        }

                     $('#dg1').edatagrid('appendRow',{kd_kegiatan1:ckd_kegiatan1,nm_kegiatan1:cnm_kegiatan1,kd_sub_kegiatan90:ckd_sub_kegiatan90,nm_sub_kegiatan90:cnm_sub_kegiatan90,kd_kegiatan90:ckd_kegiatan90,nm_kegiatan90:cnm_kegiatan90,kd_program90:ckd_program90,nm_program90:cnm_program90});
                    

                    }

                    $("#totall").attr("value",ctotall);
                    $("#total").attr("value",ctotall);
                                                                                    
                                                                                                                                                                                                                                                                                                                                                                           
                    });                                                                           
                }
            });
           });   
           set_grid();

           $('#kd_kegiatan').combogrid('disable');
           $('#kd_skpd').combogrid('disable');
    }
    
    function load_detail2(){           
       $('#dg1').datagrid('selectAll');
       var rows = $('#dg1').datagrid('getSelections');             
       if (rows.length==0){
            set_grid2();
            exit();
       }                     
		for(var p=0;p<rows.length;p++){
            ckd_sub_kegiatan90      = rows[p].kd_sub_kegiatan90;
            cnm_sub_kegiatan90      = rows[p].nm_sub_kegiatan90;
            ckd_kegiatan90    = rows[p].kd_kegiatan90;
            cnm_kegiatan90    = rows[p].nm_kegiatan90;
            ckd_program90     = rows[p].kd_program90;
            cnm_program90     = rows[p].nm_program90;
            ckd_kegiatan1     = rows[p].kd_kegiatan1;
            cnm_kegiatan1     = rows[p].nm_kegiatan1;
            
            $('#dg2').edatagrid('appendRow',{kd_kegiatan1:ckd_kegiatan1,nm_kegiatan1:cnm_kegiatan1,kd_sub_kegiatan90:ckd_sub_kegiatan90,nm_sub_kegiatan90:cnm_sub_kegiatan90,kd_kegiatan90:ckd_kegiatan90,nm_kegiatan90:cnm_kegiatan90,kd_program90:ckd_program90,nm_program90:cnm_program90});            
        }
        $('#dg1').edatagrid('unselectAll');
    } 
    
    function get(kd_skpd,nm_skpd,kd_kegiatan,nm_kegiatan,indikator){
        $('#nm_skpd').attr('value',nm_skpd);
        $("#kd_skpd").combogrid("setValue",kd_skpd);
        $('#nm_kegiatan').attr('value',nm_kegiatan);
        $("#kd_kegiatan").combogrid("setValue",kd_kegiatan);
        $('#nm_kegiatan1').attr('value',nm_kegiatan);
        $("#kd_kegiatan1").attr("value",kd_kegiatan);
        $("#indikator").attr("value",indikator);
        
    }
    
    function hapus_giat(){         
         var rows = $('#dg1').edatagrid('getSelected'); 
         var ckd_kegiatan90 = rows.kd_kegiatan90;        
         var cnm_kegiatan90 =  rows.nm_kegiatan90;         
         
         var tny = confirm('Yakin Ingin Menghapus Data, kegiatan : '+cnm_kegiatan90);
         if (tny==true){                                      
             $('#dg1').edatagrid('deleteRow',idx);
         }              
    }
    
    function hapus_detail(){
        var ctotal      = angka(document.getElementById('total').value);
        var rows        = $('#dg2').edatagrid('getSelected');
        

        
        var idx = $('#dg2').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data ini??');
        if (tny==true){
            $('#dg2').edatagrid('deleteRow',idx);
            $('#dg1').edatagrid('deleteRow',idx);


            //hitung ulang total

            ctotal=ctotal-1;
            $('#total').attr('value',ctotal);

            kosong3();
        }                     
    }

    
    function hapus(){
        var cnm_kegiatan = document.getElementById('nm_kegiatan').value;
        var ckd_kegiatan  = $('#kd_kegiatan').combogrid('getValue');
        var urll = '<?php echo base_url(); ?>index.php/mapping/hapus_mapping_kegiatan';
        var tny = confirm('Yakin Ingin Menghapus Data, kegiatan : '+ckd_kegiatan+'-'+cnm_kegiatan);        
        if (tny==true){
        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({kode:ckd_kegiatan}),
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
        var ckd_sub_kegiatan90  = $('#kd_sub_kegiatan90').combogrid('getValue');
        var cnm_sub_kegiatan90  = document.getElementById('nm_sub_kegiatan90').value;
        
        var ckd_kegiatan90  = $('#kd_kegiatan90').combogrid('getValue');
        var cnm_kegiatan90  = document.getElementById('nm_kegiatan90').value;

        var ckd_program90  = $('#kd_program90').combogrid('getValue');
        var cnm_program90  = document.getElementById('nm_program90').value;

        
        var ckd_kegiatan1   = document.getElementById('kd_kegiatan1').value;
        var cnm_kegiatan1   = document.getElementById('nm_kegiatan1').value;
        var nil             = 1;
        //lalu
        var ctotal           = document.getElementById('total').value;
        var ctotals          = angka(ctotal);

        // var cstatus           = document.getElementById('tahapan').value;
        
        if (ckd_sub_kegiatan90!=0){

            //hitung jumlah mappingan
            if (ctotal==''){
              ctotal=0;
            }
                ctotal = parseInt(ctotal)+parseInt(nil);
                $('#total').attr('value',ctotal);
                $('#totall').attr('value',ctotal);
                $('#total').attr('value',ctotal);
                
        
            $('#dg1').edatagrid('appendRow',{kd_kegiatan1:ckd_kegiatan1,nm_kegiatan1:cnm_kegiatan1,kd_sub_kegiatan90:ckd_sub_kegiatan90,nm_sub_kegiatan90:cnm_sub_kegiatan90,kd_kegiatan90:ckd_kegiatan90,nm_kegiatan90:cnm_kegiatan90,kd_program90:ckd_program90,nm_program90:cnm_program90});

            $('#dg2').edatagrid('appendRow',{kd_kegiatan1:ckd_kegiatan1,nm_kegiatan1:cnm_kegiatan1,kd_sub_kegiatan90:ckd_sub_kegiatan90,nm_sub_kegiatan90:cnm_sub_kegiatan90,kd_kegiatan90:ckd_kegiatan90,nm_kegiatan90:cnm_kegiatan90,kd_program90:ckd_program90,nm_program90:cnm_program90});
            
            kosong3();
       }else {
                alert('Sub Kegiatan tidak boleh kosong');
                exit();
        }
    }

    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#dg').edatagrid({
         url: '<?php echo base_url(); ?>/index.php/mapping/load_mapping',
         queryParams:({cari:kriteria})
        });        
     });
    }


    // function sisa_anggaran(){
    //     var nilai1      = angka(document.getElementById('nilai1').value);
    //     var totaln90    = document.getElementById('total90').value;
    //     var nilai90     = angka(document.getElementById('nilai90').value);

    //     if(totaln90==''){
    //         total90=0;
    //     }else{
    //         total90=angka(totaln90);
    //     }
        
    //     sisa            = nilai1 - total90;
    //     sisasekarang    = (sisa - nilai90); 
    //     $("#sisa").attr("value",number_format(sisasekarang,2,'.',','));   
    //     if (sisasekarang < 0){
    //             alert('Nilai Melebihi Pagu Anggaran');
    //             $("#nilai90").attr("value",'0.00');
    //             sisa_anggaran();
    //             exit();                
    //     }
    // }



    // function sisa_anggaranlalu(){
    //     var nilailalu1      = angka(document.getElementById('nilailalu1').value);
    //     var totalnlalu90    = document.getElementById('totallalu90').value;
    //     var nilailalu90     = angka(document.getElementById('nilailalu90').value);

    //     if(totalnlalu90==''){
    //         totallalu90=0;
    //     }else{
    //         totallalu90=angka(totalnlalu90);
    //     }
        
    //     sisalalu            = nilailalu1 - totallalu90;
    //     sisasekaranglalu    = (sisalalu - nilailalu90); 
    //     $("#sisalalu").attr("value",number_format(sisasekaranglalu,2,'.',','));   
    //     if (sisasekarang < 0){
    //             alert('Nilai Melebihi Anggaran Lalu');
    //             $("#nilailalu90").attr("value",'0.00');
    //             sisa_anggaranlalu();
    //             exit();                
    //     }
    // }

function keluarcetak(){
        $("#dialog-modal-cetak").dialog('close');
    }
    
    function cetak(){
        $("#dialog-modal-cetak").dialog('open');
    }
    function cetak1(){
        // var status_cetak  = document.getElementById('tahapan_cetak').value;
        // var cskpd         = $('#kd_skpdcetak').combogrid('getValue');
       
          var url ="<?php echo site_url(); ?>/mapping/cetak_kertas_kerja/0/";
          window.open(url);
          window.focus();
        
  
       
    }
    function cetak2(){
        // var status_cetak = document.getElementById('tahapan_cetak').value;
        // var cskpd         = $('#kd_skpdcetak').combogrid('getValue');
      
          var url ="<?php echo site_url(); ?>/mapping/cetak_kertas_kerja/1/";
          window.open(url);
          window.focus();
       
        
    }
    function cetak3(){
      // var status_cetak = document.getElementById('tahapan_cetak').value;
      // var cskpd         = $('#kd_skpdcetak').combogrid('getValue');
     
          var url ="<?php echo site_url(); ?>/mapping/cetak_kertas_kerja/2/";
          window.open(url);
          window.focus();  
   
        
    }

    function opt(val){  
        ctk = val; 
        if (ctk=='1'){
          $("#kode_org").hide();
          $("#kd_skpdcetak").combogrid("setValue",'');
          $("#kd_skpd90cetak").attr("value",'');
        }  else if(ctk=='3'){
       $("#kode_org").show();
        }else{
       $("#kode_org").hide();
    }        
    }
    
    function simpan(){
        // var status_cetak = document.getElementById('tahapan_cetak').value;
        var cnm_kegiatan  = document.getElementById('nm_kegiatan').value;
        var cindikator  = document.getElementById('indikator').value;
        var ckd_kegiatan  = $('#kd_kegiatan').combogrid('getValue');

        var ctotall = document.getElementById('totall').value;

        // var csasaran_giat = document.getElementById('sasaran_giat').value;
        // var cwaktu_giat = document.getElementById('waktu_giat').value;
        // var ctu_capai = document.getElementById('tu_capai').value;
        // var ctu_mas = document.getElementById('tu_mas').value;
        // var ctu_kel = document.getElementById('tu_kel').value;
        // var ctu_has = document.getElementById('tu_has').value;
        // var ctk_capai = document.getElementById('tk_capai').value;
        // var ctk_kel = document.getElementById('tk_kel').value;
        // var ctk_has = document.getElementById('tk_has').value;
        // var clokasi = document.getElementById('lokasi').value;
        var ckd_skpd  = $('#kd_skpd').combogrid('getValue');
        var cnm_skpd  = document.getElementById('nm_skpd').value;
        var ckd_skpd90  = document.getElementById('kd_skpd90').value;
        var cnm_skpd90  = document.getElementById('nm_skpd90').value;
        var urusan      = ckd_skpd90.substring(0,4);
             
        if (ctotall=='' || ctotall==0){
            alert('Anda harus melakukan mapping kegiatan terlebih dahulu');
            exit();
        }

        if (ckd_skpd==''){
            alert('Kode dan Nama SKPD Asal Tidak Boleh Kosong');
            exit();
        } 

        if (ckd_kegiatan==''){
            alert('Kode dan nama kegiatan Asal Tidak Boleh Kosong');
            exit();
        }      
            // if (ctotalb<ctotala){
            //     alert('Nilai Pagu Hasil Mapping masih kurang dari jumlah pagu asal');
            //     exit();
            // }else if(ctotalb>ctotala){
            //     alert('Nilai Pagu Hasil Mapping melebihi dari jumlah pagu asal');
            //     exit();
            // }
                                                      
                $('#dg1').datagrid('selectAll');
                var dgrid = $('#dg1').datagrid('getSelections');
 			           for(var w=0;w<dgrid.length;w++){
            				        ckd_sub_kegiatan90  = dgrid[w].kd_sub_kegiatan90;
                            ckd_kegiatan90      = dgrid[w].kd_kegiatan90;
                            ckd_program90       = dgrid[w].kd_program90;
                            ckd_kegiatan1       = dgrid[w].kd_kegiatan1;
                            cnm_kegiatan90      = dgrid[w].nm_kegiatan90;
                            cnm_sub_kegiatan90  = dgrid[w].nm_sub_kegiatan90;
                            cnm_program90       = dgrid[w].nm_program90;
                            cnm_kegiatan1       = dgrid[w].nm_kegiatan1;
                            
                            if (ckd_program90.substring(0,4)=='X.XX'){
                                urusan1             = urusan.replace('-','.');
                                skd_program90       = ckd_program90.replace('X.XX',urusan1);
                                skd_kegiatan90      = ckd_kegiatan90.replace('X.XX',urusan1);
                                skd_sub_kegiatan90  = ckd_sub_kegiatan90.replace('X.XX',urusan1);
                            } else{
                                skd_program90       = ckd_program90;
                                skd_kegiatan90      = ckd_kegiatan90;
                                skd_sub_kegiatan90  = ckd_sub_kegiatan90;
                            }

                            if (w>0) {
                                //ke tabel imapping
                                csql = csql+",('"+ckd_kegiatan1+"','"+cnm_kegiatan1+"','"+skd_sub_kegiatan90+"','"+cnm_sub_kegiatan90+"','"+skd_kegiatan90+"','"+cnm_kegiatan90+"','"+skd_program90+"','"+cnm_program90+"','"+ckd_skpd+"','"+cnm_skpd+"','"+ckd_skpd90+"','"+cnm_skpd90+"','"+cindikator+"')";
                                //ke tabel trskpd
                                // csqltrskpd = csqltrskpd+",('"+ckd_kegiatan90+"','"+ckd_kegiatan90+"','"+ckd_kegiatan90.substring(0,7)+"','"+ckd_kegiatan90.substring(0,4)+"','"+ckd_skpd90+"','"+cnm_skpd90+"','"+ckd_kegiatan90+"','"+cnm_kegiatan90+"','','','','"+cwaktu_giat+"','"+csasaran_giat+"','"+ctu_capai+"','"+ctu_mas+"','"+ctu_kel+"','"+ctu_has+"','"+ctk_capai+"','"+ctk_kel+"','"+ctk_has+"','"+cnilailalu90+"','"+cnilai90+"','"+clokasi+"')";
                            } else {
                                //ke tabel mapping
                                csql = " values('"+ckd_kegiatan1+"','"+cnm_kegiatan1+"','"+skd_sub_kegiatan90+"','"+cnm_sub_kegiatan90+"','"+skd_kegiatan90+"','"+cnm_kegiatan90+"','"+skd_program90+"','"+cnm_program90+"','"+ckd_skpd+"','"+cnm_skpd+"','"+ckd_skpd90+"','"+cnm_skpd90+"','"+cindikator+"')";
                                //ke tabel trskpd
                                // csqltrskpd = " values('"+ckd_kegiatan90+"','"+ckd_kegiatan90+"','"+ckd_kegiatan90.substring(0,7)+"','"+ckd_kegiatan90.substring(0,4)+"','"+ckd_skpd90+"','"+cnm_skpd90+"','"+ckd_kegiatan90+"','"+cnm_kegiatan90+"','','','','"+cwaktu_giat+"','"+csasaran_giat+"','"+ctu_capai+"','"+ctu_mas+"','"+ctu_kel+"','"+ctu_has+"','"+ctk_capai+"','"+ctk_kel+"','"+ctk_has+"','"+cnilailalu90+"','"+cnilai90+"','"+clokasi+"')";
                            }
                                                                                          
             			}                                                    
                        $(document).ready(function(){     
                            $.ajax({
                                type: "POST",   
                                dataType : 'json',                 
                                data: ({tabel:'imapping',sql:csql,kode:ckd_kegiatan}),
                                url: '<?php echo base_url(); ?>/index.php/mapping/simpan_mapping',
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
      .button2 {background-color: #3be8ff;}
      .button1 {background-color: #ffa66b;}
      .button3 {background-color: #fff;}
    </style>
 </head>
    
<body>

<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1" >List kegiatan</a></h3>
    <div>
      <p>Keterangan Warna:</p>
      <p>
        <button class="button button2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> Sudah Input Indikator<br />
        <button class="button button3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> Belum Input Indikator</p>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak Hasil Maping</a>    
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="List kegiatan" style="width:870px;height:600px;" >  
        </table>                          
    </p> 
    </div>   

<h3><a href="#" id="section2">MAPPING KEGIATAN</a></h3>
   <div  style="height: 350px;">
   <p>         
        <table align="center" style="width:100%;">                        
            <tr>
                <td>Kode SKPD</td>
                <td><input id="kd_skpd" name="kd_skpd" style="width: 140px;" /></td>
                <td></td>
                <td></td> 
                <td><input type="text" id="nm_skpd" style="border:0;width: 400px;" disabled="true"/></td>                                
            </tr>
            <tr>
                <td>Kode SKPD PERMENDAGRI 90</td>
                <td><input id="kd_skpd90" name="kd_skpd90" style="width: 140px;" disabled/></td>
                <td></td>
                <td></td> 
                <td><input type="text" id="nm_skpd90" style="border:0;width: 400px;" disabled="true"/></td>                                
            </tr>
            <tr>
                <td>Kode kegiatan</td>
                <td><input id="kd_kegiatan" name="kd_kegiatan" style="width: 140px;" /></td>
                <td></td>
                <td></td> 
                <td>
                    <input type="text" id="nm_kegiatan" style="border:0;width: 400px;" readonly="true"/>
                    <input type="text" id="indikator" style="border:0;width: 400px;" readonly="true" hidden/>
                    <!-- <input id="sasaran_giat" name="sasaran_giat" style="width: 140px;" hidden />
                    <input type="text" id="waktu_giat" name="waktu_giat" style="width: 140px;" readonly="true" hidden/>
                    <input id="tu_capai" name="tu_capai" style="width: 140px;" hidden />
                    <input type="text" id="tu_mas" name="tu_mas" style="width: 140px;" readonly="true" hidden/>
                    <input id="tu_kel" name="tu_kel" style="width: 140px;" hidden />
                    <input type="text" id="tu_has" name="tu_has" style="width: 140px;" readonly="true" hidden/>
                    <input id="tk_capai" name="tk_capai" style="width: 140px;" hidden />
                    <input type="text" id="tk_kel" name="tk_kel" style="width: 140px;" readonly="true" hidden/>
                    <input id="tk_has" name="tk_has" style="width: 140px;" hidden />
                    <input type="text" id="lokasi" name="lokasi" style="width: 140px;" readonly="true" hidden /> -->
                </td>                                
            </tr>                                                 
           <tr>
                <td colspan="5" align="right"><a  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();" >Tambah</a>
                    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
		            <a id="hapusbutton" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
  		            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>                                   
                </td>
           </tr>
        </table>          
        <table id="dg1" title="Kode kegiatan Permendagri 90" style="width:870px;height:350px;" >  
        </table>  
        <div id="toolbar" align="right">
    		<a class="easyui-linkbutton" id="tambah90button" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah kegiatan Permendagri 90</a>
   		    <!--<input type="checkbox" id="semua" value="1" /><a onclick="">Semua kegiatan Permen 90</a>-->
            <a class="easyui-linkbutton" id="hapus90button" iconCls="icon-remove" plain="true" onclick="javascript:hapus_giat();">Hapus kegiatan Permendagri 90</a>               		
        </div>
        <table align="center" style="width:100%;">
        <tr>
            
            
            <td align="right"></td>
            <td></td>
            <td align="left"></td>
            <td align="right"></td>
            <td></td>
            <td align="left">
            <input type="text" id="totall" style="text-align: right;border:0;width: 200px;" readonly="true" />
            </td>
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
            <td >Kode kegiatan</td>
            <td>:</td>
            <td><input id="kd_kegiatan1" name="kd_kegiatan1" style="width: 200px;" readonly="true" />&nbsp;&nbsp;<input type="text" id="nm_kegiatan1" name="nm_kegiatan1" readonly="true" style="border:0;width: 400px;" readonly="true"/></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <p>Permendagri 90</p>
        <hr>
        <tr>
            <td >Kode Sub Kegiatan </td>
            <td>:</td>
            <td><input id="kd_sub_kegiatan90" name="kd_sub_kegiatan90" style="width: 200px;" />&nbsp;&nbsp;<input type="text" id="nm_sub_kegiatan90" readonly="true" style="border:0;width: 400px;"/></td>
            <td ></td>
            <td></td>
            <td></td>
        </tr>  
        <tr>
            <td >Kode Kegiatan </td>
            <td>:</td>
            <td><input id="kd_kegiatan90" name="kd_kegiatan90" style="width: 200px;" disabled/>&nbsp;&nbsp;<input type="text" id="nm_kegiatan90" readonly="true" style="border:0;width: 400px;"/></td>
            <td ></td>
            <td></td>
            <td></td>
        </tr>    
        <tr>
            <td >Kode Program</td>
            <td>:</td>
            <td><input id="kd_program90" name="kd_program90" style="width: 200px;" disabled/>&nbsp;&nbsp;<input type="text" id="nm_program90" readonly="true" style="border:0;width: 400px;"/></td>
            <td ></td>
            <td></td>
            <td></td>
        </tr>

<!--         <tr>
            <td >Status</td>
            <td>:</td>
            <td>
                <select  name="tahapan" id="tahapan" >
                   <option value="">Status</option>
                   <option value="1">Diterima</option>
                   <option value="2">Diusulkan</option>
                   <option value="3">Ditindaklanjuti</option>
                 </select>
            </td>
            <td ></td>
            <td></td>
            <td></td>
        </tr> -->



        
                 
      
    </table>  
    </fieldset>
    <fieldset>
    <table align="center">
        <tr>
            <td><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();disablecombo();">Keluar</a>                               
            </td>
        </tr>
    </table>   
    </fieldset>
    <fieldset>
        <table align="left">           
            <tr>
                <td>Total</td>
                <td>:</td>
                <td> <input type="text" id="total" style="text-align: right;border:0;width: 200px;" readonly="true" /></td>
                <td></td>
                <td></td>
                <td></td>    
            </tr>
        </table>
        <table id="dg2" title="Input kegiatan Permendagri 90" style="width:950px;height:270px;"  >  
        </table>  
     
    </fieldset>  
</div>
<div id="dialog-modal-cetak" title="Cetak Mapping">
    <fieldset>
    <table align="center" >
 <!--      <tr><td colspan="2"><input type="radio" name="status" value="1" onclick="opt(this.value)" /><b>Keseluruhan</b></td></tr>
  <tr><td colspan="2"><input type="radio" name="status" value="3" id="status" onclick="opt(this.value)" /><b>Per SKPD</b>
                    <div id="kode_org">
                        <table style="width:100%;" border="0">
                            <tr >
                          <td width="22px" height="40%" ><B>SKPD&nbsp;&nbsp;</B></td>
                          <td width="900px"><input id="kd_skpdcetak" name="kd_skpdcetak" style="width: 140px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_skpdcetak" style="border:0;width: 400px;" disabled="true"/></td>
                        </tr>
                        <tr >
                          <td width="22px" height="40%" ><B>SKPD&nbsp;&nbsp;</B></td>
                          <td width="900px"><input id="kd_skpd90cetak" name="kd_skpd90cetak" style="width: 140px;" disabled/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_skpd90cetak" style="border:0;width: 400px;" disabled="true"/></td>
                        </tr>
                        </table> 
                    </div>
        </td>
        </tr> -->


<!--       <tr>
                <td>Kode SKPD</td>
                <td><input id="kd_skpdcetak" name="kd_skpdcetak" style="width: 140px;" /></td>
                <td><input type="text" id="nm_skpdcetak" style="border:0;width: 400px;" disabled="true"/></td>                                
            </tr>
            <tr>
                <td>Kode SKPD PERMENDAGRI 90</td>
                <td><input id="kd_skpd90cetak" name="kd_skpd90cetak" style="width: 140px;" disabled/></td>
                <td><input type="text" id="nm_skpd90cetak" style="border:0;width: 400px;" disabled="true"/></td>                                
            </tr> -->


<!--         <tr>
          <td>Status</td>          
          <td><select  name="tahapan_cetak" id="tahapan_cetak" >
                   <option value="">Status</option>
                   <option value="1">Diterima</option>
                   <option value="2">Diusulkan</option>
                   <option value="3">Ditindaklanjuti</option>
                   <option value="4">Keseluruhan</option>
                 </select></td>
          <td></td>
        </tr> -->
        <tr>
            <td colspan="3" align="center"><br>
                <a class="easyui-linkbutton" iconCls="icon-print" plain="false" onclick="javascript:cetak2();">Layar</a>
                <a class="easyui-linkbutton" iconCls="icon-print" plain="false" onclick="javascript:cetak1();">PDF</a>
                <a class="easyui-linkbutton" iconCls="icon-excel" plain="false" onclick="javascript:cetak3();">Export</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="false" onclick="javascript:keluarcetak();">Kembali</a>
            </td>
        </tr>
    </table>   
    </fieldset>
    
</div>

</body>

</html>