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
    });         
    var idx ='';    
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/mapping/load_kegiatan',
        idField:'no_voucher',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",rowStyler: function(index,row){
                    if (row.jml>0){
                      return 'background-color:#3be8ff;';
                    }
                  },                       
        columns:[[
    	    {field:'kd_kegiatan',title:'Kode kegiatan',width:80},
            {field:'nm_kegiatan',title:'kegiatan',width:300},
            {field:'pagu',title:'Pagu Anggaran',width:100,align:'right'}
        ]],
        onSelect:function(rowIndex,rowData){    
           kd_kegiatan  = rowData.kd_kegiatan;
           nm_kegiatan  = rowData.nm_kegiatan;
           kd_skpd      = rowData.kd_skpd;
           nm_skpd      = rowData.nm_skpd;
           get(kd_skpd,nm_skpd,kd_kegiatan,nm_kegiatan);
        },
        onDblClickRow:function(rowIndex,rowData){         
            section2();
          $("#waktu_giat").attr("value",rowData.waktu_giat.toUpperCase());
           $("#sasaran_giat").attr("value",rowData.sasaran_giat.toUpperCase());
           $("#tu_mas").attr("value",rowData.tu_mas.toUpperCase());
           $("#tu_capai").attr("value",rowData.tu_capai.toUpperCase());
           $("#tu_kel").attr("value",rowData.tu_kel.toUpperCase());
           $("#tu_has").attr("value",rowData.tu_has.toUpperCase());
           $("#tk_capai").attr("value",rowData.tk_capai.toUpperCase());
           $("#tk_kel").attr("value",rowData.tk_kel.toUpperCase());
           $("#tk_has").attr("value",rowData.tk_has.toUpperCase());
           $("#lokasi").attr("value",rowData.lokasi.toUpperCase());
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
        	    {field:'kd_kegiatan1',title:'Kode kegiatan',width:50},
                {field:'nm_kegiatan1',title:'Nama kegiatan',width:70},
                {field:'kd_kegiatan90',title:'Kode kegiatan 90',width:50},
                {field:'nm_kegiatan90',title:'Nama kegiatan 90',width:70},
                {field:'nilai90',title:'Pagu',width:70,align:'right'},
                {field:'nilailalu90',title:'Anggaran Lalu',width:70,align:'right'}
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
           $("#nm_skpd90").attr("value",rowData.nm_skpd90.toUpperCase());
           $("#kd_skpd90").attr("value",rowData.kd_skpd90.toUpperCase());
           get_kegiatan();
           get_kegiatan90();
                                  
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
           {field:'nm_kegiatan',title:'Nama Kegiatan',width:400},
           {field:'pagu1',title:'Pagu',width:100,align:'right'},  
           {field:'angg_lalu1',title:'Anggaran Lalu',width:100,align:'right'}    
       ]],  
       onSelect:function(rowIndex,rowData){
           // kd_prog = rowData.kd_kegiatan;
       
       //$("#kd_progr").attr("value",rowData.kd_kegiatan.toUpperCase());
           $("#nm_kegiatan").attr("value",rowData.nm_kegiatan.toUpperCase());
           $("#totala1").attr("value",rowData.pagu.toUpperCase());
           $("#totala").attr("value",rowData.pagu1.toUpperCase());
           $("#totallalua").attr("value",rowData.angg_lalu1.toUpperCase());
           $("#nilai1").attr("value",rowData.pagu1.toUpperCase());
           $("#nilai1a").attr("value",rowData.pagu.toUpperCase());
           $("#nilailalu1").attr("value",rowData.angg_lalu1.toUpperCase());

           $("#waktu_giat").attr("value",rowData.waktu_giat.toUpperCase());
           $("#sasaran_giat").attr("value",rowData.sasaran_giat.toUpperCase());
           $("#tu_mas").attr("value",rowData.tu_mas.toUpperCase());
           $("#tu_capai").attr("value",rowData.tu_capai.toUpperCase());
           $("#tu_kel").attr("value",rowData.tu_kel.toUpperCase());
           $("#tu_has").attr("value",rowData.tu_has.toUpperCase());
           $("#tk_capai").attr("value",rowData.tk_capai.toUpperCase());
           $("#tk_kel").attr("value",rowData.tk_kel.toUpperCase());
           $("#tk_has").attr("value",rowData.tk_has.toUpperCase());
           $("#lokasi").attr("value",rowData.lokasi.toUpperCase());

           $("#kd_kegiatan1").attr("value",rowData.kd_kegiatan.toUpperCase());
           $("#nm_kegiatan1").attr("value",rowData.nm_kegiatan.toUpperCase());

                                  
       }  
     }); 
     }
     

     

function get_kegiatan90(){
         var ckd_skpd = $('#kd_skpd').combogrid('getValue');
     $('#kd_kegiatan90').combogrid({  

           panelWidth:700,  
           idField:'kd_sub_kegiatan',  
           textField:'kd_sub_kegiatan',  
           mode:'remote',                      
           url:'<?php echo base_url(); ?>index.php/mapping/ambil_kegiatan90',
           queryParams:({skpd:ckd_skpd}), 
           columns:[[  
               {field:'kd_sub_kegiatan',title:'Kode Sub kegiatan',width:100},  
               {field:'nm_sub_kegiatan',title:'Nama Sub kegiatan',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){              
               $('#nm_kegiatan90').attr('value',rowData.nm_sub_kegiatan);                               
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
    }
    
    function kosong2(){        
        $("#kd_kegiatan90").combogrid("setValue",'');
        $("#nm_kegiatan90").attr("value",'');
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
        $("#nilai90").attr("value",'');
        $("#sisa").attr("value",'');
        $("#total").attr("value",'');
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
              {field:'nm_kegiatan1',title:'Nama kegiatan',width:70},
              {field:'kd_kegiatan90',title:'Kode kegiatan 90',width:50},
              {field:'nm_kegiatan90',title:'Nama kegiatan 90',width:70},
              {field:'nilai90',title:'Pagu',width:70},      
              {field:'nilailalu90',title:'Anggaran Lalu',width:70}       
            ]]
        });               
    }
    
    function set_grid2(){
        $('#dg2').edatagrid({                                                                   
           columns:[[
                {field:'hapus',title:'Hapus',width:19,align:'center',formatter:function(value,rec){ return "<img src='<?php echo base_url(); ?>/assets/images/icon/cross.png' onclick='javascript:hapus_detail();'' />";}},
        	     {field:'kd_kegiatan90',title:'Kode kegiatan',width:50},
               {field:'nm_kegiatan90',title:'Nama kegiatan',width:70},
               {field:'nilai90',title:'Pagu',width:70},
               {field:'nilailalu90',title:'Anggaran Lalu',width:70}
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
        
        var cek = document.getElementById('totalb').value;    

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
        var kk = $('#kd_kegiatan').combogrid('getValue');             
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/mapping/load_detail_kegiatan',
                data: ({no:kk}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    
                    if (n['kd_kegiatan90']=='' || n['kd_kegiatan90'] ==null){
                      ckd_kegiatan1    = '';
                      cnm_kegiatan1    = '';
                      ctotall  = 0;  
                    }else{
                        ckd_kegiatan1    = n['kd_kegiatan1'];
                        cnm_kegiatan1    = n['nm_kegiatan1'];
                        ckd_kegiatan90   = n['kd_kegiatan90'];
                        cnm_kegiatan90   = n['nm_kegiatan90'];
                        cnilai90         = number_format(n['nilai90'],2,'.',',');
                        cnilailalu90     = number_format(n['nilailalu90'],2,'.',',');
                        ctotall          = n['jml'];
                        ctotala          = n['nilai'];
                        ctotallalua      = n['nilailalu'];

                     $('#dg1').edatagrid('appendRow',{kd_kegiatan1:ckd_kegiatan1,nm_kegiatan1:cnm_kegiatan1,kd_kegiatan90:ckd_kegiatan90,nm_kegiatan90:cnm_kegiatan90,nilai90:cnilai90,nilailalu90:cnilailalu90});
                    

                    }

                    $("#totall").attr("value",ctotall);
                    $("#totala").attr("value",number_format(ctotala,2,'.',','));
                    $("#totalb").attr("value",number_format(ctotala,2,'.',','));
                    $("#total90").attr("value",number_format(ctotala,2,'.',','));

                    $("#nilai1").attr("value",number_format(ctotala,2,'.',','));
                    $("#nilailalu1").attr("value",number_format(ctotala,2,'.',','));

                    $("#totallalua").attr("value",number_format(ctotallalua,2,'.',','));
                    $("#totallalub").attr("value",number_format(ctotallalua,2,'.',','));
                    $("#totallalu90").attr("value",number_format(ctotallalua,2,'.',','));


                                                                                    
                                                                                                                                                                                                                                                                                                                                                                           
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
            ckd_kegiatan90      = rows[p].kd_kegiatan90;
            cnm_kegiatan90      = rows[p].nm_kegiatan90;
            cnilai90            = rows[p].nilai90;
            cnilailalu90        = rows[p].nilailalu90;
            $('#dg2').edatagrid('appendRow',{kd_kegiatan90:ckd_kegiatan90,nm_kegiatan90:cnm_kegiatan90,nilai90:cnilai90,nilailalu90:cnilailalu90});            
        }
        $('#dg1').edatagrid('unselectAll');
    } 
    
    function get(kd_skpd,nm_skpd,kd_kegiatan,nm_kegiatan){
        $('#nm_skpd').attr('value',nm_skpd);
        $("#kd_skpd").combogrid("setValue",kd_skpd);
        $('#nm_kegiatan').attr('value',nm_kegiatan);
        $("#kd_kegiatan").combogrid("setValue",kd_kegiatan);
        $('#nm_kegiatan1').attr('value',nm_kegiatan);
        $("#kd_kegiatan1").attr("value",kd_kegiatan);
        
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
        var ctotal90        = angka(document.getElementById('total90').value);
        var ctotallalu90    = angka(document.getElementById('totallalu90').value);
        var rows = $('#dg2').edatagrid('getSelected');
        ckd_kegiatan90 = rows.ckd_kegiatan90;
        cnm_kegiatan90 = rows.nm_kegiatan90;
        cnilai90        = angka(rows.nilai90);
        cnilailalu90    = angka(rows.nilailalu90);
        

        
        var idx = $('#dg2').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, kegiatan : '+ckd_kegiatan90+' Nama : '+cnm_kegiatan90+' dengan Nilai:'+cnilai90);
        if (tny==true){
            $('#dg2').edatagrid('deleteRow',idx);
            $('#dg1').edatagrid('deleteRow',idx);


            //hitung ulang total

            ctotal90=ctotal90 -cnilai90;
            ctotallalu90=ctotallalu90 -cnilailalu90;

            $('#total90').attr('value',number_format(ctotal90,2,'.',','));
            $('#totallalu90').attr('value',number_format(ctotallalu90,2,'.',','));

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
        var ckd_kegiatan90  = $('#kd_kegiatan90').combogrid('getValue');
        var cnm_kegiatan90  = document.getElementById('nm_kegiatan90').value;
        var ckd_kegiatan1   = document.getElementById('kd_kegiatan1').value;
        var cnm_kegiatan1   = document.getElementById('nm_kegiatan1').value;
        var nil             = 1;
        var nilai90         = document.getElementById('nilai90').value;
        var cnilai90        =angka(nilai90);
        //lalu
        var nilailalu90         = document.getElementById('nilailalu90').value;
        var cnilailalu90        =angka(nilailalu90);

        var cnm_kegiatan1   = document.getElementById('nm_kegiatan1').value;
        var ctotal           = document.getElementById('total').value;
        var ctotals          = angka(ctotal);
        var ctotal90        = document.getElementById('total90').value;
        var ctotallalu90        = document.getElementById('totallalu90').value;
        if ((ckd_kegiatan90 != '' || cnm_kegiatan90 != '') && (nilai90!='' || nilai90!=0) && (nilailalu90!='' || nilailalu90!=0)){

            //hitung jumlah mappingan
            if (ctotal==''){
              ctotal=0;
            }
                ctotal = parseInt(ctotal)+parseInt(nil);
                $('#totall').attr('value',ctotal);
                $('#total').attr('value',ctotal);

                if (ctotal90==''){
                 ctotal90='0.00';
                }

                
            //hitung total pagu yang sudah di mapping


            ctotal90 = angka(ctotal90) + cnilai90;
            $('#total90').attr('value',number_format(ctotal90,2,'.',','));
            $('#totalb').attr('value',number_format(ctotal90,2,'.',','));

            // alert(ctotal90);
            //hitung anggaran lalu
            if (ctotallalu90==''){
                 ctotallalu90='0.00';
                }
            ctotallalu90 = angka(ctotallalu90) + cnilailalu90;
            $('#totallalu90').attr('value',number_format(ctotallalu90,2,'.',','));
            $('#totallalub').attr('value',number_format(ctotallalu90,2,'.',','));
            // alert(ctotallalu90);

            $('#dg1').edatagrid('appendRow',{kd_kegiatan1:ckd_kegiatan1,nm_kegiatan1:cnm_kegiatan1,kd_kegiatan90:ckd_kegiatan90,nm_kegiatan90:cnm_kegiatan90,nilai90:nilai90,nilailalu90:nilailalu90});

            $('#dg2').edatagrid('appendRow',{kd_kegiatan90:ckd_kegiatan90,nm_kegiatan90:cnm_kegiatan90,nilai90:nilai90,nilailalu90:nilailalu90});
            
            kosong3();
       }else {
                alert('Kode, Nama kegiatan dan nilai tidak boleh kosong');
                exit();
        }
    }

    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#dg').edatagrid({
         url: '<?php echo base_url(); ?>/index.php/mapping/load_kegiatan',
         queryParams:({cari:kriteria})
        });        
     });
    }


    function sisa_anggaran(){
        var nilai1      = angka(document.getElementById('nilai1').value);
        var totaln90    = document.getElementById('total90').value;
        var nilai90     = angka(document.getElementById('nilai90').value);

        if(totaln90==''){
            total90=0;
        }else{
            total90=angka(totaln90);
        }
        
        sisa            = nilai1 - total90;
        sisasekarang    = (sisa - nilai90); 
        $("#sisa").attr("value",number_format(sisasekarang,2,'.',','));   
        if (sisasekarang < 0){
                alert('Nilai Melebihi Pagu Anggaran');
                $("#nilai90").attr("value",'0.00');
                sisa_anggaran();
                exit();                
        }
    }



    function sisa_anggaranlalu(){
        var nilailalu1      = angka(document.getElementById('nilailalu1').value);
        var totalnlalu90    = document.getElementById('totallalu90').value;
        var nilailalu90     = angka(document.getElementById('nilailalu90').value);

        if(totalnlalu90==''){
            totallalu90=0;
        }else{
            totallalu90=angka(totalnlalu90);
        }
        
        sisalalu            = nilailalu1 - totallalu90;
        sisasekaranglalu    = (sisalalu - nilailalu90); 
        $("#sisalalu").attr("value",number_format(sisasekaranglalu,2,'.',','));   
        if (sisasekarang < 0){
                alert('Nilai Melebihi Anggaran Lalu');
                $("#nilailalu90").attr("value",'0.00');
                sisa_anggaranlalu();
                exit();                
        }
    }
    
    function simpan(){
        var cnm_kegiatan  = document.getElementById('nm_kegiatan').value;
        var ckd_kegiatan  = $('#kd_kegiatan').combogrid('getValue');
        var ctotall = document.getElementById('totall').value;
        var ctotala = document.getElementById('totala').value;
        var atotala = angka(ctotala);
        var ctotallalua = document.getElementById('totallalua').value;
        var atotallalua = angka(ctotallalua);
        var ctotalb = document.getElementById('totalb').value;

        var csasaran_giat = document.getElementById('sasaran_giat').value;
        var cwaktu_giat = document.getElementById('waktu_giat').value;
        var ctu_capai = document.getElementById('tu_capai').value;
        var ctu_mas = document.getElementById('tu_mas').value;
        var ctu_kel = document.getElementById('tu_kel').value;
        var ctu_has = document.getElementById('tu_has').value;
        var ctk_capai = document.getElementById('tk_capai').value;
        var ctk_kel = document.getElementById('tk_kel').value;
        var ctk_has = document.getElementById('tk_has').value;
        var clokasi = document.getElementById('lokasi').value;

        var ckd_skpd90  = document.getElementById('kd_skpd90').value;
        var cnm_skpd90  = document.getElementById('nm_skpd90').value;
             
        if (ctotall=='' || ctotall==0){
            alert('Anda harus melakukan mapping kegiatan terlebih dahulu');
            exit();
        } 
        if (ckd_kegiatan==''){
            alert('Kode dan nama kegiatan Asal Tidak Boleh Kosong');
            exit();
        }      
            if (ctotalb<ctotala){
                alert('Nilai Pagu Hasil Mapping masih kurang dari jumlah pagu asal');
                exit();
            }else if(ctotalb>ctotala){
                alert('Nilai Pagu Hasil Mapping melebihi dari jumlah pagu asal');
                exit();
            }
                                                      
                $('#dg1').datagrid('selectAll');
                var dgrid = $('#dg1').datagrid('getSelections');
 			           for(var w=0;w<dgrid.length;w++){
            				ckd_kegiatan90  = dgrid[w].kd_kegiatan90;                                            
                            ckd_kegiatan1   = dgrid[w].kd_kegiatan1;
                            cnm_kegiatan90  = dgrid[w].nm_kegiatan90;
                            cnm_kegiatan1   = dgrid[w].nm_kegiatan1;
                            cnilai90        = angka(dgrid[w].nilai90);
                            cnilailalu90    = angka(dgrid[w].nilailalu90);
                                                                            
                            if (w>0) {
                                //ke tabel mapping
                                csql = csql+",('"+ckd_kegiatan1+"','"+cnm_kegiatan1+"','"+atotala+"','"+ckd_kegiatan90+"','"+cnm_kegiatan90+"','"+cnilai90+"','"+atotallalua+"','"+cnilailalu90+"')";
                                //ke tabel trskpd
                                csqltrskpd = csqltrskpd+",('"+ckd_kegiatan90+"','"+ckd_kegiatan90+"','"+ckd_kegiatan90.substring(0,7)+"','"+ckd_kegiatan90.substring(0,4)+"','"+ckd_skpd90+"','"+cnm_skpd90+"','"+ckd_kegiatan90+"','"+cnm_kegiatan90+"','','','','"+cwaktu_giat+"','"+csasaran_giat+"','"+ctu_capai+"','"+ctu_mas+"','"+ctu_kel+"','"+ctu_has+"','"+ctk_capai+"','"+ctk_kel+"','"+ctk_has+"','"+cnilailalu90+"','"+cnilai90+"','"+clokasi+"')";
                            } else {
                                //ke tabel mapping
                                csql = " values('"+ckd_kegiatan1+"','"+cnm_kegiatan1+"','"+atotala+"','"+ckd_kegiatan90+"','"+cnm_kegiatan90+"','"+cnilai90+"','"+atotallalua+"','"+cnilailalu90+"')";
                                //ke tabel trskpd
                                csqltrskpd = " values('"+ckd_kegiatan90+"','"+ckd_kegiatan90+"','"+ckd_kegiatan90.substring(0,7)+"','"+ckd_kegiatan90.substring(0,4)+"','"+ckd_skpd90+"','"+cnm_skpd90+"','"+ckd_kegiatan90+"','"+cnm_kegiatan90+"','','','','"+cwaktu_giat+"','"+csasaran_giat+"','"+ctu_capai+"','"+ctu_mas+"','"+ctu_kel+"','"+ctu_has+"','"+ctk_capai+"','"+ctk_kel+"','"+ctk_has+"','"+cnilailalu90+"','"+cnilai90+"','"+clokasi+"')";
                            }
                                                                                          
             			}                                                    
                        $(document).ready(function(){     
                            $.ajax({
                                type: "POST",   
                                dataType : 'json',                 
                                data: ({tabel:'mapping_kegiatan',sql:csql,sqltrskpd:csqltrskpd,kode:ckd_kegiatan}),
                                url: '<?php echo base_url(); ?>/index.php/mapping/simpan_kegiatan',
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
      .button1 {background-color: #fff;}
    </style>
 </head>
    
<body>

<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1" >List kegiatan</a></h3>
    <div>
      <p>Keterangan Warna:</p>
      <p>
        <button class="button button2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> Sudah dilakukan mapping<br />
        <button class="button button1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> Belum dilakukan mapping</p>
    <p align="right">         
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
                    <input id="sasaran_giat" name="sasaran_giat" style="width: 140px;" hidden />
                    <input type="text" id="waktu_giat" name="waktu_giat" style="width: 140px;" readonly="true" hidden/>
                    <input id="tu_capai" name="tu_capai" style="width: 140px;" hidden />
                    <input type="text" id="tu_mas" name="tu_mas" style="width: 140px;" readonly="true" hidden/>
                    <input id="tu_kel" name="tu_kel" style="width: 140px;" hidden />
                    <input type="text" id="tu_has" name="tu_has" style="width: 140px;" readonly="true" hidden/>
                    <input id="tk_capai" name="tk_capai" style="width: 140px;" hidden />
                    <input type="text" id="tk_kel" name="tk_kel" style="width: 140px;" readonly="true" hidden/>
                    <input id="tk_has" name="tk_has" style="width: 140px;" hidden />
                    <input type="text" id="lokasi" name="lokasi" style="width: 140px;" readonly="true" hidden />
                </td>                                
            </tr>

          


            <tr>
                <td colspan="5"><font color="red">Catatan: Jika kegiatan yang akan di mapping tidak tersedia, silahkan input program dari kegiatan tersebut terlebih dahulu.</font></td>
            </tr>                                                   
           <tr>
                <td colspan="5" align="right"><a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Tambah</a>
                    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
		            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
  		            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>                                   
                </td>
           </tr>
        </table>          
        <table id="dg1" title="Kode kegiatan Permendagri 90" style="width:870px;height:350px;" >  
        </table>  
        <div id="toolbar" align="right">
    		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah kegiatan Permendagri 90</a>
   		    <!--<input type="checkbox" id="semua" value="1" /><a onclick="">Semua kegiatan Permen 90</a>-->
            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_giat();">Hapus kegiatan Permendagri 90</a>               		
        </div>
        <table align="center" style="width:100%;">
        <tr>
            
            
            <td align="right">Total BAPPEDA :</td>
            <td>Pagu<br/>Anggaran Lalu</td>
            <td align="left">
                <input type="text" id="totala" style="text-align: right;border:0;width: 200px;" readonly="true"/><br />
                <input type="text" id="totallalua" style="text-align: right;border:0;width: 200px;" readonly="true"/>
                <input type="text" id="totala1" style="text-align: right;border:0;width: 200px;" hidden/></td>
            <td align="right"> Total PERMEN 90:</td>
            <td>Pagu<br/>Anggaran Lalu</td>
            <td align="left">
                <input type="text" id="totalb" style="text-align: right;border:0;width: 200px;" readonly="true"/><br/>
                <input type="text" id="totallalub" style="text-align: right;border:0;width: 200px;" readonly="true"/>
            <input type="text" id="totall" style="text-align: right;border:0;width: 200px;" hidden />
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
        <tr>
            <td >Nilai Pagu</td>
            <td>:</td>
            <td><input id="nilai1" name="nilai1" style="width: 200px;text-align: right;" readonly="true"  onkeypress="return(currencyFormat(this,',','.',event))" />&nbsp;&nbsp;Anggaran Lalu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nilailalu1" name="nilailalu1" style="width: 200px;" readonly="true" /></td>
            <td ></td>
            <td></td>
            <td></td>
        </tr> 

         <tr>
            <td >Kode kegiatan Permendagri 90</td>
            <td>:</td>
            <td><input id="kd_kegiatan90" name="kd_kegiatan90" style="width: 200px;" />&nbsp;&nbsp;<input type="text" id="nm_kegiatan90" readonly="true" style="border:0;width: 400px;"/></td>
            <td ></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td >Sisa Pagu</td>
            <td>:</td>
            <td><input id="sisa" name="sisa" style="width: 200px;text-align: right;" readonly="true" /><input id="sisa1" name="sisa1" style="width: 200px;text-align: right;" readonly="true" hidden/>&nbsp;&nbsp;Sisa Anggaran Lalu&nbsp;<input id="sisalalu" name="sisalalu" style="width: 200px;text-align: right;" readonly="true" /></td>
            <td ></td>
            <td></td>
            <td></td>
        </tr>                 
        <tr>
            <td >Nilai sub kegiatan</td>
            <td>:</td>
            <td><input id="nilai90" name="nilai90" style="width: 200px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"  onkeyup="javascript:sisa_anggaran();"/>&nbsp;&nbsp;Nilai Lalu &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nilailalu90" name="nilailalu90" style="width: 200px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"  onkeyup="javascript:sisa_anggaranlalu();"/></td>
            <td ></td>
            <td></td>
            <td></td>
        </tr>                
      
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
                <td>Total Pagu Permendagri 90</td>
                <td>:</td>
                <td><input type="text" id="total" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;"/>
                    <input type="text" id="total90" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;"/></td>
                <td>Total Anggaran Lalu Permendagri 90</td>
                <td>:</td>
                <td><input type="text" id="totallalu90" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;"/></td>    
            </tr>
        </table>
        <table id="dg2" title="Input kegiatan Permendagri 90" style="width:950px;height:270px;"  >  
        </table>  
     
    </fieldset>  
</div>


</body>

</html>