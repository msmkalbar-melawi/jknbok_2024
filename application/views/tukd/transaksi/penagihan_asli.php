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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
 
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
                      
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 650,
                width: 1050,
                modal: true,
                autoOpen:false                
            });              
            $("#tagih").hide();
            get_skpd();
			get_tahun();
			//seting_tombol();
        });    
     
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_penagihan',
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
          jns   = rowData.jns_beban; 
          tot   = rowData.total;
          notagih=rowData.no_tagih;
          tgltagih=rowData.tgl_tagih;
          ststagih=rowData.sts_tagih;
          sts=rowData.status;
		  jenis=rowData.jenis;
		  kontrak=rowData.kontrak;
          get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,sts,jenis,kontrak);   
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
    	    {field:'kd_kegiatan',
    		title:'Kegiatan',
    		width:50},
            {field:'nm_kegiatan',
    		title:'Nama Kegiatan',    		
            hidden:"true"},
            {field:'kd_rek5',
    		title:'Kode Rekening',
    		width:30},
            {field:'nm_rek5',
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
    		width:30}
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
    	    {field:'kd_kegiatan',
    		title:'Kegiatan',
            width:50},
            {field:'nm_kegiatan',
    		title:'Nama Kegiatan',    		
            hidden:"true",
            width:30},
            {field:'kd_rek5',
    		title:'Kode Rekening',
            width:25,
            align:'center'},
            {field:'nm_rek5',
    		title:'Nama Rekening',
            align:"left",
            width:40},
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
            {field:'kd_rek',
    		title:'Rekening',
    		width:30}
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
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd',
           queryParams:({kd:skpd}),             
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:140},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               idxGiat = rowIndex;               
               giat = rowData.kd_kegiatan;
			   nm_giat = rowData.nm_kegiatan;
               $("#nmkegiatan").attr("value",rowData.nm_kegiatan);
               var nobukti = document.getElementById('nomor').value;
               var kode = document.getElementById('skpd').value;
               var frek = '';
			   kosong2();
               $('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_rek_penagihan',
                                   queryParams:({no:nobukti,
                                                 giat:giat,
                                                 kd:kode,
                                                 rek:frek})
                                   });
			   load_total_spd(giat);
			   load_total_trans(giat);
			   $("#rek").combogrid('disable');

           }
		   
        });
        

        
        
		$('#kontrak').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/tukd/kontrak',  
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
           idField:'kd_rek5',  
           textField:'kd_rek5',  
           mode:'remote',                                   
           columns:[[  
		       {field:'kd_rek',title:'Kode Rekening Ang.',width:70,align:'center'},  
               {field:'kd_rek5',title:'Kode Rekening',width:70,align:'center'},  
               {field:'nm_rek5',title:'Nama Rekening',width:200},
               {field:'lalu',title:'Lalu',width:120,align:'right'},
               {field:'sp2d',title:'SP2D',width:120,align:'right'},
               {field:'anggaran',title:'Anggaran',width:120,align:'right'}
           ]],
           onSelect:function(rowIndex,rowData){
                var anggaran = rowData.anggaran;
                var anggaran_semp = rowData.anggaran_semp;
                var anggaran_ubah = rowData.anggaran_ubah;
                var lalu = rowData.lalu;
                sisa = anggaran-lalu;
                sisa_semp = anggaran_semp-lalu;
                sisa_ubah = anggaran_ubah-lalu;
                $("#rek1").attr("value",rowData.kd_rek);
                $("#nmrek").attr("value",rowData.nm_rek5);
                $('#sisa').attr('value',number_format(sisa,2,'.',','));
                $('#sisa_semp').attr('value',number_format(sisa_semp,2,'.',','));
                $('#sisa_ubah').attr('value',number_format(sisa_ubah,2,'.',','));
                document.getElementById('nilai').select();
				total_sisa_spd();
           }
        });                        
    }); 
    
	function load_total_spd(giat){
		var kode = document.getElementById('skpd').value;
		$(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/load_total_spd",
            dataType:"json",
			data: ({giat:giat,kode:kode}),
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#tot_spd").attr("value",n['total_spd']);
                });
            }
         });
        });
    }
    function load_total_trans(giat){
		var no_simpan = document.getElementById('no_simpan').value;  
		var kode = document.getElementById('skpd').value;
        
		$(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd2/load_total_trans_tagih",
            dataType:"json",
			data: ({giat:giat,kode:kode,no_simpan:no_simpan}),
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#tot_trans").attr("value",n['total']);
                });
			 $("#rek").combogrid('enable');
            }
         });
        });
    }
	
	function total_sisa_spd(){ 
        var tot_spd   = angka(document.getElementById('tot_spd').value);  
       var tot_trans = angka(document.getElementById('tot_trans').value);  
		   totsisa = tot_spd-tot_trans;
		
	   $('#sisa_spd').attr('value',number_format(totsisa,2,'.',','));

    }
	
    function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
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
	
	function seting_tombol(){
		$('#tambah').linkbutton('disable');
		$('#save').linkbutton('disable');
        $('#del').linkbutton('disable');
        //document.getElementById("p1").innerHTML="Batas Pembuatan SPP LS sudah selesai";
	}
	
    function get_tahun() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/config_tahun',
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
                url:'<?php echo base_url(); ?>index.php/tukd/cek_status_ang',
                data: ({tgl_cek:tgl_cek}),
                type: "POST",
                dataType:"json",                         
                success:function(data){
                $("#status_ang").attr("value",data.status_ang);
            }  
            });
        }
    
    function kegia(){
      $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd',queryParams:({kd:skpd,jenis:'52'})});  
    }
               
    
    function hapus_detail(){
        var rows = $('#dg2').edatagrid('getSelected');
        cgiat    = rows.kd_kegiatan;
        crek     = rows.kd_rek5;
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
            url:"<?php echo base_url(); ?>index.php/tukd/load_tot_tagih",
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
                url      : '<?php echo base_url(); ?>/index.php/tukd/load_dtagih',
                data     : ({no:kk}),
                dataType : "json",
                success  : function(data){                                          
                    $.each(data,function(i,n){                                    
                    no        = n['no_bukti'];
                    nosp2d    = n['no_sp2d'];                                                                    
                    giat      = n['kd_kegiatan'];
                    nmgiat    = n['nm_kegiatan'];
                    rek5      = n['kd_rek5'];
                    rek       = n['kd_rek'];
                    nmrek5    = n['nm_rek5'];
                    nil       = number_format(n['nilai'],2,'.',',');
                    clalu     = number_format(n['lalu'],2,'.',',');
                    csp2d     = number_format(n['sp2d'],2,'.',',');
                    canggaran = number_format(n['anggaran'],2,'.',',');                                                                                      
                    $('#dg1').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_kegiatan:giat,nm_kegiatan:nmgiat,kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil,lalu:clalu,sp2d:csp2d,anggaran:canggaran,kd_rek:rek});                                                                                                                                                                                                                                                                                                                                                                                             
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
        	    {field:'kd_kegiatan',
        		title:'Kegiatan',
        		width:50},
                {field:'nm_kegiatan',
        		title:'Nama Kegiatan',        		
                hidden:"true"},
                {field:'kd_rek5',
        		title:'Kode Rekening',
        		width:30},
                {field:'nm_rek5',
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
  		        width:30,
                hidden:'true'}
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
            giat    = rows[p].kd_kegiatan;
            nmgiat  = rows[p].nm_kegiatan;
            rek5    = rows[p].kd_rek5;
            rek     = rows[p].kd_rek;
            nmrek5  = rows[p].nm_rek5;
            nil     = rows[p].nilai;
            lal     = rows[p].lalu;
            csp2d   = rows[p].sp2d;
            canggaran   = rows[p].anggaran;                                                                                                                              
            $('#dg2').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_kegiatan:giat,nm_kegiatan:nmgiat,kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil,lalu:lal,sp2d:csp2d,anggaran:canggaran,kd_rek:rek});            
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
            hidden:"true",
            width:40},
    	    {field:'kd_kegiatan',
    		title:'Kegiatan',
            width:50},
            {field:'nm_kegiatan',
    		title:'Nama Kegiatan',    		
            hidden:"true",
            width:30},
            {field:'kd_rek5',
    		title:'Kode Rekening',
            width:25,
            align:'center'},
            {field:'nm_rek5',
    		title:'Nama Rekening',
            align:"left",
            width:40},
            {field:'nilai',
    		title:'Rupiah',
            align:"right",
            width:30},
            {field:'lalu',
    		title:'Sudah Dibayarkan',
            align:"right",
            hidden:"true",
            width:30},
            {field:'sp2d',
    		title:'SP2D Non UP',
            align:"right",
            hidden:"true",
            width:30},
            {field:'anggaran',
    		title:'Anggaran',
            align:"right",
            width:30},
            {field:'kd_rek',
    		title:'Rekening',
    		width:30}
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
       
     
    function get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,sts,jenis,kontrak){
        $("#nomor").attr("value",nomor);
        $("#nomor_hide").attr("value",nomor);
        $("#no_simpan").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#keterangan").attr("value",ket);        
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
		url: '<?php echo base_url(); ?>/index.php/tukd/load_penagihan',
        queryParams:({cari:kriteria})
        });        
     });
    }    
        

    function append_save(){
        var no  = document.getElementById('nomor').value;
        var giat    = $('#giat').combogrid('getValue');
        var nmgiat  = document.getElementById('nmkegiatan').value;                
        var nosp2d  = '';
        var rek5  = document.getElementById('rek1').value;
        var rek     = $('#rek').combogrid('getValue');
        var nmrek   = document.getElementById('nmrek').value;
        var crek    = $('#rek').combogrid('grid');	
        var grek    = crek.datagrid('getSelected');	
        var canggaran = number_format(grek.anggaran,2,'.',',');
        var csp2d   = 0;
        var clalu   = 0;
        var sisa    = angka(document.getElementById('sisa').value);                
        var sisa_semp = angka(document.getElementById('sisa_semp').value);                
        var sisa_ubah = angka(document.getElementById('sisa_ubah').value);                
        var nil     = angka(document.getElementById('nilai').value);        
        var sisa_spd     = angka(document.getElementById('sisa_spd').value);        
        var nil_rek     = document.getElementById('nilai').value;        
        var status_ang  = document.getElementById('status_ang').value ;
		var total = angka(document.getElementById('total1').value) + nil;

            if (status_ang==''){
				swal("Error", "Pilih Tanggal Dahulu", "error");
                 exit();
            }
            if ( nil == 0 ){
				swal("Error", "Nilai Nol.....!!!, Cek Lagi...!!!", "error");
                 exit();
            }
			if ( nil > sisa_spd){
				swal("Error", "Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
			if ( total > sisa_spd){
				swal("Error", "Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Perubahan')&&(nil > sisa_ubah)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil > sisa_ubah)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil > sisa_semp)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa_ubah)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa_semp)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if (giat==''){
				swal("Error", "Pilih Kegiatan Dahulu", "error");
                 exit();
            }
            if (nmgiat==''){
				swal("Error", "Pilih Kegiatan Dahulu", "error");
                 exit();
            }
			var len = giat.length;
			if (len !=21){
				swal("Error", "Format Kegiatan Salah", "error");
				exit();
			}
			
			
            if (nmrek==''){
				swal("Error", "Pilih Rekening Dahulu", "error");
                 exit();
            }
			

                $('#dg1').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                 kd_kegiatan:giat,
                                                 nm_kegiatan:nmgiat,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil_rek,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5});
                $('#dg2').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                 kd_kegiatan:giat,
                                                 nm_kegiatan:nmgiat,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil_rek,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5});                                                 
                kosong2();
                $('#total1').attr('value',number_format(total,2,'.',','));
                $('#total').attr('value',number_format(total,2,'.',','));
        /*tot         = sisa - angka(nil);

        if (tot >= 0){                                    
            if (giat != '' && nil != 0 && canggaran != 0) {
                $('#dg1').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                 kd_kegiatan:giat,
                                                 nm_kegiatan:nmgiat,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5});
                $('#dg2').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                 kd_kegiatan:giat,
                                                 nm_kegiatan:nmgiat,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5});                                                 
                kosong2();
                total = angka(document.getElementById('total1').value) + angka(nil);
                $('#total1').attr('value',number_format(total,2,'.',','));
                $('#total').attr('value',number_format(total,2,'.',','));
            } else {
                alert('Kode Kegiatan,Nilai dan Anggaran tidak boleh kosong');
                exit();
            }
        } else {
            alert('Nilai Melebihi Sisa');
            exit();                
        }*/     
    }     
    
    function tambah(){
        var nor = document.getElementById('nomor').value;
        var tot = document.getElementById('total').value;
        var kd  = document.getElementById('skpd').value;
		var kontrak  = $('#kontrak').combogrid("getValue");
        $('#dg2').edatagrid('reload');
        $('#total1').attr('value',tot);
        $('#giat').combogrid('setValue','');
        $('#rek').combogrid('setValue','');
        var tgl = $('#tanggal').datebox('getValue');
        if (kd != '' && tgl != '' && nor !='' &&kontrak !=''){            
            $("#dialog-modal").dialog('open'); 
            load_detail2();           
        } else {
			swal("Error", "Harap Isi Kode, Tanggal, Nomor Penagihan & Nomor Kontrak", "error");
        }
    }
    
    function kosong2(){        
        $('#giat').combogrid('setValue','');
        $('#sp2d').combogrid('setValue','');
        $('#rek').combogrid('setValue','');
        $('#sisasp2d').attr('value','0');
        $('#sisa').attr('value','0');
        $('#sisa_semp').attr('value','0');
        $('#sisa_ubah').attr('value','0');
        $('#nilai').attr('value','0');
        $('#rek1').attr('value','');
        $('#nmgiat').attr('value','');        
        $('#sisa_spd').attr('value','');        
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
							swal("Berhasil", "Data Berhasil Terhapus", "success");
                        } else {
							swal("Error", "Gagal Hapus", "error");
                        }        
                 }
                 
                });           
        });
        }     
    }
    
    function simpan_transout2() {
        var cno     	 = (document.getElementById('nomor').value).split(" ").join("");
        var cno_hide 	 = document.getElementById('nomor_hide').value;
        var cjenis_bayar = document.getElementById('status_byr').value;
        var ctgl     	 = $('#tanggal').datebox('getValue');
        var cskpd  	  	 = document.getElementById('skpd').value;
        var cnmskpd  	 = document.getElementById('nmskpd').value;
        var cket     	 = document.getElementById('keterangan').value;
		var jns      	 = document.getElementById('jns').value;
		var kontrak  	 = $('#kontrak').combogrid("getValue");
		var status_ang   = document.getElementById('status_ang').value ;		
        var cjenis   = '6';
        var cstatus  = '';
        var csql     = '';
		
		var tahun_input = ctgl.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			swal("Error", "Tahun tidak sama dengan tahun Anggaran", "error");
			exit();
		}
		if (status_ang==''){
			swal("Error", "Pilih Tanggal Dahulu", "error");
                 exit();
            }
        if (cstatus==false){
            cstatus=0;
        }else{
            cstatus=1;
        }
        
        var ctagih    = '';
        var ctgltagih = '2014-12-1';
        var ctotal    = angka(document.getElementById('total').value);        
        
        if ( cno=='' ){
			swal("Error", "Nomor Bukti Tidak Boleh Kosong", "error");
            exit();
        } 
        if ( ctgl=='' ){
			swal("Error", "Tanggal Bukti Tidak Boleh Kosong", "error");
            exit();
        }
        if ( cskpd=='' ){
			swal("Error", "Kode SKPD Tidak Boleh Kosong", "error");
            exit();
        }
		if ( cnmskpd=='' ){
			swal("Error", "Nama SKPD Tidak Boleh Kosong", "error");
            exit();
        }
		if ( kontrak=='' ){
			swal("Error", "Kontrak Tidak Boleh Kosong", "error");
            exit();
        }
        if ( cket=='' ){
			swal("Error", "Keterangan Tidak boleh kosong", "error");
            exit();
        }
		var lenket = cket.length;
		if ( lenket>1000 ){
			swal("Error", "Keterangan Tidak boleh lebih dari 1000 karakter", "error");
            exit();
        }
		
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
						swal("Error", "Nomor Telah Dipakai!", "error");
						exit();
						} 
						if(status_cek==0 || cno==cno_hide){
						alert("Nomor Bisa dipakai");
		//--------
		 lcquery    = " UPDATE trhtagih  SET username='', tgl_update='', status='"+cjenis_bayar+"', jenis='"+jns+"' where no_bukti='"+cno_hide+"' AND kd_skpd='"+cskpd+"' "; 
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/update_penagihan_header_ar',
                data     : ({st_query:lcquery,tabel:'trhtagih',cid:'no_bukti',lcid:cno,lcid_h:cno_hide,status : cjenis_bayar}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        
                    }
            });
            });
		//----------
			}
			}
		});
     });
	
	}
		
     
    function simpan_transout() {
        var cno     	 = (document.getElementById('nomor').value).split(" ").join("");
        var cno_hide 	 = document.getElementById('nomor_hide').value;
        var cjenis_bayar = document.getElementById('status_byr').value;
        var ctgl     	 = $('#tanggal').datebox('getValue');
        var cskpd  	  	 = document.getElementById('skpd').value;
        var cnmskpd  	 = document.getElementById('nmskpd').value;
        var cket     	 = document.getElementById('keterangan').value;
		var jns      	 = document.getElementById('jns').value;
		var kontrak  	 = $('#kontrak').combogrid("getValue");
		var status_ang   = document.getElementById('status_ang').value ;		
        var cjenis   = '6';
        var cstatus  = '';
        var csql     = '';
		
		var tahun_input = ctgl.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			swal("Error", "Tahun tidak sama dengan tahun Anggaran", "error");
			exit();
		}
		if (status_ang==''){
			swal("Error", "Pilih Tanggal Dahulu", "error");
                 exit();
            }
        if (cstatus==false){
            cstatus=0;
        }else{
            cstatus=1;
        }
        
        var ctagih    = '';
        var ctgltagih = '2014-12-1';
        var ctotal    = angka(document.getElementById('total').value);        
        
       if ( cno=='' ){
			swal("Error", "Nomor Bukti Tidak Boleh Kosong", "error");
            exit();
        } 
        if ( ctgl=='' ){
			swal("Error", "Tanggal Bukti Tidak Boleh Kosong", "error");
            exit();
        }
       if ( cskpd=='' ){
			swal("Error", "Kode SKPD Tidak Boleh Kosong", "error");
            exit();
        }
		if ( cnmskpd=='' ){
			swal("Error", "Nama SKPD Tidak Boleh Kosong", "error");
            exit();
        }
		if ( kontrak=='' ){
			swal("Error", "Kontrak Tidak Boleh Kosong", "error");
            exit();
        }
        if ( cket=='' ){
			swal("Error", "Keterangan Tidak boleh kosong", "error");
            exit();
        }
		var lenket = cket.length;
		if ( lenket>1000 ){
			swal("Error", "Keterangan Tidak boleh lebih dari 1000 karakter", "error");
            exit();
        }
		
		if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhtagih',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						swal("Error", "Nomor Telah Dipakai", "error");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						swal({
							  title: "Nomor Bisa Dipakai",
							  text: "Harap Tunggu sampai muncul pesan tersimpan!",
							  timer: 2000,
							  showConfirmButton: false
							});
						
	//---------------------------
			lcinsert     = " ( no_bukti,  tgl_bukti,  ket,        username, tgl_update, kd_skpd,     nm_skpd,       total,        no_tagih,     sts_tagih,  status ,   tgl_tagih,       jns_spp, jenis, kontrak      ) " ; 
            lcvalues     = " ( '"+cno+"', '"+ctgl+"', '"+cket+"', '',       '',         '"+cskpd+"', '"+cnmskpd+"', '"+ctotal+"', '"+ctagih+"', '"+cstatus+"','"+cjenis_bayar+"', '"+ctgltagih+"', '"+cjenis+"', '"+jns+"', '"+kontrak+"' ) " ;
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_penagihan_ar',
                    data     : ({tabel    : 'trhtagih',  kolom    :lcinsert,    nilai    : lcvalues,    cid    : 'no_bukti',   lcid    : cno,
                                 proses   : 'header', status_byr : cjenis_bayar }),

                    dataType : "json",
                    success  : function(data) {
                        status = data;
                        if ( status == '0') {
							swal("Error", "Gagal Simpan", "error");
                            exit();
                        } else if(status=='1') {
							swal("Error", "Data Sudah Ada", "error");
                                  exit();
                               } else {
                                
                                    $('#dg1').datagrid('selectAll');
                                    var rows = $('#dg1').datagrid('getSelections');           
                                    for(var p=0;p<rows.length;p++){
                        
                                        cnobukti   = rows[p].no_bukti;
                                        cnosp2d    = rows[p].no_sp2d;
                                        ckdgiat    = rows[p].kd_kegiatan;
                                        cnmgiat    = rows[p].nm_kegiatan;
                                        crek       = rows[p].kd_rek5;
                                        cnmrek     = rows[p].nm_rek5;
                                        cnilai     = angka(rows[p].nilai);
                                        crek5      = rows[p].kd_rek;
                        
                                        if ( p > 0 ) {
                                           csql = csql+","+"('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"')";
                                        } else {
                                            csql = "values('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"')";                                            
                                        }
                                    }
									//alert(csql);
                                                  
                                    $(document).ready(function(){
                                    $.ajax({
                                         type     : "POST",   
                                         dataType : 'json',                 
                                         data     : ({tabel_detail:'trdtagih',no_detail:cno,sql_detail:csql,proses:'detail', status_byr : cjenis_bayar}),
                                         url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_penagihan_ar',
                                         success  : function(data){                        
                                                    status = data;   
                                                    if ( status=='5' ) {               
													swal("Error", "Data Detail Gagal Simpan", "error");
                                                    } 
                                                    }
                                                    });
                                    });            

									swal("Berhasil", "Data Tersimpan", "success");
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
						swal("Error", "Nomor Telah Dipakai", "error");
						exit();
						} 
						if(status_cek==0 || cno==cno_hide){
						swal({
							  title: "Nomor Bisa Dipakai",
							  text: "Harap Tunggu sampai muncul pesan tersimpan!",
							  timer: 2000,
							  showConfirmButton: false
							});
		//--------
		 lcquery    = " UPDATE trhtagih  SET no_bukti='"+cno+"',   tgl_bukti='"+ctgl+"',   ket='"+cket+"', username='', tgl_update='', nm_skpd='"+cnmskpd+"', total='"+ctotal+"',   no_tagih='"+ctagih+"', sts_tagih='"+cstatus+"', status='"+cjenis_bayar+"', tgl_tagih='"+ctgltagih+"', jns_spp='"+cjenis+"', jenis='"+jns+"', kontrak='"+kontrak+"' where no_bukti='"+cno_hide+"' AND kd_skpd='"+cskpd+"' "; 
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/update_penagihan_header_ar',
                data     : ({st_query:lcquery,tabel:'trhtagih',cid:'no_bukti',lcid:cno,lcid_h:cno_hide,status : cjenis_bayar}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        
                        if ( status=='1' ){
                            swal("Error", "Nomor Bukti sudah dipakai", "error");
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
                                        ckdgiat    = rows[p].kd_kegiatan;
                                        cnmgiat    = rows[p].nm_kegiatan;
                                        crek       = rows[p].kd_rek5;
                                        cnmrek     = rows[p].nm_rek5;
                                        cnilai     = angka(rows[p].nilai);
                                        crek5      = rows[p].kd_rek;
                        
                                        if ( p > 0 ) {
                                           csql = csql+","+"('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"')";
                                        } else {
                                            csql = "values('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"')";                                            
                                        }
                              }
                                
                                                  
                              $(document).ready(function(){
                                    $.ajax({
                                         type     : "POST",   
                                         dataType : 'json',                 
                                         data     : ({tabel_detail:'trdtagih',no_detail:cno,sql_detail:csql,
                                                      nomor:a_hide,lcid:a,lcid_h:a_hide}),
                                         url      : '<?php echo base_url(); ?>/index.php/tukd/update_penagihan_detail_ar',
                                         success  : function(data){                        
                                                    status = data;  
													if(status=='1'){
														$("#nomor_hide").attr("Value",cno) ;
														$("#no_simpan").attr("Value",cno) ;
														$('#dg1').edatagrid('unselectAll');
														swal("Berhasil", "Data Tersimpan", "success");
														lcstatus = 'edit';
														$('#dg1').edatagrid('unselectAll');
														} 
														else {               
														swal("Error", "Detail data Gagal simpan", "error");
                                                    } 
                                                    }
                                                    });
                                }); 
							}
                        if ( status=='0' ){
                            swal("Error", "Gagal simpan", "error");
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
        
        var sisa     = angka(document.getElementById('sisa').value);             
        var nil      = angka(document.getElementById('nilai').value);        
        var sisasp2d = angka(document.getElementById('sisasp2d').value);
        var tot      = 0;
        tot          = sisa - nil;
        
        if (nil > sisasp2d) {    
					swal("Error", "Nilai Melebihi Sisa Sp2d", "error");
                    exit();
        } else {
            if (tot < 0){
					swal("Error", "Nilai Melebihi Sisa", "error");
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
        <a id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();load_detail();">Tambah</a>               
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
                <td>No. Penagihan</td>
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
					<a id="edit" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_transout2();">Simpan Edit</a>
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
    <table>
        <tr>
            <td>Kode Kegiatan</td>
            <td>:</td>
            <td width="300"><input id="giat" name="giat" style="width: 200px;" /></td>
            <td>Nama Kegiatan</td>
            <td>:</td>
            <td><input type="text" id="nmkegiatan" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>        
         <tr>
            <td >Kode Rekening</td>
            <td>:</td>
            <td><input id="rek" name="rek" style="width: 200px;" />
            <input id="rek1" name="rek1" style="width: 200px; border:0;" readonly="true"/></td>
            <td >Nama Rekening</td>
            <td>:</td>
            <td><input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>        
        <tr>
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
            <td >Status</td>
            <td>:</td>
            <td><input type="text" id="status_ang" readonly="true" style="text-align:right;border:0;width: 150px;"/></td>            
        </tr>
        <tr>
            <td >Nilai</td>
            <td>:</td>
            <td><input type="text" id="nilai" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:sisa_bayar();"/></td>            
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
                <td>Total</td>
                <td>:</td>
                <td><input type="text" id="total1" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;"/></td>
            </tr>
        </table>
        <table id="dg2" title="Input Rekening" style="width:1000px;height:270px;"  >  
        </table>  
     
    </fieldset>  
</div>
</body>
</html>