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
    <style>    
    #tagih {
        position: relative;
        width: 500px;
        height: 70px;
        padding: 0.4em;
    }  
    
    </style>
    <script type="text/javascript">
    
    var kode     = '';
    var giat     = '';
    var jenis    = '';
    var nomor    = '';
    var cid      = 0;
    var lcstatus = '';
    var skpdrek  = '';
                      
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 700,
                width: 1050,
                modal: true,
                autoOpen:false                
            });              
            $("#tagih").hide();
            get_skpd();
            get_tahun();
        });    
          
     $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/penagihan/load_penagihanskpd',
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
            width:50},
            {field:'tgl_bukti',
            title:'Tanggal',
            width:30},
            {field:'nm_skpd',
            title:'Nama SKPD',
            width:100,
            align:"left"},
            {field:'ket',
            title:'Keterangan',
            width:100,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor = rowData.no_bukti;
          tgl   = rowData.tgl_bukti;
          kode  = rowData.kd_skpd;
          nama  = rowData.nm_skpd;
          ket   = rowData.ket;
          ket_bast   = rowData.ket_bast;          
          jns   = rowData.jns_beban; 
          tot   = rowData.total;
          notagih=rowData.no_tagih;
          tgltagih=rowData.tgl_tagih;
          ststagih=rowData.sts_tagih;
          sts=rowData.status;
          jenis=rowData.jenis;
          kontrak=rowData.kontrak;
          get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,sts,jenis,kontrak,ket_bast);   
          load_detail();  
              load_tot_tagih();
        },
        onDblClickRow:function(rowIndex,rowData){         
            section2(); 
            lcstatus = 'edit';
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
                    nilx = rowData.nilai;
            },                                                     
            columns:[[
            {field:'no_bukti',
            title:'No Bukti',           
            hidden:"true"},
            {field:'no_sp2d',
            title:'No SP2D',            
            hidden:"true"},
            {field:'kd_sub_kegiatan',
            title:'Kegiatan',
            width:60},
            {field:'nm_sub_kegiatan',
            title:'Nama Kegiatan',          
            hidden:"true"},
            {field:'kd_rek6',
            title:'Kode Rekening',
            width:30},
            {field:'nm_rek6',
            title:'Nama Rekening',
            width:100,
            align:"left"},
            {field:'nilai',
            title:'Nilai',
            width:70,
            align:"right"},
            {field:'lalu',
            title:'Sudah Dibayarkan',
            align:"right",
            width:30,
            hidden:'true'},
            {field:'sp2d',
            title:'SP2D Non UP',
            align:"right",
            width:30,
            hidden:'true'},
            {field:'anggaran',
            title:'Anggaran',
            align:"right",
            width:30,
            hidden:'true'},
            {field:'kd_rek',
            title:'Rekening',
            width:30},
            {field:'sumber',
            title:'Sumber',
            width:80,align:"center"}
            ]]
        });    
                
    $('#dg2').edatagrid({                       
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"true",
        loadMsg:"Tunggu Sebentar....!!",              
        nowrap:"false",
        onSelect:function(rowIndex,rowData){                    
            cidx = rowIndex;            
        },                 
        columns:[[
            {field:'hapus',
            title:'Hapus',
            width:11,
            align:"center",
            formatter:function(value,rec){                                                                       
                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';                  
                }                
            },
            {field:'no_bukti',
            title:'No Bukti',           
            hidden:"true",
            width:30},
            {field:'no_sp2d',
            title:'No SP2D',
            width:40,
            hidden:"true"},
            {field:'kd_sub_kegiatan',
            title:'Kegiatan',
            width:38},
            {field:'nm_sub_kegiatan',
            title:'Nama Kegiatan',          
            hidden:"true",
            width:30},
            {field:'kd_rek6',
            title:'REK LO',
            width:20,
            align:'center'},
            {field:'kd_rek',
            title:'REK 13',
            width:20,
            align:'center'},            
            {field:'nm_rek6',
            title:'Nama Rekening',
            align:"left",
            width:45},
            {field:'nilai',
            title:'Rupiah',
            align:"right",
            width:30},
            {field:'lalu',
            title:'Sudah Dibayarkan',
            align:"right",
            width:30,
            hidden:"true"},
            {field:'sp2d',
            title:'SP2D Non UP',
            align:"right",
            width:30,
            hidden:"true"},
            {field:'anggaran',
            title:'Anggaran',
            align:"right",
            width:30},
            {field:'sumber',
            title:'Sumber',
            align:"center",
            width:20}
            ]]        
      });
        
        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();    
                return y+'-'+m+'-'+d;
           }, onSelect: function(date){
                cek_status_ang();
            }
        });
        
        $('#tgltagih').datebox({  
                required:true,
                formatter :function(date){
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();    
                    return y+'-'+m+'-'+d;
                }
        });
            
             $('#giat').combogrid({  
           panelWidth:700,  
           idField:'kd_subkegiatan',  
           textField:'kd_subkegiatan',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd_sgiat/'+giat,
           queryParams:({kd:skpd}),             
           columns:[[  
               {field:'kd_subkegiatan',title:'Kode SubKegiatan',width:140},  
               {field:'nm_subkegiatan',title:'Nama SubKegiatan',width:700}
           ]]
        }); 
            
           $('#kgiat').combogrid({  
           panelWidth:700,  
           idField:'kd_sub_kegiatan',  
           textField:'kd_sub_kegiatan',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/penagihan/load_trskpd_giat',
           queryParams:({kd:skpd}),             
           columns:[[  
               {field:'kd_sub_kegiatan',title:'Kode Kegiatan',width:140},  
               {field:'nm_sub_kegiatan',title:'Nama Kegiatan',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               idxGiat = rowIndex;               
               giat = rowData.kd_sub_kegiatan;
               nm_giat = rowData.nm_sub_kegiatan;
               $("#knmkegiatan").attr("value",rowData.nm_sub_kegiatan);
               $("#giat").combogrid("setValue","");
               // validate_sgiat(giat);               
               $('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/penagihan/load_rek_penagihan',
                                   queryParams:({giat:giat,kd:skpd})
                                   });
               load_total_spd(giat);
               load_total_trans(giat);
               
               //$("#rek").combogrid('disable');
             $("#rek").combogrid('enable');
           }
           
        });

         function validate_sgiat(giat){                                                                 
           $('#giat').combogrid({  
           panelWidth:700,  
           idField:'kd_subkegiatan',  
           textField:'kd_subkegiatan',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd_sgiat/'+giat,
           queryParams:({kd:skpd}),             
           columns:[[  
               {field:'kd_sub_kegiatan',title:'Kode Kegiatan',width:140},  
               {field:'nm_sub_kegiatan',title:'Nama Kegiatan',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               idxGiat = rowIndex;               
               giat = rowData.kd_sub_kegiatan;
               nm_giat = rowData.nm_sub_kegiatan;
               $("#nmkegiatan").attr("value",rowData.nm_sub_kegiatan);
               var nobukti = document.getElementById('nomor').value;
               var kode = document.getElementById('skpd').value;
               var frek = '';
               kosong2();
               $('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/penagihan/load_rek_penagihan',
                                   queryParams:({no:nobukti,
                                                 giat:giat,
                                                 kd:kode,
                                                 rek:frek})
                                   });
               document.getElementById('sisa_spd').value = 0;  
               document.getElementById('nilai_sisa_spd').value = 0;                  
               //$('#sisa_spd').attr('value',number_format(0,2,'.',','));                   
               //$("#nilai_sisa_spd").attr("Value",number_format(0,2,'.',','));               
               load_total_spd(giat);
               load_total_trans(giat);             
               
               //$("#rek").combogrid('disable');
             $("#rek").combogrid('enable');
           }
           
        });
        }     
        
        $('#kontrak').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/penagihan/kontrak',  
                    idField:'kontrak',  
                    textField:'kontrak',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kontrak',title:'Kontrak',width:40} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //$("#kode").attr("value",rowData.kode);
                    $("#kontrak").attr("value",rowData.kontrak);    
                    }   
        });        
        
        $('#rek').combogrid({  
           panelWidth:750,  
           idField:'kd_rek6',  
           textField:'kd_rek6',  
           mode:'remote',                                   
           columns:[[  
               {field:'kd_rek',title:'Kode Rekening Ang.',width:70,align:'center'},  
               {field:'kd_rek6',title:'Kode Rekening',width:70,align:'center'},  
               {field:'nm_rek6',title:'Nama Rekening',width:200},
               {field:'lalu',title:'Lalu',width:120,align:'right'},
               {field:'sp2d',title:'SP2D',width:120,align:'right'},
               {field:'anggaran_ubah',title:'Anggaran',width:120,align:'right'}
           ]],
           onSelect:function(rowIndex,rowData){
                var anggaran = rowData.anggaran;
                var anggaran_semp = rowData.anggaran_semp;
                var anggaran_ubah = rowData.anggaran_ubah;
                var lalu = rowData.lalu;
                sisa = anggaran-lalu;
                sisa_semp = anggaran_semp-lalu;
                sisa_ubah = anggaran_ubah-lalu;
                skpdrek = rowData.kd_rek;
                $("#rek1").attr("value",rowData.kd_rek);
                $("#nmrek").attr("value",rowData.nm_rek6);                               
                
                $("#rek_nilai_ang").attr("Value",number_format(anggaran,2,'.',','));
                $("#rek_nilai_ang_semp").attr("Value",number_format(anggaran_semp,2,'.',','));
                $("#rek_nilai_ang_ubah").attr("Value",number_format(anggaran_ubah,2,'.',','));
                
                $("#rek_nilai_spp").attr("Value",number_format(lalu,2,'.',','));                
                $("#rek_nilai_spp_semp").attr("Value",number_format(lalu,2,'.',','));               
                $("#rek_nilai_spp_ubah").attr("Value",number_format(lalu,2,'.',','));
                  
                $("#rek_nilai_sisa").attr("Value",number_format(sisa,2,'.',','));
                $("#rek_nilai_sisa_semp").attr("Value",number_format(sisa_semp,2,'.',','));
                $("#rek_nilai_sisa_ubah").attr("Value",number_format(sisa_ubah,2,'.',','));
                //
                
                total_sisa_spd();
                //load_realisasi_rek(skpdrek,giat);
                load_sumber_dn();
           }
        });      
        
        
        $('#sumber_dn').combogrid({  
           panelWidth:200,  
           idField:'sumber_dana',  
           textField:'sumber_dana',  
           mode:'remote',                        
           columns:[[  
               {field:'sumber_dana',title:'Sumber Dana',width:180}
           ]] 
        });

                          
    }); 
    
    function load_total_spd(giat){
        var kode = document.getElementById('skpd').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/penagihan/load_total_spd",
            dataType:"json",
            data: ({giat:giat,kode:kode}),
            success:function(data){ 
                $.each(data, function(i,n){
                    //$("#tot_spd").attr("value",n['total_spd']);
                    $("#total_spd").attr("Value",n['total_spd']);              
                });
            }
         });
        });
    }
    
    function load_sumber_dn(){
       var kode = document.getElementById('skpd').value;
       var kode_keg = $('#kgiat').combogrid('getValue') ;
       var koderek = document.getElementById('rek1').value;
       
       $(function(){ 
       $('#sumber_dn').combogrid({  
           url:'<?php echo base_url(); ?>index.php/penagihan/load_reksumber_dana',
           queryParams:({giat:kode_keg,kd:kode,rek:koderek}), 
           panelWidth:200,  
           idField:'sumber_dana',  
           textField:'sumber_dana',  
           mode:'remote',                        
           columns:[[  
               {field:'sumber_dana',title:'Sumber Dana',width:180}
           ]] ,
           onSelect:function(rowIndex,rowData){
              var parsumber = rowData.sumber_dana;    
              var vnilaidana = rowData.nilaidana;
              var vnilaidana_semp = rowData.nilaidana_semp;
              var vnilaidana_ubah = rowData.nilaidana_ubah;                                                                               
              var lalu_ubahspp = rowData.nilaidana_lalu;//angka(document.getElementById('rek_nilai_spp_ubah').value);                 
              
              $("#rek_nilai_ang_dana").attr("Value",number_format(vnilaidana,2,'.',','));
              $("#rek_nilai_spp_dana").attr("Value",number_format(lalu_ubahspp,2,'.',','));
              $("#rek_nilai_ang_semp_dana").attr("Value",number_format(vnilaidana_semp,2,'.',','));
              $("#rek_nilai_spp_semp_dana").attr("Value",number_format(lalu_ubahspp,2,'.',','));
                   $("#rek_nilai_ang_ubah_dana").attr("Value",number_format(vnilaidana_ubah,2,'.',','));
              $("#rek_nilai_spp_ubah_dana").attr("Value",number_format(lalu_ubahspp,2,'.',','));  
              
              var sisa_nil_dana = vnilaidana-lalu_ubahspp;
              var sisa_nil_semp_dana = vnilaidana_semp-lalu_ubahspp;
              var sisa_nil_ubah_dana = vnilaidana_ubah-lalu_ubahspp;
                              
              $("#rek_nilai_sisa_dana").attr("Value",number_format(sisa_nil_dana,2,'.',','));
              $("#rek_nilai_sisa_semp_dana").attr("Value",number_format(sisa_nil_semp_dana,2,'.',','));
              $("#rek_nilai_sisa_ubah_dana").attr("Value",number_format(sisa_nil_ubah_dana,2,'.',','));   
              document.getElementById('nilai').select();
           }
        }); });
    }
    
    function load_realisasi_rek(skpdrek,giat){
        var kode = document.getElementById('skpd').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/jumlah_ang_spp_tagih",
            dataType:"json",
            data: ({kegiatan:giat,kd_skpd:kode,kdrek5:skpdrek,no_spp:'XxXxX'}),
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#nilai_real").attr("value",n['nilai_real']);
                });
            }
         });
        });
    }
    
    //perbaikan
    function load_total_trans(giat){
        var no_simpan = document.getElementById('no_simpan').value;  
        var kode = document.getElementById('skpd').value;
        
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/penagihan/load_total_trans",
            dataType:"json",
            data: ({giat:giat,kode:kode,no_simpan:no_simpan}),
            success:function(data){ 
                $.each(data, function(i,n){
                    //$("#tot_trans").attr("value",n['total']);
                    $("#nilai_spd_lalu").attr("Value",n['total']);
                });
             $("#rek").combogrid('enable');                          
            }
         });
        });
    }
    
    function total_sisa_spd(){ 
        var tot_spd   = angka(document.getElementById('total_spd').value);  
        var tot_trans = angka(document.getElementById('nilai_spd_lalu').value);               
        
        totsisa = tot_spd-tot_trans;
       //$('#sisa_spd').attr('value',number_format(totsisa,2,'.',','));       
       //$("#total_spd").attr("Value",number_format(tot_spd,2,'.',','));
       //$("#nilai_spd_lalu").attr("Value",number_format(tot_trans,2,'.',','));
       $("#nilai_sisa_spd").attr("Value",number_format(totsisa,2,'.',','));
       
    }
    
    function get_skpd()
        {
        
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/penagihan/config_skpd',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                                        $("#skpd").attr("value",data.kd_skpd);
                                        $("#nmskpd").attr("value",data.nm_skpd);
                                        skpd = data.kd_skpd;
                                         kegia();                 
                                      }                                     
            });  
        }       
    function get_tahun() {
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/penagihan/config_tahun',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                    tahun_anggaran = data;
                    }                                     
            });
             
        }

    function cek_status_ang(){
        var tgl_cek = $('#tanggal').datebox('getValue');      
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/penagihan/cek_status_ang',
                data: ({tgl_cek:tgl_cek}),
                type: "POST",
                dataType:"json",                         
                success:function(data){
                $("#status_ang").attr("value",data.status_ang);
            }  
            });
        }
    
    function kegia(){
      //$('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd',queryParams:({kd:skpd,jenis:'52'})});  
      $('#kgiat').combogrid({url:'<?php echo base_url(); ?>index.php/penagihan/load_trskpd_giat',queryParams:({kd:skpd,jenis:'5'})});
    }        
               
    
    function hapus_detail(){
        var rows = $('#dg2').edatagrid('getSelected');
        cgiat    = rows.kd_subkegiatan;
        crek     = rows.kd_rek6;
        cnil     = rows.nilai;
        var idx = $('#dg2').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Kegiatan : '+cgiat+' Rekening : '+crek+' Nilai : '+cnil);
        if (tny==true){
            $('#dg2').edatagrid('deleteRow',idx);
            $('#dg1').edatagrid('deleteRow',idx);
            total = angka(document.getElementById('total1').value) - angka(cnil);            
            $('#total1').attr('value',number_format(total,2,'.',','));    
            $('#total').attr('value',number_format(total,2,'.',','));
            kosong2();
            
        } 
    }
    
    function load_tot_tagih(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({no_tagih:nomor}),
            url:"<?php echo base_url(); ?>index.php/penagihan/load_tot_tagih",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#total").attr("value",n['total']);
                });
            }
         });
        });
    }
    
    function load_detail(){        
        var kk    = document.getElementById("nomor").value;
        var ctgl  = $('#tanggal').datebox('getValue');
        var cskpd = document.getElementById("skpd").value;            
           
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/penagihan/load_dtagih',
                data     : ({no:kk}),
                dataType : "json",
                success  : function(data){                                          
                    $.each(data,function(i,n){                                    
                    no        = n['no_bukti'];
                    nosp2d    = n['no_sp2d'];                                                                    
                    //giat      = n['kd_subkegiatan'];
                    //nmgiat    = n['nm_subkegiatan'];
                    kgiat      = n['kd_sub_kegiatan'];
                    knmgiat    = n['nm_sub_kegiatan'];
                    rek5      = n['kd_rek6'];
                    rek       = n['kd_rek'];
                    nmrek5    = n['nm_rek6'];
                    nil       = number_format(n['nilai'],2,'.',',');
                    clalu     = number_format(n['lalu'],2,'.',',');
                    csp2d     = number_format(n['sp2d'],2,'.',',');
                    canggaran = number_format(n['anggaran'],2,'.',',');    


                    cdana     = n['sumber'];                                                                                  
                    $('#dg1').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_rek6:rek5,nm_rek6:nmrek5,nilai:nil,lalu:clalu,sp2d:csp2d,anggaran:canggaran,kd_rek:rek,kd_sub_kegiatan:kgiat,nm_sub_kegiatan:knmgiat,sumber:cdana});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });                
           set_grid();                                                  
    }
    
    
    
        function set_grid(){
        $('#dg1').edatagrid({                                                                   
            columns:[[
                {field:'no_bukti',
                title:'No Bukti',               
                hidden:"true"},
                {field:'no_sp2d',
                title:'No SP2D',                
                hidden:"true"},
                {field:'kd_sub_kegiatan',
                title:'Kegiatan',
                width:120},
                {field:'nm_sub_kegiatan',
                title:'Nama Kegiatan',              
                hidden:"true"},
                {field:'kd_rek6',
                title:'Kode Rekening',
                width:100},
                {field:'nm_rek6',
                title:'Nama Rekening',
                width:200,
                align:"left"},
                {field:'nilai',
                title:'Nilai',
                width:100,
                align:"right"},
                {field:'lalu',
                title:'Sudah Dibayarkan',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'sp2d',
                title:'SP2D Non UP',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'anggaran',
                title:'Anggaran',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'kd_rek',
                title:'Rekening',
                width:30,
                hidden:'true'},
                {field:'sumber',
                title:'Sumber',
                width:150,align:"left"}
            ]]
        });                 
    }
    
    
    
    function load_detail2(){        
       $('#dg1').datagrid('selectAll');
       var rows = $('#dg1').datagrid('getSelections');             
       if (rows.length==0){
            set_grid2();
            exit();
       }                     
        for(var p=0;p<rows.length;p++){
            no      = rows[p].no_bukti;
            nosp2d  = rows[p].no_sp2d;
            giat    = rows[p].kd_sub_kegiatan;
            nmgiat  = rows[p].nm_sub_kegiatan;
            //sgiat    = rows[p].kd_subkegiatan;
            //snmgiat  = rows[p].nm_subkegiatan;
            rek5    = rows[p].kd_rek6;
            rek     = rows[p].kd_rek;
            nmrek5  = rows[p].nm_rek6;
            nil     = rows[p].nilai;
            lal     = rows[p].lalu;
            csp2d   = rows[p].sp2d;
            canggaran   = rows[p].anggaran;
            csumber   = rows[p].sumber;                                                                                                                              
            $('#dg2').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_rek6:rek5,nm_rek6:nmrek5,nilai:nil,lalu:lal,sp2d:csp2d,anggaran:canggaran,kd_rek:rek,kd_sub_kegiatan:giat,nm_sub_kegiatan:nmgiat,sumber:csumber});            
        }
        $('#dg1').edatagrid('unselectAll');
    } 
    
    
    
    function set_grid2(){
        $('#dg2').edatagrid({      
         columns:[[
            {field:'hapus',
            title:'Hapus',
            width:11,
            align:"center",
            formatter:function(value,rec){                                                                       
                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';                  
                }                
            },
            {field:'no_bukti',
            title:'No Bukti',           
            hidden:"true",
            width:30},
            {field:'no_sp2d',
            title:'No SP2D',
            width:40,
            hidden:"true"},
            {field:'kd_sub_kegiatan',
            title:'Kegiatan',
            width:38},
            {field:'nm_sub_kegiatan',
            title:'Nama Kegiatan',          
            hidden:"true",
            width:30},
            {field:'kd_rek6',
            title:'REK LO',
            width:20,
            align:'center'},
            {field:'kd_rek',
            title:'REK 13',
            width:20,
            align:'center'},            
            {field:'nm_rek6',
            title:'Nama Rekening',
            align:"left",
            width:45},
            {field:'nilai',
            title:'Rupiah',
            align:"right",
            width:30},
            {field:'lalu',
            title:'Sudah Dibayarkan',
            align:"right",
            width:30,
            hidden:"true"},
            {field:'sp2d',
            title:'SP2D Non UP',
            align:"right",
            width:30,
            hidden:"true"},
            {field:'anggaran',
            title:'Anggaran',
            align:"right",
            width:30},
            {field:'sumber',
            title:'Sumber',
            align:"center",
            width:20}
            ]]     
        });
    }
    
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });         
         $('#dg').edatagrid('reload');
         set_grid();
    }
     
     
    function section2(){
         $(document).ready(function(){                
             $('#section2').click(); 
             document.getElementById("nomor").focus();                                              
         });                 
         set_grid();
    }
       
     
    function get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,sts,jenis,kontrak,ket_bast){
        $("#nomor").attr("value",nomor);
        $("#nomor_hide").attr("value",nomor);
        $("#no_simpan").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#keterangan").attr("value",ket); 
        $("#kete").attr("value",ket_bast);       
        $("#beban").attr("value",jns);
        //$("#total").attr("value",number_format(tot,2,'.',','));
        $("#notagih").attr("value",notagih);        
        $("#tgltagih").datebox("setValue",tgltagih);    
        $("#status").attr("checked",false);
        $("#status_byr").attr("value",sts);
        $("#jns").attr("Value",jenis);
        $("#kontrak").combogrid("setValue",kontrak);
        if (ststagih==1){            
            $("#status").attr("checked",true);
            $("#tagih").show();
        } else {
            $("#status").attr("checked",false);
            $("#tagih").hide();
        }    
        
        //tombol(sts);
    }
    
   
        function tombol(st){  
            if (st=='1'){
            $('#save').linkbutton('disable');
            $('#del').linkbutton('disable');
             } else {
             $('#save').linkbutton('enable');
             $('#del').linkbutton('enable');
             }
            }
   
    function kosong(){
        cdate = '<?php echo date("Y-m-d"); ?>';        
        $("#nomor").attr("value",'');
        $("#nomor_hide").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#keterangan").attr("value",'');
        $("#kontrak").combogrid("setValue",'');
        $("#total").attr("value",'0');         
        document.getElementById("nomor").focus();  
        lcstatus = 'tambah';
    }
    

    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/penagihan/load_penagihanskpd',
        queryParams:({cari:kriteria})
        });        
     });
    }    
        
//okokokok
    function append_save(){
        var no  = document.getElementById('nomor').value;
        var giat    = $('#giat').combogrid('getValue');
        var nmgiat  = document.getElementById('nmkegiatan').value;                
        var kgiat    = $('#kgiat').combogrid('getValue');
        var knmgiat  = document.getElementById('knmkegiatan').value;
        var nosp2d  = '';
        var rek5  = document.getElementById('rek1').value;
        var rek     = $('#rek').combogrid('getValue');
        var nmrek   = document.getElementById('nmrek').value;
        var crek    = $('#rek').combogrid('grid');          
        var grek    = crek.datagrid('getSelected'); 
        var cdana   = $('#sumber_dn').combogrid('getValue');
        var canggaran = number_format(grek.anggaran,2,'.',',');
        var csp2d   = 0;
        var clalu   = 0;
        //        
        var sisa    = angka(document.getElementById('rek_nilai_sisa').value);                
        var sisa_semp = angka(document.getElementById('rek_nilai_sisa_semp').value);                
        var sisa_ubah = angka(document.getElementById('rek_nilai_sisa_ubah').value); 

        var sisa_spd     = angka(document.getElementById('nilai_sisa_spd').value);        
         
        
        var sisa_spd_rekening = angka(document.getElementById('nilai_spd_lalu').value)+sisa_spd;

        var sisa_dana      = angka(document.getElementById('rek_nilai_sisa_dana').value);                
        var sisa_semp_dana = angka(document.getElementById('rek_nilai_sisa_semp_dana').value);                
        var sisa_ubah_dana = angka(document.getElementById('rek_nilai_sisa_ubah_dana').value);                                            
        //        
        var nil     = angka(document.getElementById('nilai').value);       
        var nil_rek     = document.getElementById('nilai').value;        
        var status_ang  = document.getElementById('status_ang').value ;
            var total = angka(document.getElementById('total1').value) + nil;



            if (status_ang==''){
                 alert('Pilih Tanggal Dahulu') ;
                 exit();
            }
            
            if ( nil == 0 ){
                 alert('Nilai Nol.....!!!, Cek Lagi...!!!') ;
                 exit();
            }
            
            //sumber              
            if ( (status_ang=='Perubahan')&&(nil > sisa_ubah_dana || total > sisa_ubah_dana )){
                 alert('Nilai Melebihi Sisa Sumber Dana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil > sisa_ubah_dana || total > sisa_ubah_dana)){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil > sisa_semp_dana || total > sisa_semp_dana)){
                 alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa_ubah_dana || total > sisa_ubah_dana)){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa_semp_dana || total > sisa_semp_dana)){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa_dana || total > sisa_dana)){
                 alert('Nilai Melebihi Sisa Sumber Dana Penyusunan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            
            //rek    
            if ( (status_ang=='Perubahan')&&(nil > sisa_ubah || total > sisa_ubah)){
                 alert('Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil > sisa_ubah || total > sisa_ubah)){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil > sisa_semp || total > sisa_semp)){
                 alert('Nilai Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa_ubah || total > sisa_ubah)){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa_semp || total > sisa_semp)){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa || total > sisa)){
                 alert('Nilai Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!') ;
                 exit();
            }

            // TOTAL

            if (document.getElementById('no_simpan').value==no){

                
                if ( (status_ang=='Perubahan')&&(total > sisa_spd_rekening)){
                 alert('Nilai Total Rekeing Melebihi Sisa SPD...!!!, Cek Lagi...!!!') ;
                 exit();
                }
                if ( (status_ang=='Penyempurnaan')&&(total > sisa_spd_rekening)){
                     alert('Nilai Total Rekeing Melebihi Sisa SPD...!!!, Cek Lagi...!!!') ;
                     exit();
                }
                
                if ( (status_ang=='Penyusunan')&&(total > sisa_spd_rekening)){
                     alert('Nilai Total Rekeing Melebihi Sisa SPD...!!!, Cek Lagi...!!!') ;
                     exit();
                }

            }else{
                
                if ( (status_ang=='Perubahan')&&(total > sisa_spd)){
                 alert('Nilai Total Rekeing Melebihi Sisa SPD...!!!, Cek Lagi...!!!') ;
                 exit();
                }
                if ( (status_ang=='Penyempurnaan')&&(total > sisa_spd)){
                     alert('Nilai Total Rekeing Melebihi Sisa SPD...!!!, Cek Lagi...!!!') ;
                     exit();
                }
                
                if ( (status_ang=='Penyusunan')&&(total > sisa_spd)){
                     alert('Nilai Total Rekeing Melebihi Sisa SPD...!!!, Cek Lagi...!!!') ;
                     exit();
                }

            }



            


            
            if (cdana==''){
                 alert('Pilih Sumber Dana Dahulu') ;
                 exit();
            }             
            
            if ( nil > sisa_spd){
                 alert('Nilai Melebihi Sisa Dana SPD...!!!, Cek Lagi...!!!') ;
                 exit();
            }
                
            if (nmrek==''){
                 alert('Pilih Rekening Dahulu') ;
                 exit();
            }           

                $('#dg1').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                // kd_subkegiatan:kgiat,
                                                 //nm_subkegiatan:knmgiat,
                                                 kd_rek6:rek,
                                                 nm_rek6:nmrek,
                                                 nilai:nil_rek,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5,
                                                 kd_sub_kegiatan:kgiat,
                                                 nm_sub_kegiatan:knmgiat,
                                                 sumber:cdana
                                                 });
                $('#dg2').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                 //kd_subkegiatan:kgiat,
                                                 //nm_subkegiatan:knmgiat,
                                                 kd_rek6:rek,
                                                 nm_rek6:nmrek,
                                                 nilai:nil_rek,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5,
                                                 kd_sub_kegiatan:kgiat,
                                                 nm_sub_kegiatan:knmgiat,
                                                 sumber:cdana
                                                 });                                                 
                kosong2();
                $('#total1').attr('value',number_format(total,2,'.',','));
                $('#total').attr('value',number_format(total,2,'.',','));
    }     
    
    function tambah(){
        var nor = document.getElementById('nomor').value;
        var tot = document.getElementById('total').value;
        var kd  = document.getElementById('skpd').value;
        var kontrak  = $('#kontrak').combogrid("getValue");
        $('#dg2').edatagrid('reload');
        $('#total1').attr('value',tot);
        $('#kgiat').combogrid('setValue','');
        $('#rek').combogrid('setValue','');
        cek_status_ang();
        var tgl = $('#tanggal').datebox('getValue');
        if (kd != '' && tgl != '' && nor !='' &&kontrak !=''){            
            $("#dialog-modal").dialog('open'); 
            load_detail2();           
        } else {
            alert('Harap Isi Kode , Tanggal , Nomor Penagihan & Nomor Kontrak ') ;         
        }        
    }
    
    function kosong2(){        
        $('#giat').combogrid('setValue','');
        $('#sp2d').combogrid('setValue','');
        $('#rek').combogrid('setValue','');
        $('#nmrek').attr('value','');  
        $('#sumber_dn').combogrid('setValue','');
        $('#sisasp2d').attr('value','0');        
        
        $("#rek_nilai_ang").attr("Value",'0');
        $("#rek_nilai_ang_semp").attr("Value",'0');
        $("#rek_nilai_ang_ubah").attr("Value",'0');                
        $("#rek_nilai_spp").attr("Value",'0');                
        $("#rek_nilai_spp_semp").attr("Value",'0');             
        $("#rek_nilai_spp_ubah").attr("Value",'0');
        
        $("#rek_nilai_ang_dana").attr("Value",'0');
        $("#rek_nilai_ang_semp_dana").attr("Value",'0');
        $("#rek_nilai_ang_ubah_dana").attr("Value",'0');                
        $("#rek_nilai_spp_dana").attr("Value",'0');                
        $("#rek_nilai_spp_semp_dana").attr("Value",'0');                
        $("#rek_nilai_spp_ubah_dana").attr("Value",'0');
        
        
        $('#nilai_sisa_spd').attr('value','0');        
        $('#rek_nilai_sisa').attr('value','0');
        $('#rek_nilai_sisa_semp').attr('value','0');
        $('#rek_nilai_sisa_ubah').attr('value','0');
        $('#rek_nilai_sisa_dana').attr('value','0');
        $('#rek_nilai_sisa_semp_dana').attr('value','0');
        $('#rek_nilai_sisa_ubah_dana').attr('value','0');
        
        $('#nilai').attr('value','0');
        $('#rek1').attr('value','');
        $('#nmgiat').attr('value','');        
        $('#sisa_spd').attr('value','0');        
    }
    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg2').edatagrid('reload');
        kosong2();                        
    }   
     
    function hapus_giat(){
         tot3 = 0;
         var tot = angka(document.getElementById('total').value);
         tot3 = tot - nilx;
         $('#total').attr('value',number_format(tot3,2,'.',','));        
         $('#dg1').datagrid('deleteRow',idx);              
    }
    
    
    function hapus(){
        var cnomor = document.getElementById('nomor_hide').value;
        var urll = '<?php echo base_url(); ?>index.php/tukd/hapus_penagihan';
        var tny = confirm('Yakin Ingin Menghapus Data, Nomor Penagihan : '+cnomor);        
        if (tny==true){
        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({no:cnomor}),
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
    
    
    function simpan_transout(){
        var cno          = (document.getElementById('nomor').value).split(" ").join("");
        var cno_hide     = document.getElementById('nomor_hide').value;
        var cjenis_bayar = document.getElementById('status_byr').value;
        var ctgl         = $('#tanggal').datebox('getValue');
        var cskpd        = document.getElementById('skpd').value;
        var cnmskpd      = document.getElementById('nmskpd').value;
        var cket         = document.getElementById('keterangan').value;
            var cket2    = document.getElementById('kete').value;
        var jns          = document.getElementById('jns').value;
            var kontrak  = $('#kontrak').combogrid("getValue");
        
        var cjenis   = '6';
        var cstatus  = '';
        var csql     = '';
        
        var tahun_input = ctgl.substring(0, 4);
        if (tahun_input != tahun_anggaran){
            alert('Tahun tidak sama dengan tahun Anggaran');
            exit();
        }
        if (cstatus==false){
            cstatus=0;
        }else{
            cstatus=1;
        }
        
        var ctagih    = '';
        var ctgltagih = '2016-12-1';
        var ctotal    = angka(document.getElementById('total').value);        
        var jns_trs = '1';
        
        if ( cno=='' ){
            alert('Nomor Bukti Tidak Boleh Kosong');
            exit();
        } 
        if ( ctgl=='' ){
            alert('Tanggal Bukti Tidak Boleh Kosong');
            exit();
        }
        if ( cskpd=='' ){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        if ( cnmskpd=='' ){
            alert('Nama SKPD Tidak Boleh Kosong');
            exit();
        }
        if ( kontrak=='' ){
            alert('Kontrak Tidak Boleh Kosong');
            exit();
        }
        if ( cket=='' ){
            alert('Keterangan Tidak boleh kosong');
            exit();
        }
        var lenket = cket.length;
        if ( lenket>1000 ){
            alert('Keterangan Tidak boleh lebih dari 1000 karakter');
            exit();
        }
        
        if(lcstatus == 'tambah'){
        $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhtagih',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/penagihan/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1){
                        alert("Nomor Telah Dipakai!");
                        document.getElementById("nomor").focus();
                        exit();
                        } 
                        if(status_cek==0){
                        alert("Nomor Bisa dipakai");
    //---------------------------
            lcinsert     = " ( no_bukti,  tgl_bukti,  ket,        username, tgl_update, kd_skpd,     nm_skpd,       total,        no_tagih,     sts_tagih,  status ,   tgl_tagih,       jns_spp, jenis, kontrak, jns_trs,ket_bast      ) " ; 
            lcvalues     = " ( '"+cno+"', '"+ctgl+"', '"+cket+"', '',       '',         '"+cskpd+"', '"+cnmskpd+"', '"+ctotal+"', '"+ctagih+"', '"+cstatus+"','"+cjenis_bayar+"', '"+ctgltagih+"', '"+cjenis+"', '"+jns+"', '"+kontrak+"', '"+jns_trs+"', '"+cket2+"' ) " ;
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/penagihan/simpan_penagihan_ar',
                    data     : ({tabel    : 'trhtagih',  kolom    :lcinsert,    nilai    : lcvalues,    cid    : 'no_bukti',   lcid    : cno,
                                 proses   : 'header', status_byr : cjenis_bayar }),

                    dataType : "json",
                    success  : function(data) {
                        status = data;
                        if ( status == '0') {
                            alert('Gagal Simpan..!!');
                            exit();
                        } else if(status=='1') {
                                  alert('Data Sudah Ada..!!');
                                  exit();
                               } else {
                                
                                    $('#dg1').datagrid('selectAll');
                                    var rows = $('#dg1').datagrid('getSelections');           
                                    for(var p=0;p<rows.length;p++){
                        
                                        cnobukti   = rows[p].no_bukti;
                                        cnosp2d    = rows[p].no_sp2d;
                                        ckdgiat    = rows[p].kd_sub_kegiatan;
                                        cnmgiat    = rows[p].nm_sub_kegiatan;                                        
                                        crek       = rows[p].kd_rek6;
                                        cnmrek     = rows[p].nm_rek6;
                                        cnilai     = angka(rows[p].nilai);
                                        crek5      = rows[p].kd_rek;
                                        csumber    = rows[p].sumber;
                                        if ( p > 0 ) {
                                           csql = csql+","+"('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"')";
                                        } else {
                                            csql = "values('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"')";                                            
                                        }
                                    }
                                    //alert(csql);
                                                  
                                    $(document).ready(function(){
                                    $.ajax({
                                         type     : "POST",   
                                         dataType : 'json',                 
                                         data     : ({tabel_detail:'trdtagih',no_detail:cno,sql_detail:csql,proses:'detail', status_byr : cjenis_bayar}),
                                         url      : '<?php echo base_url(); ?>/index.php/penagihan/simpan_penagihan_ar',
                                         success  : function(data){                        
                                                    status = data;   
                                                    if ( status=='5' ) {               
                                                        alert('Data Detail Gagal Tersimpan');
                                                    } 
                                                    }
                                                    });
                                    });            

                                    alert('Data Tersimpan..!!');
                                     $("#nomor_hide").attr("value",cno);
                                     $("#no_simpan").attr("value",cno);
                                    lcstatus = 'edit';
                                    exit();
                              
                               }
                    }
                });
            });
            //--------------            
            
        }
        }
        });
        });
    } else{
        $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhtagih',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1 && cno!=cno_hide){
                        alert("Nomor Telah Dipakai!");
                        exit();
                        } 
                        if(status_cek==0 || cno==cno_hide){
                        alert("Nomor Bisa dipakai");
        //--------
         lcquery    = " UPDATE trhtagih  SET no_bukti='"+cno+"',   tgl_bukti='"+ctgl+"',   ket='"+cket+"', username='', tgl_update='', nm_skpd='"+cnmskpd+"', total='"+ctotal+"',   no_tagih='"+ctagih+"', sts_tagih='"+cstatus+"', status='"+cjenis_bayar+"', tgl_tagih='"+ctgltagih+"', jns_spp='"+cjenis+"', jenis='"+jns+"', kontrak='"+kontrak+"' where no_bukti='"+cno_hide+"' AND kd_skpd='"+cskpd+"' "; 
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/penagihan/update_penagihan_header_ar',
                data     : ({st_query:lcquery,tabel:'trhtagih',cid:'no_bukti',lcid:cno,lcid_h:cno_hide,status : cjenis_bayar}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        
                        if ( status=='1' ){
                            alert('Nomor Bukti Sudah Terpakai...!!!,  Ganti Nomor Bukti...!!!');
                            exit();
                        }
                        
                        if ( status=='2' ){
                              
                              var a         = document.getElementById('nomor').value; 
                              var a_hide    = document.getElementById('nomor_hide').value; 
                              
                              $('#dg1').datagrid('selectAll');
                              var rows = $('#dg1').datagrid('getSelections');           
                              for(var p=0;p<rows.length;p++){
                        
                                        //cnobukti   = rows[p].no_bukti;
                                        cnobukti   = a ;
                                        cnosp2d    = rows[p].no_sp2d;
                                        ckdgiat    = rows[p].kd_sub_kegiatan;
                                        cnmgiat    = rows[p].nm_sub_kegiatan;                                     
                                        crek       = rows[p].kd_rek6;
                                        cnmrek     = rows[p].nm_rek6;
                                        cnilai     = angka(rows[p].nilai);
                                        crek5      = rows[p].kd_rek;
                                        csumber    = rows[p].sumber;
                                        
                                        if ( p > 0 ) {
                                           csql = csql+","+"('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"')";
                                        } else {
                                            csql = "values('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"')";                                            
                                        }
                              }
                                                                                  
                              $(document).ready(function(){
                                    $.ajax({
                                         type     : "POST",   
                                         dataType : 'json',                 
                                         data     : ({tabel_detail:'trdtagih',no_detail:cno,sql_detail:csql,
                                                      nomor:a_hide,lcid:a,lcid_h:a_hide}),
                                         url      : '<?php echo base_url(); ?>/index.php/penagihan/update_penagihan_detail_ar',
                                         success  : function(data){                        
                                                    status = data;  
                                                    if(status=='1'){
                                                        $("#nomor_hide").attr("Value",cno) ;
                                                        $("#no_simpan").attr("Value",cno) ;
                                                        $('#dg1').edatagrid('unselectAll');
                                                        alert('Data Tersimpan');
                                                        lcstatus = 'edit';
                                                        $('#dg1').edatagrid('unselectAll');
                                                        } 
                                                        else {               
                                                        alert('Data Detail Gagal Tersimpan');
                                                    } 
                                                    }
                                                    });
                                }); 
                            }
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                        
                    }
            });
            });
        //----------
            }
            }
        });
     });    
    }       
    }

    
    function sisa_bayar(){
        
        var sisa     = angka(document.getElementById('rek_nilai_sisa_ubah').value);             
        var nil      = angka(document.getElementById('nilai').value);        
        var sisasp2d = angka(document.getElementById('sisasp2d').value);
        var tot      = 0;
        tot          = sisa - nil;
        
        if (nil > sisasp2d) {    
                alert('Nilai Melebihi Sisa Sp2d');
                    exit();
        } else {
            if (tot < 0){
                    alert('Nilai Melebihi Sisa');
                    exit();                
            }
        }           
    }       
                         
                  
    function runEffect() {
        var selectedEffect = 'blind';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
    };              
                             
    </script>

</head>
<body>

<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1" >List Penagihan </a></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();load_detail();">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="List Pembayaran Transaksi" style="width:870px;height:600px;" >  
        </table>                          
    </p> 
    </div>   

<h3><a href="#" id="section2">PENAGIHAN</a></h3>
   <div  style="height: 350px;">
   <p>       
   <div id="demo"></div>
        <table align="center" style="width:100%;">
            <tr>
                <td style="border-bottom: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/></td>
                <td style="border-bottom: double 1px red;">&nbsp;&nbsp;</td>
                <td style="border-bottom: double 1px red;" colspan = "2"><i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr>
            <tr>
                <td>No.BAST/ Penagihan</td>
                <td>&nbsp;<input type="text" id="nomor" style="width: 200px;" onclick="javascript:select();"/> <input  id="nomor_hide" style="width: 20px;" onclick="javascript:select();" hidden /></td>
                <td>&nbsp;&nbsp;</td>
                <td>Tanggal </td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>     
            </tr>                        
            <tr>
                <td>S K P D</td>
                <td>&nbsp;<input id="skpd" name="skpd" readonly="true" style="width: 140px;border: 0;" /></td>
                <td></td>
                <td>Nama SKPD :</td> 
                <td><input type="text" id="nmskpd" style="border:0;width: 400px;border: 0;" readonly="true"/></td>                                
            </tr>
            
            <tr>
                <td>Keterangan</td>
                <td colspan="4"><textarea id="keterangan" style="width: 760px; height: 40px;"></textarea></td>
           </tr> 
                       <tr>
                <td>Ket (BA)</td>
                <td colspan="4"><textarea id="kete" style="width: 760px; height: 40px;"></textarea></td>
           </tr> 
                <td>Status</td>
                 <td>
                     <select name="status_byr" id="status_byr">
                         <option value="1">SELESAI</option>
                         <option value="0">BELUM SELESAI</option>
                     </select>
                 </td> 
            </tr>
            <tr>
                 <td>Jenis</td>
                 <td>
                     <select name="jns" id="jns">
                                     <option value="">TANPA TERMIN / SEKALI PEMBAYARAN</option>
                         <option value="1">KONSTRUKSI DALAM PENGERJAAN</option>
                         <option value="2">UANG MUKA</option>
                         <option value="3">HUTANG TAHUN LALU</option>
                     </select>
                 </td>
            </tr>
            <tr>
                <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Kontrak</td>
                <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input id="kontrak" name="kontrak" style="width:190px"/> 
                <td colspan="3" align="right">
                    <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();load_detail();">Baru</a>-->
                    <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_transout();">Simpan</a>
                    <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
                    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>                                   
                </td>
            </tr>
        </table>          
        <table id="dg1" title="Rekening" style="width:870px;height:350px;" >  
        </table>  
        <div id="toolbar" align="right">
            <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Kegiatan</a>
            <!--<input type="checkbox" id="semua" value="1" /><a onclick="">Semua Kegiatan</a>-->
            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_giat();">Hapus Kegiatan</a>
                    
        </div>
        <table align="center" style="width:100%;">
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td ></td>
            <td align="right">Total : <input type="text" id="total" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></td>
        </tr>
        </table>
                
   </p>
   </div>
   
</div>
</div>


<div id="dialog-modal" title="Input Kegiatan">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table border="0">
        <tr>
            <td width="21%">Kode Kegiatan</td>            
            <td width="21%"><input id="kgiat" name="kgiat" style="width: 200px;" /></td>
            <td width="18%" align="center">Nama Kegiatan</td>            
            <td colspan="3" width="40%"><input type="text" id="knmkegiatan" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>  
         <tr>
            <td hidden ='true'>Kode Sub Kegiatan</td>            
            <td hidden ='true' ><input id="giat" name="giat" style="width: 200px;" /></td>
            <td hidden ='true' align="center">Nama Sub Kegiatan</td>            
            <td colspan="3" hidden ='true'><input type="text" id="nmkegiatan" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>        
        <tr>
            <td >Kode Rekening</td>            
            <td><input id="rek" name="rek" style="width: 200px;" />
            <input id="rek1" name="rek1" style="width: 200px;" readonly="true"/></td>
            <td  align="center">Nama Rekening</td>            
            <td colspan="3"><input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>   
        <tr>
            <td >Sumber Dana</td>           
            <td colspan="5"><input id="sumber_dn" name="sumber_dn" style="width: 200px;" /></td>            
        </tr>        
        <!--<tr>
            <td >Sisa Penyusunan</td>
            <td>:</td>
            <td><input type="text" id="sisa" readonly="true" style="text-align:right;border:0;width: 150px;"/></td>            
        </tr>
        <tr>
            <td >Sisa Penyempurnaan</td>
            <td>:</td>
            <td><input type="text" id="sisa_semp" readonly="true" style="text-align:right;border:0;width: 150px;"/></td>            
        </tr>
        <tr>
            <td >Sisa Perubahan</td>
            <td>:</td>
            <td><input type="text" id="sisa_ubah" readonly="true" style="text-align:right;border:0;width: 150px;"/></td>            
        </tr>
        <tr>
            <td >Sisa SPD</td>
            <td>:</td>
            <td colspan="3"><input type="text" id="tot_spd" readonly="true" style="text-align:right;border:0;width: 100px;"/> - 
            <input type="text" id="tot_trans" readonly="true" style="text-align:right;border:0;width: 100px;"/>  =
            <input type="text" id="sisa_spd" readonly="true" style="text-align:right;border:0;width: 150px;"/></td>            
        </tr>
        <tr>
            <td >Nilai Realisasi</td>
            <td>:</td>
            <td><input type="text" id="nilai_real" style="text-align: right; border:0; width: 150px;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>            
        </tr>-->
        
        <tr>
                <td bgcolor="#99FF99">TOTAL SPD</td>                
                <td bgcolor="#99FF99"><input type="text" id="total_spd" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#99FF99" align="center">REALISASI</td>                
                <td bgcolor="#99FF99"><input type="text" id="nilai_spd_lalu" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#99FF99">SISA</td>                
                <td bgcolor="#99FF99"><input type="text" id="nilai_sisa_spd" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
            </tr>
            <tr>
              <td colspan="6">ANGGARAN REKENING :</td>
            </tr>            
            <tr>
                <td bgcolor="#87CEFA">PENYUSUNAN</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#87CEFA" align="center">REALISASI</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#87CEFA">SISA</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>               
            </tr>
            
            <tr>
                <td bgcolor="#87CEFA">PENYEMPURNAAN</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#87CEFA" align="center">REALISASI</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#87CEFA">SISA</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>              
            </tr>
            <tr>
                <td bgcolor="#87CEFA">PERUBAHAN</td>   
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#87CEFA" align="center">REALISASI</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#87CEFA">SISA</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>              
            </tr>
          <tr>
              <td colspan="6">SUMBER DANA :</td>
            </tr>            
            <tr>
                <td bgcolor="#FFA07A">PENYUSUNAN</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#FFA07A" align="center">REALISASI</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#FFA07A">SISA</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>              
            </tr>
            
            <tr>
                <td bgcolor="#FFA07A">PENYEMPURNAAN</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#FFA07A" align="center">REALISASI</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#FFA07A">SISA</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>             
            </tr>
            <tr>
                <td bgcolor="#FFA07A">PERUBAHAN</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#FFA07A" align="center">REALISASI</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#FFA07A">SISA</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>             
            </tr>
        
         <tr>
            <td >Status</td>            
            <td><input type="text" id="status_ang" readonly="true" style="text-align:right;border:0;width: 150px;"/></td>  
            <td colspan="4"></td>           
        </tr>
        <tr>
            <td >Nilai</td>            
            <td><input type="text" id="nilai" style="text-align: right; width: 196px;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:sisa_bayar();"/></td>            
            <td colspan="4"></td>  
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
        <table align="right" >           
            <tr>
                <td>Total</td>
                <td>:</td>
                <td><input type="text" id="total1" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;"/></td>
            </tr>
        </table>
        <table id="dg2" title="Input Rekening" style="width:980px;height:150px;"  >  
        </table>  
     
    </fieldset>  
</div>
</body>
</html>