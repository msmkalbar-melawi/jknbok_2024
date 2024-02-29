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
                      
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 650,
                width: 1000,
                modal: true,
                autoOpen:false                
            });              
            $("#tagih").hide();
            get_skpd();
			get_tahun();
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
          get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,sts);   
          load_detail();                                             
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
                var lalu = rowData.lalu;
                sisa = anggaran-lalu;
                $("#rek1").attr("value",rowData.kd_rek);
                $("#nmrek").attr("value",rowData.nm_rek5);
                $('#sisa').attr('value',number_format(sisa,2,'.',','));
                document.getElementById('nilai').select();
           }
        });                        
    }); 
    
    
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
       
     
    function get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,sts){
        $("#nomor").attr("value",nomor);
        $("#nomor_hide").attr("value",nomor);
        $("#no_simpan").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#keterangan").attr("value",ket);        
        $("#beban").attr("value",jns);
        $("#total").attr("value",number_format(tot,2,'.',','));
        $("#notagih").attr("value",notagih);        
        $("#tgltagih").datebox("setValue",tgltagih);    
        $("#status").attr("checked",false);
        $("#status_byr").attr("value",sts);                  
        if (ststagih==1){            
            $("#status").attr("checked",true);
            $("#tagih").show();
        } else {
            $("#status").attr("checked",false);
            $("#tagih").hide();
        }                                  
    }
    
    
    function kosong(){
        cdate = '<?php echo date("Y-m-d"); ?>';        
        $("#nomor").attr("value",'');
        $("#nomor_hide").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#tanggal").datebox("setValue",cdate);
        $("#keterangan").attr("value",'');        
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
        var nil     = document.getElementById('nilai').value;        
        
        tot         = sisa - angka(nil);

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
        }     
    }     
    
    function tambah(){
        var nor = document.getElementById('nomor').value;
        var tot = document.getElementById('total').value;
        var kd  = document.getElementById('skpd').value;
        $('#dg2').edatagrid('reload');
        $('#total1').attr('value',tot);
        $('#giat').combogrid('setValue','');
        $('#rek').combogrid('setValue','');
        var tgl = $('#tanggal').datebox('getValue');
        if (kd != '' && tgl != '' && nor !=''){            
            $("#dialog-modal").dialog('open'); 
            load_detail2();           
        } else {
            alert('Harap Isi Kode SKPD, Tanggal Transaksi & Jenis Beban SP2D ') ;         
        }
    }
    
    function kosong2(){        
        $('#giat').combogrid('setValue','');
        $('#sp2d').combogrid('setValue','');
        $('#rek').combogrid('setValue','');
        $('#sisasp2d').attr('value','0');
        $('#sisa').attr('value','0');
        $('#nilai').attr('value','0');
        $('#rek1').attr('value','');
        $('#nmgiat').attr('value','');        
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
        var cnomor = document.getElementById('nomor').value;
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
    
    
    function simpan_transout() {
        var cno      = (document.getElementById('nomor').value).split(" ").join("");
        var cno_hide = document.getElementById('nomor_hide').value;
        var cjenis_bayar = document.getElementById('status_byr').value;
        var ctgl     = $('#tanggal').datebox('getValue');
        var cskpd    = document.getElementById('skpd').value;
        var cnmskpd  = document.getElementById('nmskpd').value;
        var cket     = document.getElementById('keterangan').value;
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
        var ctgltagih = '2014-12-1';
        var ctotal    = angka(document.getElementById('total').value);        
        
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
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
	//---------------------------
			lcinsert     = " ( no_bukti,  tgl_bukti,  ket,        username, tgl_update, kd_skpd,     nm_skpd,       total,        no_tagih,     sts_tagih,  status ,   tgl_tagih,       jns_spp      ) " ; 
            lcvalues     = " ( '"+cno+"', '"+ctgl+"', '"+cket+"', '',       '',         '"+cskpd+"', '"+cnmskpd+"', '"+ctotal+"', '"+ctagih+"', '"+cstatus+"','"+cjenis_bayar+"', '"+ctgltagih+"', '"+cjenis+"' ) " ;
            
            lcinsert_ju  = " ( no_voucher, tgl_voucher, ket,        username, tgl_update, kd_skpd,     nm_skpd,       total_d,      total_k,      tabel ) " ;
			lcvalues_ju  = " ( '"+cno+"',  '"+ctgl+"',  '"+cket+"', '',       '',         '"+cskpd+"', '"+cnmskpd+"', '"+ctotal+"', '"+ctotal+"', '0'   ) " ;

            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_penagihan_ar',
                    data     : ({tabel    : 'trhtagih',  kolom    :lcinsert,    nilai    : lcvalues,    cid    : 'no_bukti',   lcid    : cno,
                                 tabel_ju : 'trhju_pkd', kolom_ju :lcinsert_ju, nilai_ju : lcvalues_ju, cid_ju : 'no_voucher', lcid_ju : cno,
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
                                        ckdgiat    = rows[p].kd_kegiatan;
                                        cnmgiat    = rows[p].nm_kegiatan;
                                        crek       = rows[p].kd_rek5;
                                        cnmrek     = rows[p].nm_rek5;
                                        cnilai     = angka(rows[p].nilai);
                                        crek5      = rows[p].kd_rek;
                        
                                        if ( p > 0 ) {
                                           csql = csql+","+"('"+cnobukti+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"')";
                                        } else {
                                            csql = "values('"+cnobukti+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"')";                                            
                                        }
                                    }
                                                  
                                    $(document).ready(function(){
                                    $.ajax({
                                         type     : "POST",   
                                         dataType : 'json',                 
                                         data     : ({tabel_detail:'trdtagih',no_detail:cno,sql_detail:csql,proses:'detail', status_byr : cjenis_bayar}),
                                         url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_penagihan_ar',
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
		 lcquery    = " UPDATE trhtagih  SET no_bukti='"+cno+"',   tgl_bukti='"+ctgl+"',   ket='"+cket+"', username='', tgl_update='', kd_skpd='"+cskpd+"', nm_skpd='"+cnmskpd+"', total='"+ctotal+"',   no_tagih='"+ctagih+"', sts_tagih='"+cstatus+"', status='"+cjenis_bayar+"', tgl_tagih='"+ctgltagih+"', jns_spp='"+cjenis+"' where no_bukti='"+cno_hide+"' "; 
            lcquery_ju = " UPDATE trhju_pkd SET no_voucher='"+cno+"', tgl_voucher='"+ctgl+"', ket='"+cket+"', username='', tgl_update='', kd_skpd='"+cskpd+"', nm_skpd='"+cnmskpd+"', total_d='"+ctotal+"', total_k='"+ctotal+"', tabel='0' where no_voucher='"+cno_hide+"' " ;
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/update_penagihan_header_ar',
                data     : ({st_query:lcquery,st_query_ju:lcquery_ju,tabel:'trhtagih',cid:'no_bukti',lcid:cno,lcid_h:cno_hide,status : cjenis_bayar}),
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
                                        ckdgiat    = rows[p].kd_kegiatan;
                                        cnmgiat    = rows[p].nm_kegiatan;
                                        crek       = rows[p].kd_rek5;
                                        cnmrek     = rows[p].nm_rek5;
                                        cnilai     = angka(rows[p].nilai);
                                        crek5      = rows[p].kd_rek;
                        
                                        if ( p > 0 ) {
                                           csql = csql+","+"('"+cnobukti+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"')";
                                        } else {
                                            csql = "values('"+cnobukti+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"')";                                            
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
                                                    alert('simpan detail');
                                                    alert(status); 
                                                    if ( status=='5' ) {               
                                                        alert('Data Detail Gagal Tersimpan');
                                                    } 
                                                    }
                                                    });
                                }); 
                                         
                                
                                alert('Data Tersimpan...!!!');
                                lcstatus = 'edit';
                                $("#nomor_hide").attr("Value",cno) ;
                                $("#no_simpan").attr("Value",cno) ;
                                $('#dg1').edatagrid('unselectAll');
                                exit();
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
        
        var sisa     = angka(document.getElementById('sisa').value);             
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
                             
       
                        
    function hit_lalu(){
        var cgiat = $('#giat').combogrid('getValue');
        var csp2d = $('#sp2d').combogrid('getValue');
        var crek  = $('#rek').combogrid('getValue');
        var cno   = document.getElementById('nomor').value;
        var ctgl  = $('#tanggal').combogrid('getValue');
        var ckode = document.getElementById('skpd').value;
        var jns   = document.getElementById('jenis').value;     
        $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/tukd/out_lalu',
                data: ({giat:cgiat,sp2d:csp2d,rek:crek,nomor:cno,tgl:ctgl,skpd:ckode,jenis:jns}),
                dataType:"json",
                success:function(data){
                    $.each(data,function(i,n){
                        clalu = n['lalu'];  
                        $('#sisa').attr('value',clalu);                    
                    });
            }
        });                      
    }
    
    
    function  hit_lalu2(cgiat,nosp2d,rek5,no,ctgl,cskpd){         
        $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/tukd/out_lalu',
                data: ({giat:cgiat,sp2d:nosp2d,rek:rek5,nomor:no,tgl:ctgl,skpd:cskpd}),
                dataType:"json",
                success:function(data){
                    //clalu =data;
                    $.each(data,function(i,n){
                        clalu = n['lalu'];                                          
                   });
            }
        });       
        return clalu;           
    }
   
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
                <td style="border-bottom: double 1px red;"><i>No. BKU<i></td>
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
                         <option value="">......</option>     
                         <option value="1">SELESAI</option>
                         <option value="0">BELUM SELESAI</option>
                     </select>
                 </td>           
                <td colspan="3" align="right"><a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();load_detail();">Baru</a>
                    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_transout();">Simpan</a>
		            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
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
            <td><input id="rek" name="rek" style="width: 200px;" /><input id="rek1" name="rek1" style="width: 200px;" /></td>
            <td >Nama Rekening</td>
            <td>:</td>
            <td><input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>        
        <tr>
            <td >Sisa</td>
            <td>:</td>
            <td><input type="text" id="sisa" readonly="true" style="text-align:right;border:0;width: 150px;"/></td>            
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
        <table id="dg2" title="Input Rekening" style="width:950px;height:270px;"  >  
        </table>  
     
    </fieldset>  
</div>
</body>
</html>