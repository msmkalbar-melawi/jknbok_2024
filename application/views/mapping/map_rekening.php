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
    
    var kode = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 650,
            width: 900,
            modal: true,
            autoOpen:false
        });
             
        });    
     
     $(function(){  
        
     $('#kd_rek13').combogrid({  
       panelWidth:500,  
       idField:'kd_rek13',  
       textField:'kd_rek13',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_rekening13',  
       columns:[[  
           {field:'kd_rek13',title:'Kode',width:100},  
           {field:'nm_rek13',title:'Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_rek13").attr("value",rowData.nm_rek13.toUpperCase());

            $("#nm_rek64").attr("value",rowData.nm_rek64.toUpperCase());
            $("#kd_rek64").attr("value",rowData.kd_rek64.toUpperCase());

          get_rekening90();           
       }  
     }); 
        
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/mapping/load_mapping_rekening',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'kd_rek13',
            title:'Kode Rekening 13',
            width:15,
            align:"center"},
            {field:'nm_rek13',
            title:'Nama Rekening 13',
            width:50},
            {field:'kd_rek6',
            title:'Kode Rekening 90',
            width:15,
            align:"center"},
            {field:'nm_rek6',
            title:'Nama Rekening 90',
            width:50}
        ]],onSelect:function(rowData,rowData){    
           $("#kd_rek13_edit").attr("value",rowData.kd_rek13.toUpperCase());
         },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Mapping Rekening'; 
           edit_data(); 
           load_detail();  
        }
        
        });

      $('#dg2').edatagrid({
        idField       :'id',
        toolbar       :"#toolbar",              
        rownumbers    :"true", 
        fitColumns    :false,
        autoRowHeight :"false",
        singleSelect  :"true",
        nowrap        :"false",          
        columns       : [[{field:'kd_rek13',title:'Kode 13',width:50},
                          {field:'nm_rek13',title:'Rekening 13',width:70},
                          {field:'kd_rek64',title:'Kode 64',width:50},
                          {field:'nm_rek64',title:'Rekening 64',width:70},
                          {field:'kd_rek90',title:'Kode 90',width:50},
                          {field:'nm_rek90',title:'Rekening 90',width:70}, 
                          // {field:'kd_rek90_lo',title:'Kode 90 LO',width:50},
                          // {field:'nm_rek90_lo',title:'Rekening 90 LO',width:70}, 
                          // {field:'kd_rek90_piu',title:'Kode 90 LO',width:50},
                          // {field:'nm_rek90_piu',title:'Rekening 90 LO',width:70}, 
                          {field:'hapus',     title:'Hapus',        width:70, align:"center",
                            formatter:function(value,rec){ 
                            return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                          }
                          }
                         ]]
        });

     //  $('#dg2').edatagrid({          
     //    rownumbers:"true", 
     //    fitColumns:"true",
     //    singleSelect:"true",
     //    autoRowHeight:"true",
     //    loadMsg:"Tunggu Sebentar....!!",              
     //    nowrap:"true",
     //    columns:[[
     //            {field:'hapus',title:'Hapus',width:11,align:"center",
     //            formatter:function(value,rec){ 
     //                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';
     //                }
     //            },
     //            {field:'kd_rek13',title:'Kode 13',width:50},
     //            {field:'nm_rek13',title:'Rekening 13',width:70},
     //            {field:'kd_rek64',title:'Kode 64',width:50},
     //            {field:'nm_rek64',title:'Rekening 64',width:70},
     //            {field:'kd_rek90',title:'Kode 90',width:50},
     //            {field:'nm_rek90',title:'Rekening 90',width:70} 
     //        ]]
     // }); 
       
    });        

function get_rekening90(){
  var ckdrek13 = $('#kd_rek13').combogrid('getValue');
  $('#kd_rek90').combogrid({  
       panelWidth:700,  
       idField:'kd_rek6',  
       textField:'kd_rek6',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_rekening90',
       queryParams:({kode:ckdrek13}), 
       columns:[[  
           {field:'kd_rek6',title:'Kode',width:100},  
           {field:'nm_rek6',title:'Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_rek90").attr("value",rowData.nm_rek6.toUpperCase());

           // get_rekening90lo();
       }  
     }); 
}

// function set_grid2(){
//         $('#dg2').edatagrid({                                                                   
//            columns:[[
//                 {field:'hapus',title:'Hapus',width:19,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/assets/images/icon/cross.png' onclick='javascript:hapus_detail();'' />";}},
//                 {field:'kd_rek13',title:'Kode 13',width:50},
//                 {field:'nm_rek13',title:'Rekening 13',width:70},
//                 {field:'kd_rek64',title:'Kode 64',width:50},
//                 {field:'nm_rek64',title:'Rekening 64',width:70},
//                 {field:'kd_rek90',title:'Kode 90',width:50},
//                 {field:'nm_rek90',title:'Rekening 90',width:70} 
//             ]]
//         });                 
//     }

    function load_detail() {
        
        var kk = document.getElementById("kd_rek13_edit").value; 

        $('#dg2').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/mapping/load_detail_mapping_rekening',
        queryParams   : ({rekening:kk}),
        idField       : 'id',
        toolbar       : "#toolbar",              
        rownumbers    : "true", 
        fitColumns    : "false",
        autoRowHeight : "false",
        singleSelect  : "true",
        nowrap        : "false",          
        columns       : [[{field:'kd_rek13',title:'Kode 13',width:50},
                          {field:'nm_rek13',title:'Rekening 13',width:70},
                          {field:'kd_rek64',title:'Kode 64',width:50},
                          {field:'nm_rek64',title:'Rekening 64',width:70},
                          {field:'kd_rek90',title:'Kode 90',width:50},
                          {field:'nm_rek90',title:'Rekening 90',width:70},
                          // {field:'kd_rek90_lo',title:'Kode 90 LO',width:50},
                          // {field:'nm_rek90_lo',title:'Rekening 90 LO',width:70}, 
                          // {field:'kd_rek90_piu',title:'Kode 90 LO',width:50},
                          // {field:'nm_rek90_piu',title:'Rekening 90 LO',width:70},  
                          {field:'hapus',     title:'Hapus',        width:70, align:"center",
                          formatter:function(value,rec){ 
                          return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                          }
                          }
                         ]]
        });
    }


    function hapus_detail(){
        
       var rows = $('#dg2').edatagrid('getSelected'); 
         var ckd_rek13 = rows.kd_rek13;        
         var cnm_rek13 =  rows.nm_rek13;         
         var idx  = $('#dg2').edatagrid('getRowIndex',rows);
         var tny = confirm('Yakin Ingin Menghapus Data, Rekening : '+cnm_rek13);
         if (tny==true){                                      
             $('#dg2').edatagrid('deleteRow',idx);
         }     
    }


    // function load_detail(){
    //     var i = 0;
    //     var kk = $('#kd_rek13').combogrid('getValue'); 
    //        $(document).ready(function(){
    //         $.ajax({
    //             type: "POST",
    //             url: '<?php echo base_url(); ?>/index.php/mapping/load_detail_mapping_rekening',
    //             data: ({no:kk}),
    //             dataType:"json",
    //             success:function(data){                                          
    //                 $.each(data,function(i,n){                                    
    //                   ckd_rek64     = n['kd_rek64'];
    //                   cnm_rek64     = n['nm_rek64'];
    //                   ckd_rek13     = n['kd_rek13'];
    //                   cnm_rek13     = n['nm_rek13'];
    //                   ckd_rek90     = n['kd_rek90'];
    //                   cnm_rek90     = n['nm_rek90'];

    //                  $('#dg2').edatagrid('appendRow',{kd_rek13:ckd_rek13,nm_rek13:cnm_rek13,kd_rek64:ckd_rek64,nm_rek64:cnm_rek64,kd_rek90:ckd_rek90,nm_rek90:cnm_rek90});                                                 
                                                                                                                                                                                                                                                                                                                                                                           
    //                 });                                                                           
    //             }
    //         });
    //        });   
    //        set_grid();
    // }

// function get_rekening90lo(){
//   var ckdrek13 = $('#kd_rek13').combogrid('getValue');
//   $('#kd_rek90_lo').combogrid({  
//        panelWidth:700,  
//        idField:'kd_rek6',  
//        textField:'kd_rek6',  
//        mode:'remote',
//        url:'<?php echo base_url(); ?>index.php/mapping/get_rekening90lo',
//        queryParams:({kode:ckdrek13}), 
//        columns:[[  
//            {field:'kd_rek6',title:'Kode',width:100},  
//            {field:'nm_rek6',title:'Rekening',width:400}    
//        ]],  
//        onSelect:function(rowIndex,rowData){
//            $("#nm_rek90_lo").attr("value",rowData.nm_rek6.toUpperCase());
//            get_rekening90piu();
//        }  
//      }); 
// }


// function get_rekening90piu(){
//   var ckdrek13 = $('#kd_rek13').combogrid('getValue');
//   $('#kd_rek90_piu').combogrid({  
//        panelWidth:700,  
//        idField:'kd_rek6',  
//        textField:'kd_rek6',  
//        mode:'remote',
//        url:'<?php echo base_url(); ?>index.php/mapping/get_rekening90piu',
//        queryParams:({kode:ckdrek13}), 
//        columns:[[  
//            {field:'kd_rek6',title:'Kode',width:100},  
//            {field:'nm_rek6',title:'Rekening',width:400}    
//        ]],  
//        onSelect:function(rowIndex,rowData){
//            $("#nm_rek90_piu").attr("value",rowData.nm_rek6.toUpperCase());
//        }  
//      }); 
// }
    
    // function get(kdskpd,nama,kd_u1,kd_u2,kd_u3,kd_u4,kd_u5,nm_u4) {
        
        
    //     $("#kode").combogrid("setValue",kdskpd);
    //     $("#nama").attr("value",nama);
        
    //     if (kd_u1=='0-00'){
    //       $("#kd_u1").attr("value",'');
    //     }else{
    //       $("#kd_u1").combogrid("setValue",kd_u1.replace('-','.'));
    //     }
    //     if (kd_u2=='0-00'){
    //       $("#kd_u2").attr("value",'');
    //     }else{
    //       $("#kd_u2").combogrid("setValue",kd_u2.replace('-','.'));
    //     }
    //     if (kd_u3=='0-00'){
    //       $("#kd_u3").attr("value",'');
    //     }else{
    //       $("#kd_u3").combogrid("setValue",kd_u3.replace('-','.'));
    //     }

   
                       
    // }

    function kosonginput() {

      var ckd_rek13    = $("#kd_rek13").combogrid('getValue') ;

        if(ckd_rek13==''){
              $("#nm_rek90").attr("value",'');
              $("#kd_rek90").attr("value",'');
              $("#nm_rek90_lo").attr("value",'');
              $("#kd_rek90_lo").attr("value",'');
              $("#nm_rek90_piu").attr("value",'');
              $("#kd_rek90_piu").attr("value",'');

      }else{

        $("#nm_rek90").attr("value",'');
        $("#kd_rek90").combogrid("setValue",'');
            
        // var ckd_rek90    = $("#kd_rek90").combogrid('getValue') ;

        //     if (ckd_rek90==''){
        //         $("#nm_rek90").attr("value",'');
        //         $("#kd_rek90").combogrid("setValue",'');

        //         $("#nm_rek90_lo").attr("value",'');
        //         $("#kd_rek90_lo").attr("value",'');
                
        //         $("#nm_rek90_piu").attr("value",'');
        //         $("#kd_rek90_piu").attr("value",'');

        //     }else{
        //       $("#nm_rek90").attr("value",'');
        //       $("#kd_rek90").combogrid("setValue",'');
        //       var ckd_rek90_lo    = $("#kd_rek90_lo").combogrid('getValue') ;

        //         if(ckd_rek90_lo==''){
        //           $("#nm_rek90_lo").attr("value",'');
        //           $("#kd_rek90_lo").combogrid("setValue",'');
                  
        //           $("#nm_rek90_piu").attr("value",'');
        //           $("#kd_rek90_piu").attr("value",'');
        //         }else{
        //           $("#nm_rek90_lo").attr("value",'');
        //           $("#kd_rek90_lo").combogrid("setValue",'');

        //           $("#nm_rek90_piu").attr("value",'');
        //           $("#kd_rek90_piu").combogrid("setValue",'');
        //         }
        //     }
      }
    }


       
    function kosong(){
        // $("#kd_u5").attr("value",'');

        $("#kd_rek64").attr("value",'');
        $("#nm_rek64").attr("value",'');
        $("#nm_rek13").attr("value",'');
        $("#kd_rek13").combogrid("setValue",'');

      //   if(ckd_rek13==''){
      //       $("#kd_rek90").attr("value",'');
      //       $("#kd_rek90").attr("value",'');

      // } else{
      //        $("#kd_rek90").attr("value",'');
      //        $("#kd_rek90").combogrid("setValue",'');


      //       if (ckd_rek90==''){
      //           var cnm_rek90_lo    = document.getElementById('nm_rek90_lo').value ;
      //           var ckd_rek90_lo    = document.getElementById('kd_rek90_lo').value ;
      //           var cnm_rek90_piu    = document.getElementById('nm_rek90_piu').value ;
      //           var ckd_rek90_piu    = document.getElementById('kd_rek90_piu'.value);

      //       }else{
      //         var cnm_rek90_lo    = document.getElementById('nm_rek90_lo').value ;
      //         var ckd_rek90_lo    = $("#kd_rek90_lo").combogrid('getValue') ;

      //           if(ckd_rek90_lo==''){
      //             var cnm_rek90_piu    = document.getElementById('nm_rek90_piu').value ;
      //             var ckd_rek90_piu    = document.getElementById('kd_rek90_piu'.value);
      //           }else{
      //             var cnm_rek90_piu    = document.getElementById('nm_rek90_piu').value ;
      //             var ckd_rek90_piu    = $("#kd_rek90_piu").combogrid('getValue') ;
      //           }
        

        load_detail(); 
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/mapping/load_mapping_rekening',
        queryParams:({cari:kriteria})
        });        
     });
    }


    function append_save() {


          $('#dg2').datagrid('selectAll');
            var rows  = $('#dg2').datagrid('getSelections');
                pidx  = rows.length + 1 ;
            
            var cnm_rek13    = document.getElementById('nm_rek13').value ;
            var ckd_rek13    = $("#kd_rek13").combogrid('getValue') ;
            var cnm_rek64    = document.getElementById('nm_rek64').value ;
            var ckd_rek64    = document.getElementById('kd_rek64').value ;
            
      if(ckd_rek13==''){
            var cnm_rek90    = document.getElementById('nm_rek90').value ;
            var ckd_rek90    = document.getElementById('kd_rek90').value ;

      } else{
            var cnm_rek90    = document.getElementById('nm_rek90').value ;
            var ckd_rek90    = $("#kd_rek90").combogrid('getValue') ;


            // if (ckd_rek90==''){
            //     var cnm_rek90_lo    = document.getElementById('nm_rek90_lo').value ;
            //     var ckd_rek90_lo    = document.getElementById('kd_rek90_lo').value ;
            //     var cnm_rek90_piu    = document.getElementById('nm_rek90_piu').value ;
            //     var ckd_rek90_piu    = document.getElementById('kd_rek90_piu'.value);

            // }else{
            //   var cnm_rek90_lo    = document.getElementById('nm_rek90_lo').value ;
            //   var ckd_rek90_lo    = $("#kd_rek90_lo").combogrid('getValue') ;

            //     if(ckd_rek90_lo==''){
            //       var cnm_rek90_piu    = document.getElementById('nm_rek90_piu').value ;
            //       var ckd_rek90_piu    = document.getElementById('kd_rek90_piu'.value);
            //     }else{
            //       var cnm_rek90_piu    = document.getElementById('nm_rek90_piu').value ;
            //       var ckd_rek90_piu    = $("#kd_rek90_piu").combogrid('getValue') ;
            //     }

            // }

        }   
            
            

             if ( ckd_rek13=='' ){
                 alert('Pilih Kode Rekening 13 Terlebih Dahulu...!!!');
                 exit();
            }

            if ( ckd_rek90=='' ){
                alert('Pilih Kode Rekening 90 Terlebih Dahulu...!!!');
                exit();
            }


           $('#dg2').edatagrid('appendRow',{id:pidx,kd_rek13:ckd_rek13,nm_rek13:cnm_rek13,kd_rek64:ckd_rek64,nm_rek64:cnm_rek64,kd_rek90:ckd_rek90,nm_rek90:cnm_rek90});//,kd_rek90_lo:ckd_rek90_lo,nm_rek90_lo:cnm_rek90_lo,kd_rek90_piu:ckd_rek90_piu,nm_rek90_piu:cnm_rek90_piu
              kosonginput();
            
            
       }

function simpan(){
  var ckdrek13 = $('#kd_rek13').combogrid('getValue');

        $('#dg2').datagrid('selectAll');
                var dgrid = $('#dg2').datagrid('getSelections');
                 for(var w=0;w<dgrid.length;w++){
                            ckd_rek13     = dgrid[w].kd_rek13;                                            
                            cnm_rek13     = dgrid[w].nm_rek13;
                            ckd_rek64     = dgrid[w].kd_rek64;
                            cnm_rek64     = dgrid[w].nm_rek64;
                            ckd_rek90     = dgrid[w].kd_rek90;
                            cnm_rek90     = dgrid[w].nm_rek90;
                            // ckd_rek90_lo  = dgrid[w].kd_rek90_lo;
                            // cnm_rek90_lo  = dgrid[w].nm_rek90_lo;
                            // ckd_rek90_piu = dgrid[w].kd_rek90_piu;
                            // cnm_rek90_piu = dgrid[w].nm_rek90_piu;



                            if (w>0) {
                                csql = csql+",('"+ckd_rek13+"','"+cnm_rek13+"','"+ckd_rek64+"','"+cnm_rek64+"','','','','"+ckd_rek90+"','"+cnm_rek90+"','"+ckd_rek90.substring(0,7)+"')";

                                csql2 = csql2+"OR (kd_rek13='"+ckd_rek13+"'AND kd_rek64='"+ckd_rek64+"'AND kd_rek6='"+ckd_rek90+"')";
                            }else{
                                csql = " values('"+ckd_rek13+"','"+cnm_rek13+"','"+ckd_rek64+"','"+cnm_rek64+"','','','','"+ckd_rek90+"','"+cnm_rek90+"','"+ckd_rek90.substring(0,7)+"')";
                                csql2 = "kd_rek13='"+ckd_rek13+"'AND kd_rek64='"+ckd_rek64+"'AND kd_rek6='"+ckd_rek90+"'";
                            }
                                                                                          
                  }

                  if (ckdrek13==''){
                    alert ('Silahkan pilih kode Rekening 13 dan 90 terlebih dahulu');
                    exit();
                  }


                  var tny = confirm('Apakah data mapping rekening sudah sesuai ???');
                  if (tny==true){                                      
                  
                     $(document).ready(function(){     
                            $.ajax({
                                type: "POST",   
                                dataType : 'json',                 
                                data: ({tabel:'ms_rekening',sql:csql,sql2:csql2}),
                                url: '<?php echo base_url(); ?>/index.php/mapping/simpan_map_rekening',
                                success:function(data){                        
                                    status = data.pesan;   
                                    if (status=='1'){               
                                        alert('Data Berhasil Tersimpan');
                                        $("#dialog-modal").dialog('close');
                                        
                                    } else{ 
                                        alert('Data Gagal Tersimpan');
                                    }                                             
                                }
                            });
                        });


                  }  
        $('#dg').edatagrid('reload'); 

    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data SKPD';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Rekening';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        // document.getElementById("kode").disabled=false;
        // document.getElementById("kode").focus();
        // $('#kode').combogrid("enable"); 
        // $("#kode").combogrid("setValue",'');
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 
     }    
    
     function hapus(){
        var ckode = document.getElementById('kd_rek13_edit').value ;
        if (ckode==''){
                    alert ('Silahkan pilih data yang akan dihapus terlebih dahulu');
                    exit();
                  }
        
        var urll = '<?php echo base_url(); ?>index.php/mapping/hapus_skpd';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_rekening',cnid:ckode,cid:'kd_rek13'}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                keluar();
                exit();
                
            }
         });
        });    
    } 
    

    
  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>INPUTAN MASTER REKENING</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();load_detail();">Tambah</a></td>               
        
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING SKPD" style="width:900px;height:440px;" >  
        </table>
        </td>
        </tr>
    </table>    
    
        
 
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">MAPPING KODE REKENING</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
           <tr>
                <td width="30%">KODE REKENING 13</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rek13" style="width:150px;"/>&nbsp;&nbsp;<input type="text" id="nm_rek13" style="width:360px;" disabled/><input type="hidden" id="kd_rek13_edit" style="width:360px;" disabled/></td>  
            </tr>
                       
            <tr>
                <td width="30%">KODE REKENING 64</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rek64" style="width:150px;"/>&nbsp;&nbsp;<input type="text" id="nm_rek64" style="width:360px;" disabled/>
                  <input type="text" id="status" style="width:360px;" disabled/></td>  
            </tr>
            
            <tr>
                <td width="30%">KODE REKENING 90</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rek90" style="width:150px;"/>&nbsp;&nbsp;<input type="text" id="nm_rek90" style="width:360px;" disabled/></td>  
            </tr>
   <!--          <tr>
                <td width="30%">KODE REKENING 90 LO</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rek90_lo" style="width:150px;"/>&nbsp;&nbsp;<input type="text" id="nm_rek90_lo" style="width:360px;" disabled/></td>  
            </tr>
            <tr>
                <td width="30%">KODE REKENING 90 UTANG/PIUTANG</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rek90_piu" style="width:150px;"/>&nbsp;&nbsp;<input type="text" id="nm_rek90_piu" style="width:360px;" disabled/></td>  
            </tr>            
             -->
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
        </table>
        <table style="width:755px;" border='0'>
            <tr>
                <td colspan="3" align="center">
                  <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:append_save();">Insert</a>
                  <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
             <table id="dg2" title="Rekening Permendagri 90" style="width:850px;height:300px;" >  
             </table>
    </table>

    </fieldset>
</div>

</body>

</html>