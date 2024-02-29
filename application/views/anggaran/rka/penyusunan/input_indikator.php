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
    url: '<?php echo base_url(); ?>/index.php/rka_rancang/load_indikator',
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
            {field:'kd_skpd',
            title:'Kode SKPD',
            width:15,
            align:"center"},
            {field:'nm_skpd',
            title:'Nama SKPD',
            width:50},
            {field:'kd_kegiatan',
            title:'Kode Kegiatan',
            width:15,
            align:"center"},
            {field:'nm_kegiatan',
            title:'Nama Kegiatan',
            width:50},
            {field:'pagu',
            title:'Total Pagu',
            width:50}
        ]],
        onSelect:function(rowIndex,rowData){    
           kd_skpd      = rowData.kd_skpd;
           nm_skpd      = rowData.nm_skpd;
           kd_kegiatan  = rowData.kd_kegiatan;
           nm_kegiatan  = rowData.nm_kegiatan;
           kd_program   = rowData.kd_program;
           nm_program   = rowData.nm_program;

           sasaran_program        = rowData.sasaran_program;
           capaian_program        = rowData.capaian_program;
           
           tu_capaian             = rowData.tu_capaian;
           tu_mas                 = rowData.tu_mas;
           tu_kel                 = rowData.tu_kel;
           tu_has                 = rowData.tu_has;

           tu_capaian_p             = rowData.tu_capaian_p;
           tu_mas_p                 = rowData.tu_mas_p;
           tu_kel_p                 = rowData.tu_kel_p;
           tu_has_p                 = rowData.tu_has_p;

           tk_capaian             = rowData.tk_capaian;
           tk_mas                 = rowData.tk_mas;
           tk_kel                 = rowData.tk_kel;
           tk_has                 = rowData.tk_has;

           tk_capaian_p             = rowData.tk_capaian_p;
           tk_mas_p                 = rowData.tk_mas_p;
           tk_kel_p                 = rowData.tk_kel_p;
           tk_has_p                 = rowData.tk_has_p;
           
           kel_sasaran_kegiatan   = rowData.kel_sasaran_kegiatan;
           get(kd_skpd,nm_skpd,kd_kegiatan,nm_kegiatan,kd_program,nm_program,sasaran_program,capaian_program,tu_capaian,tu_mas,tu_kel,tu_has,tu_capaian_p,tu_mas_p,tu_kel_p,tu_has_p,tk_capaian,tk_mas,tk_kel,tk_has,tk_capaian_p,tk_mas_p,tk_kel_p,tk_has_p,kel_sasaran_kegiatan);

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
                  {field:'kd_sub_kegiatan',title:'Kode Sub Kegiatan',width:50},
                  {field:'nm_sub_kegiatan',title:'Nama Sub Kegiatan',width:70},
                  {field:'lokasi',title:'Lokasi',width:50},
                  {field:'waktu',title:'Mulai',width:35},
                  {field:'waktu2',title:'Sampai',width:35},
                  {field:'sub_keluaran',title:'Sub Keluaran',width:50},
                  {field:'keterangan',title:'Keterangan',width:70}
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

     //ambil SKPD
     $('#kd_skpd').combogrid({  
       panelWidth:550,  
       idField:'kd_skpd',  
       textField:'kd_skpd',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/rka_rancang/skpd_mapping',  
       columns:[[  
           {field:'kd_skpd',title:'Kode skpd',width:150},  
           {field:'nm_skpd',title:'Nama skpd',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_skpd").attr("value",rowData.nm_skpd.toUpperCase()); 
           get_kegiatan();
       }  
     }); 

     // $('#kd_urusan').combogrid({  
     //   panelWidth:700,  
     //   idField:'kd_urusan',  
     //   textField:'kd_urusan',  
     //   mode:'remote',
     //   url:'<?php echo base_url(); ?>index.php/rka_rancang/ambil_urusan',  
     //   columns:[[  
     //       {field:'kd_urusan',title:'Kode Urusan',width:100},  
     //       {field:'nm_urusan',title:'Nama Urusan',width:400}    
     //   ]],  
     //   onSelect:function(rowIndex,rowData){
     //       // kd_prog = rowData.kd_program;
       
     //   //$("#kd_progr").attr("value",rowData.kd_program.toUpperCase());
     //       $("#nm_urusan").attr("value",rowData.nm_urusan.toUpperCase());
     //       $("#kd_urusan1").attr("value",rowData.kd_urusan.toUpperCase());
     //       $("#nm_urusan1").attr("value",rowData.nm_urusan.toUpperCase());

                                  
     //   }  
     // }); 

     
     // $('#kd_urusans').combogrid({  
     //       panelWidth:700,  
     //       idField:'kd_urusan',  
     //       textField:'kd_urusan',  
     //       mode:'local',                      
     //       url:'<?php echo base_url(); ?>index.php/mapping/ambil_urusan',  
     //       columns:[[  
     //           {field:'kd_urusan',title:'Kode Urusan',width:100},  
     //           {field:'nm_urusan',title:'Nama Urusan',width:700}    
     //       ]],  
     //       onSelect:function(rowIndex,rowData){
     //           cskpd = rowData.kd_skpd;               
     //           $('#nm_urusan').attr('value',rowData.nm_urusan);                               
     //       } 
     // });

     // $('#kd_urusan90').combogrid({  
     //       panelWidth:700,  
     //       idField:'kd_bidang_urusan',  
     //       textField:'kd_bidang_urusan',  
     //       mode:'remote',                      
     //       url:'<?php echo base_url(); ?>index.php/mapping/ambil_urusan90',  
     //       columns:[[  
     //           {field:'kd_bidang_urusan',title:'Kode Urusan',width:100},  
     //           {field:'nm_bidang_urusan',title:'Nama Urusan',width:700}    
     //       ]],  
     //       onSelect:function(rowIndex,rowData){              
     //           $('#nm_urusan90').attr('value',rowData.nm_bidang_urusan);                               
     //       } 
     // });
               
    
      
        

      $('#kd_kegiatan').combogrid({  
       panelWidth:500,  
       idField:'kd_kegiatan',  
       textField:'kd_kegiatan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/rka_rancang/get_kegiatan90', 
       columns:[[  
           {field:'kd_kegiatan',title:'Kode Kegiatan',width:100},  
           {field:'nm_kegiatan',title:'Nama Kegiatan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_kegiatan").attr("value",rowData.nm_kegiatan.toUpperCase());
           get_sub_kegiatan();              
       }  
     }); 

      $('#kd_program').combogrid({  
       panelWidth:500,  
       idField:'kd_program',  
       textField:'kd_program',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/rka_rancang/get_program90',
       columns:[[  
           {field:'kd_program',title:'Kode program',width:100},  
           {field:'nm_program',title:'Nama program',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_program").attr("value",rowData.nm_program.toUpperCase());
       }  
     });
     
    });

function get_kegiatan(){
      var ckdskpd     = $('#kd_skpd').combogrid('getValue');
      var indikator   = document.getElementById('tu_capaian').value;
      var indikator_p   = document.getElementById('capaian_program').value;
      $('#kd_kegiatan').combogrid({  
       panelWidth:500,  
       idField:'kd_kegiatan',  
       textField:'kd_kegiatan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/rka_rancang/get_kegiatan90', 
       queryParams:({kode:ckdskpd}), 
       columns:[[  
           {field:'kd_kegiatan',title:'Kode Kegiatan',width:100},  
           {field:'nm_kegiatan',title:'Nama Kegiatan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_kegiatan").attr("value",rowData.nm_kegiatan.toUpperCase());

          if(rowData.kd_kegiatan.substring(5,7)=='01'){
             $("#nm_program").attr("value",'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH');
          }
           $("#kd_program").combogrid("setValue",rowData.kd_kegiatan.substring(0,7));
             
             if (indikator==''){
              ambil_indikator();
             }

             // if (indikator_p==''){
             ambil_indikator_program();
           // }
           
       }  
     }); 
}

function ambil_indikator_program(){
      var ckd_program     = $('#kd_program').combogrid('getValue');
      var cskpd           = $('#kd_skpd').combogrid('getValue');
      $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/rka_rancang/ambil_indikator_program',
                data: ({no:ckd_program,skpd:cskpd}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                      ikd_program    = n['kd_program'];
                      
                      icapaian_program     = n['capaian_program'];
                      isasaran_program     = n['sasaran_program'];

                      //penerapan
                    if(ikd_program=='' || ikd_program==0){
                      $("#capaian_program").attr("value",'');
                      $("#sasaran_program").attr("value",'');
                      $("#capaian_program").attr('disabled',false);
                      $("#sasaran_program").attr('disabled',false);

                    }else{

                    $("#capaian_program").attr("value",icapaian_program);
                    $("#sasaran_program").attr("value",isasaran_program);
                    $("#capaian_program").attr('disabled',true);
                    $("#sasaran_program").attr('disabled',true);
                    
                  }

                    
                    });                                                                           
                }
            });
           }); 
}

function ambil_indikator(){
      var ckdkegiatan     = $('#kd_kegiatan').combogrid('getValue');
      $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/rka_rancang/ambil_indikator',
                data: ({no:ckdkegiatan}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                      ikd_kegiatan    = n['kd_kegiatan'];
                      inm_kegiatan    = n['nm_sub_kegiatan'];
                      
                      itu_capaian     = n['tu_capaian'];
                      itu_mas     = n['tu_mas'];
                      itu_kel     = n['tu_kel'];
                      itu_has     = n['tu_has'];

                      itu_capaian_p     = n['tu_capaian_p'];
                      itu_mas_p     = n['tu_mas_p'];
                      itu_kel_p     = n['tu_kel_p'];
                      itu_has_p     = n['tu_has_p'];

                      itk_capaian     = n['tk_capaian'];
                      itk_mas     = n['tk_mas'];
                      itk_kel     = n['tk_kel'];
                      itk_has     = n['tk_has'];

                      itk_capaian_p     = n['tk_capaian_p'];
                      itk_mas_p     = n['tk_mas_p'];
                      itk_kel_p     = n['tk_kel_p'];
                      itk_has_p     = n['tk_has_p'];

                                       
                      ikel_sasaran_kegiatan  = n['kel_sasaran_kegiatan'];

                      //penerapan

                    $("#tu_capaian").attr("value",itu_capaian);
                    $("#tu_mas").attr("value",itu_mas);
                    $("#tu_kel").attr("value",itu_kel);
                    $("#tu_has").attr("value",itu_has);

                    $("#tu_capaian_p").attr("value",itu_capaian_p);
                    $("#tu_mas_p").attr("value",itu_mas_p);
                    $("#tu_kel_p").attr("value",itu_kel_p);
                    $("#tu_has_p").attr("value",itu_has_p);

                    $("#tk_capaian").attr("value",itk_capaian);
                    $("#tk_mas").attr("value",itk_mas);
                    $("#tk_kel").attr("value",itk_kel);
                    $("#tk_has").attr("value",itk_has);

                    $("#tk_capaian_p").attr("value",itk_capaian_p);
                    $("#tk_mas_p").attr("value",itk_mas_p);
                    $("#tk_kel_p").attr("value",itk_kel_p);
                    $("#tk_has_p").attr("value",itk_has_p);


                    $("#kel_sasaran_kegiatan").attr("value",ikel_sasaran_kegiatan);
                    

                                                                                    
                                                                                                                                                                                                                                                                                                                                                                           
                    });                                                                           
                }
            });
           }); 
}

function get_program(){
  $('#kd_program').combogrid({  
       panelWidth:500,  
       idField:'kd_program',  
       textField:'kd_program',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/rka_rancang/get_program90',
       columns:[[  
           {field:'kd_program',title:'Kode program',width:100},  
           {field:'nm_program',title:'Nama program',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_program").attr("value",rowData.nm_program.toUpperCase());
       }  
     }); 
}

function get_sub_kegiatan(){

    var ckdkegiatan = $('#kd_kegiatan').combogrid('getValue');
    var ckdskpd = $('#kd_skpd').combogrid('getValue');
    $('#kd_sub_kegiatan').combogrid({  
       panelWidth:500,  
       idField:'kd_sub_kegiatan',  
       textField:'kd_sub_kegiatan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/rka_rancang/get_sub_kegiatan90',
       queryParams:({kode:ckdkegiatan,skpd:ckdskpd}), 
       columns:[[  
           {field:'kd_sub_kegiatan',title:'Kode Sub Kegiatan',width:100},  
           {field:'nm_sub_kegiatan',title:'Nama Sub Kegiatan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
          $("#nm_sub_kegiatan").attr("value",rowData.nm_sub_kegiatan.toUpperCase());
       }  
     }); 
}
    //kosong section 2
    function kosong(){
        $("#kd_skpd").combogrid("setValue",'');
        $("#kd_kegiatan").combogrid("setValue",'');
        $("#kd_program").combogrid("setValue",'');
        $("#kd_program").combogrid("disable");
        $("#nm_skpd").attr("value",'');
        $("#nm_kegiatan").attr("value",'');
        $("#nm_program").attr("value",'');

        $("#capaian_program").attr("value",'');
        $("#sasaran_program").attr("value",'');
        
        $("#tu_capaian").attr("value",'');
        $("#tk_capaian").attr("value",'');
        $("#tu_mas").attr("value",'');
        $("#tk_mas").attr("value",'');
        $("#tu_kel").attr("value",'');
        $("#tk_kel").attr("value",'');
        $("#tu_has").attr("value",'');
        $("#tk_has").attr("value",'');

        $("#tu_capaian_p").attr("value",'');
        $("#tk_capaian_p").attr("value",'');
        $("#tu_mas_p").attr("value",'');
        $("#tk_mas_p").attr("value",'');
        $("#tu_kel_p").attr("value",'');
        $("#tk_kel_p").attr("value",'');
        $("#tu_has_p").attr("value",'');
        $("#tk_has_p").attr("value",'');

        $("#kd_skpd").combogrid("enable");
        $("#kd_kegiatan").combogrid("enable");

        $("#capaian_program").attr('disabled',false);
        $("#sasaran_program").attr('disabled',false);

        $("#kel_sasaran_kegiatan").attr("value",'');
    }
    
    //kosong section 3
    function kosong2(){        
        $("#kd_sub_kegiatan").combogrid("setValue",'');
        $("#nm_sub_kegiatan").attr("value",'');
        $("#lokasi").attr("value",'');
        $("#waktu").attr("value",'');
        $("#waktu2").attr("value",'');
        $("#sub_keluaran").attr("value",'');
        $("#keterangan").attr("value",'');
        $("#ang_lalu").attr("value",'');
        $("#ang_ini").attr("value",'');
        $("#ang_depan").attr("value",'');
          
    }
    
    function set_grid(){
        $('#dg1').edatagrid({                                                                   
           columns:[[
              {field:'kd_sub_kegiatan',title:'Kode Sub Kegiatan',width:50},
              {field:'nm_sub_kegiatan',title:'Nama Sub Kegiatan',width:70},
              {field:'lokasi',title:'Lokasi',width:50},
              {field:'waktu',title:'Mulai',width:70},
              {field:'waktu2',title:'Sampai',width:70},
              {field:'sub_keluaran',title:'Sub Keluaran',width:50},
              {field:'keterangan',title:'Keterangan',width:70},
                  {field:'ang_lalu',title:'Angg. Lalu',width:70},
                  {field:'ang_ini',title:'Angg. Ini',width:70},
                  {field:'ang_depan',title:'Angg. Depan',width:70},
                  {field:'statussub',title:'Status',width:70,hidden:'true'}
            ]]
        });                 
    }
    
    function set_grid2(){
        $('#dg2').edatagrid({                                                                   
           columns:[[
                {field:'hapus',title:'Hapus',width:19,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/assets/images/icon/cross.png' onclick='javascript:hapus_detail();'' />";}},
                   {field:'kd_sub_kegiatan',title:'Kode Sub Kegiatan',width:50},
                  {field:'nm_sub_kegiatan',title:'Nama Sub Kegiatan',width:70},
                  {field:'lokasi',title:'Lokasi',width:50},
                  {field:'waktu',title:'Mulai',width:35},
                  {field:'waktu2',title:'Sampai',width:35},
                  {field:'sub_keluaran',title:'Sub Keluaran',width:50},
                  {field:'keterangan',title:'Keterangan',width:70},
                  {field:'ang_lalu',title:'Angg. Lalu',width:70},
                  {field:'ang_ini',title:'Angg. Ini',width:70},
                  {field:'ang_depan',title:'Angg. Depan',width:70}
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
        var ckd_skpd      = $('#kd_skpd').combogrid('getValue');
        var ckd_kegiatan  = $('#kd_kegiatan').combogrid('getValue');
        var cnm_kegiatan  = document.getElementById('nm_kegiatan').value;
        if (ckd_skpd != '' && ckd_kegiatan != ''){
            $("#dialog-modal").dialog('open'); 
            $("#kd_kegiatan1").attr("value",ckd_kegiatan);
            $("#nm_kegiatan1").attr("value",cnm_kegiatan);
            get_sub_kegiatan();
            kosong2();
            set_grid2();
            load_detail2();
            
        } else {
            alert('Harap Isi SKPD, & KEGIATAN ') ;         
        }
    }
    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg2').edatagrid('reload');
        kosong2();                        
    }   
    
    function load_detail(){
        var i = 0;
        var kk = $('#kd_kegiatan').combogrid('getValue'); 
        var sk = $('#kd_skpd').combogrid('getValue');             
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/rka_rancang/load_detail_indikator',
                data: ({no:kk,kode:sk}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                      ckd_sub_kegiatan    = n['kd_sub_kegiatan'];
                      cnm_sub_kegiatan    = n['nm_sub_kegiatan'];
                      clokasi         = n['lokasi'];
                      cwaktu          = n['waktu'];
                      cwaktu2          = n['waktu_giat2'];
                      csub_keluaran   = n['sub_keluaran'];
                      cketerangan     = n['keterangan'];
                      cstatussub       = n['statussub'];

                      cang_lalu       = number_format(n['ang_lalu'],2,'.',',');
                      cang_ini        = number_format(n['ang_ini'],2,'.',',');
                      cang_depan      = number_format(n['ang_depan'],2,'.',',');

                                       
                      ctotall  = n['jml'];


                     $('#dg1').edatagrid('appendRow',{kd_sub_kegiatan:ckd_sub_kegiatan,nm_sub_kegiatan:cnm_sub_kegiatan,lokasi:clokasi,waktu:cwaktu,waktu2:cwaktu2,sub_keluaran:csub_keluaran,keterangan:cketerangan,ang_lalu:cang_lalu,ang_ini:cang_ini,ang_depan:cang_depan,statussub:cstatussub});
                    

                    

                    $("#totall").attr("value",ctotall);
                    $("#total").attr("value",ctotall);

                                                                                    
                                                                                                                                                                                                                                                                                                                                                                           
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
            ckd_sub_kegiatan      = rows[p].kd_sub_kegiatan;
            cnm_sub_kegiatan      = rows[p].nm_sub_kegiatan;
            clokasi               = rows[p].lokasi;
            cwaktu                = rows[p].waktu;
            cwaktu2                = rows[p].waktu2;
            csub_keluaran         = rows[p].sub_keluaran;
            cketerangan           = rows[p].keterangan;

            cang_lalu             = rows[p].ang_lalu;
            cang_ini              = rows[p].ang_ini;
            cang_depan            = rows[p].ang_depan;

            $('#dg2').edatagrid('appendRow',{kd_sub_kegiatan:ckd_sub_kegiatan,nm_sub_kegiatan:cnm_sub_kegiatan,lokasi:clokasi,waktu:cwaktu,waktu2:cwaktu2,sub_keluaran:csub_keluaran,keterangan:cketerangan,ang_lalu:cang_lalu,ang_ini:cang_ini,ang_depan:cang_depan});            
        }
        $('#dg1').edatagrid('unselectAll');
    } 
    
    function get(kd_skpd,nm_skpd,kd_kegiatan,nm_kegiatan,kd_program,nm_program,sasaran_program,capaian_program,tu_capaian,tu_mas,tu_kel,tu_has,tu_capaian_p,tu_mas_p,tu_kel_p,tu_has_p,tk_capaian,tk_mas,tk_kel,tk_has,tk_capaian_p,tk_mas_p,tk_kel_p,tk_has_p,kel_sasaran_kegiatan){
        $('#nm_skpd').attr('value',nm_skpd);
        $('#nm_kegiatan').attr('value',nm_kegiatan);
        $('#nm_program').attr('value',nm_program);
        $("#kd_skpd").combogrid("setValue",kd_skpd);
        $("#kd_kegiatan").combogrid("setValue",kd_kegiatan);
        $("#kd_program").combogrid("setValue",kd_program);
        
        $('#sasaran_program').attr('value',sasaran_program);
        $("#capaian_program").attr("value",capaian_program);

        $("#tu_capaian").attr("value",tu_capaian);
        $("#tu_mas").attr("value",tu_mas);
        $("#tu_kel").attr("value",tu_kel);
        $("#tu_has").attr("value",tu_has);

        $("#tu_capaian_p").attr("value",tu_capaian_p);
        $("#tu_mas_p").attr("value",tu_mas_p);
        $("#tu_kel_p").attr("value",tu_kel_p);
        $("#tu_has_p").attr("value",tu_has_p);

        $("#tk_capaian").attr("value",tk_capaian);
        $("#tk_mas").attr("value",tk_mas);
        $("#tk_kel").attr("value",tk_kel);
        $("#tk_has").attr("value",tk_has);

        $("#kd_skpd").combogrid("disable");
        $("#kd_kegiatan").combogrid("disable");
        $("#kd_program").combogrid("disable");

        $("#tk_capaian_p").attr("value",tk_capaian_p);
        $("#tk_mas_p").attr("value",tk_mas_p);
        $("#tk_kel_p").attr("value",tk_kel_p);
        $("#tk_has_p").attr("value",tk_has_p);
        
        $("#kel_sasaran_kegiatan").attr("value",kel_sasaran_kegiatan);
    }
    
    function hapus_giat(){         
         var rows = $('#dg1').edatagrid('getSelected'); 
         var ckd_urusan90 = rows.kd_urusan90;        
         var cnm_urusan90 =  rows.nm_urusan90;         
         
         var tny = confirm('Yakin Ingin Menghapus Data, Urusan : '+cnm_urusan90);
         if (tny==true){                                      
             $('#dg1').edatagrid('deleteRow',idx);
         }              
    }
    
    function hapus_detail(){
        var ctotal        = angka(document.getElementById('total').value);
        var ckd_skpd      = $('#kd_skpd').combogrid('getValue');
        var rows          = $('#dg1').edatagrid('getSelected');
        ckd_sub_kegiatan  = rows.kd_sub_kegiatan;
        cnm_sub_kegiatan  = rows.nm_sub_kegiatan;

        

        var urll = '<?php echo base_url(); ?>index.php/rka_rancang/hapus_rinci_indikator';
        var idx = $('#dg1').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Sub Kegiatan : '+ckd_sub_kegiatan+' - '+cnm_sub_kegiatan);
        if (tny==true){

        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({kode:ckd_sub_kegiatan,kode2:ckd_skpd}),
                 success:function(data){
                        status = data.pesan;
                        if (status=='1'){
                            $('#dg1').edatagrid('deleteRow',idx);
                            kosong2();

                            ctotal=ctotal-1;

                            $("#total").attr("value",ctotal);
                            $("#totall").attr("value",ctotal);         
                        } else if(status=='2'){
                            alert('Sub Kegiatan sudah Divalidasi, Anda Tidak Bisa Hapus SUb Kegiatan ini');
                        } else {
                            alert('Gagal Hapus');
                        }    

                 }
                 
                });           
        });




            

        }                     
    }
    
    function hapus(){
        var cnm_kegiatan_ = document.getElementById('nm_kegiatan').value;
        var ckd_skpd      = $('#kd_skpd').combogrid('getValue');
        var ckd_kegiatan  = $('#kd_kegiatan').combogrid('getValue');
        var urll = '<?php echo base_url(); ?>index.php/rka_rancang/hapus_indikator';
        var tny = confirm('Yakin Ingin Menghapus Data, Kegiatan : '+ckd_kegiatan+'-'+cnm_kegiatan_);        
        if (tny==true){
        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({kode:ckd_kegiatan,kode2:ckd_skpd}),
                 success:function(data){
                        status = data.pesan;
                        if (status=='1'){
                            alert('Data Berhasil Terhapus');         
                        } else if(status=='2'){
                            alert('Kegiatan sudah Divalidasi, Anda Tidak Bisa Hapus Kegiatan ini');
                        } else {
                            alert('Gagal Hapus');
                        }    

                 }
                 
                });           
        });
        }
        $('#dg').edatagrid('reload');     
    }
    
    function append_save(){
        var ckd_sub_kegiatan  = $('#kd_sub_kegiatan').combogrid('getValue');
        var cnm_sub_kegiatan  = document.getElementById('nm_sub_kegiatan').value;
        var ckd_kegiatan1     = document.getElementById('kd_kegiatan1').value;
        var cnm_kegiatan1     = document.getElementById('nm_kegiatan1').value;
        //indikator
        var clokasi           = document.getElementById('lokasi').value;
        var cwaktu            = document.getElementById('waktu').value;
        var cwaktu2            = document.getElementById('waktu2').value;
        var csub_keluaran     = document.getElementById('sub_keluaran').value;
        var cketerangan       = document.getElementById('keterangan').value;

        //pagu
        
        
        var nil           = 1;  
        var ctotal        = document.getElementById('total').value;
        if (ckd_sub_kegiatan != '' || cnm_sub_kegiatan != ''){

            //hitung jumlah sub kegiatan
            if (ctotal==''){
              ctotal=0;
            }
                ctotal = parseInt(ctotal)+parseInt(nil);
                $('#totall').attr('value',ctotal);
                $('#total').attr('value',ctotal);

                if (document.getElementById('ang_lalu').value==''){

                  cang_lalu ='0.00';
                }else{
                    var cang_lalu         = document.getElementById('ang_lalu').value;
                  }

                  if (document.getElementById('ang_ini').value==''){

                  cang_ini ='0.00';
                }else{
                    var cang_ini          = document.getElementById('ang_ini').value;
                  }

                  if (document.getElementById('ang_depan').value==''){

                  cang_depan ='0.00';
                }else{
                    var cang_depan        = document.getElementById('ang_depan').value;
                  }

            
            $('#dg1').edatagrid('appendRow',{kd_sub_kegiatan:ckd_sub_kegiatan,nm_sub_kegiatan:cnm_sub_kegiatan,lokasi:clokasi,waktu:cwaktu,waktu2:cwaktu2,sub_keluaran:csub_keluaran,keterangan:cketerangan,ang_lalu:cang_lalu,ang_ini:cang_ini,ang_depan:cang_depan});

            $('#dg2').edatagrid('appendRow',{kd_sub_kegiatan:ckd_sub_kegiatan,nm_sub_kegiatan:cnm_sub_kegiatan,lokasi:clokasi,waktu:cwaktu,waktu2:cwaktu2,sub_keluaran:csub_keluaran,keterangan:cketerangan,ang_lalu:cang_lalu,ang_ini:cang_ini,ang_depan:cang_depan});
            
            kosong2();
       }else {
                alert('Kode dan Nama Sub Kegiatan tidak boleh kosong');
                exit();
        }
    }


    function cetak(){
        $("#dialog-modal-cetak").dialog('open');
    }
    function cetak1(){
        var url ="<?php echo site_url(); ?>/mapping/cetak_urusan/0";
        window.open(url);
        window.focus();
    }
    function cetak2(){
        var url ="<?php echo site_url(); ?>/mapping/cetak_urusan/1";
        window.open(url);
        window.focus();
    }
    function cetak3(){
        var url ="<?php echo site_url(); ?>/mapping/cetak_urusan/2";
        window.open(url);
        window.focus();
    }
                

    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#dg').edatagrid({
         url: '<?php echo base_url(); ?>/index.php/rka_rancang/load_indikator',
         queryParams:({cari:kriteria})
        });        
     });
    }
    
    function simpan(){
        var cnm_skpd      = document.getElementById('nm_skpd').value;
        var cnm_kegiatan  = document.getElementById('nm_kegiatan').value;
        var cnm_program   = document.getElementById('nm_program').value;
        var ckd_skpd      = $('#kd_skpd').combogrid('getValue');
        var ckd_kegiatan  = $('#kd_kegiatan').combogrid('getValue');
        var ckd_program   = $('#kd_program').combogrid('getValue');


        var ccapaian_program   = document.getElementById('capaian_program').value;
        var csasaran_program   = document.getElementById('sasaran_program').value;

        var ctu_capaian           = document.getElementById('tu_capaian').value;
        var ctu_mas               = document.getElementById('tu_mas').value;
        var ctu_kel               = document.getElementById('tu_kel').value;
        var ctu_has               = document.getElementById('tu_has').value;

        var ctu_capaian_p           = document.getElementById('tu_capaian_p').value;
        var ctu_mas_p               = document.getElementById('tu_mas_p').value;
        var ctu_kel_p               = document.getElementById('tu_kel_p').value;
        var ctu_has_p               = document.getElementById('tu_has_p').value;

        var ctk_capaian           = document.getElementById('tk_capaian').value;
        var ctk_mas               = document.getElementById('tk_mas').value;
        var ctk_kel               = document.getElementById('tk_kel').value;
        var ctk_has               = document.getElementById('tk_has').value;

        var ctk_capaian_p           = document.getElementById('tk_capaian_p').value;
        var ctk_mas_p               = document.getElementById('tk_mas_p').value;
        var ctk_kel_p               = document.getElementById('tk_kel_p').value;
        var ctk_has_p               = document.getElementById('tk_has_p').value;

        var ckel_sasaran_kegiatan = document.getElementById('kel_sasaran_kegiatan').value;

        var ctotall = document.getElementById('totall').value;
             
        if (ctotall=='' || ctotall==0){
            alert('Anda harus input Indikator Sub Kegiatan terlebih dahulu');
            exit();
        } 
        if (ckd_skpd==''){
            alert('Kode dan nama SKPD Asal Tidak Boleh Kosong');
            exit();
        }
        if (ckd_kegiatan==''){
            alert('Kode dan nama kegiatan Asal Tidak Boleh Kosong');
            exit();
        }      
                        
                                                            
                $('#dg1').datagrid('selectAll');
                var dgrid = $('#dg1').datagrid('getSelections');
                 for(var w=0;w<dgrid.length;w++){
                            ckd_sub_kegiatan  = dgrid[w].kd_sub_kegiatan;                                            
                            cnm_sub_kegiatan  = dgrid[w].nm_sub_kegiatan;
                            clokasi           = dgrid[w].lokasi;
                            cwaktu            = dgrid[w].waktu;
                            cwaktu2            = dgrid[w].waktu2;
                            csub_keluaran     = dgrid[w].sub_keluaran;
                            cketerangan       = dgrid[w].keterangan;

                            if (dgrid[w].ang_lalu==undefined){
                              cang_lalu='0.00';
                            }else{
                              cang_lalu         = angka(dgrid[w].ang_lalu);
                            }

                            if (dgrid[w].ang_ini==undefined){
                              cang_ini='0.00';
                            }else{
                              cang_ini          = angka(dgrid[w].ang_ini);
                            }

                            if (dgrid[w].ang_depan==undefined){
                              cang_depan='0.00';
                            }else{
                              cang_depan        = angka(dgrid[w].ang_depan);
                            }

                            if (dgrid[w].statussub=='' || dgrid[w].statussub== null){
                              cstatussub        = '0';
                            }else{
                              cstatussub        = dgrid[w].statussub;
                            }

                            gabung=ckd_skpd+'.'+ckd_sub_kegiatan;
                                                                            
                            if (w>0) {
                                csql = csql+",('"+gabung+"','"+ckd_kegiatan+"','"+ckd_program+"','"+ckd_program.substring(0,4)+"','"+ckd_skpd+"','"+cnm_skpd+"','"+cnm_kegiatan+"','"+cnm_program+"','"+clokasi+"','"+csasaran_program+"','"+ccapaian_program+"','"+cwaktu+"','"+ctu_capaian+"','"+ctu_mas+"','"+ctu_kel+"','"+ctu_has+"','"+ctk_capaian+"','"+ctk_mas+"','"+ctk_kel+"','"+ctk_has+"','"+cang_lalu+"','"+cang_ini+"','"+cang_depan+"','"+ckd_sub_kegiatan+"','"+cnm_sub_kegiatan+"','"+ckel_sasaran_kegiatan+"','"+csub_keluaran+"','"+cketerangan+"','"+ctu_capaian_p+"','"+ctu_mas_p+"','"+ctu_kel_p+"','"+ctu_has_p+"','"+ctk_capaian_p+"','"+ctk_mas_p+"','"+ctk_kel_p+"','"+ctk_has_p+"','"+cstatussub+"','"+cwaktu2+"')";
                            } else {
                                csql = " values('"+gabung+"','"+ckd_kegiatan+"','"+ckd_program+"','"+ckd_program.substring(0,4)+"','"+ckd_skpd+"','"+cnm_skpd+"','"+cnm_kegiatan+"','"+cnm_program+"','"+clokasi+"','"+csasaran_program+"','"+ccapaian_program+"','"+cwaktu+"','"+ctu_capaian+"','"+ctu_mas+"','"+ctu_kel+"','"+ctu_has+"','"+ctk_capaian+"','"+ctk_mas+"','"+ctk_kel+"','"+ctk_has+"','"+cang_lalu+"','"+cang_ini+"','"+cang_depan+"','"+ckd_sub_kegiatan+"','"+cnm_sub_kegiatan+"','"+ckel_sasaran_kegiatan+"','"+csub_keluaran+"','"+cketerangan+"','"+ctu_capaian_p+"','"+ctu_mas_p+"','"+ctu_kel_p+"','"+ctu_has_p+"','"+ctk_capaian_p+"','"+ctk_mas_p+"','"+ctk_kel_p+"','"+ctk_has_p+"','"+cstatussub+"','"+cwaktu2+"')";                                            
                            }
                                                                                          
                  }                                                    
                        $(document).ready(function(){     
                            $.ajax({
                                type: "POST",   
                                dataType : 'json',                 
                                data: ({tabel:'trskpd',sql:csql,kode1:ckd_skpd,kode2:ckd_kegiatan}),
                                url: '<?php echo base_url(); ?>/index.php/mapping/simpan_indikator',
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
<h3><a href="#" id="section1" >INPUT INDIKATOR PROGRAM, KEGIATAN DAN SUB KEGIATAN</a></h3>
    <div>
      <!-- <p>Keterangan Warna:</p>
      <p>
        <button class="button button2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> Sudah dilakukan mapping<br />
        <button class="button button1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> Belum dilakukan mapping</p> -->
    <p align="right">         
        <!-- <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak Hasil Maping</a>                -->
        <button class="easyui-linkbutton" type="submit"  plain="true" onclick="javascript:section2();kosong();load_detail();"><i class="fa fa-plus"></i> Tambah</button>               
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" value="" id="txtcari"/><button class="easyui-linkbutton" type="primary"  plain="true" onclick="javascript:cari();"><i class="fa fa-search"></i> </button>
        <table id="dg" title="List Urusan" style="width:870px;height:600px;" >  
        </table>                          
    </p> 
    </div>   

<h3><a href="#" id="section2">INPUT INDIKATOR PROGRAM, KEGIATAN DAN SUB KEGIATAN</a></h3>
   <div  style="height: 350px;">
   <p>         
        <table align="center" style="width:100%;">                        
            <tr>
                <td>SKPD</td>
                <td colspan="4"><input id="kd_skpd" name="kd_skpd" style="width: 140px;" />&nbsp;<input type="text" id="nm_skpd" style="border:0;width: 400px;" readonly="true"/></td> 
                
            </tr>
            <tr>
                <td>KEGIATAN</td>
                <td colspan="4"><input id="kd_kegiatan" name="kd_kegiatan" style="width: 140px;" />&nbsp;<input type="text" id="nm_kegiatan" style="border:0;width: 400px;" readonly="true"/></td>
            </tr>
            <tr>
                <td>PROGRAM</td>
                <td colspan="4"><input id="kd_program" name="kd_program" style="width: 140px;" />&nbsp;<input type="text" id="nm_program" style="border:0;width: 400px;" readonly="true"/></td>                         
            </tr>
            <tr>
              <td colspan="5">
                <hr>
              </td>
            </tr>
            <tr>
                <td>SASARAN PROGRAM</td>
                <td colspan="4"><textarea id="sasaran_program" name="sasaran_program" style="width: 700px;" /></textarea></td>
            </tr>
            <tr>

                <td >CAPAIAN PROGRAM</td> 
                <td colspan="4"><textarea type="text" id="capaian_program" name="capaian_program" style="width: 700px;" /></textarea></td>                                
            </tr>
             <tr>
              <td colspan="5">
                <hr>
              </td>
            </tr>
            <tr>
              <td rowspan="2" align="center">
                INDIKATOR KEGIATAN
              </td>
              <td colspan="2" align="center">
                TOLAK UKUR KINERJA
              </td>
              <td colspan="2" align="center">
                TARGET KINERJA
              </td>
            </tr>
            <tr>

              <td align="center">
                Utama
              </td>
              <td align="center">
                Penunjang
              </td>
              <td align="center">
                Utama
              </td>
              <td align="center">
                Penunjang
              </td>
            </tr>

            <tr>
                <td>CAPAIAN KEGIATAN</td>
                <td><textarea id="tu_capaian" name="tu_capaian" style="width: 150px;" /></textarea></td>
                <td><textarea id="tu_capaian_p" name="tu_capaian_p" style="width: 150px;" /></textarea></td>
                <td><textarea type="text" id="tk_capaian" name="tk_capaian" style="width: 150px;"/></textarea></td>  
                <td><textarea type="text" id="tk_capaian_p" name="tk_capaian_p" style="width: 150px;"/></textarea></td>                                
            </tr>
            <tr>
                <td>MASUKAN</td>
                <td><textarea id="tu_mas" name="tu_mas" style="width: 150px;" /></textarea></td>
                <td><textarea id="tu_mas_p" name="tu_mas_p" style="width: 150px;" /></textarea></td>
                <td><textarea type="text" id="tk_mas" name="tk_mas" style="width: 150px;" /></textarea></td> 
                <td><textarea type="text" id="tk_mas_p" name="tk_mas_p" style="width: 150px;" /></textarea></td>                                
            </tr>
            <tr>
                <td>KELUARAN</td>
                <td><textarea id="tu_kel" name="tu_kel" style="width: 150px;" /></textarea></td>
                <td><textarea id="tu_kel_p" name="tu_kel_p" style="width: 150px;" /></textarea></td>
                <td><textarea type="text" id="tk_kel" name="tk_kel" style="width: 150px;" /></textarea></td>
                <td><textarea type="text" id="tk_kel_p" name="tk_kel_p" style="width: 150px;" /></textarea></td>                                
            </tr>
            <tr>
                <td>HASIL</td>
                <td><textarea id="tu_has" name="tu_has" style="width: 150px;" /></textarea></td>
                <td><textarea id="tu_has_p" name="tu_has_p" style="width: 150px;" /></textarea></td>
                <td><textarea type="text" id="tk_has" name="tk_has" style="width: 150px;" /></textarea></td> 
                <td><textarea type="text" id="tk_has_p" name="tk_has_p" style="width: 150px;" /></textarea></td>                                
            </tr>
            <tr>
                <td>KELOMPOK SASARAN KEGIATAN</td>
                <td colspan="4"><textarea id="kel_sasaran_kegiatan" name="kel_sasaran_kegiatan" style="width: 700px;" /></textarea></td>
            </tr>
            <tr>
              <td colspan="5">
                &nbsp;
              </td>
              
            </tr>
           <tr>
                <td colspan="5" align="right"><a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();load_detail();">Tambah</a>
                    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
                  <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Keluar</a>                                   
                </td>
           </tr>
        </table>          
        <table id="dg1" title="Indikator Sub Kegiatan" style="width:870px;height:350px;" >  
        </table>  
        <div id="toolbar" align="right">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Sub Kegiatan</a>
          <!--<input type="checkbox" id="semua" value="1" /><a onclick="">Semua Urusan Permen 90</a>-->
            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_detail();">Hapus Sub Kegiatan</a>                   
        </div>
        <table align="center" style="width:100%;">
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td ></td>
            <td align="right">Total Sub Kegiatan: <input type="text" id="totall" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></td>
        </tr>
        </table>
                
   </p>
   </div>
   
</div>
</div>


<div id="dialog-modal" title="Input ">
    <p class="validateTips">SUB KEGIATAN</p> 
    <fieldset>
    <table>
        <tr>
            <td >KODE KEGIATAN</td>
            <td>:</td>
            <td><input id="kd_kegiatan1" name="kd_kegiatan1" style="width: 200px;" readonly="true" disabled/></td>
            <td colspan="3"><input type="text" id="nm_kegiatan1" name="nm_kegiatan1" readonly="true" style="border:0;width: 400px;" disabled/></td>
        </tr> 

         <tr>
            <td >SUB KEGIATAN</td>
            <td>:</td>
            <td><input id="kd_sub_kegiatan" name="kd_sub_kegiatan" style="width: 200px;" /></td>
            <td colspan="3"><input type="text" id="nm_sub_kegiatan" readonly="true" style="border:0;width: 400px;"/></td>
        </tr> 
        <tr>
            <td colspan="6"><hr></td>
        </tr>
        
        <tr>
            <td >LOKASI</td>
            <td>:</td>
            <td><textarea id="lokasi" name="lokasi" style="width: 200px;" /></textarea></td>
            <td >WAKTU</td>
            <td>:</td>
            <td ><textarea id="waktu" name="waktu" style="width: 100px;" placeholder="mulai" /></textarea> <textarea id="waktu2" placeholder="sampai" name="waktu2" style="width: 100px;" /></textarea></td>
        </tr>
        <tr>
            <td >SUB KELUARAN</td>
            <td>:</td>
            <td><textarea id="sub_keluaran" name="sub_keluaran" style="width: 200px;" /></textarea></td>
            <td >KETERANGAN</td>
            <td>:</td>
            <td ><textarea id="keterangan" name="keterangan" style="width: 200px;" /></textarea></td>
        </tr> 
        <tr>
            <td >Anggaran Tahun Lalu</td>
            <td>:</td>
            <td><input bg_color="red" id="ang_lalu" name="ang_lalu" style="width: 200px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            <td ></td>
            <td></td>
            <td ></td>
        </tr> 
        <tr>
            <td >Anggaran Tahun ini</td>
            <td>:</td>
            <td><input id="ang_ini" name="ang_ini" style="width: 200px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            <td ></td>
            <td></td>
            <td ></td>
        </tr> 
        <tr>
            <td >Anggaran Tahun Depan</td>
            <td>:</td>
            <td><input id="ang_depan" name="ang_depan" style="width: 200px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            <td ></td>
            <td></td>
            <td ></td>
        </tr> 
      
    </table>  
    </fieldset>
    <fieldset>
    <table align="center">
        <tr>
            <td><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>                               
            </td>
        </tr>
    </table>   
    </fieldset>
    <fieldset>
        <table align="right">           
            <tr>
                <td>Jumlah Sub Kegiatan</td>
                <td>:</td>
                <td><input type="text" id="total" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;"/></td>
            </tr>
        </table>
        <table id="dg2" title="Sub Kegiatan" style="width:950px;height:270px;"  >  
        </table>  
     
    </fieldset>  
</div>


<div id="dialog-modal-cetak" title="Cetak Urusan">
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