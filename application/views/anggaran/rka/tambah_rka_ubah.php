<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head> 
<style>
.loader {
  border: 10px solid #f3f3f3;
  border-radius: 50%;
  border-top: 10px solid #3498db;
  width: 20px;
  height: 20px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 1s linear infinite;
}
   
/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css" /> 
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script> 
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
      
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/shortcut.js"></script>
        <link href="<?php echo base_url(); ?>assets/js/select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>     

    <script type="text/javascript">
   
    var nl       = 0;
    var tnl      = 0;
    var idx      = 0;
    var tidx     = 0;
    var oldRek   = 0;
    var rek      = 0;
    var detIndex = 0;
    var kdrek    = '';
    var id       = 0;
    var status   = '0';
    var zfrek    = '';
    var zkdrek   = '';
    var status_apbd = '';
    var total_kas   = 0;
    
    shortcut.add("ctrl+m", function() {
        detsimpan();
    });
    
    $(document).ready(function() {
            $('#satuan1').select2({
                placeholder: 'Pilih Satuan 1'
            });
            $('#satuan2').select2({
                placeholder: 'Pilih Satuan 2'
            });
            $('#satuan4').select2({
                placeholder: 'Pilih Satuan 4'
            });
            $('#satuan3').select2({
                placeholder: 'Pilih Satuan 3'
            });
            $('#satuan1_edit').select2({
                placeholder: 'Pilih Satuan 1'
            });
            $('#satuan2_edit').select2({
                placeholder: 'Pilih Satuan 2'
            });
            $('#satuan4_edit').select2({
                placeholder: 'Pilih Satuan 4'
            });
            $('#satuan3_edit').select2({
                placeholder: 'Pilih Satuan 3'
            });
            $('#pesen').hide();
            $('#proses').hide();
            $('#sdana1').combogrid();
            $('#standart').combogrid();
            $('#standart_edit').combogrid();
            $('#kode_header').combogrid();
            $('#kode_edit').combogrid();
            $('#sdana2').combogrid();
            $('#sdana3').combogrid();
            $('#sdana4').combogrid();
            $('#kdgiat').combogrid(); 
            $('#kdrek5').combogrid();
            $('#sbiaya').combogrid();
            $('#kdlokasi').combogrid();
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 450,
                width: 970,
                modal: true,
                autoOpen:false                
            });
            $("#loading").hide();
            $( "#dialog-keluaran" ).dialog({
                height: 500,
                width: 500,
                modal: true,
                autoOpen:false                
            });            
            $("#dialog-modal-edit" ).dialog({
                height: 500,
                width: 1000,
                modal: true,
                autoOpen:false                
            });
           $("#panduan" ).dialog({
                height: 300,
                width: 1000,
                modal: true,
                autoOpen:false                
            });            
            get_nilai_kua();
            
            alert("PERHATIAN!!! Diharapkan SKPD yang akan mengurangi angka di rincian rekening untuk tidak menghapus rekening dan rinciannya jika ada nilai di penyempurnaan/pergeseran sebelumnya. Terima Kasih");
        });    
    
    $(document).ready(function(){
        $('#skpd').hide();
        $('#giat').hide();
    });
    

    $(function(){
             
           $('#dg').edatagrid({
                url           : '',
                 idField      : 'id',
                 toolbar      : "#toolbar",              
                 rownumbers   : "true", 
                 fitColumns   : "true",
                 singleSelect : "true",
                onSelect:function(rowIndex,rowData){                            
                    },
                columns:[[
                    {field:'id',
                     title:'id',
                     width:10,
                     hidden:true
                    },
                    {field:'kd_rek5',
                     title:'Rekening',
                     width:12,
                     align:'left'   
                    },
                    {field:'nm_rek5',
                     title:'Nama Rekening',
                     width:78
                    },
                    {field:'nilai',
                     title:'Nilai Murni',
                     width:30,
                     align:'right'
                     },
                    {field:'nilai_sempurna',
                     title:'Nilai Geser',
                     width:30,
                     align:'right'
                     },
                    {field:'nilai_ubah',
                     title:'Nilai Perubahan',
                     width:30,
                     align:'right'
                     },
                     {field:'rinci',
                      title:'Detail',
                      width:10,
                      align:'center', 
                      formatter:function(value,rec){
                            rek         = rec.kd_rek5
                            return ' <p  style="cursor: pointer"  onclick="javascript:kosongsumber();section('+rec.kd_rek5+');">Rincian</p>';
                        }
                    }
                ]]
            });
             
            
            
            function ambil_sumberdana(){


            var selectRow = null;
            artChanged = false;  
            
            var skpd= $('#sskpd').combogrid('getValue');   
            $("#sdana1").combogrid({
                panelWidth:400,
                idField   :'kd_sdana',
                textField :'kd_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/anggaran_murni/ambil_sdana',
                queryParams: ({kdskpd:skpd}),
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:300}
                ]],
                onSelect :function(rowIndex,rowData){
                    selectRow = rowData.kd_sdana;  
                    artChanged = true;
                    $("#saldosumber").attr("value",number_format(rowData.nilai,2,'.',','));

                    
                },
                onChange: function(rowIndex,rowData){
                      artChanged = true;     
                      selectRow = rowData.kd_sdana;                                      
                },
                onLoadSuccess : function (data) {  
                    var t = $(this).combogrid('getValue');
                    if (artChanged) {  
                    if (selectRow == null || t != selectRow) { 
                        $(this).combogrid('setValue', '');
                        
                    } 
                    }   
                    
                    artChanged = false;  
                    selectRow = null; 
                
                },
                onHidePanel: function () {  
                    var t = $(this).combogrid('getValue');  
                    if (artChanged) {  
                    if (selectRow == null || t != selectRow) {
                        $(this).combogrid('setValue', '');  
                        
                    } 
                    }  
                    artChanged = false;  
                    selectRow = null;  
                }                                  
            });

            }
            
            

            function ambil_sumberdana2(){
            var skpd= $('#sskpd').combogrid('getValue');   
            var selectRow = null;
            artChanged = false;    
            $("#sdana2").combogrid({
               panelWidth:400,
                idField   :'kd_sdana',
                textField :'kd_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/anggaran_murni/ambil_sdana',
                queryParams: ({kdskpd:skpd}),
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:300}
                ]],
                onSelect :function(rowIndex,rowData){
                    selectRow = rowData.kd_sdana;   
                    artChanged = true;
                    $("#saldosumber2").attr("value",number_format(rowData.nilai,2,'.',','));

                },
                onChange: function(rowIndex,rowData){
                      artChanged = true;   
                      selectRow = rowData.kd_sdana;                                       
 
                },
                onLoadSuccess : function (data) {  
                    var t = $(this).combogrid('getValue');
                    if (artChanged) {  
                    if (selectRow == null || t != selectRow) { 
                        $(this).combogrid('setValue', '');
                    } 
                    }  
                    
                    artChanged = false;  
                    selectRow = null;  
                },
                onHidePanel: function () {  
                   var t = $(this).combogrid('getValue');  
                    if (artChanged) {  
                    if (selectRow == null || t != selectRow) {
                        $(this).combogrid('setValue', '');  
                    } 
                    }  
                    artChanged = false;  
                    selectRow = null;  
                }             
            });
            }
             
            
            function ambil_sumberdana3(){
                var skpd= $('#sskpd').combogrid('getValue');   
                var selectRow = null;
                artChanged = false; 
            $("#sdana3").combogrid({
                panelWidth:400,
                idField   :'kd_sdana',
                textField :'kd_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/anggaran_murni/ambil_sdana',
                queryParams: ({kdskpd:skpd}),
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:300}
                ]],
                onSelect :function(rowIndex,rowData){
                    selectRow = rowData.kd_sdana;   
                    artChanged = true;
                    $("#saldosumber3").attr("value",number_format(rowData.nilai,2,'.',','));
                },
                onChange: function(rowIndex,rowData){
                      artChanged = true;   
                      selectRow = rowData.kd_sdana;                                       
 
                },
                onLoadSuccess : function (data) {  
                    var t = $(this).combogrid('getValue');
                    if (artChanged) {  
                    if (selectRow == null || t != selectRow) { 
                        $(this).combogrid('setValue', '');
                    } 
                    }  
                    
                    artChanged = false;  
                    selectRow = null;  
                },
                onHidePanel: function () {  
                   var t = $(this).combogrid('getValue');  
                    if (artChanged) {  
                    if (selectRow == null || t != selectRow) {
                        $(this).combogrid('setValue', '');  
                    } 
                    }  
                    artChanged = false;  
                    selectRow = null;  
                }             
            });
            }
            
            
            
            function ambil_sumberdana4(){
            var skpd= $('#sskpd').combogrid('getValue');   
            var selectRow = null;
            artChanged = false; 
            $("#sdana4").combogrid({
              panelWidth:400,
                idField   :'kd_sdana',
                textField :'kd_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/anggaran_murni/ambil_sdana',
                queryParams: ({kdskpd:skpd}),
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:300}
                ]],
                onSelect :function(rowIndex,rowData){
                    selectRow = rowData.kd_sdana;   
                    artChanged = true;
                    $("#saldosumber4").attr("value",number_format(rowData.nilai,2,'.',','));
                },
                onChange: function(rowIndex,rowData){
                      artChanged = true;   
                      selectRow = rowData.kd_sdana;                                       
 
                },
                onLoadSuccess : function (data) {  
                    var t = $(this).combogrid('getValue');
                    if (artChanged) {  
                    if (selectRow == null || t != selectRow) { 
                        $(this).combogrid('setValue', '');
                    } 
                    }  
                    
                    artChanged = false;  
                    selectRow = null;  
                },
                onHidePanel: function () {  
                   var t = $(this).combogrid('getValue');  
                    if (artChanged) {  
                    if (selectRow == null || t != selectRow) {
                        $(this).combogrid('setValue', '');  
                    } 
                    }  
                    artChanged = false;  
                    selectRow = null;  
                }             
            });
            }

         
            
             $(function(){
            $('#sskpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/anggaran_murni/skpduser',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:150},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                skpd = rowData.kd_skpd;
                $("#nm_skpd").attr("value",rowData.nm_skpd);
               ambil_sumberdana();
               ambil_sumberdana2();
               ambil_sumberdana3();
               ambil_sumberdana4();
               get_realisasi();
                get_skpd(skpd,rowData.nm_skpd,rowData.statu)
                $("#kdgiat").combogrid("clear");
                $("#nmgiat").attr("value",'');
                $("#kdrek5").combogrid("clear");
                //validate_combo();
            }
            });
            });
            
        });
             
        
    function get_skpd(kd_s='',nm_s='',st_s='') {

            $("#sskpd").attr("value",kd_s);
            $("#nmskpd").attr("value",nm_s.toUpperCase());
            $("#skpd").attr("value",kd_s);
            
            kdskpd = kd_s;
            
            validate_giat();
            get_nilai_kua();
            //tombol(sta);
            validate_rekening();
            $("#kdrek5").combogrid("disable");
            cek_status(kdskpd);
    }                                     


      function cek_status(kdskpd){
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/anggaran_murni/config_skpd2',
                type: "POST",
                dataType:"json",
                data      : ({kdskpd:kdskpd}),                         
                success:function(data){
                                        sta    = data.status_ubah;
                                        kunci    = data.kunci_ubah;
                                        tombol(sta,kunci);
                                      }                                     
            });        
       }

      function kunci_inputan(){
        var kdskpd = $('#sskpd').combogrid('getValue');
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/anggaran_murni/config_skpd2',
                type: "POST",
                dataType:"json",
                data      : ({kdskpd:kdskpd}),                         
                success:function(data){
                                        sta    = data.status_ubah;
                                        kunci    = data.kunci_ubah;
                    if(kunci=='1'){
                        tombol(5,1);
                        alert("INPUTAN TELAH DIKUNCI"); 
                        return exit();                       
                    } 
                }                                     
            });                
       }                                      
                                      
        
        function get_nilai_kua()
        {
            $kdskpd = document.getElementById('sskpd').value;
            var jenis = document.getElementById('jnskegi').value;
            kdskpd = $kdskpd;
            
            $.ajax({
                
                url:'<?php echo base_url(); ?>index.php/anggaran_murni/load_nilai_kua_rancang/'+kdskpd,
                
                type: "POST",
                dataType:"json",                         
                success:function(data){
                      $.each(data, function(i,n){
                        $("#nilai_kua").attr("Value",n['nilai']); 
                        $("#nilai_kua_ang").attr("Value",n['kua_terpakai']);

                        var n_kua  = n['nilai'] ;                
                        var n_kua_terpakai = n['kua_terpakai'];
                        var n_sisa_kua = angka(n_kua) - angka(n_kua_terpakai);
                        $("#sisa_nilai_kua").attr("Value",number_format(n_sisa_kua,2,'.',','));
                        $("#sisa_kua2").attr("Value",number_format(n_sisa_kua,2,'.',','));
                         
                    });
                }
            });
        }
        
        function get_nilai_kua_rek(){
        var kua_rek = document.getElementById('nilairek').value;
        var jenis = document.getElementById('jnskegi').value;
            $.ajax({
                   
                url:'<?php echo base_url(); ?>index.php/anggaran_murni/load_nilai_kua_rancang/'+kdskpd,
                type: "POST",
                dataType:"json",                         
                success:function(data){
                      $.each(data, function(i,n){
                        $("#nilai_kua").attr("Value",n['nilai']); /*n['nilai'] nilai kua*/           
                        $("#nilai_kua_ang").attr("Value",n['kua_terpakai']);  /*n['kua_terpakai'] nilai rka murni*/
                        var n_kua  = n['nilai'] ;
                        var n_kua_terpakai = n['kua_terpakai'];
                        var n_sisa_kua = angka(n_kua) - angka(n_kua_terpakai) ;
                        var n_sisa_kua_rek = n_sisa_kua + angka(kua_rek) ;
                        $("#nilai_kua_rek").attr("Value",kua_rek);


                        if(jenis=='5'){
                            $("#sisa_kua2").attr("Value",number_format(n_sisa_kua,2,'.',','));
                            $("#total_sisa_kua_rek").attr("Value",number_format(n_sisa_kua_rek,2,'.',','));
                            $("#nilai_kua_rek").attr("Value",kua_rek);
                        }else{
                            $("#sisa_kua2").attr("Value",number_format(0.00,2,'.',','));
                            $("#total_sisa_kua_rek").attr("Value",number_format(0.00,2,'.',','));
                            $("#nilai_kua_rek").attr("Value","0.00");
                        }
                    });
                }
            });
        }

        function get_nilai_kua2(nrek1)
        {
            var nrek1 = angka(nrek1);
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/anggaran_murni/load_nilai_kua_rancang/'+kdskpd,
                type: "POST",
                dataType:"json",                         
                success:function(data){
                      $.each(data, function(i,n){
                        
                        $("#nilai_kua").attr("Value",n['nilai']);
                        $("#nilai_kua_ang").attr("Value",n['kua_terpakai']);                    
                        var n_kua  = n['nilai'] ;
                        var n_kua_terpakai = n['kua_terpakai'];
                        var n_kua_terpakai = angka(n_kua_terpakai) - nrek1; 
                        var n_sisa_kua = angka(n_kua) - n_kua_terpakai ;
                        $("#sisa_nilai_kua").attr("Value",number_format(n_sisa_kua,2,'.',','));
                        $("#sisa_kua2").attr("Value",number_format(n_sisa_kua,2,'.',','));
                    });
                }
            });
        }
        
        
        function cek_transaksi(){
            var xxskpd = $('#sskpd').combogrid('getValue');
            var xxgiat   = $("#kdgiat").combogrid("getValue");
            var reke   = $("#kdrek5").combogrid("getValue");
            total_kas = 0;
            $("#cek_kas").attr("value",0);
            
            $.ajax({
               
               url       : '<?php echo base_url(); ?>/index.php/anggaran_murni/cek_transaksi',
               type      : 'POST',
               dataType  : 'json',
               data      : ({skpd:xxskpd,kegiatan:xxgiat,rek6:reke}),
               success   : function(data) {
                        var isi=data;
                        $("#cek_kas").attr("value",isi);
               }
            });
        }
        
        
        
        function validate_giat(){
            $(function(){
            $('#kdgiat').combogrid({  
            panelWidth : 1000,  
            idField    : 'kd_kegiatan',  
            textField  : 'kd_kegiatan',  
            mode       : 'remote',
            url        : '<?php echo base_url(); ?>index.php/anggaran_murni/pgiat_rancang/'+kdskpd,  
            columns    : [[  
                {field:'kd_kegiatan',title:'Kode Sub Kegiatan',width:110},  
                {field:'nm_kegiatan',title:'Nama Kegiatan',width:600},
                {field:'nm_subskpd',title:'Unit',width:250},   
            ]],
            onSelect:function(rowIndex,rowData){
                kegiatan = rowData.kd_kegiatan;
                $("#nmgiat").attr("value",rowData.nm_kegiatan.toUpperCase());
                $("#giats").attr("value",rowData.kd_kegiatan);
                $("#jnskegi").attr("value",rowData.jns_kegiatan);
                validate_combo();
                $("#kdrek5").combogrid("disable");
                $("#kdrek5").combogrid("setValue",'');
                $("#sdana1").combogrid("setValue",'');
                $("#sdana2").combogrid("setValue",'');
                $("#sdana3").combogrid("setValue",'');
                $("#sdana4").combogrid("setValue",'');
                $("#nilaisumber").attr("value",'0.00');
                $("#nilaisumber2").attr("value",'0.00');
                $("#nilaisumber3").attr("value",'0.00');
                $("#nilaisumber4").attr("value",'0.00');
                
                document.getElementById('nilairek').value   = 0;
                document.getElementById('nmrek5').value     = '';
                $('#section1').click();
            },
            }); 
            });
        }
 
            
        
        function validate_rekening(){
            
            $("#dg").datagrid("unselectAll");
            $("#dg").datagrid("selectAll");
            var rows   = $("#dg").datagrid("getSelections");
            var jrows  = rows.length ;

            zfrek  = '';
            zkdrek = '';
            
            for (z=0;z<jrows;z++){
               zkdrek=rows[z].kd_rek5;                 
               if ( z == 0 ){
                   zfrek  = zkdrek ;
               } else {
                   zfrek  = zfrek+','+zkdrek ;
               }
            }          
            
            var jkegi = document.getElementById('jnskegi').value ;


            
            $('#kdrek5').combogrid({  
               panelWidth : 700,  
               idField    : 'kd_rek6',  
               textField  : 'kd_rek6',  
               mode       : 'remote',
               url        : '<?php echo base_url(); ?>index.php/anggaran_murni/ambil_rekening5_all_ar',  
               queryParams: ({reknotin:zfrek,jns_kegi:jkegi}),
               columns    : [[  
                   {field:'kd_rek6',title:'Kode Rekening',width:100},  
                   {field:'nm_rek6',title:'Nama Rekening',width:400}   
               ]],  
               onSelect:function(rowIndex,rowData){
                    kd_rek5 = rowData.kd_rek6;
                    $("#nmrek5").attr("value",rowData.nm_rek6.toUpperCase());
               },
               onLoadSuccess:function(data){
                    $("#nilairek").attr("value",0);
                    $("#nilaisumber").attr("value",'0.00');
                    $("#nilaisumber2").attr("value",'0.00');
                    $("#nilaisumber3").attr("value",'0.00');
                    $("#nilaisumber4").attr("value",'0.00');

                }  
             });     
        }
        

        
        function getSelections(idx){
            var ids = [];
            var rows = $('#dg').edatagrid('getSelections');
            for(var i=0;i<rows.length;i++){
                ids.push(rows[i].kd_rek5);
            }
            return ids.join(':');
        }

        
        function getSelections2(idx){
            var ids = [];
            var rows = $('#dg1').edatagrid('getSelections');
            for(var i=0;i<rows.length;i++){
                ids.push(rows[i].no_po);
            }
            return ids.join(':');
        }


        function getRowIndex(target){  
            var tr = $(target).closest('tr.datagrid-row');  
            return parseInt(tr.attr('datagrid-row-index'));  
        }  


        function refresh(){  
            $('#dg').datagrid('reload');
        } 
        
        function get_realisasi(){
            kdskpd = $('#sskpd').combogrid('getValue'); 
            
            $.ajax({
                
                url:'<?php echo base_url(); ?>index.php/anggaran_murni/get_realisasi/'+kdskpd,
                
            type: "POST",
                dataType:"json",                         
                success:function(data){
                      $.each(data, function(i,n){
                            $("#total_realisasi").attr("Value",n['nrealisasi']);
                    });
                }
            });
        }

        function validate_combo(){
            btl();
            var cskpd = $('#sskpd').combogrid('getValue'); 
            var cgiat = $('#kdgiat').combogrid('getValue');             
            $(function(){
            $('#dg').edatagrid({
                 url: '<?php echo base_url(); ?>/index.php/anggaran_murni/select_rka_rancang/'+cgiat+'/'+cskpd,
                 idField     : 'id',
                 toolbar     : "#toolbar",              
                 rownumbers  : "true", 
                 fitColumns  : "true",
                 singleSelect: "true",
                 showFooter  : true,
                 nowrap      : false,
                 onSelect:function(rowIndex,rowData){                           
                              
                              oldRek   = getSelections(getRowIndex(this));
                              vvkdrek  = rowData.kd_rek5;
                              vvnmrek  = rowData.nm_rek5;
                              vvnilai  = rowData.nilai_ubah;
                              vvsdana1 = rowData.sumber1_ubah;
                              vvsdana2 = rowData.sumber2_ubah;
                              vvsdana3 = rowData.sumber3_ubah;
                              vvsdana4 = rowData.sumber4_ubah;
                              vvnsdana1 = rowData.nsumber1_ubah;
                              vvnsdana2 = rowData.nsumber2_ubah;
                              vvnsdana3 = rowData.nsumber3_ubah;
                              vvnsdana4 = rowData.nsumber4_ubah;
                                
                              $("#nilairek").attr("value",vvnilai);
                              $("#nilai_rekening_murni").attr("value",rowData.nilai);
                              $("#nilai_rekening_geser").attr("value",rowData.nilai_sempurna);
                              $("#jnskegi").attr("value",rowData.jenis_kegiatan);
                              $("#nmrek5").attr("value","");
                              $("#nmrek5").attr("value",vvnmrek);
                              $("#kdrek5").combogrid("setValue",vvkdrek);
                              $("#sdana1").combogrid("setValue",vvsdana1);
                              $("#sdana2").combogrid("setValue",vvsdana2);
                              $("#sdana3").combogrid("setValue",vvsdana3);
                              $("#sdana4").combogrid("setValue",vvsdana4);
                              $("#nilaisumber").attr("value",vvnsdana1);
                              $("#nilaisumber2").attr("value",vvnsdana2);
                              $("#nilaisumber3").attr("value",vvnsdana3);
                              $("#nilaisumber4").attr("value",vvnsdana4);
                              document.getElementById('nilaisumber').disabled=false;
                              document.getElementById('nilaisumber2').disabled=false;
                              document.getElementById('nilaisumber3').disabled=false;
                              document.getElementById('nilaisumber4').disabled=false;
                              get_nilai_real_rek();
                              cek_transaksi();                              
                          },
                onLoadSuccess:function(data){
                            load_sum_rek(); 
                            load_sum_real();     
                          },
                columns:[[
                    {field:'id',
                     title:'id',
                     width:10,
                     hidden:true
                    },
                    {field:'kd_rek5',
                     title:'Rekening',
                     width:20,
                     align:'left'   
                    },
                    {field:'nm_rek5',
                     title:'Nama Rekening',
                     width:75
                    },
                    {field:'nilai',
                     title:'Nilai Murni',
                     width:30,
                     align:'right'
                     },
                    {field:'nilai_sempurna',
                     title:'Nilai Sempurna',
                     width:30,
                     align:'right'
                     },
                    {field:'nilai_ubah',
                     title:'Nilai Perubahan',
                     width:30,
                     align:'right'
                     },
                     {field:'rinci',
                      title:'Detail',
                      width:10,
                      align:'center', 
                      formatter:function(value,rec){
                            rek         = rec.kd_rek5;
                            $("#nmrek5").attr("value","");
                            $("#nmrek5").attr("value",rec.nm_rek5);
                            return ' <p class="button button-abu" style="cursor: pointer" onclick="javascript:kosongsumber();section('+rec.kd_rek5+');"> <i class="fa fa-edit"></i> </p>';
                        }
                    }
                ]]

            });
        });
        }

    function load_sum_real(){                
                var b = $('#kdgiat').combogrid('getValue'); 
                var a = $('#sskpd').combogrid('getValue'); 
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b}),
            url:"<?php echo base_url(); ?>index.php/anggaran_murni/get_realisasi_keg",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#n_realkeg").attr("value",n['nrealisasi']);
                });
            }
         });
        });
    } 

        function get_nilai_real_rek(){
                var b = $('#kdgiat').combogrid('getValue'); 
                var a = $('#sskpd').combogrid('getValue'); 
            var reke   = $("#kdrek5").combogrid("getValue");
            
            $.ajax({           
                url:'<?php echo base_url(); ?>index.php/anggaran_murni/get_realisasi_rek/'+kdskpd,
                type: "POST",
                dataType:"json",  
                data      : ({skpd:a,keg:b,rek5:reke}),                        
                success:function(data){
                      $.each(data, function(i,n){
                           $("#nreal_rek").attr("Value",n['nrealisasi']);
                           $("#n_realrek").attr("Value",n['nrealisasi']);
                    });
                }
            });
        } 

        function hapus(){
          
                var nil_cek_kas = document.getElementById('cek_kas').value ;
                var nilai_rekening_murni = angka(document.getElementById('nilai_rekening_murni').value);
                var nilai_rekening_geser = angka(document.getElementById('nilai_rekening_geser').value) ;
                
                if ( nil_cek_kas > 0 ) {
                    alert('SUDAH DITRANSAKSIKAN. TIDAK DAPAT DIHAPUS!');
                    exit();
                }
                
                
                if ( status_apbd=='1' ){
                    alert("APBD TELAH DI SAHKAN...!!!  DATA TIDAK DAPAT DI HAPUS...!!!");
                    exit();
                }

                if ( nilai_rekening_murni > 0 ||  nilai_rekening_geser > 0){
                    alert("DATA APBD YANG SUDAH TERINPUT DI PENYUSUNAN DAN PERGESERAN TIDAK DAPAT DI HAPUS...!!!");
                    exit();
                }

                var cgiat = $('#kdgiat').combogrid('getValue'); 
                var cskpd = $('#sskpd').combogrid('getValue'); 
                var rek   = getSelections();
                if (rek !=''){
                var del=confirm('Anda yakin akan menghapus rekening '+rek+' ?');
                if  (del==true){
                    $("#loading").show();
                    $(function(){
                        $('#dg').edatagrid({
                             url: '<?php echo base_url(); ?>/index.php/anggaran_murni/thapus_rancang/'+cskpd+'/'+cgiat+'/'+rek,
                             idField:'id',
                             toolbar:"#toolbar",              
                             rownumbers:"true", 
                             fitColumns:"true",
                             singleSelect:"true"
                        });
                    });
                    get_nilai_kua();
                    $("#loading").hide();
                    $("#kdrek5").combogrid("disable");
                    $("#kdrek5").combogrid("setValue",'');
                    $("#nilairek").attr("value",0);
                    document.getElementById('nmrek5').value = '';
                    $("#sdana1").combogrid("setValue",'');
                    $("#sdana2").combogrid("setValue",'');
                    $("#sdana3").combogrid("setValue",'');
                    $("#sdana4").combogrid("setValue",'');
                    $("#nilai_rekening_murni").attr("value",0);
                    $("#nilai_rekening_geser").attr("value",0);
                    $("#nilaisumber").attr("value",0);
                    $("#nilaisumber2").attr("value",0);
                    $("#nilaisumber3").attr("value",0);
                    $("#nilaisumber4").attr("value",0);
                      
                    $("#dg").datagrid("reload");
                    zfrek  = '';
                    zkdrek = '';
                
                }
                }
        }


        function hapus_rinci(){
          
                var cgiat = $('#kdgiat').combogrid('getValue'); 
                var cskpd = $('#sskpd').combogrid('getValue'); 
                var crek  = document.getElementById('reke').value;
                var nilai_rekening_rincian_murni  = angka(document.getElementById('nilai_rekening_rincian_murni').value);
                var nilai_rekening_rincian_geser  = angka(document.getElementById('nilai_rekening_rincian_geser').value);
                if(nilai_rekening_rincian_murni > 0 || nilai_rekening_rincian_geser > 0){
                    alert("DATA APBD YANG SUDAH TERINPUT DI PENYUSUNAN DAN PERGESERAN TIDAK DAPAT DI HAPUS...!!!");
                    exit();
                }

                var norka = cskpd+'.'+cgiat+'.'+crek;
                var nopo  = document.getElementById('nopo').value;
                var urai  = document.getElementById('uraian').value;
                var kode_unik  = document.getElementById('kode_unik').value;          
                var total_awal = angka(document.getElementById('rektotal_rinci').value) ;
                 
                var rows        = $('#dg1').edatagrid('getSelected');
                    urai        = rows.uraian ;
                var total_rinci = angka(rows.total) ;
                var cfm   = confirm("Hapus Uraian "+urai+" ?") ;
                
                if ( cfm == true ){

                    var idx   = $('#dg1').edatagrid('getRowIndex',rows);
                    $('#dg1').datagrid('deleteRow',idx);     
                    $('#dg1').datagrid('unselectAll');
                    
                    var total_rincian = total_awal - total_rinci ;
                    var data = $('#dg1').datagrid('getData');
                    var rows = data.rows;
                    var total_rincian = 0;
                    for (i=0; i < rows.length; i++) {
                        total_rincian+=angka(rows[i].total);
                    }
                    $("#loading").show();
                    $(document).ready(function(){
                        $.ajax({
                               type     : 'POST',
                               dataType : 'json',
                               data     : ({norka:norka,kode_unik:kode_unik,skpd:cskpd,giat:cgiat}),
                               url      : '<?php echo base_url(); ?>index.php/anggaran_murni/hapus_rincian_dpo',
                               success  : function(data){
                                    if(data=="7"){
                                        alert("DATA PENYUSUNAN TIDAK BISA DIHAPUS!");
                                            $("#loading").hide();
                                            $('#dg1').edatagrid('reload');  
                                        exit();
                                    }
                                            kosong();
                                            get_nilai_kua();
                                            get_nilai_kua_rek(); 
                                            $("#loading").hide();
                                            $('#dg1').edatagrid('reload');  
                                            alert("Data Telah Terhapus...!!!");
                                        
                               }    
                        });
                    });

                    $("#rektotal_rinci").attr("value",number_format(total_rincian,"2",'.',','));
                    $("#nilaisumber").attr("value",number_format(total_rincian,"2",'.',','));
                    $('#dg1').datagrid('unselectAll');
                   
                }
        }


        function cek_jns_kegiatan(){
            var jkegi = document.getElementById('jnskegi').value ;

            if (jkegi=='4' || jkegi=='61'){
                detsimpan();
            }else{
                /*cek_sdana();*/ detsimpan();
            }
        }

        function cek_sdana(){
        var cgiat = $('#kdgiat').combogrid('getValue'); 
        var cskpd = $('#sskpd').combogrid('getValue'); 
        var crek =  document.getElementById('reke').value;

        var ndana1 =  angka(document.getElementById('nilaisumber').value);
        var sdana1 = $("#sdana1").combogrid("getValue");

        var sdana2 = $("#sdana2").combogrid("getValue");


            $.ajax({
               url       : '<?php echo base_url(); ?>/index.php/rka_rancang/sumber',
               type      : 'POST',
               dataType  : 'json',
               data      : ({tabel:'sisa_sumber_dana',field:'nm_sumberdana',ndana:ndana1,sdana:sdana1,cgiat:cgiat,cskpd:cskpd,crek:crek}),
                success  : function(data){                        
                        status = data.pesan; 
                         if (status=='1'){               
                            if (sdana2!=''){
                                cek_sdana2();
                            }else{
                                detsimpan();
                            }
                        } else{ 
                            alert('Nilai Melebihi Batas Anggaran Sumber Dana: '+sdana1+' ...!!!');
                            exit();
                        }                                              
                    }
            });
        }
        
        function cek_sdana2(){
        var cgiat = $('#kdgiat').combogrid('getValue'); 
        var cskpd = $('#sskpd').combogrid('getValue'); 
        var crek =  document.getElementById('reke').value;

        var ndana2 =  angka(document.getElementById('nilaisumber2').value);
        var sdana2 = $("#sdana2").combogrid("getValue");

        var sdana3 = $("#sdana3").combogrid("getValue");


            $.ajax({
               
               url       : '<?php echo base_url(); ?>/index.php/rka_rancang/sumber2',
               type      : 'POST',
               dataType  : 'json',
               data      : ({tabel:'sisa_sumber_dana',field:'nm_sumberdana',ndana:ndana2,sdana:sdana2,cgiat:cgiat,cskpd:cskpd,crek:crek}),
                success  : function(data){                        
                        status = data.pesan; 
                         if (status=='1'){               
                            if (sdana3!=''){
                                // alert('cek sumber 3');
                                cek_sdana3();
                            }else{
                                // alert('Simpan di 2');
                                detsimpan();
                            }
                        } else{ 
                            alert('Nilai Melebihi Batas Anggaran Sumber Dana '+sdana2+' ...!!!');
                            exit();
                        }                                             
                    }
            });
        }
        function cek_sdana3(){

        var cgiat = $('#kdgiat').combogrid('getValue'); 
        var cskpd = $('#sskpd').combogrid('getValue'); 
        var crek =  document.getElementById('reke').value;

        var ndana3 =  angka(document.getElementById('nilaisumber3').value);
        var sdana3 = $("#sdana3").combogrid("getValue");
        
        var sdana4 = $("#sdana4").combogrid("getValue");

            $.ajax({
               
               url       : '<?php echo base_url(); ?>/index.php/rka_rancang/sumber3',
               type      : 'POST',
               dataType  : 'json',
               data      : ({tabel:'sisa_sumber_dana',field:'nm_sumberdana',ndana:ndana3,sdana:sdana3,cgiat:cgiat,cskpd:cskpd,crek:crek}),
                success  : function(data){                        
                        status = data.pesan; 
                         if (status=='1'){               
                            if (sdana4!=''){
                                // alert('cek sumber 4');
                                cek_sdana4();
                            }else{
                                // alert('simpan di 3');
                                detsimpan();
                            }
                        } else{ 
                            alert('Nilai Melebihi Batas Anggaran Sumber Dana '+sdana3+' ...!!!');
                            exit();
                        }                                             
                    }
            });
        }
        function cek_sdana4(){

        var cgiat = $('#kdgiat').combogrid('getValue'); 
        var cskpd = $('#sskpd').combogrid('getValue'); 
        var crek =  document.getElementById('reke').value;

        var ndana4 =  angka(document.getElementById('nilaisumber4').value);
        var sdana4 = $("#sdana4").combogrid("getValue");

            $.ajax({
               
               url       : '<?php echo base_url(); ?>/index.php/rka_rancang/sumber4',
               type      : 'POST',
               dataType  : 'json',
               data      : ({tabel:'sisa_sumber_dana',field:'nm_sumberdana',ndana:ndana4,sdana:sdana4,cgiat:cgiat,cskpd:cskpd,crek:crek}),
                success  : function(data){                        
                        status = data.pesan; 
                         if (status=='0'){               
                            alert('Nilai Melebihi Batas Anggaran Sumber Dana '+sdana4+' ...!!!');
                            exit();
                        }else{
                            // alert('simpan di 4');
                            detsimpan();
                        }                                             
                    }
            });
        }


        
        function detsimpan(){
            
        $('#dg1').datagrid('unselectAll');    
        //var rektotal_rka  = angka(document.getElementById('rektotal_rka').value) ;
        var total_rinci  = angka(document.getElementById('rektotal_rinci').value) ;
        //alert(total_rinci)
        /*if (total_rinci!==0 && total_rinci!== rektotal_rka ){
            alert("Total Rincian Rekening tidak sama dengan Anggaran");
            exit();
            kosong();
            }
        */
        kunci_inputan();
        var cgiat = $('#kdgiat').combogrid('getValue'); 
        var cskpd = $('#sskpd').combogrid('getValue'); 
        var crek =  document.getElementById('reke').value;
        var norka  = cskpd+'.'+cgiat+'.'+crek;
        var ndana1 =  angka(document.getElementById('nilaisumber').value);
        var ndana2 =  angka(document.getElementById('nilaisumber2').value);
        var ndana3 =  angka(document.getElementById('nilaisumber3').value);
        var ndana4 =  angka(document.getElementById('nilaisumber4').value);
        var totaldana = ndana1+ndana2+ndana3+ndana4;
        var sdana1 = $("#sdana1").combogrid("getValue");
        var sdana2 = $("#sdana2").combogrid("getValue");
        var sdana3 = $("#sdana3").combogrid("getValue");
        var sdana4 = $("#sdana4").combogrid("getValue");
        
        if(sdana1==''){
            alert('Harap Lengkapi Sumber Dana!');
            exit();
        }
        if(sdana1!=''){        
            switch (sdana1) {
                case sdana2:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana3:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana4:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            } 

        }

        if(sdana2!=''){                 
            switch (sdana2) {
                case sdana1:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana3:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana4:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            } 
        }
        
        if(sdana3!=''){
            switch (sdana3) {
                case sdana2:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana1:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana4:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            } 
        }
        
        if(sdana4!=''){
            switch (sdana4) {
                case sdana2:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana3:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana1:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            }       
        }      
        if((sdana1.replace(/\s/g,"") =="") || ndana1 == undefined) {
            ndana1 = 0;
        }

        if((sdana2.replace(/\s/g,"") =="") || ndana2 == undefined){
            ndana2 = 0;
            
        }
        
        
        if(sdana3.replace(/\s/g,"") =="" || ndana3 == undefined){
            ndana3 = 0;
        }        
        
        if(sdana4.replace(/\s/g,"") =="" || ndana4 == undefined){
            ndana4 = 0;
        }
           
  
        if ( cgiat=='' ){
            alert("Pilih Kegiatan Terlebih Dahulu...!!!");
            exit();
        }
        
        if ( crek=='' ){
            alert("Pilih Rekening Terlebih Dahulu...!!!");
            exit();
        }
        var total_rinci  = angka(document.getElementById('rektotal_rinci').value) ;

        var n_sisakua   = angka(document.getElementById('total_sisa_kua_rek').value) ;        
        var lcrek=crek.substr(0,1);           
        if(totaldana != total_rinci){
            alert('Pembagian Nilai Sumber Dana Masih Selisih');
            return;
        }
        
        cekgj = cgiat.substr(10,15);
        if((cekgj!='00.61') & (cekgj!='00.04')){
            if (( n_sisakua < total_rinci )&&(lcrek='5')){
                alert('Nilai Melebihi nilai KUA');
                exit();
            }
        }
        $("save1").hide();
/*==========untuk update apabila rincian tidak diisi*/
        $('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');
        if ( rows.length == 0 ) { 
            $(document).ready(function(){
            $.ajax({
                    type     : 'POST',
                    dataType : 'json',
                    data     : ({vnorka:norka,skpd:cskpd,giat:cgiat,rek:crek}),
                    url      : '<?php echo base_url(); ?>index.php/anggaran_murni/thapus_rinci_ar_all_rancang',
                    success  : function(data){

                               }    
                        });
                    });
            get_nilai_kua();
            alert("Data Rinci Tersimpan...!!!");
            $("save1").show();
            exit();
        }
/*end ==========untuk update apabila rincian tidak diisi*/       
         // id,header,no_po,kode,kd_barang,uraian,volume1,satuan1,volume2,satuan2,volume3,satuan3,volume,harga1,total
        for(var i=0;i<rows.length;i++){     
            cheader   = rows[i].header;
            ckode     = rows[i].kode;
            curaian   = rows[i].uraian;
            cvolume1  = rows[i].volume1;
            csatuan1  = rows[i].satuan1;
            cvolume2  = 0;
            csatuan2  = '';
            cvolume3  = 0;
            csatuan3  = '';
            if(rows[i].kd_barang==undefined){
                ckode_brg = '';
            }else{
                ckode_brg = rows[i].kd_barang;
            }
            cvolume   = rows[i].volume;
            charga1   = angka(rows[i].harga1);
            ctotal    = angka(rows[i].total);            
            no        = i + 1 ;           
            
            if ( i > 0 ) {
                csql = csql+","+"('"+no+"','"+cheader+"','"+ckode+"','"+ckode_brg+"','"+norka+"','"+curaian+"','"+cvolume1+"','"+csatuan1+"','"+charga1+"','"+ctotal+"','"+cvolume1+"','"+csatuan1+"','"+charga1+"','"+ctotal+"','"+cvolume2+"','"+csatuan2+"','"+cvolume2+"','"+csatuan2+"','"+cvolume3+"','"+csatuan3+"','"+cvolume3+"','"+csatuan3+"','"+cvolume+"','"+cvolume+"','"+cvolume1+"','"+cvolume2+"','"+cvolume3+"','"+cvolume+"','"+csatuan1+"','"+csatuan2+"','"+csatuan3+"','"+charga1+"','"+ctotal+"')";
            } else {
                csql = "values('"+no+"','"+cheader+"','"+ckode+"','"+ckode_brg+"','"+norka+"','"+curaian+"','"+cvolume1+"','"+csatuan1+"','"+charga1+"','"+ctotal+"','"+cvolume1+"','"+csatuan1+"','"+charga1+"','"+ctotal+"','"+cvolume2+"','"+csatuan2+"','"+cvolume2+"','"+csatuan2+"','"+cvolume3+"','"+csatuan3+"','"+cvolume3+"','"+csatuan3+"','"+cvolume+"','"+cvolume+"','"+cvolume1+"','"+cvolume2+"','"+cvolume3+"','"+cvolume+"','"+csatuan1+"','"+csatuan2+"','"+csatuan3+"','"+charga1+"','"+ctotal+"')";                                            
            
            } 

        }

        $(document).ready(function(){
                $.ajax({
                    type     : "POST",   
                    dataType : 'json',                 
                    data     : ({no:norka,sql:csql,skpd:cskpd,giat:cgiat,dana1:sdana1,dana2:sdana2,dana3:sdana3,dana4:sdana4,
                                vdana1:ndana1,vdana2:ndana2,vdana3:ndana3,vdana4:ndana4}),
                    url      : '<?php echo base_url(); ?>/index.php/anggaran_murni/tsimpan_rinci_jk_rancang',
                    success  : function(data) {
                        status = data.pesan;
                        if (status == '1') {
                            $("save1").show();
                            alert('Data Rincian Berhasil Tersimpan!');
                            load_sum_rek();
                            get_nilai_kua();
                            get_nilai_kua_rek();                            
                        } else {
                            $("save1").show();
                            alert('Data Gagal Tersimpan!');
                        }
                    }
                });
        });

        $('#dg1').edatagrid('unselectAll');
        
      
    }
    
        
        
        /*untuk tampilan awal tabel rincian*/           
        $(function(){
            $('#dg1').edatagrid({
                 rowStyler:function(index,row){
                    if (row.header==1){
                       return 'color:red;font-weight:bold;';
                    }
                 },
                 url           : '',
                 idField      : 'id',
                 toolbar      : "#toolbar1",              
                 rownumbers   : "true", 
                 fitColumns   : false,
                 singleSelect : "true",
                 onAfterEdit  : function(rowIndex, rowData, changes){                               
                                },
                 onSelect:function(rowIndex, rowData, changes){                         
                              detIndex=rowIndex;
                              po=rowData.no_po;
                              $("#noid").attr("value",detIndex);
                              $("#nopo").attr("value",po);  
    /*id,header,no_po,kode,kd_barang,uraian,volume1,satuan1,volume2,satuan2,volume3,satuan3,volume,harga1,total */                         
                          },
                columns:[[ 
                    {field:'id',
                     title:'id',
                     width:20,
                     hidden:true
                    },
                    {field:'header',
                     title:'Header',
                     width:20,
                     hidden:true
                    },
                    {field:'no_po',
                     title:'no',
                     width:20,
                     hidden:true
                    },
                    {field:'id_lokasi',
                    hidden:true,
                     title:'ID',
                     width:50,
                     align:'left',
                     styler: function(value,row,index){
                        return 'background-color:#E9967A;';
                     },
                     },
                    {field:'kode',
                     title:'Header',
                     width:200,
                     align:'left'
                     },
                     {field:'kd_barang',
                     title:'Kode Barang',
                     width:20,
                     hidden:true
                    },
                    {field:'uraian',
                     title:'Uraian',
                     width:500,
                    },
                    {field:'spesifikasi_ubah',
                     title:'Spesifikai',
                     width:500,
                    },
                    {field:'koefisien_ubah',
                     title:'Koefisien',
                     width:200,
                     align:'left'
                     },
                    {field:'satuan_ubah1',
                     title:'Satuan',
                     width:100,
                     align:'left'
                     },
                    {field:'pajak_ubah',
                     title:'Pajak (%)',
                     width:50,
                     align:'right'
                     },
                    {field:'harga_ubah',
                     title:'Harga',
                     width:110,
                     align:'right'
                     },
                    {field:'total_ubah',
                     title:'Total+Pajak Perubahan',
                     width:150,
                     align:'right',
                     styler: function(value,row,index){
                        return 'background-color:#d9e3fc;color:red;font-weight:bold;';
                     },
                     },
                    {field:'total_sempurna1',
                     title:'Total Geser',
                     width:150,
                     align:'right'
                     },
                    {field:'total',
                     title:'Total Murni',
                     width:150,
                     align:'right'
                     }
                ]]  
            });
        });
        

        $(document).ready(function() {
            $("#accordion").accordion();
        });
  
        
        function section(kdrek){
            
            
            var mskpd = $('#sskpd').combogrid('getValue'); 
            var mgiat = $('#kdgiat').combogrid('getValue'); 
           var nama= document.getElementById('nmrek5').value;
   
           if ( mgiat=='' ){
                alert("Pilih Kegiatan Terlebih Dahulu...!!!");
            }
            
            if (kdrek=='' ){
                alert("Pilih Rekening Terlebih Dahulu...!!!");
            }
            $("#reke").attr("value",kdrek);
            subheader();
            kdrek1='Rincian dari '+kdrek+' - ';
            /*menampilkan rincian dpo*/
            standardharga();
            $(document).ready(function(){
                $("#reke").attr("value",kdrek);            
                $('#section2').click(); 
                $(function(){
                    $('#dg1').edatagrid({
                         url          : '<?php echo base_url(); ?>/index.php/anggaran_murni/rka_rinci_rancang/'+mskpd+'/'+mgiat+'/'+kdrek,
                         idField      : 'id',
                         toolbar      : "#toolbar1",              
                         rownumbers   : "true", 
                         fitColumns   : false,
                         title        : kdrek1,
                         pagination   :"true",
                         pageList     : [10,20,50,100,500,1000],
                         singleSelect : "true",
                         onSelect     : function(rowIndex,rowData){
                                        stsheader    = rowData.header;
                                        if (stsheader==1){            
                                            $("#header_po_edit").attr("checked",true);
                                        } else {
                                            $("#header_po_edit").attr("checked",false);
                                        }
                                        
                                       $("#nilai_rekening_rincian_murni").attr("value",rowData.total);  /*# untuk cek apakah nilainya */
                                       $("#nilai_rekening_rincian_geser").attr("value",rowData.total_sempurna);

                                       $("#spesifikasi_edit").attr("value",rowData.spesifikasi_ubah);
                                       $("#kode_unik").attr("value",rowData.unik);          /*kode unik id trdpo unik*/
                                       $("#kode_edit").combogrid("setValue",rowData.kode);  /*nama header*/
                                       $("#kode_edit2").attr("value",rowData.kode);
                                       $("#id_standar_harga_edit").attr("value",rowData.id_standar_harga);
                                       $("#kd_barang_edit").attr("value",rowData.kd_barang);
                                       
                                       $("#uraian_edit").attr("value",rowData.uraian);
                                       $("#volume1_edit").attr("value",rowData.volume_ubah1);
                                       $("#volume2_edit").attr("value",rowData.volume_ubah2);
                                       $("#volume3_edit").attr("value",rowData.volume_ubah3);
                                       $("#volume4_edit").attr("value",rowData.volume_ubah4);

                                       $("#volume1_edit").attr("value",rowData.volume_ubah1);
                                       $("#volume2_edit").attr("value",rowData.volume_ubah2);
                                       $("#volume3_edit").attr("value",rowData.volume_ubah3);
                                       $("#volume4_edit").attr("value",rowData.volume_ubah4);
                                       $("#sat1_edit").attr("value",rowData.satuan_ubah1);
            
                                       $('#satuan1_edit').val(rowData.satuan_ubah1).trigger("change");
                                       $('#satuan2_edit').val(rowData.satuan_ubah2).trigger("change");
                                       $('#satuan3_edit').val(rowData.satuan_ubah3).trigger("change");
                                       $('#satuan4_edit').val(rowData.satuan_ubah4).trigger("change");

                         
                                       $("#sat1_edit").attr("value",rowData.satuan_ubah1);
                                       $("#pajak_edit").attr("value",rowData.pajak_ubah);

                                       $("#harga_edit").attr("value",rowData.harga_ubah);
                                       $("#nomor_insert").attr("value",rowData.no_po) ;
                                       
                                       
                                       var vol1_e  = rowData.volume_ubah1 ;
                                       var harga_e = rowData.harga_ubah ;
                                       

                                       var ntotal_edit = rowData.total_ubah;
                                       $("#total_edit").attr("value",ntotal_edit);
                                       
                       },
                       onDblClickRow  : function(rowIndex,rowData){
                                       $("#harga_proteksi_edit").attr("value",rowData.harga1) ;
                                       $("#kdbarang_edit").attr("value",rowData.kd_barang);
                                       $("#dialog-modal-edit").dialog('open');

                                       if(rowData.kd_barang.length>5){
                                            document.getElementById('spesifikasi_edit').disabled=true;
                                            document.getElementById('harga_edit').disabled=true;
                                            document.getElementById('sat1_edit').disabled=true;
                                       }else{
                                            document.getElementById('spesifikasi_edit').disabled=false;
                                            document.getElementById('harga_edit').disabled=false;
                                            document.getElementById('sat1_edit').disabled=false;
                                       }

                                       $("#kode_edit").combogrid("setValue",rowData.kode) ;
                                       $("#kode_edit2").attr("value",rowData.kode) ;
                                        subheader_edit();
                                        cekbok();

                       },
                        onLoadSuccess:function(data){
                                        load_sum_rek_rinci(kdrek);  
                                        get_nilai_kua_rek();
                                     }
                    });
                });
                    
            }); 

        }
 


       function sbiayaharga(){
            var reke   = $("#kdrek5").combogrid("getValue");
            $(function(){
            $('#sbiaya').combogrid('clear');
            $('#sbiaya').combogrid({  
            panelWidth : 1000,  
            idField    : 'kd_kegiatan',  
            textField  : 'kd_kegiatan',  
            mode       : 'remote',
            url        : '<?php echo base_url(); ?>index.php/rka_rancang/load_daftar_harga_detail_ck',
            queryParams: ({rekening:reke}),
            columns   : [[
                {field:'uraian',title:'uraian',width:400},
                {field:'satuan',title:'satuan',width:100},
                {field:'harga_satuan',title:'harga',width:300, align:'right'}                
            ]],
            onSelect:function(rowIndex,rowData){

                    if(rowData.harga==null){
                        alert("Pilih Rincian Standart!");
                        exit();
                    }

                    $("#uraian").attr("value", rowData.uraian);
                    $("#kdbarang").attr("value", rowData.kd_barang);
                    $("#vol1").attr("value", 1);
                    $("#sat1").attr("value", rowData.satuan);
                    $("#harga_proteksi").attr("value", rowData.harga);
                    $("#harga").attr("value", number_format(rowData.harga,2,'.',','));  
                
            },
            }); 
            });
        }
       function sbiayaharga_edit(){
            var reke   = $("#kdrek5").combogrid("getValue");
            $(function(){
            $('#sbiaya_edit').combogrid({  
            panelWidth : 1000,  
            idField    : 'kd_kegiatan',  
            textField  : 'kd_kegiatan',  
            mode       : 'remote',
            url        : '<?php echo base_url(); ?>index.php/rka_rancang/load_daftar_harga_detail_ck',
            queryParams: ({rekening:reke}),
            columns   : [[
                {field:'uraian',title:'uraian',width:400},
                {field:'satuan',title:'satuan',width:100},
                {field:'harga_satuan',title:'harga',width:300,align:'right'}                
            ]],
            onSelect:function(rowIndex,rowData){

                    if(rowData.harga==null){
                        alert("Pilih Rincian Standart!");
                        exit();
                    }

                    $("#uraian_edit").attr("value", rowData.uraian);
                    $("#vol1_edit").attr("value", 1);
                    $("#kdbarang_edit").attr("value", rowData.kd_barang);
                    $("#sat1_edit").attr("value", rowData.satuan);
                    $("#harga_edit").attr("value", number_format(rowData.harga,2,'.',','));
                    $("#harga_proteksi_edit").attr("value", rowData.harga);
                
            },
            }); 
            });
        }
  
 


       function section1(){
         validate_combo();
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
       }
       
       function section3(){
        // validate_combo();
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
       }


    function load_sum_rek(){   /*total nilai satu kegiatan*/             
        var a = $('#sskpd').combogrid('getValue'); 
        var b = $('#kdgiat').combogrid('getValue');
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b}),
            url:"<?php echo base_url(); ?>index.php/anggaran_murni/load_sum_rek_rancang",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal_ubah']);
                });
            }
         });
        });
    }

    
    function load_sum_rek_rinci(c){     /* rincian rekening trdpo*/          
        var a = $('#sskpd').combogrid('getValue'); 
        var b = $('#kdgiat').combogrid('getValue');
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b,rek:c}),
            url:"<?php echo base_url(); ?>index.php/anggaran_murni/load_sum_rek_rinci_rancang",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){

                    $("#rektotal_rinci").attr("value",n['rektotal_rinci_ubah']);
                });
            }
         });
        });
    }
    
    
    function input(){
        $("#kdrek5").combogrid("setValue","");
        var cek_giat = $("#kdgiat").combogrid('getValue');
        
        if ( cek_giat=='' ){
            alert('Pilih Kegiatan Terlebih Dahulu...!!!');
            exit();
        }
        $("#kdrek5").combogrid("enable");
        //$("#kdrek5").combogrid("setValue",'');
        $("#kdrek5").combogrid("setValue",'');
        $("#sdana1").combogrid("setValue",'');
        $("#sdana2").combogrid("setValue",'');
        $("#sdana3").combogrid("setValue",'');
        $("#sdana4").combogrid("setValue",'');
        $("#nilaisumber").attr("value",0);
        $("#nilaisumber2").attr("value",0);
        $("#nilaisumber3").attr("value",0);
        $("#nilaisumber4").attr("value",0);                                        
 
        
        document.getElementById('nilairek').value    = 0;
        document.getElementById('nilairek').disabled = true;
        document.getElementById('nilaisumber').disabled = true;
        document.getElementById('nilaisumber2').disabled = true;
        document.getElementById('nilaisumber3').disabled = true;
        document.getElementById('nilaisumber4').disabled = true;
        document.getElementById('nmrek5').value      = '';
        document.getElementById('nopo').value        = '';
        validate_rekening();
        $("#kdrek5").combogrid("enable");
        }
    

    function tambah(){
        kunci_inputan();
        var skpd   = $('#sskpd').combogrid('getValue'); 
        var kegi   = $("#kdgiat").combogrid("getValue");
        var reke   = $("#kdrek5").combogrid("getValue");
        var nmrek5 = document.getElementById('nmrek5').value;
        var nrek   = angka(document.getElementById('nilairek').value);
        var tvolum   = angka(document.getElementById('hasil').value);
        var sdana1 = $("#sdana1").combogrid("getValue");
        var sdana2 = $("#sdana2").combogrid("getValue");
        var sdana3 = $("#sdana3").combogrid("getValue");
        var sdana4 = $("#sdana4").combogrid("getValue");
        var ndana1   = angka(document.getElementById('nilaisumber').value) ;
        var ndana2   = angka(document.getElementById('nilaisumber2').value) ;
        var ndana3   = angka(document.getElementById('nilaisumber3').value) ;
        var ndana4   = angka(document.getElementById('nilaisumber4').value) ;

        var n_sisakua   = angka(document.getElementById('sisa_nilai_kua').value) ;
        
        if ( kegi == '' ){
            alert('Pilih Kode Kegiatan Terlebih Dahulu...!!!');
            exit();
        }
        if ( reke == '' ){
            alert('Pilih Rekening Terlebih Dahulu...!!!');
            exit();
        }

        if(tvolum!=0){
            alert("Total sumber dana tidak sama dengan pagu!");
            exit();
        }

        $("#dg").datagrid("selectAll");
        var rows = $("#dg").datagrid("getSelections");
        var jrow = rows.length - 1;
        jidx     = jrow + 1 ;

        $("#dg").edatagrid('appendRow',{kd_rek5:reke,nm_rek5:nmrek5,nilai:nrek});
        $(document).ready(function(){
            $("#proses").show();
        $.ajax({
           type     : "POST", 
           dataType : "json",
           data     : ({kd_skpd:skpd,kd_kegiatan:kegi,kd_rek5:reke,nilai:nrek,dana1:sdana1,dana2:sdana2,dana3:sdana3,dana4:sdana4,
                        vdana1:ndana1,vdana2:ndana2,vdana3:ndana3,vdana4:ndana4}),
           url      : '<?php echo base_url(); ?>index.php/anggaran_murni/tsimpan_ar_ubah', 
           success  : function(data){
                      st12 = data;
                      if ( st12 == '1' ){
                        $("#proses").hide();
                        alert("Data Tersimpan...!!!");
                        $("#dg").datagrid("unselectAll");                        
                        $('#dg').datagrid('reload');                     
                        get_nilai_kua();
                      } else {
                        $("#proses").hide();
                        alert("Gagal Simpan...!!!");
                      }
                      }
        });
        });
        
        $("#dg").datagrid("unselectAll");
        validate_combo();
        $('#dg').datagrid('reload');
        $("#kdrek5").combogrid("disable");
        $("#kdrek5").combogrid("setValue",'');
        $("#nilairek").attr("value",0);
        document.getElementById('nmrek5').value = '';
        $("#sdana1").combogrid("setValue",'');
        $("#sdana2").combogrid("setValue",'');
        $("#sdana3").combogrid("setValue",'');
        $("#sdana4").combogrid("setValue",'');
        $("#nilaisumber").attr("value",0);
        $("#nilaisumber2").attr("value",0);
        $("#nilaisumber3").attr("value",0);
        $("#nilaisumber4").attr("value",0);
        document.getElementById('nilaisumber').disabled = false;
        document.getElementById('nilaisumber2').disabled = false;
        document.getElementById('nilaisumber3').disabled = false;
        document.getElementById('nilaisumber4').disabled = false;                                
}
    
    
    function btl(){
        $("#kdrek5").combogrid("setValue",'');

        $("#sdana1").combogrid("setValue",'');
        $("#sdana2").combogrid("setValue",'');
        $("#sdana3").combogrid("setValue",'');
        $("#sdana4").combogrid("setValue",'');

        $("#nilaisumber").attr("value",0);
        $("#hasil").attr("value",0);
        $("#nilaisumber2").attr("value",0);
        $("#nilaisumber3").attr("value",0);
        $("#nilaisumber4").attr("value",0);                                
        document.getElementById('nilairek').value = 0;
        document.getElementById('nmrek5').value   = '';
        kosong();
        $("#dg").datagrid("unselectAll");
        
    }
    

    function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg_rek').datagrid('unselectAll');
        $('#dg').edatagrid('reload');
    } 
      

    function simpan_det_keg(){
        var a = $('#sskpd').combogrid('getValue'); 
        var b = document.getElementById('giats').value;
        var c = document.getElementById('lokasi').value; 
        var d = document.getElementById('keterangan').value;
        var e = document.getElementById('waktu_giat').value;
        var f = document.getElementById('waktu_giat2').value;
        var g = document.getElementById('sub_keluaran').value;
        var sas_prog   = document.getElementById('sasaran_program').value;
        var cap_prog   = document.getElementById('capaian_program').value;

        var tu_capai   = document.getElementById('tu_capai').value;
        var tk_capai   = document.getElementById('tk_capai').value;
        var tu_capai_p = document.getElementById('tu_capai_p').value;
        var tk_capai_p = document.getElementById('tk_capai_p').value;

        var tu_mas   = document.getElementById('tu_mas').value;
        var tk_mas   = document.getElementById('tk_mas').value;
        var tu_mas_p = document.getElementById('tu_mas_p').value;
        var tk_mas_p = document.getElementById('tk_mas_p').value;
        
        var tu_kel   = document.getElementById('tu_kel').value;
        var tk_kel   = document.getElementById('tk_kel').value;
        var tu_kel_p = document.getElementById('tu_kel_p').value;
        var tk_kel_p = document.getElementById('tk_kel_p').value;

        var tu_has   = document.getElementById('tu_has').value;
        var tk_has   = document.getElementById('tk_has').value;
        var tu_has_p = document.getElementById('tu_has_p').value;
        var tk_has_p = document.getElementById('tk_has_p').value;

        var kel_sa   = document.getElementById('kel_sasaran_kegiatan').value;
        var n = document.getElementById('ttd').value;
        var lalu = angka(document.getElementById('ang_lalu').value);
        if ( b=='' ){
            alert('Pilih Kegiatan Terlebih Dahulu...!!!');
            exit();
        }


        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({skpd:a,giat:b,lokasi:c,keterangan:d,waktu_giat:e,waktu_giat2:f,sub_keluaran:g,ttd:n,lalu:lalu,
                    tu_capai  :tu_capai,
                    tk_capai  :tk_capai,
                    tu_capai_p:tu_capai_p,
                    tk_capai_p:tk_capai_p,                   
                    tu_mas : tu_mas,
                    tk_mas : tk_mas,
                    tu_mas_p : tu_mas_p,
                    tk_mas_p : tk_mas_p,
                    tu_kel : tu_kel,
                    tk_kel : tk_kel,
                    tu_kel_p : tu_kel_p,
                    tk_kel_p : tk_kel_p,
                    tu_has : tu_has,
                    tk_has : tk_has,
                    tu_has_p : tu_has_p,
                    tk_has_p : tk_has_p,
                    kel_sa :kel_sa,
                    sas_prog: sas_prog,
                    cap_prog:cap_prog

                    }),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/anggaran_murni/simpan_det_keg_rancang",
            success:function(data){ 
                    alert('Data Tersimpan');
                    }
         });
        });
    }




    function load_detail_keg(){  /*untuk menampilkan Indikator*/            
        var a = $('#sskpd').combogrid('getValue'); 
        var b = document.getElementById('giats').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b}),
            url:"<?php echo base_url(); ?>index.php/anggaran_murni/load_det_keg_rancang",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#ang_lalu").attr("value",n['ang_lalu']);
                    $("#lokasi").attr("value",n['lokasi']);
                    $("#sasaran_program").attr("value",n['sasaran_program']);
                    $("#capaian_program").attr("value",n['capaian_program']);
                    $("#waktu_giat").attr("value",n['waktu_giat']);
                    $("#waktu_giat2").attr("value",n['waktu_giat2']);
                    $("#ttd").attr("value",n['ttd']);
                    $("#tu_capai").attr("value",n['tu_capai']);
                    $("#tu_capai_p").attr("value",n['tu_capai_p']);
                    $("#tu_mas").attr("value",n['tu_mas']);
                    $("#tu_mas_p").attr("value",n['tu_mas_p']);
                    $("#tu_kel").attr("value",n['tu_kel']);
                    $("#tu_kel_p").attr("value",n['tu_kel_p']);
                    $("#tu_has").attr("value",n['tu_has']);
                    $("#tu_has_p").attr("value",n['tu_has_p']);
                    $("#tk_capai").attr("value",n['tk_capai']);
                    $("#tk_capai_p").attr("value",n['tk_capai_p']);
                    $("#tk_mas").attr("value",n['tk_mas']);
                    $("#tk_mas_p").attr("value",n['tk_mas_p']);
                    $("#tk_kel").attr("value",n['tk_kel']);
                    $("#tk_kel_p").attr("value",n['tk_kel_p']);
                    $("#tk_has").attr("value",n['tk_has']);
                    $("#tk_has_p").attr("value",n['tk_has_p']);
                    $("#kel_sasaran_kegiatan").attr("value",n['kel_sasaran_kegiatan']);
                    $("#sub_keluaran").attr("value",n['sub_keluaran']);
                    $("#keterangan").attr("value",n['keterangan']);
                });
            }
         });
        });
    }


        function insert(){
            $('#dg1').datagrid('insertRow',{
                index:detIndex,
                row:{uraian:''              
                    }
            });
            $('#dg1').datagrid('beginEdit',detIndex+1);     
        }   

        
        //DASAR HUKUM ==================================================================================================
        $(function(){ //load pertama
            $('#dg2').edatagrid({
                url: '',
                 idField:'id',
                 rownumbers:true, 
                 fitColumns:false,
                 singleSelect:false,
                 onLoadSuccess:function(data){
                                   selectall();      
                               },
                 columns:[[
                    {field:'kd_hukum',
                     title:'kode',
                     width:5,
                     hidden:true,
                     editor:{type:"text"}
                    },
                    {field:'nm_hukum',
                     title:'Dasar Hukum',
                     width:780,
                     editor:{type:"text"}
                    },
                    {field:'ck',
                     title:'ck',
                     width:5,
                     checkbox:true
                     }
                ]]  
            
            });
        });

    
    //load dasar hukum pada combo giat==========================================================
    var sell = new Array();
    var max  = 0;
    function getcek(){
        var ids = [];  
        var a=null;
        var rows = $('#dg2').edatagrid('getSelections');  
        for(var i=0; i<rows.length; i++){  
            a=rows[i].ck;
            max=i;
            if (a!=null){
                sell[i]=a-1;
            }else{
                sell[i]=1000;           
            }
        }  
    }
    
    function setcek(){
        for(var i=0; i<max+1; i++){ 
            if (sell[i]!=1000){
                selectRecord(sell[i]);
            }
        }       
    }


    function selectall(){
        max  = 0;
        $('#dg2').edatagrid('selectAll');
        getcek();
        Unselectall();
        setcek();
    }

    function Unselectall(){
        $('#dg2').edatagrid('unselectAll');
    }


    function selectRecord(rec){
        $('#dg2').edatagrid('selectRecord',rec);
    }
    
    function load_dhukum(){
        $(function(){
            var mskpd = $('#sskpd').combogrid('getValue'); 
            var mgiat = $('#kdgiat').combogrid('getValue');
            var mkdrek5 = $("#kdrek5").combogrid("getValue");
            //alert(mkdrek5);
            $('#dg2').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/rka_rancang/rka_hukum/'+mskpd+'/'+mgiat+'/'+mkdrek5,
                 idField:'id',
                 rownumbers:true, 
                 fitColumns:false,
                 singleSelect:false,
                 columns:[[
                    {field:'kd_hukum',
                     title:'kode',
                     width:5,
                     hidden:true,
                     editor:{type:"text"}
                    },
                    {field:'nm_hukum',
                     title:'Dasar Hukum',
                     width:780,
                     editor:{type:"text"}
                    },
                    {field:'ck',
                     title:'ck',
                     width:5,
                     checkbox:true
                     }
                ]]  
            
            });
        });
        selectall();
    }


    function simpan_dhukum(){
        var ids = [];  
        var rows = $('#dg2').edatagrid('getSelections');  
        for(var i=0; i<rows.length; i++){  
            ids.push(rows[i].kd_hukum);
        }  
        hukum_cont(ids.join('||'));  
    }

    function hukum_cont(isi){
        var a = $('#sskpd').combogrid('getValue'); 
        var b = $('#kdgiat').combogrid('getValue');
        var mkdrek5 = $("#kdrek5").combogrid("getValue");
        //alert();
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({skpd:a,giat:b,cisi:isi,rek5:mkdrek5}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/rka_rancang/simpan_dhukum",
            success:function(data){ 
                    alert('Data Tersimpan');
                    }
         });
        });
    }
    
    function tombol(st,kunci){  

    if (st=='1'){
            $('#add').remove();
            $('#input').remove();
            $('#btl').remove();
            $('#del').remove();
            $('#save').remove();
            $('#cancel').remove();
            $('#add1').remove();
            $('#del1').remove();
            $('#delrek').remove();
            $('#dpo').hide();
            $('#save1').remove();
            $('#cancel1').remove();
            $('#insert1').remove();
            $('#save2').remove();
            $('#pil_edit').remove();
            $('#save4').remove();
            $('#pesen').show();
            document.getElementById("p1").innerHTML="APBD TELAH DI - SAH - KAN...!!!";
            document.getElementById("isi").innerHTML="APBD TELAH DI - SAH - KAN...!!!";
            status_apbd = '1';
            
     }else if(kunci=='1'){
            $('#add').remove();
            $('#input').remove();
            $('#pil_edit').remove();
            $('#btl').remove();
            $('#del').remove();
            $('#dpo').hide();
            $('#save').remove();
            $('#cancel').remove();
            $('#add1').remove();
            $('#del1').remove();
            $('#delrek').remove();
            $('#save1').remove();
            $('#cancel1').remove();
            $('#insert1').remove();
            $('#save2').remove();
            $('#save4').remove();
            $('#pesen').show();
            document.getElementById("p1").innerHTML="INPUTAN TELAH DIKUNCI";
            document.getElementById("isi").innerHTML="INPUTAN TELAH DIKUNCI";
            status_apbd = '1';
     }else {
            $('#add').show();
            $('#input').show();
            $('#btl').show();
            $('#del').show();
            $('#save').show();
            $('#cancel').show();
            $('#add1').show();
            $('#del1').show();
            $('#save1').show();
            $('#cancel1').show();
            $('#insert1').show();
            $('#save2').show();
            $('#save4').show();
            
            document.getElementById("p1").innerHTML="";
            status_apbd = '0';
            
     }
    }
    
    
    function append_save() {

            kunci_inputan();
                    var as_kdgiat = $("#kdgiat").combogrid("getValue") ;
                    var as_reke   = document.getElementById('reke').value ;
                    
                    if ( as_kdgiat=='' ){
                        alert('Pilih Kegiatan Terlebih Dahulu...!!!');
                        exit();
                    }
                    if ( as_reke=='' ){
                        alert("Pilih Rekening Terlebih Dahulu...!!!");
                        exit();
                    }
    

            var cgiat = $('#kdgiat').combogrid('getValue'); 
            var cskpd = $('#sskpd').combogrid('getValue'); 
            var ckdlokasi = '';
           
            var crek  = document.getElementById('reke').value;
            var kd_barang         = document.getElementById('kd_barang').value;
            var id_standar_harga  = document.getElementById('id_standar_harga').value;
            var spesifikasi  = document.getElementById('spesifikasi').value;
            var spesifikasi  = spesifikasi.replace("Spesifikasi :", "");
            var spesifikasi  = spesifikasi.replace("Spesifikasi : ", "");
            var spesifikasi  = spesifikasi.replace("Spesifikasi", "");        
            var kdbarang  = '';
            var norka = cskpd+'.'+cgiat+'.'+crek;
            var jkegi = document.getElementById('jnskegi').value ;

            /*proteksi harga standart*/
            var harga_  = angka(document.getElementById('harga').value);            
            var harga_proteksi   = document.getElementById('harga_proteksi').value;

            var pajak_persen = document.getElementById('pajak').value;
            var pajak_decimal= pajak_persen/100;

            if(!pajak_persen){
                pajak_decimal=0;
                pajak_persen=0;
            }

            var satuan1 = document.getElementById('satuan1').value;
            var satuan2 = document.getElementById('satuan2').value;
            var satuan3 = document.getElementById('satuan3').value;
            var satuan4 = document.getElementById('satuan4').value;

            var volume1 = document.getElementById('volume1').value;
            var volume2 = document.getElementById('volume2').value;
            var volume3 = document.getElementById('volume3').value;
            var volume4 = document.getElementById('volume4').value;

            var satuan1_koefisien = document.getElementById('satuan1').value+" x ";
            var satuan2_koefisien = document.getElementById('satuan2').value+" x ";
            var satuan3_koefisien = document.getElementById('satuan3').value+" x ";
            var satuan4_koefisien = document.getElementById('satuan4').value+" x ";

            var volume1_koefisien = document.getElementById('volume1').value;
            var volume2_koefisien = document.getElementById('volume2').value;
            var volume3_koefisien = document.getElementById('volume3').value;
            var volume4_koefisien = document.getElementById('volume4').value;

            if(satuan1==''){
                volume1_koefisien="";
                satuan1_koefisien="";
            }

            if(satuan2==''){
                volume2_koefisien="";
                satuan2_koefisien="";
                satuan1_koefisien = satuan1;
            }

            if(satuan3==''){
                volume3_koefisien="";
                satuan3_koefisien="";
                satuan2_koefisien = satuan2;
            }

            if(satuan4==''){
                volume4_koefisien="";
                satuan4_koefisien="";
                satuan3_koefisien = satuan3;
            }

            var koefisien=volume1_koefisien+" "+satuan1_koefisien+" "+volume2_koefisien+" "+satuan2_koefisien+" "+volume3_koefisien+" "+satuan3_koefisien+" "+volume4_koefisien+" "+satuan4_koefisien;






            var uraian = document.getElementById('uraian').value;
            var uraian = uraian.replace("'", "`");                
            var o       = document.getElementById('header_po').checked; 
            if ( o == false ){
                   o=0;
                if(satuan1 == ''){
                    alert('Satuan 1 Harap dilengkapi!!');
                    exit();
                }
            }else{
                    var spesifikasi="";
                    var uraian="[-] "+uraian;
                    o=1;
            }

            var kode_header = $('#kode_header').combogrid('getValue'); 

            if(uraian == ''){
                alert('Uraian Tidak Boleh Kosong!!');
                exit();
            }


     
            var nilai  = document.getElementById('harga').value ;
            if ( nilai == '' ){
                var harga = 0;              
            }else {
                harga = angka(nilai);
            }
            
            if(!volume1){
                volume1=0;
            }
            if(!volume2){
                volume2=1;
            }
            if(!volume3){
                volume3=1;
            }
            if(!volume4){
                volume4=1;
            }

            var total_kotor  = volume1*volume2*volume3*volume4*harga;
            var total        = (total_kotor*pajak_decimal)+total_kotor;

              
            var total_awal  = angka(document.getElementById('rektotal_rinci').value) ;
            var total_rinci = total + total_awal;
            
            var n_sisakua   = angka(document.getElementById('total_sisa_kua_rek').value) ;
            var lcrek=crek.substr(0,1);  

            cekgj = cgiat.substr(10,15);

            var nreal_rek  = angka(document.getElementById('nreal_rek').value);  

            if(total_awal<nreal_rek){
                alert("Nilai tidak boleh Kurang dari Realisasi");
                exit();
            }

            if(jkegi=='5'){
                if((cekgj!='00.61') & (cekgj!='00.04')){
                    if (( n_sisakua < total_rinci )&&(lcrek=='5')){
                        alert('Nilai Melebihi nilai KUA');
                        exit();
                    }
                }

            }

            $("#loading").show();

            if(o==1){
                koefisien="";
                satuan1 = "";
                satuan2 = "";
                satuan3 = "";
                satuan4 = "";
                volume1 = "0.00";
                volume2 = "0.00";
                volume3 = "0.00";
                volume4 = "0.00";
                tvol   = '0.00';
                harga = '0.00';
                total = '0.00';
            }     
/*==================  */    
                 $(function(){      
                 $.ajax({
                    type: 'POST',
                    data: ({
                        header:o,
                        kd_lokasi:ckdlokasi,
                        kd_barang:kdbarang,
                        kode:kode_header,
                        kd_kegiatan:cgiat,
                        kd_rek:crek,
                        spesifikasi:spesifikasi,
                        no_trdrka:norka,
                        uraian:uraian,

                        volume1:volume1,
                        volume2:volume2,
                        volume3:volume3,
                        volume4:volume4,

                        satuan1:satuan1,
                        satuan2:satuan2,
                        satuan3:satuan3,
                        satuan4:satuan4,
                        pajak  :pajak_persen,

                        harga:harga,
                        total:total,

                        unik:'',
                        koefisien:koefisien,
                        id_standar_harga:id_standar_harga,
                        kd_barang:kd_barang,
                        skpd:cskpd}),
                    dataType:"json",
                    url:"<?php echo base_url(); ?>index.php/anggaran_murni/simpan_rincian_dpo_ubah",
                    success:function(data){
                        alert('Data Tersimpan');
                        $("#loading").hide();
                        $('#kod').show();
                        section(crek);
                        get_nilai_kua();
                        get_nilai_kua_rek();
                        kosong();
                        $("#kod2").show();
                        $("#dg1").datagrid("reload");
                    }
                 });
                 });
/*===============*/
            
            
            $("#dg1").datagrid("unselectAll");
            
           
            //alert(tvol);
            var data = $('#dg1').datagrid('getData');
            var rows = data.rows;
            var total_rinci = 0;
            
            for (i=0; i < rows.length; i++) {
                total_rinci+=angka(rows[i].total);
            }

            var data = $('#dg1').datagrid('getData');
            var rows = data.rows;
            var total_rinci = 0;
            
            for (i=0; i < rows.length; i++) {
                total_rinci+=angka(rows[i].total);
            }
            $("#nilaisumber").attr("value",number_format(total_rinci,2,'.',','));
            $("#rektotal_rinci").attr("value",number_format(total_rinci,2,'.',','));
            $('#dg1').datagrid('unselectAll');
            kosong();
       
       }
       
       
       
       function insert_row() {
            var kode_unik  = document.getElementById('nomor_insert').value;
            var rows     = $('#dg1').edatagrid('getSelected');
            var idx_ins  = $('#dg1').edatagrid('getRowIndex',rows);

            if ( idx_ins == -1){
                alert("Pilih Lokasi Insert Terlebih Dahulu...!!!") ;
                exit();
            }


            kunci_inputan();  

            var cgiat = $('#kdgiat').combogrid('getValue'); 
            var cskpd = $('#sskpd').combogrid('getValue'); 
            var ckdlokasi = '';
           
            var crek  = document.getElementById('reke').value;
            var kd_barang         = document.getElementById('kd_barang').value;
            var id_standar_harga  = document.getElementById('id_standar_harga').value;
            var spesifikasi  = document.getElementById('spesifikasi').value;
            var spesifikasi  = spesifikasi.replace("Spesifikasi :", "");
            var spesifikasi  = spesifikasi.replace("Spesifikasi : ", "");
            var spesifikasi  = spesifikasi.replace("Spesifikasi", "");        
            var kdbarang  = '';
            var norka = cskpd+'.'+cgiat+'.'+crek;
            var jkegi = document.getElementById('jnskegi').value ;

            var pajak_persen = document.getElementById('pajak').value;
            var pajak_decimal= pajak_persen/100;

            if(!pajak_persen){
                pajak_decimal=0;
                pajak_persen=0;
            }

            var satuan1 = document.getElementById('satuan1').value;
            var satuan2 = document.getElementById('satuan2').value;
            var satuan3 = document.getElementById('satuan3').value;
            var satuan4 = document.getElementById('satuan4').value;

            var volume1 = document.getElementById('volume1').value;
            var volume2 = document.getElementById('volume2').value;
            var volume3 = document.getElementById('volume3').value;
            var volume4 = document.getElementById('volume4').value;

            var satuan1_koefisien = document.getElementById('satuan1').value+" x ";
            var satuan2_koefisien = document.getElementById('satuan2').value+" x ";
            var satuan3_koefisien = document.getElementById('satuan3').value+" x ";
            var satuan4_koefisien = document.getElementById('satuan4').value+" x ";

            var volume1_koefisien = document.getElementById('volume1').value;
            var volume2_koefisien = document.getElementById('volume2').value;
            var volume3_koefisien = document.getElementById('volume3').value;
            var volume4_koefisien = document.getElementById('volume4').value;

            if(satuan1==''){
                volume1_koefisien="";
                satuan1_koefisien="";
            }

            if(satuan2==''){
                volume2_koefisien="";
                satuan2_koefisien="";
                satuan1_koefisien = satuan1;
            }

            if(satuan3==''){
                volume3_koefisien="";
                satuan3_koefisien="";
                satuan2_koefisien = satuan2;
            }

            if(satuan4==''){
                volume4_koefisien="";
                satuan4_koefisien="";
                satuan3_koefisien = satuan3;
            }

            var koefisien=volume1_koefisien+" "+satuan1_koefisien+" "+volume2_koefisien+" "+satuan2_koefisien+" "+volume3_koefisien+" "+satuan3_koefisien+" "+volume4_koefisien+" "+satuan4_koefisien;


            var uraian = document.getElementById('uraian').value;
            var uraian = uraian.replace("'", "`");                
            var o       = document.getElementById('header_po').checked; 
            if ( o == false ){
                   o=0;
                }else{
                    var spesifikasi="";
                    var uraian="[-] "+uraian;
                    o=1;
                }
            var kode_header = $('#kode_header').combogrid('getValue'); 
            if(uraian == ''){
                alert('Uraian Tidak Boleh Kosong!!');
                exit();
            }

            var nilai  = document.getElementById('harga').value ;
            if ( nilai == '' ){
                var harga = 0;              
            }else {
                harga = angka(nilai);
            }
            
            if(!volume1){
                volume1=0;
            }
            if(!volume2){
                volume2=1;
            }
            if(!volume3){
                volume3=1;
            }
            if(!volume4){
                volume4=1;
            }

            var total_kotor  = volume1*volume2*volume3*volume4*harga;
            var total        = (total_kotor*pajak_decimal)+total_kotor;
                      
            var total_awal  = angka(document.getElementById('rektotal_rinci').value);
            var total_rinci = total_awal + total; 
            
            var n_sisakua   = angka(document.getElementById('total_sisa_kua_rek').value) ;
            var lcrek=crek.substr(0,1);  

            cekgj = cgiat.substr(10,15);

            var nreal_rek  = angka(document.getElementById('nreal_rek').value);  

            if(total_awal<nreal_rek){
                alert("Nilai tidak boleh Kurang dari Realisasi");
                exit();
            }

            if(jkegi=='5'){
                if((cekgj!='00.61') & (cekgj!='00.04')){
                    if (( n_sisakua < total_rinci )&&(lcrek=='5')){
                        alert('Nilai Melebihi nilai KUA');
                        exit();
                    }
                }

            }
            $("#loading").show();
            if(o==1){
                koefisien="";
                satuan1 = "";
                satuan2 = "";
                satuan3 = "";
                satuan4 = "";
                volume1 = "0.00";
                volume2 = "0.00";
                volume3 = "0.00";
                volume4 = "0.00";
                tvol   = '0.00';
                fharga = '0.00';
                ftotal = '0.00';
            }     
/*==================  */    
                 $(function(){      
                 $.ajax({
                    type: 'POST',
                    data: ({
                        header:o,
                        kd_lokasi:ckdlokasi,
                        kd_barang:kdbarang,
                        kode:kode_header,
                        kd_kegiatan:cgiat,
                        kd_rek:crek,
                        spesifikasi:spesifikasi,
                        no_trdrka:norka,
                        uraian:uraian,
                        koefisien:koefisien,
                        volume1:volume1,
                        volume2:volume2,
                        volume3:volume3,
                        volume4:volume4,

                        satuan1:satuan1,
                        satuan2:satuan2,
                        satuan3:satuan3,
                        satuan4:satuan4,
                        pajak  :pajak_persen,

                        harga:harga,
                        total:total,

                        unik:kode_unik,
                        id_standar_harga:id_standar_harga,
                        kd_barang:kd_barang,
                        skpd:cskpd}),
                    dataType:"json",
                    url:"<?php echo base_url(); ?>index.php/anggaran_murni/simpan_rincian_dpo_ubah",
                    success:function(data){
                        alert('Data Tersimpan');
                        $("#loading").hide();
                        $('#kod').show();
                        section(crek);
                        get_nilai_kua();
                        get_nilai_kua_rek();
                        kosong();
                        $("#kod2").show();
                        $("#dg1").datagrid("reload");
                    }
                 });
                 });
/*===============*/
            

            
           
            //alert(tvol);
            var data = $('#dg1').datagrid('getData');
            var rows = data.rows;
            var total_rinci = 0;
            
            for (i=0; i < rows.length; i++) {
                total_rinci+=angka(rows[i].total);
            }

            var data = $('#dg1').datagrid('getData');
            var rows = data.rows;
            var total_rinci = 0;
            
            for (i=0; i < rows.length; i++) {
                total_rinci+=angka(rows[i].total);
            }
            $("#nilaisumber").attr("value",number_format(total_rinci,2,'.',','));
            $("#rektotal_rinci").attr("value",number_format(total_rinci,2,'.',','));
            $('#dg1').datagrid('unselectAll');
            kosong();
            
    }

    function kosongsumber(){
         $("#sdana1").combogrid("setValue",'');
        $("#sdana2").combogrid("setValue",'');
        $("#sdana3").combogrid("setValue",'');
        $("#sdana4").combogrid("setValue",'');
        $("#saldosumber").attr("value",0);
        $("#saldosumber2").attr("value",0);
        $("#saldosumber3").attr("value",0);
        $("#saldosumber4").attr("value",0); 
    }
       
    
    function kosong(){
            $("#nilairek").attr("value",0);
            $("#nilaisumber").attr("value",0);
            $("#nilaisumber2").attr("value",0);
            $("#nilaisumber3").attr("value",0);
            $("#nilaisumber4").attr("value",0);  
            $("#uraian").attr("value","");
            $("#total_dpo").attr("value","");
            $("#kode_edit").attr("value","");
            $("#kode_unik").attr("value","");
            $("#harga_proteksi").attr("value",0);
            $("#harga_proteksi_edit").attr("value",0);
            $("#kdbarang").attr("value","");
            $("#kdbarang_edit").attr("value","");

            $("#volume1").attr("value","");
            $("#volume2").attr("value","");
            $("#volume3").attr("value","");
            $("#volume4").attr("value","");

            $("#volume1_edit").attr("value","");
            $("#volume2_edit").attr("value","");
            $("#volume3_edit").attr("value","");
            $("#volume4_edit").attr("value","");

            $('#satuan1').val("").trigger("change");
            $('#satuan2').val("").trigger("change");
            $('#satuan3').val("").trigger("change");
            $('#satuan4').val("").trigger("change");

            $('#satuan1_edit').val("").trigger("change");
            $('#satuan2_edit').val("").trigger("change");
            $('#satuan3_edit').val("").trigger("change");
            $('#satuan4_edit').val("").trigger("change");

            $("#sat1").attr("value","");
            $("#pajak").attr("value","");
            $("#hasil_pajak").attr("value","");
            $("#harga").attr("value","");
            $("#no_po").attr("value","");
            $("#nomor_insert").attr("value","");
            $("#id_standar_harga").attr("value","");
            $("#kd_barang").attr("value",""); 
            $("#id_standar_harga_edit").attr("value","");
            $("#kd_barang_edit").attr("value","");                       
            document.getElementById('kode_header').focus();
            $("#header_po").attr("checked",false);
            $("#spesifikasi").attr("value","");
            $("#spesifikasi_edit").attr("value",""); 
            document.getElementById('sat1').disabled=false;
            document.getElementById('harga').disabled=false;
            document.getElementById('spesifikasi').disabled=false;                 

            
    }
        
    
    function enter(ckey,_cid){
        if (ckey==13)
            {                                  
               document.getElementById(_cid).focus();
               if(_cid=='uraian'){
                    var as_kdgiat = $("#kdgiat").combogrid("getValue") ;
                    var as_reke   = document.getElementById('reke').value ;
                    
                    if ( as_kdgiat=='' ){
                        alert('Pilih Kegiatan Terlebih Dahulu...!!!');
                        exit();
                    }
                    if ( as_reke=='' ){
                        alert("Pilih Rekening Terlebih Dahulu...!!!");
                        exit();
                    }
                    append_save();
               }
            }     
        }

        function cari(){
    var kriteria = document.getElementById("txtcari").value;
    var crekening = document.getElementById('reke').value ;
    $(function(){ 
     $('#dg_std').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/rka_rancang/load_daftar_harga_detail_ck',
        queryParams:({cari:kriteria,rekening:crekening})
        });        
     });
    }
        
      
     function standard_harga(){

        $("#dialog-modal").dialog("open");
        var crekening = document.getElementById('reke').value ;
        $('#dg_std').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_detail_ck', //sengaja di errorin biar tidak nampil semua di awal ngeload data
        queryParams   : ({rekening:crekening}),
        idField       : 'id',
        rownumbers    : true, 
        fitColumns    : false,
        singleSelect  : false,
        columns       : [[{field:'id',        title:'id',           width:70, align:"left",hidden:"true"},
                          {field:'kd_barang', title:'Kode Barang',  width:150, align:"left"},
                          {field:'kd_rek5',   title:'Rekening',     width:80, align:"left",hidden:"true"},
                          {field:'uraian',    title:'Uraian',       width:330,align:"left"},
                          {field:'merk',      title:'Merk',         width:100,align:"left"},
                          {field:'satuan',    title:'Satuan',       width:100,align:"left"},
                          {field:'harga',     title:'Harga',        width:150,align:"right"},
                          {field:'ck',        title:'ck',           checkbox:true}
                         ]]
        });
        selectall_std();         
     }   
     
     function kembali_std(){
        $("#section2").click();
        $("#dialog-modal").dialog("close");
     }

     var sell_std = new Array();
     var max_std  = 0;
      
     function getcek_std(){
        var ids   = [];  
        var a     = null;
        var rows  = $('#dg_std').edatagrid('getSelections');  
        for(var i=0; i<rows.length; i++){  
            a       = rows[i].ck;
            max_std = i;
            if (a!=null){
                sell_std[i]=a-1;
            }else{
                sell_std[i]=1000;           
            }
        }  
      }
    
     
     function setcek_std(){
        for(var i=0; i<max+1; i++){ 
            if (sell_std[i]!=1000){
                selectRecord_std(sell_std[i]);
            }
        }       
     }


     function selectall_std(){
        max_std = 0;
        $('#dg_std').edatagrid('selectAll');
        getcek_std();
        Unselectall_std();
        setcek_std();
     }

     
     function Unselectall_std(){
        $('#dg_std').edatagrid('unselectAll');
     }
     
     function selectRecord_std(rec){
        $('#dg_std').edatagrid('selectRecord',rec);
     }
     
     function pilih_std(){

        var ids  = [];  
        var rows = $('#dg_std').edatagrid('getSelections');  
        for(var i=0; i<rows.length; i++){  

            var urai_std  = rows[i].uraian;
            var satu_std  = rows[i].satuan;
            var harga_std = rows[i].harga;
            var kd_brg    = rows[i].kd_barang;
            
            var vol1      = 1;
            var vol2      = '';
            var vol3      = '';
            var tvol      = vol1;

            var sat2      = '';
            var sat3      = '';
            
            var fharga    = number_format(harga_std,2,'.',',');
            var ftotal    = number_format(harga_std,2,'.',',');
            var total     = harga_std;
            
            $("#dg1").datagrid("unselectAll");
            $('#dg1').datagrid('selectAll');
            var rows_2 = $('#dg1').datagrid('getSelections') ;
                jgrid  = rows_2.length ;
           
            var id     = jgrid  ;
            
            $('#dg1').edatagrid('appendRow',{header:'0',kode:'1',kd_barang:kd_brg,uraian:urai_std,volume1:vol1,satuan1:satu_std,volume2:vol2,satuan2:sat2,volume3:vol3,volume:tvol,satuan3:sat3,harga1:fharga,id:id,total:ftotal});
            $("#dg1").datagrid("unselectAll");
            
            var total_awal  = angka(document.getElementById('rektotal_rinci').value) ;
            var total_rinci = angka(total)
                total_rinci = total_rinci + total_awal; 

            $("#rektotal_rinci").attr("value",number_format(total_rinci,2,'.',','));
            kosong();
            $("#dialog-modal").dialog("close");
            
        }  
    }
    
    function kembali_edit(){
        $("#section2").click();
        $("#dialog-modal-edit").dialog("close");
    }
    
    
    function edit_rincian_po(){ /*ini untuk sementara */
            get_nilai_kua();
            var kd_barang_edit = document.getElementById('kd_barang_edit').value;
            var id_standar_harga_edit = document.getElementById('id_standar_harga_edit').value;

            var spesifikasi_edit  = document.getElementById('spesifikasi_edit').value;
            var spesifikasi_edit  = spesifikasi_edit.replace("Spesifikasi :", "");
            var spesifikasi_edit  = spesifikasi_edit.replace("Spesifikasi : ", "");
            var spesifikasi_edit  = spesifikasi_edit.replace("Spesifikasi", "");        

            var pajak_persen = document.getElementById('pajak_edit').value;

            var pajak_decimal= pajak_persen/100;

            if(!pajak_persen){
                pajak_decimal=0;
                pajak_persen=0;
            }

            var satuan1 = document.getElementById('satuan1_edit').value;
            var satuan2 = document.getElementById('satuan2_edit').value;
            var satuan3 = document.getElementById('satuan3_edit').value;
            var satuan4 = document.getElementById('satuan4_edit').value;

            
            var volume1 = document.getElementById('volume1_edit').value;
            var volume2 = document.getElementById('volume2_edit').value;
            var volume3 = document.getElementById('volume3_edit').value;
            var volume4 = document.getElementById('volume4_edit').value;

            var satuan1_koefisien = document.getElementById('satuan1_edit').value+" x ";
            var satuan2_koefisien = document.getElementById('satuan2_edit').value+" x ";
            var satuan3_koefisien = document.getElementById('satuan3_edit').value+" x ";
            var satuan4_koefisien = document.getElementById('satuan4_edit').value+" x ";

            var volume1_koefisien = document.getElementById('volume1_edit').value;
            var volume2_koefisien = document.getElementById('volume2_edit').value;
            var volume3_koefisien = document.getElementById('volume3_edit').value;
            var volume4_koefisien = document.getElementById('volume4_edit').value;

            if(satuan1==''){
                volume1_koefisien="";
                satuan1_koefisien="";
            }

            if(satuan2==''){
                volume2_koefisien="";
                satuan2_koefisien="";
                satuan1_koefisien = satuan1;
            }

            if(satuan3==''){
                volume3_koefisien="";
                satuan3_koefisien="";
                satuan2_koefisien = satuan2;
            }

            if(satuan4==''){
                volume4_koefisien="";
                satuan4_koefisien="";
                satuan3_koefisien = satuan3;
            }

            var koefisien=volume1_koefisien+" "+satuan1_koefisien+" "+volume2_koefisien+" "+satuan2_koefisien+" "+volume3_koefisien+" "+satuan3_koefisien+" "+volume4_koefisien+" "+satuan4_koefisien;

            
            if(!volume1){
                volume1=0;
            }
            if(!volume2){
                volume2=1;
            }
            if(!volume3){
                volume3=1;
            }
            if(!volume4){
                volume4=1;
            }

        
            var uraian_edit = document.getElementById('uraian_edit').value;
            var o       = document.getElementById('header_po_edit').checked; 
                if ( o == false ){
                       o=0;
                       var kode_edit = document.getElementById('kode_edit2').value;
                        if(satuan1==''){
                            alert("Pilih satuan");
                            exit();
                        }
                    }else{
                        o=1;
                        var uraian_edit="[-] "+uraian_edit;
                        var kode_edit = "";

                    }
          

           
            var harga  = angka(document.getElementById('harga_edit').value) ;

            var total_kotor  = volume1*volume2*volume3*volume4*harga;
            var total        = (total_kotor*pajak_decimal)+total_kotor;


            var fharga_edit = number_format(harga_edit,2,'.',',');           
            var ftotal_edit = number_format(total_edit,2,'.',',');      


            if(o==1){
                koefisien="";
                volume1  = '0.00';
                volume2  = '0.00';
                volume3  = '0.00';
                volume4  = '0.00';
                satuan1   = '';
                satuan2   = '';
                satuan3   = '';
                satuan4   = '';
                pajak_persen="0.00";
                fharga_edit = '0.00';
                ftotal_edit = '0.00';
                total_edit  = '0.00';
                harga_edit  = '0.00';
                harga  = '0.00';
            }
            var cgiat = $('#kdgiat').combogrid('getValue'); 
            var cskpd = $('#sskpd').combogrid('getValue');
           
            var crek  = document.getElementById('reke').value;
            var norka = cskpd+'.'+cgiat+'.'+crek; 
            var kode_unik = document.getElementById('kode_unik').value;
            var nreal_rek  = angka(document.getElementById('nreal_rek').value);

            var total_pertama    = angka(document.getElementById('total_edit').value) ;    /*harga*volume hasil sebelum di edit*/
            var total_awal_edit  = angka(document.getElementById('rektotal_rinci').value); /*jumlah rincian dpo*/
            var total_rinci_edit = total_awal_edit-total_pertama+total;

            var n_sisakua   = angka(document.getElementById('total_sisa_kua_rek').value) ;
            var jenis = document.getElementById('jnskegi').value;


            if(nreal_rek>total_rinci_edit){
                alert("Nilai Kurang dari Realisasi");
                exit();
            }
            $("#dialog-modal-edit").dialog('close');
            $("#loading").show();
            $(function(){      
                 $.ajax({
                    type: 'POST',
                    data: ({
                        header:o,
                        kode:kode_edit, 
                        uraian:uraian_edit,
                        koefisien:koefisien,
                        volume1:volume1,
                        volume2:volume2,
                        volume3:volume3,
                        volume4:volume4,

                        satuan1:satuan1,
                        satuan2:satuan2,
                        satuan3:satuan3,
                        satuan4:satuan4,

                        pajak:pajak_persen,

                        harga:harga,
                        total:total,
                        unik:kode_unik,
                        no_trdrka:norka,
                        kd_kegiatan:cgiat, 
                        kd_rek5:crek,
                        spesifikasi:spesifikasi_edit,
                        kd_barang_edit:kd_barang_edit,
                        id_standar_harga_edit:id_standar_harga_edit
                    }),
                    dataType:"json",
                    url:"<?php echo base_url(); ?>index.php/anggaran_murni/update_rincian_dpo_sementara_ubah",
                    success:function(data){ 
                        $("#loading").hide();
                        $("#dialog-modal-edit").dialog('close');
                        $("#dg1").datagrid("reload");
                        section(crek);
                        
                        kosong();
                        get_nilai_kua();
                        get_nilai_kua_rek();
                        alert('Data Tersimpan');
                    }
                 });
                }); 
    }



    
     function copy_edit(){
            var kode_copy = document.getElementById('uraian_edit').value;
            var uraian_copy = document.getElementById('uraian_edit').value;
            var vol1_copy   = document.getElementById('vol1_edit').value;
            if ( vol1_copy == '' ){
                 volu1_copy = 1;
            }else{
                volu1_copy  = vol1_copy;
            }
            var sat1_copy = document.getElementById('sat1_edit').value;
            var vol2_copy = document.getElementById('vol2_edit').value;
            
            if ( vol2_copy == '' ){
                 volu2_copy = 1 ;
            }else{
                volu2_copy  = vol2_copy;
            }
            var sat2_copy = document.getElementById('sat2_edit').value;
            var vol3_copy = document.getElementById('vol3_edit').value;
            if ( vol3_copy == '' ){
                 volu3_copy = 1 ;
            }else{
                volu3_copy = vol3_copy ;
            }
            var sat3_copy   = document.getElementById('sat3_edit').value;
            var nilai_copy  = document.getElementById('harga_edit').value ;
            $("#dialog-modal-edit").dialog('close');
            kosong();
    }

     function handle(e){
        if(e.keyCode === 13){
            e.preventDefault(); // Ensure it is only this code that rusn

           cari();
        }
    }

    function paste_copy(){
            var uraian_copy = document.getElementById('uraian_edit').value;
            var vol1_copy   = document.getElementById('vol1_edit').value;
            if ( vol1_copy == '' ){
                 volu1_copy = 1;
            }else{
                volu1_copy  = vol1_copy;
            }
            var sat1_copy = document.getElementById('sat1_edit').value;
            var vol2_copy = document.getElementById('vol2_edit').value;
            
            if ( vol2_copy == '' ){
                 volu2_copy = 1 ;
            }else{
                volu2_copy  = vol2_copy;
            }
            var sat2_copy = document.getElementById('sat2_edit').value;
            var vol3_copy = document.getElementById('vol3_edit').value;
            if ( vol3_copy == '' ){
                 volu3_copy = 1 ;
            }else{
                volu3_copy = vol3_copy ;
            }
            var sat3_copy   = document.getElementById('sat3_edit').value;
            var nilai_copy  = document.getElementById('harga_edit').value ;
            $("#dialog-modal-edit").dialog('close');
            kosong();
            
            $("#kode_header").attr("value",kode_copy);
            $("#uraian").attr("value",uraian_copy);
            $("#vol1").attr("value",volu1_copy);
            $("#sat1").attr("value",sat1_copy);
            $("#vol2").attr("value",volu2_copy);
            $("#sat2").attr("value",sat2_copy);
            $("#vol3").attr("value",volu3_copy);
            $("#sat3").attr("value",sat3_copy);
            $("#harga").attr("value",nilai_copy);
            document.getElementById('harga').focus();
            
    }

    function hitung(){
        var pagu = angka(document.getElementById('nilairek').value);
        var a = angka(document.getElementById('nilaisumber').value);
        var b = angka(document.getElementById('nilaisumber2').value);
        var c = angka(document.getElementById('nilaisumber3').value); 
        var d = angka(document.getElementById('nilaisumber4').value);
        var total=a+b+c+d
        var hasil=pagu-hasil;
        if(hasil==0){
            $("#hasil").css("background-color", "white");
            document.getElementById('notif').innerHTML="";
        }else{
            $("#hasil").css("background-color", "yellow");
            document.getElementById('notif').innerHTML="";
        }
        $("#hasil").attr("value",number_format(pagu-total,2,'.',','));

    }

    function subheader(){
            var cgiat = $('#kdgiat').combogrid('getValue'); 
            var cskpd = $('#sskpd').combogrid('getValue');
            var crek  = document.getElementById('reke').value;
            var no_trdrka=cskpd+'.'+cgiat+'.'+crek;
            $('#kode_header').combogrid('clear');
                $(function(){      
                    $('#kode_header').combogrid({  
                    panelWidth :  770,  
                    idField    : 'uraian',  
                    textField  : 'uraian',  
                    mode       : 'remote',
                    url        : '<?php echo base_url(); ?>index.php/anggaran_murni/subheader',
                    queryParams: ({no_trdrka:no_trdrka}),
                    columns   : [[
                        {field:'uraian',title:'Header',width:770},  
                    ]]

                    });                       
                });          
    }

    function subheader_edit(){
            var cgiat = $('#kdgiat').combogrid('getValue'); 
            var cskpd = $('#sskpd').combogrid('getValue');
            var crek  = document.getElementById('reke').value;
            var no_trdrka=cskpd+'.'+cgiat+'.'+crek;
                $(function(){      
                    $('#kode_edit').combogrid({  
                    panelWidth :  770,  
                    idField    : 'uraian',  
                    textField  : 'uraian',  
                    mode       : 'remote',
                    url        : '<?php echo base_url(); ?>index.php/anggaran_murni/subheader',
                    queryParams: ({no_trdrka:no_trdrka}),
                    columns   : [[
                        {field:'uraian',title:'Header',width:770},  
                    ]],
                    onSelect :function(rowIndex,rowData){
                        $('#kode_edit2').attr('value',rowData.uraian);  
                    }

                    });                       
                });          
    }

    function cekbok(){

            var o       = document.getElementById('header_po').checked; 
                if ( o == false ){
                    $("#kod").show();
                    $("#kod2").show();
                    
                    }else{
                        $("#kod").hide();
                        $("#kod2").hide();
                        $('#kode_header').combogrid('setValue','');
                      
                    }
            var ok      = document.getElementById('header_po_edit').checked; 
                if ( ok == false ){
                    $("#kod_edit").show();
                        var uraian_edit = document.getElementById('uraian_edit').value;
                        var uraian = uraian_edit.replace("[-] ", "");
                        var uraian = uraian.replace("[-]", "");
                        $('#uraian_edit').attr('value',uraian);
                    
                    }else{

                        $("#kod_edit").hide();
                        $('#kode_edit').combogrid('setValue','');
                        $('#kode_edit2').attr('value','');
                      
                    }
    }

    function standardharga(kdrek) {

                $('#standart').combogrid('clear');
                $('#standart_edit').combogrid('clear');
                $(function(){      
                    $('#standart').combogrid({  
                    panelWidth :  1470,
                    panelHeight:  400,   
                    idField    : 'uraian',  
                    textField  : 'uraian',  
                    mode       : 'remote',
                    url        : '<?php echo base_url(); ?>index.php/anggaran_murni/standarhargasipd',
                    queryParams: ({kdrek:kdrek}),
                    columns   : [[
                        {field:'nama_standar_harga',title:'Standar Harga',width:300},
                        {field:'harga_satuan',title:'Harga',width:100, align:'right'},   
                        {field:'satuan',title:'Satuan',width:70}, 
                        {field:'spesifikasi',title:'Spesifikasi',width:1000}, 
                    ]],
                    onSelect :function(rowIndex,rowData){
                       $('#kd_barang').attr("value",rowData.kode_standar_harga);
                       $('#id_standar_harga').attr("value",rowData.id_standar_harga);
                       $('#uraian').attr("value",rowData.nama_standar_harga);
                       $('#sat1').attr("value",rowData.satuan);
                       $('#spesifikasi').attr("value",rowData.spesifikasi);
                       $('#harga').attr("value",rowData.harga_satuan);
                       $('#total_dpo').attr("value", rowData.harga_satuan);
                       document.getElementById('sat1').disabled=true;
                       document.getElementById('harga').disabled=true;
                       document.getElementById('spesifikasi').disabled=true;
                    }

                    });

                    $('#standart_edit').combogrid({  
                    panelWidth :  1470,
                    panelHeight:  400,   
                    idField    : 'uraian',  
                    textField  : 'uraian',  
                    mode       : 'remote',
                    url        : '<?php echo base_url(); ?>index.php/anggaran_murni/standarhargasipd',
                    queryParams: ({kdrek:kdrek}),
                    columns   : [[
                        {field:'nama_standar_harga',title:'Standar Harga',width:300},
                        {field:'harga_satuan',title:'Harga',width:100, align:'right'},   
                        {field:'satuan',title:'Satuan',width:70}, 
                        {field:'spesifikasi',title:'Spesifikasi',width:1000}, 
                    ]],
                    onSelect :function(rowIndex,rowData){
                       $('#kd_barang_edit').attr("value",rowData.kode_standar_harga);
                       $('#id_standar_harga_edit').attr("value",rowData.id_standar_harga);
                       $('#uraian_edit').attr("value",rowData.nama_standar_harga);
                       $('#sat1_edit').attr("value",rowData.satuan);
                       $('#spesifikasi_edit').attr("value",rowData.spesifikasi);
                       $('#harga_edit').attr("value",rowData.harga_satuan);
                       $('#total_dpo_edit').attr("value", rowData.harga_satuan);
                    }

                    });                       
                });  
    }

    function total_dpo() {
            var volume1 = document.getElementById('volume1').value;
            var volume2 = document.getElementById('volume2').value;
            var volume3 = document.getElementById('volume3').value;
            var volume4 = document.getElementById('volume4').value;
            var pajak   = document.getElementById('pajak').value/100;
            if(!volume1){
                volume1=0;
            }
            if(!volume2){
                volume2=1;
            }
            if(!volume3){
                volume3=1;
            }
            if(!volume4){
                volume4=1;
            }
 
        var harga=angka(document.getElementById('harga').value);
        let total_kotor=volume1*volume2*volume3*volume4*harga;
        let total_bersih=number_format((pajak*total_kotor)+total_kotor,2,'.',',');
        $('#total_dpo').attr("value", total_bersih);
        $('#hasil_pajak').attr("value", number_format(pajak*total_kotor,2,'.',','));
    }

    function total_dpo_edit() {
            let volume1 = document.getElementById('volume1_edit').value;
            let volume2 = document.getElementById('volume2_edit').value;
            let volume3 = document.getElementById('volume3_edit').value;
            let volume4 = document.getElementById('volume4_edit').value;
            let pajak   = document.getElementById('pajak_edit').value/100;
            if(!volume1){
                volume1=0;
            }
            if(!volume2){
                volume2=1;
            }
            if(!volume3){
                volume3=1;
            }
            if(!volume4){
                volume4=1;
            }
 
        let harga=angka(document.getElementById('harga_edit').value);
        let total_kotor=volume1*volume2*volume3*volume4*harga;
        let total_bersih=number_format((pajak*total_kotor)+total_kotor,2,'.',',');
        $('#total_dpo_edit').attr("value", total_bersih);
        $('#hasil_pajak_edit').attr("value", number_format(pajak*total_kotor,2,'.',','));
    }
</script>

<!-- untuk keperluan hapus data murni, apabila > 0 tidak dapat dihapus -->
<input hidden type="text" id="nilai_rekening_murni" name="nilai_rekening_murni"> 
<input hidden type="text" id="nilai_rekening_rincian_murni" name="nilai_rekening_rincian_murni">
<input hidden type="text" id="nilai_rekening_geser" name="nilai_rekening_geser"> 
<input hidden type="text" id="nilai_rekening_rincian_geser" name="nilai_rekening_rincian_geser">


<div id="proses" class="loader1"><div class="loader2"></div></div>

</head>
<body >

<div id="content" >
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <table style="border-collapse:collapse;border-style:hidden;" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
       <tr style="border-style:hidden;">
           <td width="10%">S K P D </td>
           <td>
                <input id="sskpd" name="sskpd" style="width:170px;border: 0;" />
                <input id="nmskpd" name="nmskpd" readonly="true" style="width: 600px; border:0; " />
           </td>
       </tr>
       <tr style="border-style:hidden;">
            <td width="10%">SUB KEGIATAN 
                <input type="hidden" id="giats" name="giats" style="width:20px;" /><br>&nbsp;
            </td>
            <td>
                <input id="kdgiat" name="kdgiat" style="width:170px;" />
                <input id="nmgiat" name="nmgiat" readonly="true" style="width:600px;border:0;background-color:transparent;color: black;" disabled="true"/>  
                <br><input hidden type="text" id="jnskegi" name="jnskegi" style="width:20px; border:0;" />
            </td>
                
       </tr>
   </table>

<div id="accordion">
<h2><a href="#" id="section1" onclick="javascript:validate_combo(); ">Rekening Anggaran Perubahan</a></h2>
   
   <div  style="height:700px;" >      
   
       <table border='1'  style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;width:100%;border-style: ridge;" >
       
       <tr style="border-bottom-style:hidden;">
       <td colspan="5" style="border-bottom-style:hidden;"></td>
       </tr>
       
       <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">REKENING</td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:770px;border-bottom-style:hidden;" colspan="4"><input id="kdrek5" name="kdrek5" style="width:211px;" />  
           <input id="nmrek5" name="nmrek5" readonly="true" style="width:570px;border:0;background-color:transparent;color:black;" disabled="true" />
       </td>
       </tr>

 
       <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">NILAI</td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;"><input id="nilairek" class="input" type="decimal" name="nilairek" style="width:200px;text-align:right;" onkeypress="javascript:enter(event.keyCode,'add');return(currencyFormat(this,',','.',event))" disabled/><input type="hidden" id="cek_kas" name="cek_kas" style="width:170px;text-align:right;"/></td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:600px;border-bottom-style:hidden;" colspan="3"></td>
       </tr>


           <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
               <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">SUMBER DANA</td>
               <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;border-right-style:hidden;"><input class="input" id="sdana1" name="sdana1" style="width:211px;"/></td>  
               <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;border-right-style:hidden;"><input class="input" id="sdana2" name="sdana2" style="width:211px"/></td>  
               <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;border-right-style:hidden;"><input class="input" id="sdana3" name="sdana3" style="width:211px"/></td> 
               <td colspan="2" style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:260px;border-bottom-style:hidden;"><input class="input" id="sdana4" name="sdana4" style="width:211px"/></td> 
           </tr>

           <tr hidden style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
               <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">SALDO S.DANA</td>
               <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;border-right-style:hidden;"><input class="input" id="saldosumber" name="saldosumber"  type="decimal" value='0.00' style="text-align:right; width:200px" disabled/></td>  
               <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;border-right-style:hidden;"><input class="input" id="saldosumber2" name="saldosumber2" type="decimal" value='0.00'  style="text-align:right; width:200px" disabled/></td>  
               <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;border-right-style:hidden;"><input class="input" id="saldosumber3" name="saldosumber3" type="decimal" value='0.00'  style="text-align:right; width:200px" disabled/></td> 
               <td colspan="2" style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:260px;border-bottom-style:hidden;"><input class="input" id="saldosumber4" type="decimal" name="saldosumber4" value='0.00' style="text-align:right; width:200px" disabled/></td> 
           </tr>


           <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
                <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">NILAI S.DANA</td>
                <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;border-right-style:hidden;"><input class="input"  id="nilaisumber" name="nilaisumber"  type="decimal" value='0.00' style="text-align:right; width:200px;" onkeyup="javascript:hitung();" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>  
                <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;border-right-style:hidden;"><input class="input"  id="nilaisumber2" name="nilaisumber2" type="decimal" value='0.00'  style="text-align:right; width:200px;" onkeyup="javascript:hitung();" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>  
                <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;border-right-style:hidden;"><input class="input"  id="nilaisumber3" name="nilaisumber3" type="decimal" value='0.00'  style="text-align:right; width:200px;" onkeyup="javascript:hitung();" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td> 
                <td colspan="2" style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:260px;border-bottom-style:hidden;"><input class="input"  id="nilaisumber4" type="decimal" name="nilaisumber4" value='0.00' style="text-align:right; width:200px;" onkeyup="javascript:hitung();" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td> 
           </tr>
           <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
                <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"></td>    
                <td colspan="4" style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:260px;border-bottom-style:hidden;"><input class="input" disabled id="hasil" type="decimal" name="hasil" value='0.00' style="text-align:right; width:200px; display: inline;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/> <label id="notif"></label></td> 
           </tr>      


       <tr style="border-bottom-style:hidden;">
       <td colspan="5" align="center" style="border-bottom-style:hidden;">
    <label id="pesen" ><label id="isi"></label></label>
       <button id="input" class="button" onclick="javascript:input()"><i class="fa fa-tambah"></i> Tambah</button>
       <button id="btl" class="button-cerah" onclick="javascript:btl()"><i class="fa fa-batal"> </i>Batal</button>
       <button id="delrek" class="button-merah" onclick="javascript:hapus()"><i class="fa fa-hapus"></i> Hapus</button>
       <button id="add" class="button-biru" onclick="javascript:tambah()"> <i class="fa fa-save"></i> Simpan Rekening</button>
       </td>
       </tr>
       <tr style="border-bottom-color:black;height:1px;" >
       <td colspan="5" style="border-bottom-color:black;height:1px;"></td>
       </tr>
       </table>
       
       <table id="dg" title="Input Rekening Rencana Kegiatan Anggaran Perubahan" style="width:950px; height:400px;" >          
       </table>  <br>
                                <div id="toolbarx">
                                    <table style="border-bottom: solid 0px #ddd; width:100%;height:10px;border-style:hidden;">
                                        <tr style="border-bottom: solid 0px">
                                            <td>
                                                &nbsp;&nbsp;<button class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:refresh()">Refresh Tabel</button>
                                                <button hidden class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section3()">Hukum</button>
                                                <button hidden id="reload" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:get_nilai_kua();">Refresh Nilai KUA</button>
                                            </td>
                                            <td align="right"> 
                                                <B>TOTAL</B>
                                                &nbsp;&nbsp;<input class="input" type="text" name="rektotal" id="rektotal" style="width:200px;text-align:right;" readonly="true"/><br>
                                                <B>REALISASI SUBKEGIATAN</B>
                                                &nbsp;&nbsp;<input class="input" type="text" name="n_realkeg" id="n_realkeg" style="width:200px;text-align:right;" readonly="true"/><br>
                                                <B>REALISASI REKENING</B>
                                                &nbsp;&nbsp;<input class="input" type="text" name="n_realrek" id="n_realrek" style="width:200px;text-align:right;" readonly="true"/><br>
                                                <B>TOTAL REALISASI</B>
                                                &nbsp;&nbsp;<input class="input" type="text" name="total_realisasi" id="total_realisasi" style="width:200px;text-align:right;" readonly="true"/>
                                            </td>
                                        </tr>
                                        <tr hidden style="border-bottom: solid 0px">
                                            <td colspan="2" align="right">
                                                <B>Plafond (KUA-PPAS)</B>
                                                &nbsp;&nbsp;<input class="right" type="text" name="nilai_kua" id="nilai_kua" style="width:200px;text-align:right;" readonly="true"/>
                                            </td>
                                        </tr>
                                        <tr hidden style="border-bottom: solid 0px">
                                            <td colspan="2" align="right">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Total Belanja</B>
                                                &nbsp;&nbsp;<input class="right" type="text" name="nilai_kua_ang" id="nilai_kua_ang" style="width:200px;text-align:right;" readonly="true"/>
                                            </td>
                                        </tr>
                                        <tr hidden style="border-bottom: solid 0px">
                                            <td colspan="2" align="right">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Sisa Plafond (KUA-PPAS)</B>
                                                &nbsp;&nbsp;<input class="right" type="text" name="sisa_nilai_kua" id="sisa_nilai_kua" style="width:200px;text-align:right;" readonly="true"/>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
    </div>
    
<h3><a href="#" id="section2" >Rincian Rekening</a></h3>
  
    <div> <div id="dpo">
        <div style="display: inline-block;">
      <div style="padding: 10px"><label> Header</label>
        <input type="checkbox" id="header_po" style="width: 40px;" onclick="javascript:cekbok();" onkeypress="javascript:enter(event.keyCode,'uraian');"/>
      </div>
      <div id="kod" style="padding: 10px">
          <label> Pilih header</label><br>
          <input type="text" class="input" id="kode_header" style="width: 410px;"/>
      </div>
      <div id="kod2" style="padding: 10px">
          <label> Standar Biaya</label><br>
          <input type="text" class="input" id="standart" style="width: 410px;"/>
          <input hidden type="text" id="kd_barang" style="width: 410px;" />
          <input hidden type="text" id="id_standar_harga" style="width: 410px;" />
      </div>
      <div style="padding: 10px">
          <label>Keterangan/ Uraian</label>
          <input type="text" class="input" id="uraian" style="width: 400px;" onkeypress="javascript:enter(event.keyCode,'spesifikasi');"/>
      </div>
      <div style="padding: 10px; display: inline-block;" >
          <label>Spesifikasi</label>
          <input  type="text" class="input" id="spesifikasi" style="width: 400px;" onkeypress="javascript:enter(event.keyCode,'vol1');"/>
      </div>
      </div>
      <div style="display: inline-block;  position: absolute;">
          <div style="display: block; padding-right: 10px; width: 100%">
              
                1. Untuk membuat header. Centang <input type="checkbox" name=""> header.<br>
                2. Setelah header tersimpan. Header akan tampil di option <b> Pilih Header </b><br>
                3. Untuk nyesisipkan rincian. Klik satu kali di baris tabel yang dituju. Lengkapi isian, kemudian Klik tombol <button class="button button-abu">Insert</button><br>
                4. Tombol <button class="button button-abu"> Kosongkan</button> untuk mengosongkan form isian.<br>
                5. Memilih standar harga akan mengunci form <b>spesifikasi, satuan dan harga</b>, hanya diperbolehkan merubah <b>volume</b>.<br>
                6. Selengkapnya <a href="#">klik disini</a><br>
                <br>
                <b><font color = "red">PERHATIAN!!! Diharapkan SKPD yang akan mengurangi angka di rincian rekening untuk tidak menghapus rekening dan rinciannya jika ada nilai di penyempurnaan/pergeseran sebelumnya. Terima Kasih</b></font>
            
          </div>
      </div>
      <div style="padding: 10px;">
        <div style="display: inline-block;">Pajak (%) <input class="input" type="number"  onkeyup="javascript:total_dpo();" onchange="javascript:total_dpo();" id="pajak" value="0" style="width: 50px;"/></div>
        <div style="display: inline-block;">Satuan <input class="input" type="text"  id="sat1"  style="width: 120px;"/></div>
        <div style="display: inline-block;">Harga<input class="input" type="text"  value="0"  id="harga" style="width:200px;; text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" /></div>
        
            <input hidden type="text" id="vol2" style="width: 90px;" onkeypress="javascript:enter(event.keyCode,'sat2');"/>
            <input hidden type="text" id="sat2" style="width: 90px;" onkeypress="javascript:enter(event.keyCode,'vol3');"/>
            <input hidden type="text" id="vol3" style="width: 90px;" onkeypress="javascript:enter(event.keyCode,'sat3');"/>
            <input hidden type="text" id="sat3" style="width: 90px;" onkeypress="javascript:enter(event.keyCode,'harga');"/>
      </div>

      <br>  
      <div style="padding: 10px; ">
          <div style="display: inline-block;">
            Koefisien (Perkalian) <br>
            <input type="number" class="input" name="volume1" id="volume1" onkeyup="javascript:total_dpo();" onchange="javascript:total_dpo();" step="0.01" min="0" style="display: inline-block;" placeholder="Volume 1">          <?php echo $this->rka_model->satuan('satuan1','onkeyup="javascript:total_dpo();"'); ?> <button class="button button-abu" onclick="javascript:$('#satuan1').val('').trigger('change');"> Clear </button><br>
            <input type="number" class="input" name="volume2" id="volume2" onkeyup="javascript:total_dpo();" onchange="javascript:total_dpo();" step="0.01" min="0" style="display: inline-block;" placeholder="Volume 2">          <?php echo $this->rka_model->satuan('satuan2','onkeyup="javascript:total_dpo();"'); ?> <button class="button button-abu" onclick="javascript:$('#satuan2').val('').trigger('change');"> Clear </button><br>
            <input type="number" class="input" name="volume3" id="volume3" onkeyup="javascript:total_dpo();" onchange="javascript:total_dpo();" step="0.01" min="0" style="display: inline-block;" placeholder="Volume 3">          <?php echo $this->rka_model->satuan('satuan3','onkeyup="javascript:total_dpo();"'); ?> <button class="button button-abu" onclick="javascript:$('#satuan3').val('').trigger('change');"> Clear </button><br>
            <input type="number" class="input" name="volume4" id="volume4" onkeyup="javascript:total_dpo();" onchange="javascript:total_dpo();" step="0.01" min="0" style="display: inline-block;" placeholder="Volume 4">          <?php echo $this->rka_model->satuan('satuan4','onkeyup="javascript:total_dpo();"'); ?> <button class="button button-abu" onclick="javascript:$('#satuan4').val('').trigger('change');"> Clear </button><br>
          </div>
          <div style="display: inline-block; margin-left: 40px; position: absolute;">
              Total + Pajak <br><input class="input" type="text"  value="0" disabled readonly id="total_dpo" style="width:200px; text-align: right;"/>
              Pajak<br><input class="input" type="text"  value="0" disabled readonly id="hasil_pajak" style="width:200px; text-align: right;"/>
              <br><button class="button button-biru" onclick="javascript:append_save();"> <i class="fa fa-save"></i> Simpan</button><button id="insert1" class="button"onclick="javascript:insert_row();"><i class="fa fa-tambah"></i> Insert</button><button class="button button-abu" onclick="javascript:kosong();">Kosongkan</button><button class="button-abu" onclick="javascript:section1()"><i class="fa fa-kiri"></i> Kembali</button>
          </div>
      </div>
            <input type="hidden" id="noid" style="width: 200px;" />
            <input type="hidden" id="nopo" style="width: 200px;" />
            <input type="hidden" id="reke" style="width: 200px;" />
            <input type="text" name="harga_proteksi" id="harga_proteksi" hidden>
            <input type="text" name="kdbarang" id="kdbarang" hidden>
       
        </div>
        
        <div></div>
      
        <table id="dg1"  style="width:1024px;height:500px;"> 
        </table>  
        <table border='0' style="width: 100%">
            <tr style="border-style: hidden;">

                <td style="border-style: hidden;">
                    <button id="del1" class="button-merah" iconCls="icon-remove" plain="true" onclick="javascript:hapus_rinci();"><i class="fa fa-hapus"></i> <b>Hapus</button>
                     <button class="button-abu" onclick="javascript:section1()">Kembali</button>

                 </td>

                 <td align='right' style="border-style: hidden;">
                    <B>Total</B>&nbsp;&nbsp;&nbsp;&nbsp;<input class="input" type="text" name="rektotal_rinci" id="rektotal_rinci"  style="width:200px" align="right" readonly="true" />  <br>
                    <b>REALISASI REKENING
                        <input class="input" type="text" name="nreal_rek" id="nreal_rek"  style="width:200px" align="right" readonly="true" /> 
                    </td>

                </tr>

                <tr>
                    <td style="border-style: hidden;">

                        <button hidden class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:get_nilai_kua()">REFRESH KUA</button>


                    </td>
                    <td hidden align='right' style="border-style: hidden;">
                        <B>Sisa KUA</B>&nbsp;&nbsp;&nbsp;&nbsp;<input class="right" type="text" name="sisa_kua2" id="sisa_kua2"  style="width:200px" align="right" readonly="true" />
                    </td>
                </tr>
                <tr hidden>
                    <td style="border-style: hidden;"></td>
                    <td align='right' style="border-style: hidden; width=50%">
                        <B>Ang. Rekening</B>&nbsp;&nbsp;<input class="right" type="text" name="nilai_kua_rek" id="nilai_kua_rek"  style="width:200px" align="right" readonly="true" />

                    </td>
                </tr>
                <tr hidden>
                    <td style="border-style: hidden;"></td>
                    <td align='right' style="border-style: hidden; width=50%">
                        <B>Sisa KUA + Rek.</B>&nbsp;&nbsp;<input class="right" type="text" name="total_sisa_kua_rek" id="total_sisa_kua_rek"  style="width:200px" align="right" readonly="true" />

                    </td>
                </tr>
            </table>
        
        </table>
    </div>   
<h3><a href="#" id="section3" onclick="javascript:load_dhukum();" >Dasar Hukum</a></h3>

    <div>
        
        <table id="dg2" title="Input Dasar Hukum Per Kegiatan" style="width:1000px;height:400px;" >  
        
        </table>  <br>
        <div id="toolbar1xa">
            <button id="save2" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_dhukum();">Simpan</button>
            <button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:section1()">Kembali</button>
        </div>

    </div>   

<h3><a href="#" id="section4" onclick="javascript:load_detail_keg();" >Detail KEGIATAN</a></h3>
    <div>
    <fieldset style="width:100%;height:570px;border-color:black;border-style:hidden; border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"> 
    <br />
<fieldset>
    <legend>DETAIL PROGRAM</legend>
    <table align="center" border='0' width="100%" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
    
     <tr style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;">
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>Sasaran Program</b></td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><textarea id="sasaran_program" name="sasaran_program" rows='2' cols="40"  ></textarea></td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" >&nbsp;&nbsp;</td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>Capaian Program</b></td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><textarea id="capaian_program" name="capaian_program" rows='2' cols="40" > </textarea></td>

    </tr>

</table>
</fieldset>
<br>
<fieldset>
    <legend>DETAIL KEGIATAN</legend>
<table align="center" border='0' width="100%" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
     
    <tr style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >

        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b></b></td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;"></td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" >&nbsp;&nbsp;</td>
        
    </tr>
    
    <tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
        <td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><b>Indikator Kegiatan</b></td>
        <td colspan="2" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" > <b> Tolak Ukur</b></td>
        <td colspan="2" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" > <b> Capaian Kinerja</b></td>
    </tr>
    <tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;">
        <td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><b>Capaian</b></td>
        <td colspan="2" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><textarea id="tu_capai" name="tu_capai" style="width: 254px; height: 57px;" ></textarea></td>
        <td hidden style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="tu_capai_p" name="tu_capai_p" rows='2' cols="20" disabled="true"> </textarea></td>
        <td colspan="2" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><textarea id="tk_capai" name="tk_capai" style="width: 254px; height: 57px;"  > </textarea></td>
        <td hidden style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="tk_capai_p" name="tk_capai_p" rows='2' cols="20" disabled="true"> </textarea></td>
    </tr>
    <tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
        <td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><b>Masukan</b></td>
        <td colspan="2" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><textarea id="tu_mas" name="tu_mas" style="width: 254px; height: 57px;"  > </textarea></td>
        <td hidden style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="tu_mas_p" name="tu_mas_p" style="width: 254px; height: 57px;"  disabled="true"> </textarea></td>
        <td colspan="2" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><textarea readonly id="tk_mas" name="tk_mas" style="width: 254px; height: 57px;"  > </textarea></td>
        <td hidden style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="tk_mas_p" name="tk_mas_p" style="width: 254px; height: 57px;"  disabled="true"> </textarea></td>
    </tr>
    <tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
        <td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><b>Keluaran</b></td>
        <td colspan="2" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><textarea id="tu_kel" name="tu_kel" style="width: 254px; height: 57px;" > </textarea></td>
        <td hidden style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="tu_kel_p" name="tu_kel_p" style="width: 254px; height: 57px;" disabled="true"> </textarea></td>
        <td colspan="2" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" ><textarea id="tk_kel" name="tk_kel" style="width: 254px; height: 57px;" > </textarea></td>
        <td hidden style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="tk_kel_p" name="tk_kel_p" style="width: 254px; height: 57px;" disabled="true"> </textarea></td>
    </tr>
    <tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
        <td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><b>Hasil</b></td>
        <td colspan="2" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><textarea id="tu_has" name="tu_has" style="width: 254px; height: 57px;" > </textarea></td>
        <td hidden style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="tu_has_p" name="tu_has_p" style="width: 254px; height: 57px;" disabled="true"> </textarea></td>
        <td colspan="2" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><textarea id="tk_has" name="tk_has" style="width: 254px; height: 57px;"  > </textarea></td>
        <td hidden style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="tk_has_p" name="tk_has_p" style="width: 254px; height: 57px;" disabled="true"> </textarea></td>
    </tr>
    <tr style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;">
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>Kelompok Sasaran Kegiatan</b></td>
        <td colspan="5" style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><textarea id="kel_sasaran_kegiatan" name="kel_sasaran_kegiatan" style="width: 652px; height: 53px;"></textarea></td>

    </tr>

</table>
</fieldset>
<br>
<fieldset>
    <legend>DETAIL SUB KEGIATAN</legend>
<table align="center" border='0' width="100%" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
    <tr style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;">
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>Lokasi</b></td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><textarea id="lokasi" name="lokasi" rows='2' cols="40"></textarea></td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>Waktu</b></td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><textarea id="waktu_giat" name="waktu_giat" rows='2' cols="20" ></textarea></td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><textarea id="waktu_giat2" name="waktu_giat2" rows='2' cols="20" > </textarea></td>

    </tr>
    <tr style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;">
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>Keluaran Sub Kegiatan</b></td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><textarea id="sub_keluaran" name="sub_keluaran" rows='2' cols="40"></textarea></td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>Keterangan</b></td>
        <td colspan="2" style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><textarea id="keterangan" name="keterangan" rows='2' cols="40" > </textarea></td>

    </tr>
     <tr style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;">
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" >
            <b>Anggaran Tahun Lalu</b>
        </td>
        <td  style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" > 
            <input type="text" id="ang_lalu" style="width:190px;text-align: right;"  onkeypress="javascript:return(currencyFormat(this,',','.',event))"/>
        </td>
        <td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>PPTK</b></td>
        <td colspan="2" style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" > <?php echo $this->rka_model->combo_ttd(); ?></td>

    </tr> 
    </table>
</fieldset>
<br>
        <div id="toolbar1xa" >
            <button id="save4" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_det_keg()">Simpan Indikator</button>
            <button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:section1()">Kembali</button>
        </div>
        <br>
        <br>
        <br>
    </fieldset>    
    </div>   
</div>


<div id="dialog-modal" title="">

    <p class="validateTips"></p> 
    <fieldset>
        
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%"></td>               
        <td width="5%"></td>
        <td> <input type="text" name="txtcari" id="txtcari" onkeypress="handle(event)" /><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a><font color="red"> Masukkan kata kunci barang/jasa terlebih dahulu!</font></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg_std" title="Pilih Standard" style="width:930px;height:300px;">  
        </table>
        </td>
        </tr>
    </table> 


      
    
    <table style="width:930px;height:20px;" border="0">
        <tr>
        <td align="center" colspan='2'>&nbsp;</td>
        </tr>

        <tr>
        <td align="center" >
        <button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:pilih_std();">Pilih</button>
        <button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:kembali_std();">Kembali</button></td>
        </tr>
    </table>
    
    </fieldset>  
</div>
<div id="dialog-keluaran" title="Tambah Master SubKeluaran">
    <p class="validateTips"></p>
    <fieldset><legend>Tambah Master SubKeluaran</legend>
    <table border="0" align="">
        <tr>
            <td>Lokasi</td>
            <td> <textarea type="text" name="nm_lokasi" id="nm_lokasi"></textarea></td>
        </tr>
        <tr>
            <td>Sub Keluaran</td>
            <td> <textarea type="text" name="subkeluar" id="subkeluar"></textarea></td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button class="button" onclick="javascript:lokasi_simpen();"style="cursor: pointer;">Simpan Sub Keluaran</button></td>
        </tr>
    </table>
    </fieldset>
    <br>
    <fieldset><legend>Edit/Hapus Master SubKeluaran</legend>
    <table border="0" align="">
        <tr>
            <td>Pilih yang akan di edit/hapus</td>
            <td>&nbsp;<input style="width: 175px" type="text" name="kdlokasi_edit_emaster" id="kdlokasi_edit_emaster"></td>
        </tr>
        <tr>
            <td>Lokasi</td>
            <td> <textarea type="text" name="nm_lokasi_emaster" id="nm_lokasi_emaster"></textarea></td>
        </tr>
        <tr>
            <td>Sub Keluaran</td>
            <td> <textarea type="text" name="subkeluar_emaster" id="subkeluar_emaster"></textarea></td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button class="button" onclick="javascript:edit_lokasi_master('edit');"style="cursor: pointer;">Simpan</button>
                <button class="button" onclick="javascript:edit_lokasi_master('hapus');"style="cursor: pointer; background-color: red">Hapus</button>
            </td>
        </tr>
    </table>
    </fieldset>
</div>


<div id="dialog-modal-edit" title="Edit Rincian Rekening">

    <p class="validateTips"></p> 
    
    <fieldset>   

  <div>
      <div style="padding: 10px"><label> Header</label>
         <input type="checkbox" id="header_po_edit" onclick="javascript:cekbok();"/> 
      </div>
      <div id="kod_edit" style="padding: 10px">
          <label> Pilih header</label><br>
          <input hidden type="text" id="kode_edit2" name="kode_edit2"> <input type="text" style="display: inline; width: 300px" id="kode_edit"  onkeypress="javascript:enter(event.keyCode,'uraian');"/>
      </div>
      <div id="kod2" style="padding: 10px">
          <label> Standar Biaya</label><br>
          <input type="text" class="input" id="standart_edit" style="width: 410px;" />
      </div>

          <input hidden type="text" id="kd_barang_edit" style="width: 410px;" />
          <input hidden type="text" id="id_standar_harga_edit" style="width: 410px;" />

      <div style="padding: 10px">
          <label>Uraian</label>
          <input type="text" id="uraian_edit" class="input" style="width: 400px;" />
      </div>
      <div style="padding: 10px" >
          <label>Spesifikasi</label>
          <input type="text" class="input" id="spesifikasi_edit" style="width: 400px;" />
      </div>
      <div style="padding: 10px;">
        <div style="display: inline-block;">Pajak (%)<input type="number" id="pajak_edit" onkeyup="javascript:total_dpo_edit();" onchange="javascript:total_dpo_edit();" style="width: 90px;"class="input" /></div>
        <div style="display: inline-block;">Satuan <input  type="text" id="sat1_edit" style="width: 104px;" class="input" /></div>
        <div style="display: inline-block;">Harga <input  class="input" type="text" id="harga_edit" onkeyup="javascript:total_dpo_edit();" onchange="javascript:total_dpo_edit();" style="width: 175px; text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"  />
            <input type="text" name="harga_proteksi_edit" id="harga_proteksi_edit" hidden></div>
        
      </div>
      <div style="padding: 10px; ">
          <div style="display: inline-block;">
            Koefisien (Perkalian) <br>
            <input type="number" class="input" name="volume1_edit" id="volume1_edit" step="0.01" min="0" onkeyup="javascript:total_dpo_edit();" onchange="javascript:total_dpo_edit();" style="display: inline-block;" placeholder="Volume 1">          <?php echo $this->rka_model->satuan('satuan1_edit',''); ?> <button class="button button-abu" onclick="javascript:$('#satuan1_edit').val('').trigger('change');"> Clear </button><br>
            <input type="number" class="input" name="volume2_edit" id="volume2_edit" step="0.01" min="0" onkeyup="javascript:total_dpo_edit();" onchange="javascript:total_dpo_edit();" style="display: inline-block;" placeholder="Volume 2">          <?php echo $this->rka_model->satuan('satuan2_edit',''); ?> <button class="button button-abu" onclick="javascript:$('#satuan2_edit').val('').trigger('change');"> Clear </button><br>
            <input type="number" class="input" name="volume3_edit" id="volume3_edit" step="0.01" min="0" onkeyup="javascript:total_dpo_edit();" onchange="javascript:total_dpo_edit();" style="display: inline-block;" placeholder="Volume 3">          <?php echo $this->rka_model->satuan('satuan3_edit',''); ?> <button class="button button-abu" onclick="javascript:$('#satuan3_edit').val('').trigger('change');"> Clear </button><br>
            <input type="number" class="input" name="volume4_edit" id="volume4_edit" step="0.01" min="0" onkeyup="javascript:total_dpo_edit();" onchange="javascript:total_dpo_edit();" style="display: inline-block;" placeholder="Volume 4">          <?php echo $this->rka_model->satuan('satuan4_edit',''); ?> <button class="button button-abu" onclick="javascript:$('#satuan4_edit').val('').trigger('change');"> Clear </button><br>
          </div><br>
      Total + Pajak
      <input type="text" id="total_dpo_edit" class="input" readonly><br>
      Pajak
      <input type="text" id="hasil_pajak_edit" class="input" readonly>
      </div>

    <input type="text" hidden name="kode_unik" id="kode_unik">
    <input type="hidden" id="total_edit" style="width: 200px;" /> <!-- nilai harga dikali volume -->
    <input type="text" hidden name="nomor_insert" id="nomor_insert">

    <table border='0' style="width:800px;">



        <tr>
        <td colspan="8" align="center">
        <button class="button" id="pil_edit" onclick="javascript:edit_rincian_po();">Simpan</button> &nbsp; &nbsp; &nbsp;
        <!-- <button class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:copy_edit();">Copy</button> -->
        </td>
        </tr>
        
    </table>
    </fieldset>  
</div>


<div id="loading" align="center"> 
<div align="center" class="loader1"><div class="loader2"></div></div>
</div>
</div>      
</body>

<div id="panduan" title="Panduan">
    <img src="<?php echo base_url('image'); ?>/panduan.jpg" alt="Italian Trulli">

</div>
</html>