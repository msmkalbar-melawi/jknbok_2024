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
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    
	<style>    
    #tagih {
        position: relative;
        width: 500px;
        height: 70px;
        padding: 0.4em;
    }  
    
     .satu{
            width: 157px;
     }

     .dua{
            width: 170px;
     }
    
    </style>
    <script type="text/javascript">
    
    var kode  = '';
    var giat  = '';
    var jenis = '';
    var nomor = '';
    var cid   = 0;
                      
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 750,
                width: 1100,
                modal: true,
                autoOpen:false                
            });         
            $( "#dialog-modal-rekening" ).dialog({
                height: 260,
                width: 900,
                modal: true,
                autoOpen:false                
            });            
			
            $("#tagih").hide();
            get_skpd();
			get_tahun();
			$("#ketsdana1").attr('disabled',true);
        });    
     
    $(function(){ 
      $('#dg').edatagrid({
		/*rowStyler:function(index,row){
        if ((row.ketlpj==1) ||(row.ketspj==1)){
		   return 'color:#02087f;';
        }
		},*/
      rowStyler:function(index,row){
        if ((row.ketup==1 && row.ketval==1)){
          return 'background-color:#00a5ff;';
        }else if ((row.ketup==1)){
          return 'background-color:#12cc2e;';
        }
      },
		  url: '<?php echo base_url(); ?>/index.php/cms/ctransout_cms/load_transout',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'ck',title:'',checkbox:'true',width:20},
			{field:'no_voucher',title:'Nomor',width:15},
            {field:'tgl_voucher',title:'Tanggal',width:22 },
            {field:'kd_skpd',title:'Kode SKPD',width:23,align:"left"},
            {field:'ket',title:'Keterangan',width:100,align:"left"},
			{field:'ketup',title:'UPL',width:10,align:"left"},
			{field:'ketval',title:'VAL',width:10,align:"left"},
            {field:'stpot',title:'POT',width:10,align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor = rowData.no_voucher;
          tgl   = rowData.tgl_voucher;
          nokas_pot = rowData.no_kas_pot;
          tglpot   = rowData.tgl_pot;
          ketpot   = rowData.ketpot;
          kode  = rowData.kd_skpd;
          nama  = rowData.nm_skpd;
          ket   = rowData.ket;          
          jns   = rowData.jns_beban; 
          tot   = rowData.total;
          notagih  = rowData.no_tagih;
          tgltagih = rowData.tgl_tagih;
          ststagih = rowData.sts_tagih; 
          vpay     = rowData.pay;         
          statup     = rowData.ketup;         
          statval    = rowData.ketval; 
          statpot    = rowData.stpot; 
          srekwal     = rowData.rekening_awal;         
          snmrek_awal    = rowData.nm_rekening_tujuan;
          sbank_tjn     = rowData.bank_tujuan;         
          srek_tjn    = rowData.rekening_tujuan;
          ssp2d       = rowData.no_sp2d;   
          kettjnn       = rowData.ket_tujuan;        
 
          get(nomor,nomor_tgl,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,vpay,nokas_pot,tglpot,ketpot,statup,statval,statpot,srekwal,snmrek_awal,sbank_tjn,srek_tjn,ssp2d,kettjnn);          
          load_detail();
          load_detail_rekbank();
          //load_detail_pot(nokas_pot);
          /*if (ststagih !='1'){   
          load_detail(); 
          }          */                                  
        },
        onDblClickRow:function(rowIndex,rowData){         
            section2();                  
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
            {field:'no_bukti',title:'No Bukti',hidden:"true"},
            {field:'no_sp2d',title:'No SP2D',hidden:"true"},
    	    {field:'kd_kegiatan',title:'Kegiatan',width:50},
            {field:'nm_kegiatan',title:'Nama Kegiatan',hidden:"true"},
            {field:'kd_rek5',title:'Kode Rekening',width:30},
            {field:'nm_rek5',title:'Nama Rekening',width:100,align:"left"},
            {field:'nilai',title:'Nilai',width:70,align:"right"},
            {field:'sumber',title:'Sumber',width:20,align:"center"},
			{field:'lalu',title:'Sudah Dibayarkan',align:"right",width:30,hidden:'true'},
            {field:'sp2d',title:'SP2D Non UP',align:"right",width:30,hidden:'true'},
            {field:'anggaran',title:'Anggaran',align:"right",width:30,hidden:'true'}
            ]]
        });  

    $('#dg5').edatagrid({  
            toolbar:'#toolbar2',
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
            {field:'tgl_bukti',
    		title:'Tanggal',    		
            hidden:"true"},
    	    {field:'rekening_awal',
    		title:'rekening awal',
    		hidden:"true"},
            {field:'nm_rekening_tujuan',
    		title:'Nama',    		
            width:50},
            {field:'rekening_tujuan',
    		title:'Rek. Tujuan',
    		width:40},
            {field:'bank_tujuan',
    		title:'Bank',
    		hidden:"true"},
            {field:'kd_skpd',
    		title:'SKPD',           
            hidden:'true'},
            {field:'nilai',
    		title:'Nilai',
    		width:35,
            align:"right"}          
            ]]
        });  		
        
    $('#dgpajak').edatagrid({
    			     url            : '<?php echo base_url(); ?>/index.php/tukd/pot',
                     idField        : 'id',
                     //toolbar        : "#toolbar",              
                     rownumbers     : "true", 
                     fitColumns     : false,
                     autoRowHeight  : "true",
                     singleSelect   : false,
                     columns:[[
                        {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                        {field:'kd_rek5',title:'Rekening',width:100,align:'left'},			
    					{field:'nm_rek5',title:'Nama Rekening',width:317},
    					{field:'nilai',title:'Nilai',width:250,align:"right"},
                        {field:'hapus',title:'Hapus',width:100,align:"center",
                        formatter:function(value,rec){ 
                        return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_pot();" />';
                        }
                        }
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
            {field:'no_bukti',title:'No Bukti',hidden:"true",width:30},
            {field:'no_sp2d',title:'No SP2D',width:40},
    	    {field:'kd_kegiatan',title:'Kegiatan',width:50},
            {field:'nm_kegiatan',title:'Nama Kegiatan',hidden:"true",width:30},
            {field:'kd_rek5',title:'Kode Rekening',width:25,align:'center'},
            {field:'nm_rek5',title:'Nama Rekening',align:"left",width:40},
            {field:'nilai',title:'Rupiah',align:"right",width:30},
			{field:'sumber',title:'Sumber',align:"center",width:20},
            {field:'lalu',title:'Sudah Dibayarkan',align:"right",width:30},
            {field:'sp2d',title:'SP2D Non UP',align:"right",width:30},
            {field:'anggaran',title:'Anggaran',align:"right",width:30}
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
		  cek_status_spj();
          }
        });
        
         $('#tglkas').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();    
            	return y+'-'+m+'-'+d;
            }
        });
 
        $('#rekening_awal').combogrid({            
            url:"<?php echo base_url(); ?>index.php/cms/tukd_cms/cari_rekening",
            panelWidth:150,
            idField:'rek_bend',
            textField:'rek_bend',
            columns:[[
                {field:'rek_bend',title:'Rekening Bendahara',width:130}
            ]]
        });
        
        $('#rekening_tujuan').combogrid({            
            url:"<?php echo base_url(); ?>index.php/cms/tukd_cms/cari_rekening_tujuan/1",
            panelWidth:730,
            idField:'rekening',
            textField:'rekening',
            mode:'remote',
            columns:[[
                {field:'rekening',title:'Rekening',width:120},
                {field:'nm_rekening',title:'Nama',width:290},
                {field:'ket',title:'Keterangan Tambahan',width:290}
            ]],
            onSelect:function(rowIndex,rowData){
            $("#nm_rekening_tujuan").attr("Value",rowData.nm_rekening);
            $("#kd_bank_tujuan").combogrid("setValue",rowData.nmbank);          
            
            document.getElementById('nilai_trf').select();                              
          }
        });
 
        $('#kd_bank_tujuan').combogrid({            
            url:"<?php echo base_url(); ?>index.php/cms/tukd_cms/cari_bank",
            panelWidth:150,
            idField:'nama',
            textField:'nama',
            columns:[[
                {field:'nama',title:'Bank',width:130}
            ]]
        });
		
         $('#tglvoucher').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();    
                	return y+'-'+m+'-'+d;
                }
         });         
        
        // $('#tgltagih').datebox({  
//                required:true,
//                formatter :function(date){
//                	var y = date.getFullYear();
//                	var m = date.getMonth()+1;
//                	var d = date.getDate();    
//                	return y+'-'+m+'-'+d;
//                }
//         });
//                    
 //       $('#skpd').combogrid({  
//           panelWidth:700,  
//           idField:'kd_skpd',  
//           textField:'kd_skpd',  
//           mode:'remote',                      
//           url:'<?php echo base_url(); ?>index.php/tukd/skpd',  
//           columns:[[  
//               {field:'kd_skpd',title:'Kode SKPD',width:100},  
//               {field:'nm_skpd',title:'Nama SKPD',width:700}    
//           ]],  
//           onSelect:function(rowIndex,rowData){
//               kode = rowData.kd_skpd;               
//               $("#nmskpd").attr("value",rowData.nm_skpd);
//               $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd',queryParams:({kd:kode,jenis:'52'})});                 
//           } 
//       });              
         //alert(kode);                                 
        $('#giat').combogrid({  
           panelWidth:700,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd',
           queryParams:({kd:kode,jenis:jenis}),             
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:140},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               idxGiat = rowIndex;
               $('#rek').combogrid('clear');               
               giat = rowData.kd_kegiatan;
               var jnsbeban = document.getElementById('beban').value;
               var nomor = document.getElementById('nomor').value;
               var kode = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
               
               $("#nmgiat").attr("value",rowData.nm_kegiatan);
               $("#sp2d").combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_sp2d_transout',queryParams:({jenis:jnsbeban,giat:giat,kd:kode,bukti:nomor})});
	           $('#sp2d').combogrid('setValue','');
			   load_total_spd();
			   load_total_trans();
			   sumber_dnkosong(); 
           }  
        });
 
        $('#rekening_awal').combogrid({            
            url:"<?php echo base_url(); ?>index.php/cms/tukd_cms/cari_rekening",
            panelWidth:150,
            idField:'rek_bend',
            textField:'rek_bend',
            columns:[[
                {field:'rek_bend',title:'Rekening Bendahara',width:130}
            ]]
        });
 
         $('#rekpajak').combogrid({  
                   panelWidth : 700,  
                   idField    : 'kd_rek5',  
                   textField  : 'kd_rek5',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/tukd/rek_pot',  
                   columns:[[  
                       {field:'kd_rek5',title:'Kode Rekening',width:100},  
                       {field:'nm_rek5',title:'Nama Rekening',width:700}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                       $("#nmrekpajak").attr("value",rowData.nm_rek5.toUpperCase());
                   }  
                   });
        
        $('#notagih').combogrid({ 
           panelWidth:420,  
           idField:'no_tagih',  
           textField:'no_tagih',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/load_no_penagihan',
           queryParams:({kd:kode}),             
           columns:[[  
               {field:'no_tagih',title:'No Penagihan',width:140},  
               {field:'tgl_tagih',title:'Tanggal',width:140},
               {field:'kd_skpd',title:'SKPD',width:140}
           ]],  
           onSelect:function(rowIndex,rowData){
            var ststagih='1';
            $("#tgltagih").attr("value",rowData.tgl_tagih);
            $("#skpd").attr("value",rowData.kd_skpd);
            $("#keterangan").attr("value",rowData.ket);
            $("#beban").attr("value",'1');
            $("#total").attr("value",number_format(rowData.nil,2,'.',','));  
            load_detail_tagih();
            tombol(ststagih);
                                                                                                                                                    
           }  
        });
        
        $('#sp2d').combogrid({  
           panelWidth:200,  
           idField:'no_sp2d',  
           textField:'no_sp2d',  
           mode:'local',                        
           columns:[[  
               {field:'no_sp2d',title:'Nomor Sp2d',width:100},  
               {field:'tgl_sp2d',title:'Tanggal',width:100}    
           ]],  
           onSelect:function(rowIndex,rowData){
			  var nosp2d   = rowData.no_sp2d; 
              var nilsp2d = rowData.nilai;
              var tglsp2d = rowData.tgl_sp2d;              
              var nobukti = document.getElementById('nomor').value;
              var tglbukti = document.getElementById('tanggal').value;
              var jnsbeban = document.getElementById('beban').value;                                  
              //var sisa = angka(rowData.sisa);              
              //alert(nosp2d+'/'+nilsp2d+'/'+tglsp2d+'/'+nobukti+'/'+jnsbeban+'/'+sisa);
              var kode = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
              var giat = $('#giat').combogrid('getValue');
              var jj = 0;
              var frek = '';             
              $('#rek').combogrid('setValue','');
              if (tglsp2d < tglbukti){               
                alert('Kesalahan, Tanggal Sp2d lebih kecil Dari Tanggal Bukti');
                exit();               
               }                             
              $('#dg1').datagrid('selectAll');
              var rows = $('#dg1').datagrid('getSelections');     
		      for(var p=0;p<rows.length;p++){ 
                    cgiat   = rows[p].kd_kegiatan;
                    rek5    = rows[p].kd_rek5;
                    nil     = angka(rows[p].nilai);
                    sisa    = sisa - nil;                   
                    if (cgiat==giat){                        
                        if (jj>0){   
                            frek = frek+','+rek5;
                        } else {
                            frek = rek5;
                        }
                        jj++;
                    }                                                                                                                                                                                                  
              }             
              $('#sisasp2d').attr('value',number_format(sisa,2,'.',','));
              $('#dg1').edatagrid('unselectAll');                              
              $('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/cms/tukd_cms/load_rek',
                                   queryParams:({sp2d:nosp2d,
                                                 no:nobukti,
                                                 jenis:jnsbeban,
                                                 giat:giat,
                                                 kd:kode,
                                                 rek:''})
                                   });
			 load_sisa_bank();
			 //load_sisa_tunai();
			 load_sisa_pot_ls();
			 total_sisa_pot();
           }  
       });
        
        $('#rek').combogrid({  
           panelWidth:650,  
           idField:'kd_rek5',  
           textField:'kd_rek5',  
           mode:'remote',                                   
           columns:[[  
               {field:'kd_rek5',title:'Kode Rekening',width:70,align:'center'},  
               {field:'nm_rek5',title:'Nama Rekening',width:200},
               {field:'lalu',title:'Lalu',width:120,align:'right'}
           ]],
           onSelect:function(rowIndex,rowData){
                $('#ang').attr('value',number_format(0,2,'.',','));
                $('#ang_semp').attr('value',number_format(0,2,'.',','));
                $('#ang_ubah').attr('value',number_format(0,2,'.',','));                   

                var jenis = document.getElementById('beban').value;
                var anggaran = rowData.anggaran;
                var anggaran_semp = rowData.anggaran_semp;
                var anggaran_ubah = rowData.anggaran_ubah;
                var lalu = rowData.lalu;
                var sp2d = rowData.sp2d;
                if (jenis=='1'){
                    sisa = anggaran-lalu;    
                    sisa_semp = anggaran_semp-lalu;    
                    sisa_ubah = anggaran_ubah-lalu; 
                   $('#ang').attr('value',number_format(anggaran,2,'.',','));
                   $('#ang_semp').attr('value',number_format(anggaran_semp,2,'.',','));
                   $('#ang_ubah').attr('value',number_format(anggaran_ubah,2,'.',','));
                  

                } else {
                    sisa = sp2d-lalu;                    
                    sisa_semp = sp2d-lalu;                    
                    sisa_ubah = sp2d-lalu; 
                    $('#ang').attr('value',number_format(sp2d,2,'.',','));
                    $('#ang_semp').attr('value',number_format(sp2d,2,'.',','));
                    $('#ang_ubah').attr('value',number_format(sp2d,2,'.',','));                   
                }
                $('#lalu').attr('value',number_format(lalu,2,'.',','));
                $('#lalu_semp').attr('value',number_format(lalu,2,'.',','));
                $('#lalu_ubah').attr('value',number_format(lalu,2,'.',','));
                $('#sisa').attr('value',number_format(sisa,2,'.',','));
                $('#sisa_semp').attr('value',number_format(sisa_semp,2,'.',','));
                $('#sisa_ubah').attr('value',number_format(sisa_ubah,2,'.',','));
                $('#nmrek').attr('value',rowData.nm_rek5);
                document.getElementById('nilai').select();
				total_sisa_pot();
				total_sisa_spd();
				
				var kode_keg = $('#giat').combogrid('getValue');
				var nosp2d = $('#sp2d').combogrid('getValue');
				var koderek = $('#rek').combogrid('getValue');
				var kode_s = document.getElementById('skpd').value;
				var jnsbeban = document.getElementById('beban').value;
				
				sumber_dnkosong();
                var kd_giat = $('#giat').combogrid('getValue');
                var rek = rowData.kd_rek5;
                var ctgl = document.getElementById('tanggal').value;

                $('#sumber_dn').combogrid({  
                panelWidth : 700,  
                idField    : 'sumber',  
                textField  : 'sumber',  
                mode       : 'remote',
                url        : '<?php echo base_url(); ?>/index.php/tukd/ambil_sdana',
                queryParams:({tgl:ctgl,skpd:kode,giat:kd_giat,kdrek5:rek}),
                columns:[[  
                    {field:'sumber',title:'Sumber Dana',width:98},
                    {field:'anggaran',title:'S. Dana Penyusunan',width:98},
    	            {field:'anggaran_semp',title:'S. Dana Penyempurnaan',width:98},
                    {field:'anggaran_ubah',title:'S. Dana Perubahan',width:98},
                    {field:'kegiatan',title:'kegiatan',width:120},
                    {field:'kd_rek5',title:'rekening',width:98}
                ]],  
                onSelect:function(rowIndex,rowData){
                    selectRow = rowData.sumber;
                    $('#ketsdana1').attr('value',rowData.sumber);
                    $('#ang_sd').attr('value',rowData.anggaran);
                    $('#ang_semp_sd').attr('value',rowData.anggaran_semp);
                    $('#ang_ubah_sd').attr('value',rowData.anggaran_ubah);
                    load_total_sdana(kd_giat,rek,selectRow);   
                }  
                });

				
				
           }
        });                        
    
		$("#sumber_dn").combogrid({
            panelWidth:300,
            columns   : [[
            {field:'',title:'Sumber Dana',width:100},
            {field:'',title:'Anggaran',width:190}
            ]]                         
		}); 


	});

    function load_total_sdana(giat,kode_rek,sumber){
        var kode = document.getElementById('skpd').value;
		var ang_sd = angka(document.getElementById('ang_sd').value);
		var ang_semp_sd = angka(document.getElementById('ang_semp_sd').value);
		var ang_ubah_sd = angka(document.getElementById('ang_ubah_sd').value);
        $('#lalu_sd').attr('value','9,999,999,999,999.00');
		$('#lalu_semp_sd').attr('value','9,999,999,999,999.00');
		$('#lalu_ubah_sd').attr('value','9,999,999,999,999.00');		
		$(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/load_total_sdana2",
            dataType:"json",
			data: ({giat:giat,kode:kode,sumber:sumber,kode_rek:kode_rek}),
            success:function(data){ 
                $.each(data, function(i,n){
					var total_trans_sumber2 = angka(n['total_trans_sumber']);
					$('#lalu_sd').attr("value",n['total_trans_sumber']);
					$('#lalu_semp_sd').attr("value",n['total_trans_sumber']);
					$('#lalu_ubah_sd').attr("value",n['total_trans_sumber']);
					$('#sisa_sd').attr("value",number_format((ang_sd - total_trans_sumber2),2,'.',','));
					$('#sisa_semp_sd').attr("value",number_format((ang_semp_sd - total_trans_sumber2),2,'.',','));
					$('#sisa_ubah_sd').attr("value",number_format((ang_ubah_sd - total_trans_sumber2),2,'.',','));
				});
			}
         });
        });
		
	}    
	
	function sisa_sumber(){
		var ang_sd = angka(document.getElementById('ang_sd').value);
		var ang_semp_sd = angka(document.getElementById('ang_semp_sd').value);
		var ang_ubah_sd = angka(document.getElementById('ang_ubah_sd').value);
		var lalu_sd = angka(document.getElementById('lalu_sd').value);
		var lalu_semp_sd = angka(document.getElementById('lalu_semp_sd').value);
		var lalu_ubah_sd = angka(document.getElementById('lalu_ubah_sd').value);
		$('#sisa_sd').attr("value",number_format((ang_sd - lalu_sd),2,'.',','));
		$('#sisa_semp_sd').attr("value",number_format((ang_semp_sd - lalu_semp_sd),2,'.',','));
		$('#sisa_ubah_sd').attr("value",number_format((ang_ubah_sd - lalu_ubah_sd),2,'.',','));
	}

    function sumber_dnkosong(){
        $("#sumber_dn").combogrid("clear");
        $('#ketsdana1').attr('value','');
        var sumber_dn = $('#sumber_dn').combogrid('grid');    
        sumber_dn.datagrid('loadData',[]);      
        kosong1();                  
    }	
	
	
	
    function kosong1(){
        $("#sumber_dn").attr("value",'0.00');
        $("#sumber_semp_dn").attr("value",'0.00');
        $("#sumber_ubah_dn").attr("value",'0.00');
        $("#lalu_sd").attr("value",'9,999,999,999,999.00');
		$("#lalu_semp_sd").attr("value",'9,999,999,999,999.00');
		$("#lalu_ubah_sd").attr("value",'9,999,999,999,999.00');
		sisa_sumber();
    }	
	
function validate_sumberdana(){
	var kode_s   = document.getElementById('skpd').value  ;
    var kode_keg = $('#giat').combogrid('getValue') ;
    var koderek = $('#rek').combogrid('getValue') ;
	var nosp2d = $('#sp2d').combogrid('getValue');
	var jnsbeban = document.getElementById('beban').value;
}
	
	
	function hitung(){
        var a = angka(document.getElementById('rek_pad').value);
        var b = angka(document.getElementById('rek_dak').value);
        var c = angka(document.getElementById('rek_dau').value); 
        var d = angka(document.getElementById('rek_dbhp').value); 
        var f = angka(document.getElementById('rek_daknf').value); 
        var g = angka(document.getElementById('rek_did').value); 
		var e = a+b+c+d+f+g;
        $("#nilai").attr("value",number_format(e,2,'.',','));
	   }
	
    function load_sisa_bank(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/load_sisa_bank",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#sisa_bank").attr("value",n['sisa']);
                   // $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
        });
    }
	
	function load_total_spd(){
		var giat = $('#giat').combogrid('getValue');
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
	
	function load_total_trans(){
		var no_simpan = document.getElementById('no_simpan').value;  
		var giat = $('#giat').combogrid('getValue');
		var kode = document.getElementById('skpd').value;
        
		$(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/load_total_trans",
            dataType:"json",
			data: ({giat:giat,kode:kode,no_simpan:no_simpan}),
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#tot_trans").attr("value",n['total']);
                });
            }
         });
        });
    }
	
	 function load_sisa_tunai(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/load_sisa_tunai",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#sisa_tunai").attr("value",n['sisa']);
                });
            }
         });
        });
    }
	function load_sisa_pot_ls(){ 
       var sp2d_pot = $('#sp2d').combogrid('getValue');
       // var ckas = angka(document.getElementById('sisa_tunai').value);  

        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/load_sisa_pot_ls",
            dataType:"json",
			data: ({sp2d:sp2d_pot}),
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#pot_ls").attr("value",n['sisa']);
                });
            }
         });
        });

    }
	function cek_status_ang(){
        var tgl_cek = $('#tanggal').datebox('getValue');
          $.ajax({
            url:'<?php echo base_url(); ?>index.php/tukd/cek_status_ang',
                data: ({tgl_cek:tgl_cek}),
		   //queryParams    : ({ tgl_cek:tgl_cek }),
            type: "POST",
            dataType:"json",                         
            success:function(data){
          $("#status_ang").attr("value",data.status_ang);
          }  
            });
        }
		
		function cek_status_spj(){
        var tgl_cek = $('#tanggal').datebox('getValue');
          $.ajax({
            url:'<?php echo base_url(); ?>index.php/tukd/cek_status_spj',
                data: ({tgl_cek:tgl_cek}),
		   //queryParams    : ({ tgl_cek:tgl_cek }),
            type: "POST",
            dataType:"json",                         
            success:function(data){
			status_spj = data.status_spj;
				if(status_spj==1){
					$('#save').linkbutton('disable');
					alert("SPJ sudah disahkan pada bulan ini.")
				} else{
					$('#save').linkbutton('enable');
				}
			}  
          });
        }
		
	function total_sisa_pot(){ 
		var lcjenis    	= document.getElementById('jns_tunai').value;    
		if(lcjenis=='BANK'){
			var ckas1 = angka(document.getElementById('sisa_bank').value);  
		}else{
			var ckas1 = angka(document.getElementById('sisa_tunai').value);  			
		}
		var cpot = angka(document.getElementById('pot_ls').value);  
		$('#total_sisa').attr('value',number_format(ckas1+cpot,2,'.',','));
	}
	   
	function total_sisa_spd(){ 
        var tot_spd   = angka(document.getElementById('tot_spd').value);  
       var tot_trans = angka(document.getElementById('tot_trans').value);  
		   totsisa = tot_spd-tot_trans;
		
	   $('#tot_sisa').attr('value',number_format(totsisa,2,'.',','));

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
        								kode = data.kd_skpd;
                                        //kegia();              
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
	 var jns1 = document.getElementById('beban').value;
     $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd',queryParams:({kd:kode,jenis:jns1})});  
    }     
    
    function hapus_detail_pot(){
        
        var vnospm        = document.getElementById('nomor').value;
        var dinas         = document.getElementById('skpd').value;
        
        var rows          = $('#dgpajak').edatagrid('getSelected');
        var ctotalpotspm  = document.getElementById('totalrekpajak').value ;
        
        bkdrek            = rows.kd_rek5;
        bnilai            = rows.nilai;
        
        var idx = $('#dgpajak').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Rekening : '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#dgpajak').datagrid('deleteRow',idx);     
            $('#dgpajak').datagrid('unselectAll');
              
             var urll = '<?php  echo base_url(); ?>index.php/tukd/dsimpan_pot_delete';
             $(document).ready(function(){
             $.post(urll,({cskpd:dinas,spm:vnospm,kd_rek5:bkdrek}),function(data){
             status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    alert('Data Telah Terhapus..!!');
                    exit();
                }
             });
             });    
             
             ctotalpotspm = angka(ctotalpotspm) - angka(bnilai) ;
             $("#totalrekpajak").attr("Value",number_format(ctotalpotspm,2,'.',','));
             validate_rekening();
             }     
        }    
               
    function hapus_detail(){
        var rows = $('#dg2').edatagrid('getSelected');
        cgiat = rows.kd_kegiatan;
        crek = rows.kd_rek5;
        cnil = rows.nilai;
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
        var kk = nomor;
        var ctgl = $('#tanggal').datebox('getValue');
        var cskpd = document.getElementById("skpd").value;//$('#skpd').combogrid('getValue');             
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/cms/ctransout_cms/load_dtransout',
                data: ({no:kk}),
                dataType:"json",
                success:function(data){                                          
					$('#dg1').datagrid('loadData',[]);     
					$('#dg1').edatagrid('reload');   
					$.each(data,function(i,n){                                    
                    no      = n['no_voucher'];
                    nosp2d  = n['no_sp2d'];                                                                    
                    giat    = n['kd_kegiatan'];
                    nmgiat  = n['nm_kegiatan'];
                    rek5    = n['kd_rek5'];
                    nmrek5  = n['nm_rek5'];
                    nil     = number_format(n['nilai'],2,'.',',');
					          csumber = n['sumber'];
                    clalu   = number_format(n['lalu'],2,'.',',');
                    csp2d   = number_format(n['sp2d'],2,'.',',');
                    canggaran = number_format(n['anggaran'],2,'.',',');                                                                                                                                                                                                                                                                             
					$('#dg1').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_kegiatan:giat,nm_kegiatan:nmgiat,kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil,sumber:csumber,lalu:clalu,sp2d:csp2d,anggaran:canggaran});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });                
           set_grid();                                                  
    }

    function load_detail_rekbank(){
        var kk = nomor;        
        var ctgl = $('#tanggal').datebox('getValue');
        var cskpd = document.getElementById("skpd").value;//$('#skpd').combogrid('getValue');             
         
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/cms/ctransout_cms/load_dtransout_transfercms',
                data: ({no:kk,skpd:cskpd}),
                dataType:"json",
                success:function(data){                                        
                    $('#dg5').datagrid('loadData',[]);     
                    $('#dg5').edatagrid('reload');   

                    $.each(data,function(i,n){                                    
                    no      = n['no_voucher'];
                    tgl     = n['tgl_voucher'];                                                                    
                    rekawal = n['rekening_awal'];
                    nmrektj = n['nm_rekening_tujuan'];
                    rektj   = n['rekening_tujuan'];
                    bank    = n['bank_tujuan'];  
                    kd_skpd = n['kd_skpd'];                  
                    nilai   = n['nilai'];  
                    $("#total_dtransfer").attr("value",n['total']);                                                                                                                                       
                    $('#dg5').edatagrid('appendRow',{no_bukti:no,tgl_bukti:tgl,rekening_awal:rekawal,nm_rekening_tujuan:nmrektj,rekening_tujuan:rektj,bank_tujuan:bank,kd_skpd:kd_skpd,nilai:nilai});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });                        
         $('body').ajaxComplete(function () {           
            var total_belanja = angka(document.getElementById('total').value);
            var total_transfer = angka(document.getElementById('total_dtransfer').value);
            var hasil = total_belanja - total_transfer;        
            $("#total_dpotongan").attr("value",number_format(hasil,2)); 
         });
        set_grid5();   
                                                           
    }
    
    //function load_detail_pot(){        
//        var no_kaspot = nokas_pot; //alert(nomor);
//        var ctgl = $('#tanggal').datebox('getValue');
//        var cskpd = document.getElementById("skpd").value;//$('#skpd').combogrid('getValue');             
//           $(document).ready(function(){
//            $.ajax({
//                type: "POST",
//                url: '<?php echo base_url(); ?>/index.php/tukd/load_dpot',
//                data: ({no:no_kaspot}),
//                dataType:"json",
//                success:function(data){                                          
//                    $.each(data,function(i,n){                                    
//                    no      = n['no_bukti'];
//                    rek5    = n['kd_rek5'];
//                    nmrek5  = n['nm_rek5'];
//                    nil     = number_format(n['nilai'],2,'.',',');                                           
//                    //$('#dg1').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_kegiatan:giat,nm_kegiatan:nmgiat,kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil,lalu:clalu,sp2d:csp2d,anggaran:canggaran});
//                    $('#dgpajak').edatagrid('appendRow',{kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil});                                                                                                                                                                                                                                                                                                                                                                                             
//                    });                                                                           
//                }
//            });
//           });                
//           set_grid();                                                  
//    }
    
    function load_detail_pot(nosts){
        //alert(nosts);
      $(function(){
        $('#dgpajak').datagrid('loadData',[]);     
        $('#dgpajak').edatagrid('reload');      
        $('#dgpajak').edatagrid({
  				url: '<?php echo base_url(); ?>/index.php/cms/ctransout_cms/load_dpot',
          queryParams:({no:nosts}),
          idField:'idx',
          toolbar:"#toolbar",              
          rownumbers:"true", 
          fitColumns:false,
          autoRowHeight:"true",
          singleSelect:false,    

          columns:[[
            {field:'id',title:'ID',hidden:"true"},                
            {field:'kd_rek5',title:'Nomor Rekening',width:150},
            {field:'nm_rek',title:'Nama Rekening',width:400},                
            {field:'nilai',title:'Nilai',align:'right',width:200}               
          ]]	
			});
		});
    }
    
    function load_detail_tagih(){        
        var kk = $('#notagih').combogrid('getValue'); 
        var ctgl = $('#tanggal').datebox('getValue');
        var cskpd = document.getElementById("skpd").value;//$('#skpd').combogrid('getValue');             
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/load_dtagih',
                data: ({no:kk}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    no      = n['no_bukti'];
                    nosp2d  = n['no_sp2d'];                                                                    
                    giat    = n['kd_kegiatan'];
                    nmgiat  = n['nm_kegiatan'];
                    rek5    = n['kd_rek'];
                    nmrek5  = n['nm_rek5'];
                    nil     = number_format(n['nilai'],2,'.',',');
                    clalu    = number_format(n['lalu'],2,'.',',');
                    csp2d    = number_format(n['sp2d'],2,'.',',');
                    canggaran = number_format(n['anggaran'],2,'.',',');                                                                                      
                    $('#dg1').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_kegiatan:giat,nm_kegiatan:nmgiat,kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil,lalu:clalu,sp2d:csp2d,anggaran:canggaran});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });                
           set_grid();                                                  
    }
    function load_detail_baru(){        
        var kk =''; 
                   
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/load_dtagih',
                data: ({no:kk}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    no      = n['no_bukti'];
                    nosp2d  = n['no_sp2d'];                                                                    
                    giat    = n['kd_kegiatan'];
                    nmgiat  = n['nm_kegiatan'];
                    rek5    = n['kd_rek'];
                    nmrek5  = n['nm_rek5'];
                    nil     = number_format(n['nilai'],2,'.',',');
                    clalu    = number_format(n['lalu'],2,'.',',');
                    csp2d    = number_format(n['sp2d'],2,'.',',');
                    canggaran = number_format(n['anggaran'],2,'.',',');                                                                                      
                    $('#dg1').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_kegiatan:giat,nm_kegiatan:nmgiat,kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil,lalu:clalu,sp2d:csp2d,anggaran:canggaran});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });                
           set_grid();     
			set_grid5();
    }
    
    function set_grid(){
        $('#dg1').edatagrid({                                                                   
            columns:[[
				{field:'no_bukti',title:'No Bukti',hidden:"true"},
                {field:'no_sp2d',title:'No SP2D',hidden:"true"},
        	    {field:'kd_kegiatan',title:'Kegiatan',width:50},
                {field:'nm_kegiatan',title:'Nama Kegiatan',hidden:"true"},
                {field:'kd_rek5',title:'Kode Rekening',width:30},
                {field:'nm_rek5',title:'Nama Rekening',width:100,align:"left"},
                {field:'nilai',title:'Nilai',width:70,align:"right"},
				{field:'sumber',title:'Sumber',width:20,align:"center"},
                {field:'lalu',title:'Sudah Dibayarkan',align:"right",width:30,hidden:'true'},
                {field:'sp2d',title:'SP2D Non UP',align:"right",width:30,hidden:'true'},
                {field:'anggaran',title:'Anggaran',align:"right",width:30,hidden:'true'}
            ]]
        });                 
    }

    function set_grid5(){
        $('#dg5').edatagrid({                                                                
            columns:[[
            {field:'no_bukti',
    		title:'No Bukti',    		
            hidden:"true"},
            {field:'tgl_bukti',
    		title:'Tanggal',    		
            hidden:"true"},
    	    {field:'rekening_awal',
    		title:'rekening awal',
    		hidden:"true"},
            {field:'nm_rekening_tujuan',
    		title:'Nama',    		
            width:50},
            {field:'rekening_tujuan',
    		title:'Rek. Tujuan',
    		width:40},
            {field:'bank_tujuan',
    		title:'Bank',
    		hidden:"true"},
            {field:'kd_skpd',
    		title:'SKPD',           
            hidden:'true'},
            {field:'nilai',
    		title:'Nilai',
    		width:35,
            align:"right"}          
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
            nmrek5  = rows[p].nm_rek5;
            nil     = rows[p].nilai;
			csumber = rows[p].sumber;
            lal     = rows[p].lalu;
            csp2d   = rows[p].sp2d;
            canggaran = rows[p].anggaran;                                                                                   
            $('#dg2').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_kegiatan:giat,nm_kegiatan:nmgiat,kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil,sumber:csumber,lalu:lal,sp2d:csp2d,anggaran:canggaran});            
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
            {field:'no_bukti',title:'No Bukti',hidden:"true",width:30},
            {field:'no_sp2d',title:'No SP2D',width:40},
    	    {field:'kd_kegiatan',title:'Kegiatan',width:50},
            {field:'nm_kegiatan',title:'Nama Kegiatan',hidden:"true",width:30},
            {field:'kd_rek5',title:'Kode Rekening',width:25,align:'center'},
            {field:'nm_rek5',title:'Nama Rekening',align:"left",width:40},
            {field:'nilai',title:'Rupiah',align:"right",width:30},
			{field:'sumber',title:'Sumber',align:"center",width:20},
            {field:'lalu',title:'Sudah Dibayarkan',align:"right",width:30},
            {field:'sp2d',title:'SP2D Non UP',align:"right",width:30},
            {field:'anggaran',title:'Anggaran',align:"right",width:30}
            ]]     
        });
    }
    
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
         set_grid();
         reload_data();         
     }
     function section2(){
         $(document).ready(function(){                
             $('#section2').click(); 
             document.getElementById("nomor").focus();                                              
         });                 
         set_grid();
     }
     
     function section3(){
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
     }  
     function section5(){        
        $(document).ready(function(){    
            $('#section1').click();                                               
        });        
        load_tgltrans();
     }
     
   function get(nomor,nomor_tgl,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,vpay,nokas_pot,tglpot,ketpot,statup,statval,statpot,srekwal,snmrek_awal,sbank_tjn,srek_tjn,ssp2d,kettjnn){
        $("#nomor").attr("value",nomor);
        $("#nomor_tgl").attr("value",nomor_tgl);
		$("#no_simpan").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#nokas").attr("value",nokas_pot);
        $("#tglkas").datebox("setValue",tglpot);
        $("#kete").attr("value",ketpot);
        $("#keterangan").attr("value",ket);        
        $("#beban").attr("value",jns);
        $("#total").attr("value",number_format(tot,2,'.',','));
        $("#notagih").combogrid("setValue",notagih);        
        $("#tgltagih").attr("Value",tgltagih);
        $("#jns_tunai").attr("value",vpay);    
        $("#nmosp2d").attr("value",ssp2d);  
        $("#status").attr("checked",false); 
		status_transaksi = 'edit';

        $("#rekening_awal").combogrid("setValue",srekwal);        
        $("#nm_rekening_tujuan").attr("Value",snmrek_awal);
        $("#kd_bank_tujuan").combogrid("setValue",sbank_tjn);        
        $("#rekening_tujuan").combogrid("setValue",srek_tjn);
        $("#ketcms").attr("Value",kettjnn);  
        
		/*
		if (ststagih==1){            
            $("#status").attr("checked",true);
            $("#tagih").show();
            load_detail_tagih();
        } else {
            $("#status").attr("checked",false);
            $("#tagih").hide();
        }
         tombol(ststagih);   */                                
		tombollpj(statup,statval,statpot);
	}
    
	
	function tombollpj(statlpj,statspj,statpot){  
		if ((statlpj==1) || (statpot==1)){
			$('#save').linkbutton('disable');
			$('#del').linkbutton('disable');
		}else{
			$('#save').linkbutton('enable');
			$('#del').linkbutton('enable');
		}
    }
	
    function tombol(st){  
    if (st=='1'){
    $('#tambah').linkbutton('disable');
    $('#hapus').linkbutton('disable');
     } else {
     $('#tambah').linkbutton('enable');
     $('#hapus').linkbutton('enable');
    
     }
    }
    
    function tombolnew(){  
    
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
    
    }

    function kosong5(){
        $("#nilai_trf").attr("value",'0'); 
        $("#nm_rekening_tujuan").attr("Value",'');
        //$("#kd_bank_tujuan").combogrid("setValue",'');        
        $("#rekening_tujuan").combogrid("setValue",'');  
    }	
    
    function kosong(){
        cdate = '<?php echo date("Y-m-d"); ?>';  
		$("#tanggal").datebox('enable');
        $("#nomor").attr("value",'');
		$("#no_simpan").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        //$("#skpd").combogrid("setValue",'');
        //$("#nmskpd").attr("value",'');
		$("#ketcms").attr("value",'');
        $("#keterangan").attr("value",'');        
        $("#beban").attr("value",'');
		$("#nilpotongan").attr("value",'0'); 
        $("#total").attr("value",'0'); 
        $("#total_dpotongan").attr("value",'0'); 
        $("#total_dtransfer").attr("value",'0');         		
        $("#notagih").combogrid("setValue",'');        
        $("#tgltagih").attr("value",'');
        $("#jns_tunai").attr("value",'');
        //$("#sisa_tunai").attr("value",'');
        $("#sisa_bank").attr("value",'');
        $("#status").attr("checked",false);      
        //$("#tagih").hide();

        $("#rekening_awal").combogrid("setValue",'');        
        $("#nm_rekening_tujuan").attr("Value",'');
        $("#kd_bank_tujuan").combogrid("setValue",'');        
        $("#rekening_tujuan").combogrid("setValue",'');     		
		$('#ketsdana1').attr('value','');
		
		status_transaksi = 'tambah';
        load_detail_baru();       
        document.getElementById("nomor").focus();
        tombolnew(); 
		get_nourut();
		get_nourut_tgl();
		 
    }
	
     function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/cms/tukd_cms/no_urut_cms',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#nomor").attr("value",data.no_urut);
        							  }                                     
        	});  
        }
    
		
    function get_nourut_tgl()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/cms/tukd_cms/no_urut_tglcms',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#nomor_tgl").attr("value",data.no_urut);
        							  }                                     
        	});  
        }
    		
		
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_transout',
        queryParams:({cari:kriteria})
        });        
     });
    } 
	

    function cari_tgl(){
        
    var kriteria = $('#tglvoucher').datebox('getValue'); 
    if(kriteria==''){alert('Tanggal Tidak Boleh Kosong'); exit();}else{  
    set_grid();  
    //reload_data();     
    //reload_datag1(); 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/cms/ctransout_cms/load_tgltransout',
        queryParams:({cari:kriteria})
        });        
     });
     }
    }
    
    
    function lihat_trans(){
        
    /*var kriteria = $('#dg').datagrid('getSelections');  
    if(kriteria==''){alert('Pilih List Transaksi'); exit();}else{  
        section2();
     }*/
     
     alert('Untuk Melihat Detail: 1. Pilih List Transaksi, 2. Setelah itu Klik Dua Kali Pada List');
     
    }

    function load_tgltrans(){
        
    var kriteria = $('#tglvoucher').datebox('getValue'); 
    if(kriteria==''){
        
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();
    if(dd<10){
    dd='0'+dd;
    } 
    if(mm<10){
        mm='0'+mm;
    } 
    var kriteria = yyyy+'-'+mm+'-'+dd;
    
    }else{  
    //reload_data();   
    reload_datag1(); 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/cms/tukd_cms/load_tgltransout',
        queryParams:({cari:kriteria})
        });        
     });
     }
    }
    
    function cetak_list(){
        
    var kriteria = $('#tglvoucher').datebox('getValue'); 
    if(kriteria==''){alert('Tanggal Tidak Boleh Kosong'); exit();}
    
    var url = '<?php echo site_url(); ?>/cms/ctransout_cms/cetak_listtransaksi';
    window.open(url+'/'+kriteria+'/LIST TRANSAKSI '+kriteria, '_blank');
    window.focus();        
    }

	
    function append_save_pot() {
        
            $('#dgpajak').datagrid('selectAll');
            var rows  = $('#dgpajak').datagrid('getSelections');
            jgrid     = rows.length ; 
        
            var rek_pajak    = $("#rekpajak").combogrid("getValue") ;
            var nm_rek_pajak = document.getElementById("nmrekpajak").value ;
            var nilai_pajak  = document.getElementById("nilairekpajak").value ;
            var nil_pajak    = angka(nilai_pajak);
            var dinas        = document.getElementById('skpd').value;
            var vnospm       = document.getElementById('nomor').value;
            var cket         = '0' ;
            var jumlah_pajak = document.getElementById('totalrekpajak').value ;   
                jumlah_pajak = angka(jumlah_pajak);        
            
            if ( rek_pajak == '' ){
                alert("Isi Rekening Terlebih Dahulu...!!!");
                exit();
                }
            
            if ( nilai_pajak == 0 ){
                alert("Isi Nilai Terlebih Dahulu...!!!");
                exit();
                }
            
            pidx = jgrid + 1 ;

            $('#dgpajak').edatagrid('appendRow',{kd_rek5:rek_pajak,nm_rek5:nm_rek_pajak,nilai:nilai_pajak,id:pidx});
            //$(document).ready(function(){      
//                $.ajax({
//                type     : 'POST',
//                url      : "<?php  echo base_url(); ?>index.php/tukd/dsimpan_pot_ar",
//                data     : ({cskpd:dinas,spm:vnospm,kd_rek5:rek_pajak,nmrek:nm_rek_pajak,nilai:nil_pajak,ket:cket}),
//                dataType : "json"
//                });
//            });
            
            //$("#rekpajak").combogrid("setValue",'');
            //$("#nmrekpajak").attr("value",'');
            $("#nilairekpajak").attr("value",0);
            jumlah_pajak = jumlah_pajak + nil_pajak ;
            $("#totalrekpajak").attr('value',number_format(jumlah_pajak,2,'.',','));
            validate_rekening();
    
    }   
        
    function append_save(){
        var no  	= document.getElementById('nomor').value;
        var giat    = $('#giat').combogrid('getValue');
        var nmgiat  = document.getElementById('nmgiat').value;                
        var nosp2d  = $('#sp2d').combogrid('getValue');
        var rek     = $('#rek').combogrid('getValue');
		var csumberdn   = document.getElementById('ketsdana1').value;
		var jenis       = document.getElementById('beban').value;
        var nmrek  	 	= document.getElementById('nmrek').value;
        var crek    	= $('#rek').combogrid('grid');	// get datagrid object
        var grek    	= crek.datagrid('getSelected');	// get the selected row
        var canggaran 	= number_format(grek.anggaran,2,'.',',');
        var csp2d   	= number_format(grek.sp2d,2,'.',',');
        var clalu   	= number_format(grek.lalu,2,'.',',');
        var sisa    	= angka(document.getElementById('sisa').value);                
        var nilai_rek  	= angka(document.getElementById('nilai').value);        
        var tot_tunai  	= angka(document.getElementById('total_sisa').value);        
        var tot_bank   	= angka(document.getElementById('total_sisa').value);        
        var lcjenis    	= document.getElementById('jns_tunai').value;        
        var sisa       	= angka(document.getElementById('sisa').value);                
        var sisa_semp  	= angka(document.getElementById('sisa_semp').value);                
        var sisa_ubah  	= angka(document.getElementById('sisa_ubah').value);                
        var status_ang  = document.getElementById('status_ang').value ;
        var nil     	= document.getElementById('nilai').value; 
		var tot_sisa_spd= angka(document.getElementById('tot_sisa').value);
		var total1      = angka(document.getElementById('total1').value);
		var cpot = angka(document.getElementById('pot_ls').value);
        var sisa_sd = angka(document.getElementById('sisa_sd').value);
        var sisa_semp_sd = angka(document.getElementById('sisa_semp_sd').value);
        var sisa_ubah_sd = angka(document.getElementById('sisa_ubah_sd').value);
        var cdouble = 0; var cbedakegi = 0;
		var vsdana1    	= document.getElementById('ketsdana1').value;
		
       $('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');     
        for(var p=0;p<rows.length;p++){
            cgiat    = rows[p].kd_kegiatan;
            rek5    = rows[p].kd_rek5;
            csumber = rows[p].sumber;                 
            if (rek5==rek && csumber==vsdana1){                        
                var cdouble = 1;
            } 

            if (cgiat!=giat){                        
                var cbedakegi = 1;
            } 
                                                                                                                                                                                                             
        }             

        if(cbedakegi==1){
            swal("Error", "Tdk boleh memilih kegiatan berbeda dlm 1 no bku", "error");
            return;            
        }
        
        if(cdouble==1){
            swal("Error", "Tdk boleh memilih rekening dgn sumber dana yg sama dlm 1 no bku", "error");
            return;            
        }
			
		
		akumulasi = total1+nilai_rek;

        var init_sp2d1 = nosp2d.split("/");
        var init_sp2d  = no+"."+init_sp2d1[0]+"/"+init_sp2d1[2]+"."+rek;         
        $("#ketcms").attr("value",init_sp2d);

       if (csumberdn==''){
                 alert('Pilih Sumber Dana Dahulu') ;
                 exit();
            } 
			
       if (rek==''){
                 alert('Pilih rekening Dahulu') ;
                 exit();
            }
		 if (status_ang==''){
                 alert('Pilih Tanggal Dahulu') ;
                 exit();
            }
		if (nosp2d==''){
                 alert('Pilih sp2d Dahulu') ;
                 exit();
            }
			
		if(nosp2d=='undefined'){
				alert("No sp2d kosong");
                 exit();
		 }
		 
		if (nmrek==''){
                 alert('Pilih rekening Dahulu') ;
                 exit();
            }		
		if((lcjenis=='TUNAI') &&(nilai_rek>tot_tunai)){
			alert('Total Transaksi melebihi Sisa Kas Tunai');
			exit();
			}
		if((lcjenis=='BANK') &&(nilai_rek>tot_bank)){
			alert('Total Transaksi melebihi Sisa Simpanan Bank');
			exit();
			}

       if ( nil == 0 ){
                 alert('Nilai Nol.....!!!, Cek Lagi...!!!') ;
                 exit();
            }
		

           /*sumber dana*/
            if ( (status_ang=='Perubahan')&&(nilai_rek > sisa_ubah_sd)){
                 alert('Nilai Melebihi Sisa Anggaran Perubahan Sumber Dana...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nilai_rek > sisa_ubah_sd)){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan Sumber Dana...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nilai_rek > sisa_semp_sd)){
                 alert('Nilai Melebihi Sisa Anggaran Penyempurnaan Sumber Dana...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nilai_rek > sisa_ubah_sd)){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan Sumber Dana...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nilai_rek > sisa_semp_sd)){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan Sumber Dana...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nilai_rek > sisa_sd)){
                 alert('Nilai Melebihi Sisa Anggaran Penyusunan Sumber Dana...!!!, Cek Lagi...!!!') ;
                 exit();
            }            
 
        
		//ang	
            if ( (status_ang=='Perubahan')&&(nilai_rek > sisa_ubah)){
                 alert('Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nilai_rek > sisa_ubah)){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nilai_rek > sisa_semp)){
                 alert('Nilai Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nilai_rek > sisa_ubah)){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nilai_rek > sisa_semp)){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nilai_rek > sisa)){
                 alert('Nilai Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
			
			
			
			if((jenis=='1')&&(akumulasi>tot_sisa_spd)){
				alert('Total Transaksi melebihi Sisa SPD');
				exit();
			}
            if (giat==''){
                 alert('Pilih Kegiatan Dahulu') ;
                 exit();
            }
            if (nmgiat==''){
                 alert('Pilih Kegiatan Dahulu') ;
                 exit();
            }
       
                $('#dg1').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                 kd_kegiatan:giat,
                                                 nm_kegiatan:nmgiat,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil,
												 sumber:csumberdn,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran});
                $('#dg2').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                 kd_kegiatan:giat,
                                                 nm_kegiatan:nmgiat,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil,
												 sumber:csumberdn,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran});                                                 
                kosong2();
                total = angka(document.getElementById('total1').value) + nilai_rek;
                $('#total1').attr('value',number_format(total,2,'.',','));
                $('#total').attr('value',number_format(total,2,'.',','));
              
    }  

    function append_save_rekening(){
        var no  	= document.getElementById('nomor').value;
        var ctgl = $('#tanggal').datebox('getValue'); 
        var cskpd = document.getElementById('skpd').value;
        var crekawal = $('#rekening_awal').combogrid('getValue');
        var cnmrekawal = document.getElementById('nm_rekening_tujuan').value;
        var crektujuan = $("#rekening_tujuan").combogrid("getValue"); 
        var cbanktujuan = $('#kd_bank_tujuan').combogrid('getValue');
		var total_bel = angka(document.getElementById('total').value);
        var total_trf = angka(document.getElementById('total_dtransfer').value);
		var nilai_pot = angka(document.getElementById('nilpotongan').value);
        var nilai_trf = angka(document.getElementById('nilai_trf').value);
        var nilai_trff= document.getElementById('nilai_trf').value;
	   
        var hasil_akmulasi = total_bel-nilai_pot;
        var akumulasi = total_trf+nilai_trf;
        
       if(nilai_trf==0){
				alert("Nilai Tidak Boleh Nol");
                 exit();
		}
       
       if(akumulasi>hasil_akmulasi){
            alert('Nilai Melebihi Total Belanja');
            exit();
       }
        
       if(nilai_trf>hasil_akmulasi){
            alert('Nilai Melebihi Total Belanja');
            exit();
        }
        
       if(total_trf>hasil_akmulasi){
            alert('Nilai Melebihi Total Belanja');
            exit();
        } 
            
       if (crekawal==''){
                 alert('Pilih Rekening Sumber') ;
                 exit();
        } 
       if (cnmrekawal==''){
                 alert('Pilih rekening') ;
                 exit();
        }
       if (crektujuan==''){
                 alert('Pilih rekening') ;
                 exit();
        }
	   if (cbanktujuan==''){
                 alert('Pilih rekening') ;
                 exit();
        }			 
                      
                $('#dg5').edatagrid('appendRow',{no_bukti:no,
                                                 tgl_bukti:ctgl,
                                                 rekening_awal:crekawal,
                                                 nm_rekening_tujuan:cnmrekawal,
                                                 rekening_tujuan:crektujuan,
                                                 bank_tujuan:cbanktujuan,
                                                 kd_skpd:cskpd,
                                                 nilai:nilai_trff});                                                 
                
                total = angka(document.getElementById('total_dtransfer').value) + nilai_trf;
                $('#total_dpotongan').attr('value',number_format(nilai_pot,2,'.',','));
                $('#total_dtransfer').attr('value',number_format(total,2,'.',','));              
    }  

    
    function validate_rekening() {
           
           $('#dgpajak').datagrid('selectAll');
           var rows = $('#dgpajak').datagrid('getSelections');
                
           frek  = '' ;
           rek5  = '' ;
           for ( var p=0; p < rows.length; p++ ) { 
           rek5 = rows[p].kd_rek5;                                       
           if ( p > 0 ){   
                  frek = frek+','+rek5;
              } else {
                  frek = rek5;
              }
           }
           
           $(function(){
           $('#rekpajak').combogrid({  
                   panelWidth  : 700,  
                   idField     : 'kd_rek5',  
                   textField   : 'kd_rek5',  
                   mode        : 'remote',
                   url         : '<?php echo base_url(); ?>index.php/tukd/rek_pot_ar', 
                   queryParams :({kdrek:frek}), 
                   columns:[[  
                       {field:'kd_rek5',title:'Kode Rekening',width:100},  
                       {field:'nm_rek5',title:'Nama Rekening',width:700}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                       $("#nmrekpajak").attr("value",rowData.nm_rek5.toUpperCase());
                   }  
                   });
                   });
          $('#dgpajak').datagrid('unselectAll');         
    }   

    function tambah_rekening_bnk(){
        
        //var total_potongan = angka(document.getElementById('total_dpotongan').value);      
        //$("#nilpotongan").attr("value",number_format(total_potongan,2));         
        
        kosong5();
        //set_grid5();
        $("#dialog-modal-rekening").dialog('open');  
    }
    
    function tutup_rekening_bnk(){
        $("#dialog-modal-rekening").dialog('close');          
    }    
	
    
    function tambah(){
        var nor = document.getElementById('nomor').value;
        var tot = document.getElementById('total').value;
        var kd = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
        var cjenis = document.getElementById('beban').value; 

        

		//$('#ang').attr('value',number_format(0,2,'.',','));
        kosong2();

        $('#notagih').combogrid({  
           panelWidth:420,  
           idField:'no_tagih',  
           textField:'no_tagih',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/load_no_penagihan',
           queryParams:({kd:kode}),             
           columns:[[  
               {field:'no_tagih',title:'No Penagihan',width:140},  
               {field:'tgl_tagih',title:'Tanggal',width:140},
               {field:'kd_skpd',title:'SKPD',width:140}
           ]] 
        });


        $('#dg2').edatagrid('reload');
        $('#total1').attr('value',tot);
        $('#giat').combogrid('setValue','');
        $('#sp2d').combogrid('setValue','');
        $('#rek').combogrid('setValue','');
		$('#nmrek').attr('value','');
		sumber_dnkosong();
        var tgl = $('#tanggal').datebox('getValue');
        var jns1 = document.getElementById('beban').value;
        if (kd != '' && tgl != '' && jns1 != '' && nor !=''){
		kegia()            
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
        $('#sisa_semp').attr('value','0');
        $('#sisa_ubah').attr('value','0');
        $('#nilai').attr('value','0');             
        $('#nmgiat').attr('value','');
        $('#ang').attr('value','0');
        $('#ang_semp').attr('value','0');
        $('#ang_ubah').attr('value','0');
        $('#lalu').attr('value','0');
        $('#lalu_semp').attr('value','0');
        $('#lalu_ubah').attr('value','0');
		//$("#sisa_tunai").attr("value",'');
        //$("#sisa_bank").attr("value",'');        
        $("#pot_ls").attr("value",'');        
        $("#total_sisa").attr("value",'');
		$('#tot_spd').attr('value','0');
        $('#tot_trans').attr('value','0');
        $('#tot_sisa').attr('value','0');
	   $("#rek_pad").attr("Value",'0');
	   $("#rek_dak").attr("Value",'0');
	   $("#rek_daknf").attr("Value",'0');
	   $("#rek_dau").attr("Value",'0');
	   $("#rek_dbhp").attr("Value",'0');
	   $("#rek_did").attr("Value",'0');
	   $("#rek_nilai_ang").attr("Value",0);
	   $("#rek_nilai_spp").attr("Value",0);
	   $("#rek_nilai_sisa").attr("Value",0);
	   $("#rek_nilai_ang_semp").attr("Value",0);
	   $("#rek_nilai_spp_semp").attr("Value",0);
	   $("#rek_nilai_sisa_semp").attr("Value",0);
	   $("#rek_nilai_ang_ubah").attr("Value",0);
	   $("#rek_nilai_spp_ubah").attr("Value",0);
	   $("#rek_nilai_sisa_ubah").attr("Value",0);
    }
    
    function keluar(){
		/*var nilai_rek  	= angka(document.getElementById('nilai').value);        
        var tot_tunai  	= angka(document.getElementById('total_sisa').value);        
        var tot_bank   	= angka(document.getElementById('sisa_bank').value);        
        var lcjenis    	= document.getElementById('jns_tunai').value;        
				
		if((lcjenis=='TUNAI') &&(nilai_rek>tot_tunai)){
			alert('Total Transaksi melebihi Sisa Kas Tunai');
			exit();
			}
		if((lcjenis=='BANK') &&(nilai_rek>tot_bank)){
			alert('Total Transaksi melebihi Sisa Simpanan Bank');
			exit();
			}
		*/	
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

    function hapus_rekening_bnk(){
         var dtot_rekening = 0;      
           
         var tot = angka(document.getElementById('total_dtransfer').value);
         $('#dg5').datagrid('deleteRow',idx);
         
		 $('#dg5').datagrid('selectAll');
            var rows_rek = $('#dg5').datagrid('getSelections');           
			for(var j=0;j<rows_rek.length;j++){
			dnilai     = angka(rows_rek[j].nilai);
            dtot_rekening = dtot_rekening + dnilai;
			} 
                  
         $('#total_dtransfer').attr('value',number_format(dtot_rekening,2,'.',','));              
    }
    
     function hapus(){
        var cnomor = document.getElementById('nomor').value;
        var urll = '<?php echo base_url(); ?>cms/ctransout_cms/hapus_transout_cms';
        var tny = confirm('Yakin Ingin Menghapus Data, Nomor Bukti : '+cnomor);        
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

	function pilihanpot(){
        	var url    = "<?php echo site_url(); ?>/cms/ctrmpot";
            window.open(url, '_self');
			window.focus();		
    }	
    
    function simpan_transout(){
		
        var cno = document.getElementById('nomor').value;  
        var ctgl = $('#tanggal').datebox('getValue'); 
        var no_simpan = document.getElementById('no_simpan').value;  
        var cnokaspot = document.getElementById('nomor').value; 
        var cskpd = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
        var cnmskpd = document.getElementById('nmskpd').value;
        var cket = document.getElementById('keterangan').value.replace("'", '');
        var cjenis = document.getElementById('beban').value; 
        var cstatus = 0;//document.getElementById('status').checked;
        var cjenis_bayar = document.getElementById('jns_tunai').value; 
        
		
        var csql = '';
        if (cstatus==false){
           cstatus=0;
        }else{
            cstatus=1;
        }

        var ctagih = '';//$('#notagih').combogrid('getValue');
        var ctgltagih = '';//document.getElementById('tgltagih').value;
        var ctotal = angka(document.getElementById('total').value);  
        var ckas = angka(document.getElementById('total_sisa').value);  
        var cbank = angka(document.getElementById('total_sisa').value);  
		var tahun_input = ctgl.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			alert('Tahun tidak sama dengan tahun Anggaran');
			exit();
		}
		if (cjenis_bayar=='TUNAI' && ctotal>ckas){
            alert('Nilai Melebihi sisa KAS Tunai');
            exit();
        }
		if (cjenis_bayar=='BANK' && ctotal>cbank){
            alert('Nilai Melebihi sisa Simpanan Bank');
            exit();
        }
		if (cno==''){
            alert('Nomor Bukti Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal Bukti Tidak Boleh Kosong');
            exit();
        }
        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        if (cjenis==''){
            alert('Jenis beban Tidak Boleh Kosong');
            exit();
        }
		if (cjenis_bayar==''){
            alert('Jenis Pembayaran Tidak Boleh Kosong');
            exit();
        }
        
		var ctot_det=0;
		 $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
			for(var p=0;p<rows.length;p++){
				cnilai     = angka(rows[p].nilai);
				ctot_det = ctot_det + cnilai;
				nosp2d2 = rows[p].no_sp2d;
			} 
		
			
			
		if (ctotal != ctot_det){
			alert('Nilai Rincian tidak sama dengan Total, Silakan Refresh kembali halaman ini!');
			exit();
		}
		
		if (ctot_det == 0){
			alert('Rincian Tidak ada rekening!');
			exit();
		}
		

        var dtot_potongan = angka(document.getElementById('total_dpotongan').value);
        var dtot_rekening=0;
		 $('#dg5').datagrid('selectAll');
            var rows_rek = $('#dg5').datagrid('getSelections');           
			for(var j=0;j<rows_rek.length;j++){
			dnilai     = angka(rows_rek[j].nilai);
            dtot_rekening = dtot_rekening + dnilai;
			} 
        var hasill = ctotal - dtot_potongan;    
        if (dtot_rekening != hasill){
			alert('Total Daftar Rekening tidak sama dengan Total Belanja, Silakan periksa kembali!');
			exit();
		}
            
		if (dtot_rekening > ctotal){
			alert('Total Transfer melebihi Total Belanja!');
			exit();
		}
		
		if (dtot_rekening == 0){
			alert('Daftar Rekening Tujuan Tidak ada rekening!');
			exit();
		}       		

        var crekawal = $('#rekening_awal').combogrid('getValue');
        var init_cmss = document.getElementById('ketcms').value; 
        var cnmrekawal = '';//document.getElementById('nm_rekening_tujuan').value;
        var crektujuan = '';//$("#rekening_tujuan").combogrid("getValue"); 
        var cbanktujuan = '';//$('#kd_bank_tujuan').combogrid('getValue');                
        
        if (crekawal == ''){
			alert('Isian Rekening Belum Lengkap!');
			exit();
		}
        
        var init_cmssa = init_cmss.split(".");
        var init_cmssb  = init_cmssa[2];  
        if(init_cmssb==''){
            alert('Silahkan Ulangi Proses Input Tambah Rekening Belanja, Keterangan CMS Tidak Valid!');
			exit();
        }

		$('#save').linkbutton('disable');
		if(status_transaksi == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhtransout_cmsbank',field:'no_voucher'}),
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
						
		//------- mulai
		
        $(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                data: ({tabel:'trhtransout_cmsbank',no:cno,tgl:ctgl,nokas:cno,tglkas:ctgl,skpd:cskpd,nmskpd:cnmskpd,beban:cjenis,ket:cket,status:cstatus,notagih:ctagih,tgltagih:ctgltagih,total:ctotal,cpay:cjenis_bayar,nokas_pot:cnokaspot,nosp2d2:nosp2d2,rek_awal:crekawal,anrek_awal:cnmrekawal,rek_tjn:crektujuan,rek_bnk:cbanktujuan,cinit_ket:init_cmss}),
                url: '<?php echo base_url(); ?>/index.php/cms/ctransout_cms/simpan_transout',
                success:function(data){
                    status = data.pesan;                                                               
                }
            });
        });
        
        if (status=='0'){
           alert('Gagal Simpan...!!');
		   $('#save').linkbutton('enable');
           exit();
        }
        
        if (status !='0'){            
            $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
			for(var p=0;p<rows.length;p++){
				        cnobukti   = cno;
                cnosp2d = rows[p].no_sp2d;
                ckdgiat = rows[p].kd_kegiatan;
                cnmgiat = rows[p].nm_kegiatan;
                crek    = rows[p].kd_rek5;
                cnmrek  = rows[p].nm_rek5;
                cnilai  = angka(rows[p].nilai);
                csumber    = rows[p].sumber;  
                
                if (p>0) {
                csql = csql+","+"('"+cnobukti+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"')";
                } else {
                csql = "values('"+cnobukti+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"')";                                            
                }                                             
			}

            $('#dg5').datagrid('selectAll');
            var rows_rek = $('#dg5').datagrid('getSelections');   
            for(var j=0;j<rows_rek.length;j++){
                lnobukti   = cno;
                lnotgl    = rows_rek[j].tgl_bukti;                                                             
                lrekawal    = rows_rek[j].rekening_awal;
                lnmrektj    = rows_rek[j].nm_rekening_tujuan;
                lrektj       = rows_rek[j].rekening_tujuan;
                lbank      = rows_rek[j].bank_tujuan;
                lskpd      = rows_rek[j].kd_skpd; 
                lnilai     = angka(rows_rek[j].nilai);                               
                
                if (j>0) {
                csql_rek = csql_rek+","+"('"+cnobukti+"','"+ctgl+"','"+lrekawal+"','"+lnmrektj+"','"+lrektj+"','"+lbank+"','"+lskpd+"','"+lnilai+"')";
                } else {
                csql_rek = "values('"+cnobukti+"','"+ctgl+"','"+lrekawal+"','"+lnmrektj+"','"+lrektj+"','"+lbank+"','"+lskpd+"','"+lnilai+"')";                                            
                }                                             
			}  
			
            $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({tabel:'trdtransout_cmsbank',no:cno,sql:csql,sqlrek:csql_rek,beban:cjenis,skpd:cskpd,status:cstatus,nosp2d2:nosp2d2,xrek:crek}),
                    url: '<?php echo base_url(); ?>/index.php/cms/ctransout_cms/simpan_transout',
                    success:function(data){                        
                        status = data.pesan;   
                         if (status=='1'){               
                            //alert('Data Berhasil Tersimpan...!!!');
							status_transaksi = 'edit;'
							$("#no_simpan").attr("value",cno);
							var abc = '1';
							
							var r = confirm("Data Berhasil Tersimpan...!, Apakah Transaksi ini Terdapat Terima Potongan Pajak ?");
                            if (r == true) {
                                pilihanpot();
                            } else {
                                section5();
                            }
							
                        } else{ 
                            alert('Data Gagal Tersimpan...!!!');
                        }                                             
                    }
                });
                });            
        }

//---------
		}
		}
		});
		});
        
            
        } else {
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhtransout_cmsbank',field:'no_voucher'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cno!=no_simpan){
						alert("Nomor Telah Dipakai!");
						$('#save').linkbutton('enable');
						exit();
						} 
						if(status_cek==0 || cno==no_simpan){
						alert("Nomor Bisa dipakai");
						
		//-----
		$(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                data: ({tabel:'trhtransout_cmsbank',no:cno,tgl:ctgl,nokas:cno,tglkas:ctgl,skpd:cskpd,nmskpd:cnmskpd,beban:cjenis,ket:cket,status:cstatus,notagih:ctagih,tgltagih:ctgltagih,total:ctotal,cpay:cjenis_bayar,nokas_pot:cnokaspot,nosp2d2:nosp2d2,rek_awal:crekawal,anrek_awal:cnmrekawal,rek_tjn:crektujuan,rek_bnk:cbanktujuan,cinit_ket:init_cmss}),
                url: '<?php echo base_url(); ?>/index.php/cms/ctransout_cms/simpan_transout',
                success:function(data){
                    status = data.pesan;                                                               
                }
            });
        });
       
        if (status=='0'){
           alert('Gagal Simpan...!!');
		   $('#save').linkbutton('enable');
           exit();
        }
        
        if (status !='0'){            
            $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
			for(var p=0;p<rows.length;p++){
				cnobukti = cno;
                cnosp2d  = rows[p].no_sp2d;
                ckdgiat  = rows[p].kd_kegiatan;
                cnmgiat  = rows[p].nm_kegiatan;
                crek     = rows[p].kd_rek5;
                cnmrek   = rows[p].nm_rek5;
                cnilai   = angka(rows[p].nilai);
				csumber    = rows[p].sumber;      
                
                if (p>0) {
                csql = csql+","+"('"+cnobukti+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"')";
                } else {
                csql = "values('"+cnobukti+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"')";                                            
                }                                             
			}
 
            $('#dg5').datagrid('selectAll');
            var rows_rek = $('#dg5').datagrid('getSelections');   
            for(var j=0;j<rows_rek.length;j++){
                lnobukti   = cno;
                lnotgl    = rows_rek[j].tgl_bukti;                                                             
                lrekawal    = rows_rek[j].rekening_awal;
                lnmrektj    = rows_rek[j].nm_rekening_tujuan;
                lrektj       = rows_rek[j].rekening_tujuan;
                lbank      = rows_rek[j].bank_tujuan;
                lskpd      = rows_rek[j].kd_skpd; 
                lnilai     = angka(rows_rek[j].nilai);                               
                
                if (j>0) {
                csql_rek = csql_rek+","+"('"+cnobukti+"','"+ctgl+"','"+lrekawal+"','"+lnmrektj+"','"+lrektj+"','"+lbank+"','"+lskpd+"','"+lnilai+"')";
                } else {
                csql_rek = "values('"+cnobukti+"','"+ctgl+"','"+lrekawal+"','"+lnmrektj+"','"+lrektj+"','"+lbank+"','"+lskpd+"','"+lnilai+"')";                                            
                }                                             
			}  
 
            $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({tabel:'trdtransout_cmsbank',no:cno,sql:csql,sqlrek:csql_rek,beban:cjenis,skpd:cskpd,status:cstatus,nosp2d2:nosp2d2,xrek:crek}),
                    url: '<?php echo base_url(); ?>/index.php/cms/ctransout_cms/simpan_transout',
                    success:function(data){                        
                        status = data.pesan;   
                         if (status=='1'){               
                            //alert('Data Berhasil Tersimpan...!!!');
							status_transaksi = 'edit;'
								$("#no_simpan").attr("value",cno);
							var abc = '1';
							//section1();
							
							var r = confirm("Data Berhasil Tersimpan...!, Apakah Transaksi ini Terdapat Terima Potongan Pajak ?");
                            if (r == true) {
                                pilihanpot();
                            } else {
                                section5();
                            }
							
                        } else{ 
                            alert('Data Gagal Tersimpan...!!!');
                        }                                             
                    }
                });
                });            
        }
		
		
		//----
		}
			}
		});
		});
        }
	//End of Function
	$('#save').linkbutton('enable');
    } 
    
    function edit_transout(){
		
        var cno = document.getElementById('nomor').value;  
        var ctgl = $('#tanggal').datebox('getValue'); 
        var no_simpan = document.getElementById('no_simpan').value;  
        var cnokaspot = document.getElementById('nomor').value; 
        var cskpd = document.getElementById('skpd').value;
        var cket = document.getElementById('keterangan').value.replace("'", '');
        var cjenis = document.getElementById('beban').value; 
        
        $(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                data: ({tabel:'trhtransout_cmsbank',no:cno,tgl:ctgl,nokas:cno,tglkas:ctgl,skpd:cskpd,beban:cjenis,ket:cket}),
                url: '<?php echo base_url(); ?>/index.php/cms/ctransout_cms/edit_transout',
                success:function(data){
                    status = data.pesan;
					if (status != '0'){               
                      alert('Keterangan Berhasil Diubah...!!!');
					} else{ 
                       alert('Keterangan Gagal Terubah...!!!');
                    } 
                }
            });
        });
    }    
	
	function simpan_potongan(){
        
        var cnokas = document.getElementById('nokas').value;  
        var ctglkas = $('#tglkas').datebox('getValue'); 
        var cskpd = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
        var cnmskpd = document.getElementById('nmskpd').value;
        var ckete = document.getElementById('kete').value;
		
       
        
        var ctotal = angka(document.getElementById('totalrekpajak').value);    
        // alert(cnokas+'/'+ctglkas+'/'+cskpd+'/'+cnmskpd+'/'+ckete+'/'+ctotal)
        
             
        if (cnokas==''){
            alert('Nomor Kas Tidak Boleh Kosong');
            exit();
        } 
        if (ctglkas==''){
            alert('Tanggal Bukti Tidak Boleh Kosong');
            exit();
        }
       
        
        $(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                data: ({tabel:'trhtrmpot',no:cnokas,tgl:ctglkas,skpd:cskpd,nmskpd:cnmskpd,ket:ckete,total:ctotal}),
                url: '<?php echo base_url(); ?>/index.php/tukd/simpan_potongan',
                success:function(data){
                    status = data.pesan;                                                               
                }
            });
        });
        
        if (status=='0'){
           alert('Gagal Simpan...!!');
           exit();
        }
        
        if (status !='0'){            
            $('#dgpajak').datagrid('selectAll');
            var rows = $('#dgpajak').datagrid('getSelections');           
			for(var q=0;q<rows.length;q++){
				cnobukti   = cnokas;
                crek       = rows[q].kd_rek5;
                cnmrek     = rows[q].nm_rek5;
                cnilai     = angka(rows[q].nilai);
                
                if (q>0) {
                csql = csql+","+"('"+cnobukti+"','"+crek+"','"+cnmrek+"','"+cnilai+"')";
                } else {
                csql = "values('"+cnobukti+"','"+crek+"','"+cnmrek+"','"+cnilai+"')";                                            
                }                                             
			}                     
            $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({tabel:'trdtrmpot',no:cnokas,sql:csql}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/simpan_potongan',
                    success:function(data){                        
                        status = data.pesan;   
                         if (status=='1'){               
                            alert('Data Berhasil Tersimpan...!!!');
                        } else{ 
                            alert('Data Gagal Tersimpan...!!!');
                        }                                             
                    }
                });
                });            
        }      
    }      
    
    function sisa_bayar(){
		/*
        var sisa    = angka(document.getElementById('sisa').value);             
        var nil     = angka(document.getElementById('nilai').value);        
        var sisasp2d     = angka(document.getElementById('sisasp2d').value);
        var tot  = 0;
        //alert(sisa+'/'+nil);        
        tot = sisa - nil;
        if (nil > sisasp2d){    
                alert('Nilai Melebihi Sisa Sp2d');
                    exit();
        } else {
            if (tot < 0){
                    alert('Nilai Melebihi Sisa');
                    exit();                
            }
        }           
        */
    }       
                         
                  
    function runEffect() {
        var selectedEffect = 'blind';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
        $("#notagih").combogrid("setValue",'');
        $("#tgltagih").attr("value",'');
        //$("#skpd").combogrid("setValue",'');
        $("#keterangan").attr("value",'');
        $("#beban").attr("value",'');
        load_detail_baru();
        
    };              
                             
       
                        
    function hit_lalu(){
        var cgiat = $('#giat').combogrid('getValue');
        var csp2d = $('#sp2d').combogrid('getValue');
        var crek  = $('#rek').combogrid('getValue');
        var cno   = document.getElementById('nomor').value;
        var ctgl  = $('#tanggal').combogrid('getValue');
        var ckode = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
        var jns   = document.getElementById('jenis').value;     
       // alert(cgiat+'/'+csp2d+'/'+crek+'/'+cno+'/'+ctgl+'/'+ckode);        
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
       // alert(cgiat+'/'+nosp2d+'/'+rek5+'/'+no+'/'+ctgl+'/'+cskpd);
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
        //alert(clalu);
    return clalu;           
    }
    
    function cek(){
        var lcno = document.getElementById('nomor').value;
            if ( lcno !='' ) {
               section3();  
               $("#totalrekpajak").attr("value",0);  
               $("#nilairekpajak").attr("value",0);  
               tampil_potongan();          
               load_sum_pot();
               $("#rekpajak").combogrid("setValue",'');
               $("#nmrekpajak").attr("value",'');
               
            } else {
                alert('Nomor  Tidak Boleh kosong')
                document.getElementById('no_spm').focus();
                exit();
            }
    }
    
    function tampil_potongan () {
        
            var vnospm = document.getElementById('nomor').value ;
			alert(vnospm);
            $(function(){
			$('#dgpajak').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/losd_pot_a',
                queryParams    : ({ spm:vnospm }),
                idField       : 'id',
                //toolbar       : "#toolbar",              
                rownumbers    : "true", 
                fitColumns    : false,
                autoRowHeight : "false",
                singleSelect  : "true",
                nowrap        : "true",
      			columns       :
                     [[
                        {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                        {field:'kd_rek5',title:'Rekening',width:100,align:'left'},			
    					{field:'nm_rek5',title:'Nama Rekening',width:317},
    					{field:'nilai',title:'Nilai',width:250,align:"right"},
                        {field:'hapus',title:'Hapus',width:100,align:"center",
                        formatter:function(value,rec){ 
                        return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_pot();" />';
                        }
                        }
        			]]	
                  });
		    });
     }
     
     function load_sum_pot(){                
		var spm = document.getElementById('no_spm').value;              
        $(function(){      
         $.ajax({
            type      : 'POST',
            data      : ({spm:spm}),
            url       : "<?php echo base_url(); ?>index.php/tukd/load_sum_pot",
            dataType  : "json",
            success   : function(data){ 
                $.each(data, function(i,n){
                    //$("#totalrekpajak").attr("value",number_format(n['rektotal'],2,'.',','));
                    $("#totalrekpajak").attr("value",n['rektotal']);
                });
            }
         });
        });
    }
    
	function datagrid_kosong(){
		$('#dg1').edatagrid('selectAll');
		var rows = $('#dg1').edatagrid('getSelections');
		for(var i = rows.length-1; i>=0; i--){
		var index = $('#dg1').edatagrid('getRowIndex',rows.id);
		$('#dg1').edatagrid('deleteRow',index);
		//alert("aa");
		}
	}
	
    function reload_data() {
    $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_transout',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
		{field:'ck',
    		title:'',
			checkbox:'true',
    		width:20},
			{field:'no_bukti',
    		title:'Nomor Bukti',
    		width:50},
            {field:'tgl_bukti',
    		title:'Tanggal',
    		width:50},
            {field:'nm_skpd',
    		title:'Nama SKPD',
    		width:90,
            align:"left"},
            {field:'ket',
    		title:'Keterangan',
    		width:100,
            align:"left"},
			{field:'simbollpj',
    		title:'LPJ',
    		width:10,
            align:"left"},
			{field:'simbolspj',
    		title:'SPJ',
    		width:10,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor = rowData.no_bukti;
          tgl   = rowData.tgl_bukti;
          nokas_pot = rowData.no_kas_pot;
          tglpot   = rowData.tgl_pot;
          ketpot   = rowData.ketpot;
          kode  = rowData.kd_skpd;
          nama  = rowData.nm_skpd;
          ket   = rowData.ket;          
          jns   = rowData.jns_beban; 
          tot   = rowData.total;
          notagih  = rowData.no_tagih;
          tgltagih = rowData.tgl_tagih;
          ststagih = rowData.sts_tagih; 
          vpay     = rowData.pay;         
          statlpj  = rowData.ketlpj;         
		  statspj     = rowData.ketspj;         

          get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,vpay,nokas_pot,tglpot,ketpot,statlpj,statspj);
          load_detail();
          load_detail_rekbank();
          //load_detail_pot(nokas_pot);
          /*if (ststagih !='1'){   
          load_detail(); 
          }   */                                         
        },
        onDblClickRow:function(rowIndex,rowData){         
            section2();                  
        }
    });
    }

    function reload_datag1() {
    $('#dg').edatagrid({
		columns:[[
		{field:'ck',
    		title:'',
			checkbox:'true',
    		width:20},
			{field:'no_voucher',
    		title:'Nomor',
    		width:20},
            {field:'tgl_voucher',
    		title:'Tanggal',
    		width:50},
            {field:'nm_skpd',
    		title:'Nama SKPD',
    		width:90,
            align:"left"},
            {field:'ket',
    		title:'Keterangan',
    		width:100,
            align:"left"},
			{field:'ketup',
    		title:'UPL',
    		width:10,
            align:"left"},
            {field:'ketval',
    		title:'VAL',
    		width:10,
            align:"left"},
            {field:'stpot',
    		title:'POT',
    		width:10,
            align:"left"}
        ]]
    });
    }  
    </script>

</head>
<body>



<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1" >List Daftar Transaksi Non Tunai</a></h3>
    <div>
    <p align="right">    
		Tanggal
        <input name="tglvoucher" type="text" id="tglvoucher" style="width:100px; border: 0;"/>  
		&nbsp;
		<a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari_tgl();">Cari</a>
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_list();">Cetak List</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:lihat_trans();">Info</a> 
		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();datagrid_kosong();">Tambah</a>               
        <!--<a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>-->
        <table id="dg" title="List Transaksi" style="width:870px;height:600px;" >  
        </table>                          
    </p> 
    <font><b>Note : Warna Hijau sudah diupload <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Warna Biru sudah divalidasi </b></font>
    </div>   

<h3><a href="#" id="section2">TRANSAKSI NON TUNAI</a></h3>
   <div  style="height: 350px;">
   <p>       
   <div id="demo"></div> 
        <table align="center" style="width:100%;" >
        <!--    <tr>
                <td hidden colspan="5"><b>P E N A G I H A N</b><input type="checkbox" id="status"  onclick="javascript:runEffect();"/>
                    <div id="tagih">
                        <table>
                            <tr>
                                <td>No.Penagihan</td>
                                <td><input type="text" id="notagih"/></td>
                            
                                <td>Tgl Penagihan</td>
                                <td><input type="text" id="tgltagih" style="width: 140px;" /></td>   
                            </tr>
                        </table> 
                    </div>
                
                </td>                
            </tr>-->
            
			<tr>
                <td style="border-bottom: double 1px red;"><i>No. Voucher<i></td>
                <td style="border-bottom: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/></td>
				<td style="border-bottom: double 1px red;">&nbsp;&nbsp;</td>
				<td style="border-bottom: double 1px red;" colspan = "2"><i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr>
			
            <tr>
                <td>No. Bukti</td>
                <td><input type="number" id="nomor" style="width: 200px;" /></td>
					<input type="hidden" id="nomor_tgl" style="width: 200px;" readonly="true"/>
                <td>&nbsp;&nbsp;</td>
                <td>Tanggal</td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>     
            </tr>
                                   
            <tr>
                <td>O P D</td>
                <td><input id="skpd" name="skpd" style="width: 140px;" readonly="true"/></td>
                <td></td>
                <td>Nama :</td> 
                <td><input type="text" id="nmskpd" style="border:0;width: 400px;" readonly="true"/></td>                                
            </tr>
            <tr>
                <td>Jenis Beban</td>
                <td colspan="4"><?php echo $this->tukd_model->combo_beban('beban'); ?>                                               
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rek. Bank Bendahara&nbsp;&nbsp;
                <input type="text" id="rekening_awal" style="border:0;width: 150px;" readonly="true"/>
				</td>
			</tr>                        
            <tr>
                <td>Keterangan</td>
                <td colspan="4"><textarea id="keterangan" style="width: 650px; height: 40px;"></textarea></td>
           </tr>            
            
            <tr>
            
                <td>Pembayaran</td>
                 <td>
                     <select name="jns_tunai" id="jns_tunai">
                         <option value="BANK">BANK</option>
                     </select>
					 <input type="text" id="ketcms" name="ketcms" style="border:0;width: 200px;" hidden="true" />
                 </td>

                <td colspan="3" align="right">
					<!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();datagrid_kosong();">Tambah</a>-->
					<a id="edit" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:edit_transout();">Edit Keterangan</a>
                    <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_transout();">Simpan</a>
                    <!--<a id="poto" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:cek();">Potongan</a>-->
		            <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
  		            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>                                   
                </td>
            </tr>
        </table>          
        <table id="dg1" title="Rekening" style="width:870px;height:450px;" >  
        </table>  
        <div id="toolbar" align="right">
    		<a id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Kegiatan</a>
   		    <!--<input type="checkbox" id="semua" value="1" /><a onclick="">Semua Kegiatan</a>-->
            <a id="hapus" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_giat();">Hapus Kegiatan</a>
               		
        </div>
        <table align="center" style="width:100%;">
        <tr>            
            <td width="60%">&nbsp;</td>
            <td align="right">Total Belanja&nbsp;</td>
            <td align="right" width="27%">:&nbsp;<input type="text" id="total" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></td>
        </tr>
        <tr>            
            <td width="60%">&nbsp;</td>
            <td align="right">Total Potongan&nbsp;</td>
            <td align="right" width="27%">:&nbsp;<input type="text" id="total_dpotongan" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></td>
        </tr>
		</table>
        <table id="dg5" title="Daftar Rekening Tujuan" style="width:870px;height:200px;" >  
        </table>  
        <div id="toolbar2" align="right">
    		<a id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah_rekening_bnk();">Tambah</a>
   		    <a id="hapus" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_rekening_bnk();">Hapus</a>               		
        </div>
        <table align="center" style="width:100%;" border="0">
        <tr>            
            <td width="60%">&nbsp;</td>
            <td align="right">Total Tranfer&nbsp;</td>
            <td align="right" width="27%">:&nbsp;<input type="text" id="total_dtransfer" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></td>
        </tr>        
        </table>
         
                
   </p>
   </div>
   <h3><a href="#" id="section3" ></a></h3>

    <div>
    <fieldset>
        
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
       
       <fieldset>
       <table border='0' style="font-size:11px"> 
           <tr>
                <td>NO KAS</td>
                <td>:</td>
                <td><input type="text" id="nokas"   name="nokas" style="width:200px;"/></td>
                <td>Tanggal :<input type="text" id="tglkas" name="tglkas" style="width:100px;"/></td>
           </tr>
           <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td colspan='2'><input type="text" id="kete"   name="kete" style="width:400px;"/></td>
           </tr>
           <tr>
                <td>Rekening Potongan</td>
                <td>:</td>
                <td><input type="text" id="rekpajak"   name="rekpajak" style="width:200px;"/></td>
                <td><input type="text" id="nmrekpajak" name="nmrekpajak" style="width:400px;border:0px;"/></td>
           </tr>
           <tr>
                <td align="left">Nilai</td>
                <td>:</td>
                <td><input type="text" id="nilairekpajak" name="nilairekpajak" style="width:200px;text-align:right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td></td>
           </tr>
           <tr>
             <td colspan="4" align="center" > 
                 <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:append_save_pot();" >Tambah</a>
             </td>
           </tr>
       </table>
       </fieldset>
       
      &nbsp;&nbsp; 
       <table border='0' style="font-size:11px;width:850px;height:30px;"> 
           <tr>
                <td colspan="3" align="center">
                <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_potongan()();" >Simpan</a>
                 <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();" >Kembali</a>
                </td>
                
           </tr>
           <tr>
                <td width='50%'></td>
                <td width='20%' align="right">Total</td>
                <td width='30%'><input type="text" id="totalrekpajak" name="totalrekpajak" style="width:250px;text-align:right;"/></td>
           </tr>
       </table>
       <table id="dgpajak" title="List Potongan" style="width:850px;height:300px;">  
       </table>   
       
       
    </fieldset>   
    </div>
   
</div>
</div>


<div id="dialog-modal" title="Input Kegiatan *)Semua Inputan Harus Di Isi.">
    <!--<p class="validateTips">Semua Inputan Harus Di Isi.</p> -->
    <fieldset>
    <table border="0">
        <tr>
            <td>Kode Kegiatan</td>
            <td>:</td>
            <td ><input id="giat" name="giat" style="width: 157px;" /></td>
            <td>Nama Kegiatan</td>
            <td>:</td>
            <td colspan="4"><input type="text" id="nmgiat" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>        
        <tr>
            <td>Nomor SP2D</td>
            <td>:</td>
            <td colspan="7" ><input id="sp2d" name="sp2d" style="width: 157px;" /></td>
            <!--<td >Sisa Sp2d</td>
            <td>:</td>
            <td><input type="text" id="sisasp2d" readonly="true" style="border:0;width: 200px;"/></td>-->
        </tr>
         <tr>
            <td >Kode Rekening</td>
            <td>:</td>
            <td><input id="rek" name="rek" style="width: 157px;" /></td>
            <td >Nama Rekening</td>
            <td>:</td>
            <td colspan="4"><input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>
        <tr>
            <td >Sumber Dana</td>
            <td>:</td>
            <td colspan="7"><input id="ketsdana1" name="ketsdana1" style="width: 134px;" /><input id="sumber_dn" name="sumber_dn" style="width: 19px;" /></td>            
        </tr>    
        <tr>
            <td bgcolor="#99FF99">Anggaran Murni Rekening/ SP2D</td>
            <td bgcolor="#99FF99">:</td>
            <td bgcolor="#99FF99"><input type="text" id="ang" value="0" readonly="true" class="satu" style="text-align:right;border:0;"/></td> 
            <td bgcolor="#99FF99">Lalu</td>
            <td bgcolor="#99FF99">:</td>
            <td bgcolor="#99FF99"><input type="text" id="lalu" readonly="true" class="dua" style="text-align:right;border:0;"/></td> 
            <td bgcolor="#99FF99">Sisa</td>
            <td bgcolor="#99FF99">:</td>
            <td bgcolor="#99FF99"><input type="text" id="sisa" readonly="true" class="dua" style="text-align:right;border:0;"/></td>            
        </tr>
        <tr>
            <td bgcolor="#e374ff">Penyempurnaan / SP2D &nbsp; &nbsp; &nbsp;</td>
            <td bgcolor="#e374ff">:</td>
            <td bgcolor="#e374ff"><input type="text" id="ang_semp" value="0" readonly="true" class="satu" style="text-align:right;border:0;"/></td> 
            <td bgcolor="#e374ff">Lalu</td>
            <td bgcolor="#e374ff">:</td>
            <td bgcolor="#e374ff"><input type="text" id="lalu_semp" readonly="true" class="dua" style="text-align:right;border:0;"/></td> 
            <td bgcolor="#e374ff">Sisa</td>
            <td bgcolor="#e374ff">:</td>
            <td bgcolor="#e374ff"><input type="text" id="sisa_semp" readonly="true" class="dua" style="text-align:right;border:0;"/></td>            
         </tr>
        <tr>
            <td bgcolor="#51ffd4">Perubahan / SP2D </td>
            <td bgcolor="#51ffd4">:</td>
            <td bgcolor="#51ffd4"><input type="text" id="ang_ubah" value="0" readonly="true" class="satu" style="text-align:right;border:0;"/></td> 
            <td bgcolor="#51ffd4">Lalu</td>
            <td bgcolor="#51ffd4">:</td>
            <td bgcolor="#51ffd4"><input type="text" id="lalu_ubah" readonly="true" class="dua" style="text-align:right;border:0;"/></td> 
            <td bgcolor="#51ffd4">Sisa</td>
            <td bgcolor="#51ffd4">:</td>
            <td bgcolor="#51ffd4"><input type="text" id="sisa_ubah" readonly="true" class="dua" style="text-align:right;border:0;"/></td>            
        </tr>
		<tr>
            <td bgcolor="#FFA07A">Anggaran Murni SumberDana/SP2D</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="ang_sd" readonly="true" style="text-align:right;border:0;" class="satu"/></td> 
            <td bgcolor="#FFA07A">Lalu</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="lalu_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td> 
            <td bgcolor="#FFA07A">Sisa</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="sisa_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td>            
        </tr>
        <tr>
            <td bgcolor="#FFA07A">Penyempurnaan S.Dana/SP2D &nbsp; &nbsp; &nbsp;</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="ang_semp_sd" readonly="true" style="text-align:right;border:0;" class="satu"/></td> 
            <td bgcolor="#FFA07A">Lalu</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="lalu_semp_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td> 
            <td bgcolor="#FFA07A">Sisa</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="sisa_semp_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td>            
        </tr>
        <tr>
            <td bgcolor="#FFA07A">Perubahan SumberDana/SP2D </td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="ang_ubah_sd" readonly="true" style="text-align:right;border:0;" class="satu"/></td> 
            <td bgcolor="#FFA07A">Lalu</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="lalu_ubah_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td> 
            <td bgcolor="#FFA07A">Sisa</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="sisa_ubah_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td>            
        </tr>

		<tr id="hidethis">
            <td bgcolor="#FFD700">SPD </td>
            <td bgcolor="#FFD700">:</td>
            <td bgcolor="#FFD700"><input type="text" id="tot_spd" readonly="true" class="satu" style="text-align:right;border:0;"/></td> 
            <td bgcolor="#FFD700">Realisasi</td>
            <td bgcolor="#FFD700">:</td>
            <td bgcolor="#FFD700"><input type="text" id="tot_trans" readonly="true" class="dua" style="text-align:right;border:0;"/></td> 
            <td bgcolor="#FFD700">Sisa</td>
            <td bgcolor="#FFD700">:</td>
            <td bgcolor="#FFD700"><input type="text" id="tot_sisa" readonly="true" class="dua" style="text-align:right;border:0;"/></td>            
        </tr>
		<tr>
            <td >Status</td>
            <td>:</td>
            <td colspan="7"><input type="text" id="status_ang" readonly="true" style="text-align:left;border:0;width: 150px;"/></td>
        </tr>        

       	<tr>
            <!--<td >Sisa Kas Tunai</td>
            <td>:</td>
            <td><input type="text" id="sisa_tunai" readonly="true" class="satu" style="text-align:right;border:0;"/></td>-->
            <td >Sisa Kas Bank</td>
            <td>:</td>
            <td><input type="text" id="sisa_bank" readonly="true" style="text-align:right;border:0;" class="satu"/></td>
        </tr>
		  <tr>
            <td >Potongan LS</td>
            <td>:</td>
            <td colspan="7"><input type="text" id="pot_ls" readonly="true" style="text-align:right;border:0;" class="satu"/></td>
        </tr> 
		  <tr>
            <td >Total Sisa</td>
            <td>:</td>
            <td colspan="7"><input type="text" id="total_sisa" readonly="true" style="text-align:right;border:0;" class="satu"/></td>
        </tr> 
			<tr>
                <td>NILAI</td>
				<td>:</td>
				<td colspan="7"><input type="text" id="nilai" class="satu" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:sisa_bayar();"/></td>            
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
        <table id="dg2" title="Input Rekening" style="width:1030px;height:270px;"  >  
        </table>  
     
    </fieldset>  
</div>


<div id="dialog-modal-rekening" title="Input Rekening Tujuan *)Semua Inputan Harus Di Isi.">    
    <fieldset>
    <table>
        <tr bgcolor="#FFE4E1">
            <td>Nilai Total Potongan</td>
            <td>:</td>
            <td ><input id="nilpotongan" name="nilpotongan" style="text-align: right; width: 200px;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>            
            <td width="30%">&nbsp;*) Harus diisi jika ada potongan</td>
        </tr>        
        <tr>
            <td>Rekening Tujuan</td>
            <td>:</td>
            <td><input type="text" id="rekening_tujuan" style="border:0;width: 200px;" /></td>
            <td width="30%">&nbsp;</td>
            </td>            
        </tr>  
        <tr>
            <td>A.N. Rekening</td>
            <td>:</td>
            <td><input type="text" id="nm_rekening_tujuan" style="border:0;width: 400px;"/></td>
            <td width="30%">&nbsp;</td>
            </td>            
        </tr>
        <tr>
            <td>Bank</td>
            <td>:</td>
            <td><input type="text" id="kd_bank_tujuan" style="border:0;width: 200px;" onkeyup="javascript:cek_huruf(this);"/></td>
            <td width="30%">&nbsp;</td>
            </td>            
        </tr>          
        <tr>
            <td >Nilai Transfer</td>
            <td>:</td>
            <td><input type="text" id="nilai_trf" style="text-align: right; width: 200px;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>            
            <td width="30%">&nbsp;</td>
        </tr>
    </table>  
    </fieldset>
    <fieldset>
    <table align="center">
        <tr>
            <td><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save_rekening();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:tutup_rekening_bnk();">Keluar</a>                               
            </td>
        </tr>
    </table>   
    </fieldset>      
</div>

</body>

</html>