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
        width: 700px;
        height: 70px;
        padding: 0.4em;
    }  
    </style>
    
    <script type="text/javascript"> 
   
    var nl       = 0;
	var tnl      = 0;
	var idx      = 0;
	var tidx     = 0;
	var oldRek   = 0;
    var rek      = 0;
    var kode     = '';
    var pidx     = 0;  
    var frek     = '';             
    var rek5     = '';
    var edit     = '';
    var lcstatus = '';
                    
    $(document).ready(function() {
            $("#accordion").accordion({
            height: 600
            });
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal" ).dialog({
            height: 300,
            width: 700,
            modal: true,
            autoOpen:false
        });
        $("#dialog-batal").dialog({
        height: 300,
        width: 700,
        modal: true,
        autoOpen:false
        });
        $( "#dialog-modal-rek" ).dialog({
            height: 600,
            width: 1100,
            modal: true,
            autoOpen:false
        });
            $("#tagih").hide();
            get_skpd();
			get_tahun();
			//seting_tombol();
		$("#loading").dialog({
				resizable: false,
				width:200,
				height:130,
				modal: true,
				draggable:false,
				autoOpen:false,    
				closeOnEscape:false
				});
      });
    
        
        $(function(){
       	     $('#dd').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }, onSelect: function(date){
            	var m = date.getMonth()+1;
					$("#kebutuhan_bulan").attr('value',m);
					cek_status_ang();
				}
            });
			
			$('#tgl_mulai').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
			
			$('#tgl_akhir').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
			
			$('#tgl_ttd').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
			
			$('#rekanan').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/tukd/perusahaan',  
                    idField:'nmrekan',  
                    textField:'nmrekan',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'nmrekan',title:'Perusahaan',width:40} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //$("#kode").attr("value",rowData.kode);
                    $("#dir").attr("value",rowData.pimpinan);
                    $("#npwp").attr("value",rowData.npwp);
                    $("#alamat").attr("value",rowData.alamat);
					
                    }   
                });

			 $('#tglspd').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
                
				
		   $('#rek_skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/tukd/skpd_2',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd ;               
               $("#rek_nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
           }  
           });
				
				
                $('#cspp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/load_spp_tu',  
                    idField:'no_spp',                    
                    textField:'no_spp',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spp',title:'SPP',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:60},
                        {field:'tgl_spp',title:'Tanggal',width:60} 
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nomer = rowData.no_spp;
                    kode = rowData.kd_skpd;
                    jns = rowData.jns_spp;
                    }   
                });
                
                $('#cc').combobox({
					url:'<?php echo base_url(); ?>/index.php/tukd/load_jenis_beban',
					valueField:'id',
					textField:'text',
					onSelect:function(rowIndex,rowData){
					validate_tombol();
                    }
				});
								
				$('#ttd1').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/BK',  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd1").attr("value",rowData.nama);
                    }  
				});          
        
				$('#ttd2').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/PPTK',  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd2").attr("value",rowData.nama);
                    }  
  
				});
				
				$('#ttd3').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/PA',  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd3").attr("value",rowData.nama);
                    }  
  
				});
				
				$('#ttd4').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/tukd/load_ttd3/BUD',  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd4").attr("value",rowData.nama);
                    }  
  
				});
				
                $('#notagih').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/load_no_penagihan',  
                    idField:'no_tagih',  
                    textField:'no_tagih',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'no_tagih',title:'No Penagihan',width:140},  
                           {field:'tgl_tagih',title:'Tanggal',width:140},
                           {field:'kd_skpd',title:'SKPD',width:140}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    var ststagih='1';
					no_tagih = rowData.no_tagih;
                    $("#tgltagih").attr("value",rowData.tgl_tagih);
                    $("#nil").attr("value",rowData.nila);
                    $("#ni").attr("value",rowData.nil);
					$("#ketentuan").attr("Value",rowData.ket);
					$("#kg").combogrid("setValue",rowData.kegiatan);
					//$("#jns_beban").attr("Value",'6');
					//validate_jenis_edit(3);
					detail_tagih(no_tagih);
					$("#rektotal_ls").attr('value',rowData.nila);
                    $("#rektotal1_ls").attr('value',rowData.nil);
					get_skpd();
                    }   
                });
                   
			$('#bank1').combogrid({  
                panelWidth:700,  
                url: '<?php echo base_url(); ?>/index.php/tukd/config_bank2',  
                    idField:'kd_bank',  
                    textField:'kd_bank',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_bank',title:'Kd Bank',width:150},  
                           {field:'nama_bank',title:'Nama',width:500}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //$("#kode").attr("value",rowData.kode);
                    $("#nama_bank").attr("value",rowData.nama_bank);
                    }   
                });
                    
                    $('#spp').edatagrid({
            		url: '<?php echo base_url(); ?>/index.php/tukd/load_spp_tu',
                    idField:'id',            
                    rownumbers:"true", 
                    fitColumns:"true",
                    singleSelect:"true",
                    autoRowHeight:"false",
                    loadMsg:"Tunggu Sebentar....!!",
                    pagination:"true",
                    nowrap:"true",                       
                    columns:[[
                	    {field:'no_spp',
                		title:'Nomor SPP',
                		width:40},
                        {field:'tgl_spp',
                		title:'Tanggal',
                		width:25},
                        {field:'kd_skpd',
                		title:'Nama SKPD',
                		width:25,
                        align:"left"},
                        {field:'keperluan',
                		title:'Keterangan',
                		width:140,
                        align:"left"}
                    ]],
                    onSelect:function(rowIndex,rowData){
					  urut     = rowData.urut;
                      no_spp   = rowData.no_spp;         
                      kode     = rowData.kd_skpd;
                      sp       = rowData.no_spd;          
                      bl       = rowData.bulan;
                      tg       = rowData.tgl_spp;
                      jn       = rowData.jns_spp;
                      kep      = rowData.keperluan;
                      bk       = rowData.bank;
                      ning     = rowData.no_rek;
                      status   = rowData.status;
                      kegi     = rowData.kd_kegiatan;
                      nm       = rowData.nm_kegiatan;
                      kprog    = rowData.kd_program;
                      nprog    = rowData.nm_program;
                      tot_spp  = rowData.tot_spp_;  
                      sts_setuju = rowData.sts_setuju;
                      csp2d_batal  = rowData.sp2d_batal;
                      cket_batal  = rowData.ket_batal;                        
					  $("#no_spp").attr('disabled',true);
                      get(urut,no_spp,kode,sp,tg,bl,jn,kep,bk,ning,status,kegi,nm,kprog,nprog,tot_spp,sts_setuju,csp2d_batal,cket_batal);
                      //det();       
                      detail_trans_3();   
                      //validate_kegiatan() ;
                      load_sum_spp(); 
                      edit = 'T' ;
                      lcstatus = 'edit';
                    },
                    onDblClickRow:function(rowIndex,rowData){
                        section2();   
                    }
                });
                
                $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1',  
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true,                    
                    columns:[[  
                        {field:'no_spd',title:'No SPD',width:30},  
                        {field:'tgl_spd',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd = rowData.no_spd;
					tglspd = rowData.tgl_spd;
					$("#tglspd").datebox("setValue",tglspd);
					jnis = document.getElementById('jns_beban').value;
                    validate_kegi(spd,jnis);                                                        
                    }    
                });
                
                
                $('#kg').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/kegi',  
                    idField:'kd_kegiatan',  
                    textField:'kd_kegiatan',
                    mode:'remote',  
                    fitColumns:true,                       
                    columns:[[  
                        {field:'kd_kegiatan',title:'Kode',width:30},  
                        {field:'nm_kegiatan',title:'Nama',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kegi   = rowData.kd_kegiatan;
                    nmkegi = rowData.nm_kegiatan;
                    $("#nm_kg").attr("value",rowData.nm_kegiatan);
                    prog = rowData.kd_program;
                    $("#kp").attr("value",rowData.kd_program);
                    nmprog = rowData.nm_program;
                    $("#nm_kp").attr("value",rowData.nm_program);
                    nilai= rowData.nilai;                   
                    det();                                                        
                    }    
                });
                
                
                $('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data2',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
			});
            
                
                $('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
			});
            
            
                $('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"false",
                 columns:[[
                    {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},               
                    {field:'kdkegiatan',title:'Kegiatan',width:150,align:'left'},
					{field:'kdrek5',title:'Rekening',width:70,align:'left'},
					{field:'nmrek5',title:'Nama Rekening',width:280},
					{field:'pad',title:'P A D',width:100,align:'right'},
					{field:'dak',title:'DAK FISIK',width:100,align:'right'},
                    {field:'daknf',title:'DAK NF',width:100,align:'right'},
					{field:'dau',title:'D A U',width:100,align:'right'},
					{field:'dbhp',title:'DBHP',width:100,align:'right'},
					{field:'did',title:'DID',width:100,align:'right'},
                    {field:'nilai1',title:'Total',width:100,align:'right'},
                    {field:'hapus',title:'Hapus',width:100,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
				]]	
           }); 
            
           
           
           
           $('#rek_kegi').combogrid({  
           panelWidth:700,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:150},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:700}    
           ]]  
           });
           
           
           $('#rek_reke').combogrid({  
           panelWidth:700,  
           idField   :'kd_rek5',  
           textField :'kd_rek5',  
           mode      :'remote',
           columns   :[[  
               {field:'kd_rek5',title:'Kode Rekening',width:150},  
               {field:'nm_rek5',title:'Nama Rekening',width:700}    
           ]]  
           });

        });
        
        
        function get_skpd(){
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
					
									
  								      $("#dn").attr("value",data.kd_skpd);
        							  $("#nmskpd").attr("value",data.nm_skpd);
									  
									  /*//hapus kalau sudah
									  	    if(data.kd_skpd=='1.01.01.01'){
												$('#tambah').linkbutton('enable');
											}else{
												$('#tambah').linkbutton('disable');
											}*/ 
									  
                                      $("#rek_skpd").combogrid("setValue",data.kd_skpd);
                                      $("#rek_nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
                                      kode = data.kd_skpd;
									  
									  
        	}  
			
			
            });
        }
		
		
	    function get_tahun(){
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
        var tgl_cek = $('#dd').datebox('getValue');      
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
		
        function data_notagih(){
		  $('#notagih').combogrid({url: '<?php echo base_url(); ?>/index.php/tukd/load_no_penagihan'});  
		}
        function detail_tagih(no_tagih){
		//alert("aaa");
        $(function(){
			$('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data_tagih',
                queryParams    : ({ no:no_tagih }),
                 idField       : 'idx',
                 toolbar       : "#toolbar",              
                 rownumbers    : "true", 
                 fitColumns    : false,
                 autoRowHeight : "false",
                 singleSelect  : "true",
                 nowrap        : "true",
                 onLoadSuccess : function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                    
                    kd          = rowIndex ;  
                    idx         =  rowData.idx ;
                    tkdkegiatan = rowData.kdkegiatan ;
                    tkdrek5     = rowData.kdrek5 ;
                    tnmrek5     = rowData.nmrek5 ;
                    tnilai1     = rowData.nilai1 ;
                                                               
                },
                 columns:[[
                      {field:'idx',
					 title:'idx',
					 width:100,
					 align:'left',
                     hidden:'true'
					 },               
                     {field:'kdkegiatan',
					 title:'Sub Kegiatan',
					 width:180,
					 align:'left'
					 },
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					 },
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:100
					 },
                    {field:'pad',
					 title:'P A D',
					 width:80,
                     align:'right'
                     },
					 {field:'dak',
					 title:'DAK FISIK',
					 width:80,
                     align:'right'
                     },
					 {field:'daknf',
					 title:'DAK NF',
					 width:80,
                     align:'right'
                     },
					 {field:'dau',
					 title:'D A U',
					 width:80,
                     align:'right'
                     },
					 {field:'dbhp',
					 title:'DBHP',
					 width:80,
                     align:'right'
                     },
					 {field:'did',
					 title:'DID',
					 width:80,
                     align:'right'
                     },
					 {field:'nilai1',
					 title:'Total',
					 width:100,
                     align:'right'
                     },
                    {field:'hapus',title:'Hapus',width:100,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
				]]	
			});
		});
        }
        function validate_kegiatan(){
            var kode_s = document.getElementById('dn').value;
            $(function(){
              $('#rek_kegi').combogrid({  
              panelWidth:700,  
              idField   :'kd_kegiatan',  
              textField :'kd_kegiatan',  
              mode      :'remote',
              url       :'<?php echo base_url(); ?>index.php/tukd/load_trskpd_ar_2',  
              queryParams:({kdskpd:kode_s}), 
              columns   :[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:150},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:700}    
               ]],
               onSelect:function(rowIndex,rowData){      
               $("#nm_rek_kegi").attr("value",rowData.nm_kegiatan); 
               $("#rek_reke").combogrid("setValue",''); 
			   
			   //kd_kegia = rowData.kd_kegiatan;
               validate_rekening(); 
			   
               }              
           });
           });
        }    
        
 
        function validate_rekening(){
           //alert("asd");
           $('#dgsppls').datagrid('selectAll');
           var rows = $('#dgsppls').datagrid('getSelections');     
           frek  = '' ;
           rek5  = '' ;
           for ( var p=0; p < rows.length; p++ ) { 
           rek5 = rows[p].kdrek5;                                       
           if ( p > 0 ){   
                  frek = frek+','+rek5;
              } else {
                  frek = rek5;
              }
           }
         
                var beban   = document.getElementById('jns_beban').value;
                var kode_s   = document.getElementById('dn').value  ;
                var kode_keg = $('#rek_kegi').combogrid('getValue') ;
                var nospp    = document.getElementById('no_spp').value ;
                //alert(kode_keg);
                $(function(){
                  $('#rek_reke').combogrid({  
                  panelWidth:700,  
                  idField   :'kd_rek5',  
                  textField :'kd_rek5',  
                  mode      :'remote',
                  url       :'<?php echo base_url(); ?>index.php/tukd/load_rek_ar',  
                  queryParams:({kdkegiatan:kode_keg,kdrek:frek}), 
                  columns:[[  
                   {field:'kd_rek5',title:'Kode Rekening',width:150},  
                   {field:'nm_rek5',title:'Nama Rekening',width:700}    
                   ]],
                   onSelect:function(rowIndex,rowData){      
                   
                           $("#nm_rek_reke").attr("value",rowData.nm_rek5); 
                           var koderek = rowData.kd_rek5 ;
                   
                           $.ajax({
                                type     : "POST",
                        		dataType : "json",   
                                data     : ({kegiatan:kode_keg,kdrek5:koderek,kd_skpd:kode_s,no_spp:nospp}), 
                        		url      : '<?php echo base_url(); ?>index.php/tukd/jumlah_ang_spp',
                        		success  : function(data){
                        		      $.each(data, function(i,n){
                                        $("#rek_nilai_ang").attr("Value",n['nilai']);
                                        $("#rek_nilai_spp").attr("Value",n['nilai_spp_lalu']);
                                        $("#rek_nilai_ang_semp").attr("Value",n['nilai_sempurna']);
                                        $("#rek_nilai_spp_semp").attr("Value",n['nilai_spp_lalu']);
										$("#rek_nilai_ang_ubah").attr("Value",n['nilai_ubah']);
                                        $("#rek_nilai_spp_ubah").attr("Value",n['nilai_spp_lalu']);
										
                                        var n_ang  = n['nilai'] ;
                                        var n_ang_semp  = n['nilai_sempurna'] ;
                                        var n_ang_ubah  = n['nilai_ubah'] ;
                                        var n_spp  = n['nilai_spp_lalu'] ;
                                        var n_sisa = angka(n_ang) - angka(n_spp) ;
                                        var n_sisa_semp = angka(n_ang_semp) - angka(n_spp) ;
                                        var n_sisa_ubah = angka(n_ang_ubah) - angka(n_spp) ;
                                        $("#rek_nilai_sisa").attr("Value",number_format(n_sisa,2,'.',','));
                                        $("#rek_nilai_sisa_semp").attr("Value",number_format(n_sisa_semp,2,'.',','));
                                        $("#rek_nilai_sisa_ubah").attr("Value",number_format(n_sisa_ubah,2,'.',','));
										
										var tgl_spd   = $('#tglspd').datebox('getValue');      
								 $.ajax({
											type     : "POST",
											dataType : "json",   
											data     : ({kegiatan:kode_keg,kd_skpd:kode_s,tglspd:tgl_spd,kdrek5:koderek,beban:beban}), 
											url      : '<?php echo base_url(); ?>index.php/tukd/total_spd',
											success  : function(data){
												  $.each(data, function(i,n){
													$("#total_spd").attr("Value",n['nilai']);
													var n_totalspd  = n['nilai'] ;
												   // var n_sisa = angka(n_ang) - angka(n_spp) ;
												   // $("#rek_nilai_sisa").attr("Value",number_format(n_sisa,2,'.',','));
												});
											}                                     
									   });
									   
							   
								 $.ajax({
											type     : "POST",
											dataType : "json",   
											data     : ({kegiatan:kode_keg,kd_skpd:kode_s,kdrek5:koderek,kdrek5:koderek,beban:beban,no_spp:nospp}), 
											url      : '<?php echo base_url(); ?>index.php/tukd/pakai_spd',
											success  : function(data){
												  $.each(data, function(i,n){
													$("#nilai_spd_lalu").attr("Value",n['nilai']);
													var n_spdlalu  = n['nilai'] ;
													var total_spd = document.getElementById('total_spd').value;
													var n_sisaspd = angka(total_spd) - angka(n_spdlalu) ;
													$("#nilai_sisa_spd").attr("Value",number_format(n_sisaspd,2,'.',','));
												});
											}                                     
									   });
									   
								 $.ajax({
											type     : "POST",
											dataType : "json",   
											data     : ({kegiatan:kode_keg,kd_skpd:kode_s,kdrek5:koderek,kdrek5:koderek}), 
											url      : '<?php echo base_url(); ?>index.php/tukd/ang_sumber_dana',
											success  : function(data){
												  $.each(data, function(i,n){
													$("#nil_pad").attr("Value",n['pad_murni']);
													$("#nil_pad_semp").attr("Value",n['pad_semp']);
													$("#nil_pad_ubah").attr("Value",n['pad_ubah']);
													$("#nil_dak").attr("Value",n['dak_murni']);
													$("#nil_dak_semp").attr("Value",n['dak_semp']);
													$("#nil_dak_ubah").attr("Value",n['dak_ubah']);
													$("#nil_daknf").attr("Value",n['daknf_murni']);
													$("#nil_daknf_semp").attr("Value",n['daknf_semp']);
													$("#nil_daknf_ubah").attr("Value",n['daknf_ubah']);
                                                    $("#nil_dau").attr("Value",n['dau_murni']);
													$("#nil_dau_semp").attr("Value",n['dau_semp']);
													$("#nil_dau_ubah").attr("Value",n['dau_ubah']);
													$("#nil_dbhp").attr("Value",n['dbhp_murni']);
													$("#nil_dbhp_semp").attr("Value",n['dbhp_semp']);
													$("#nil_dbhp_ubah").attr("Value",n['dbhp_ubah']);
													$("#nil_did").attr("Value",n['did_murni']);
													$("#nil_did_semp").attr("Value",n['did_semp']);
													$("#nil_did_ubah").attr("Value",n['did_ubah']);
												});
												validate_sumberdana();
											}                                     
									   });	   
									   
									   
									   
                                    });
        						}                                     
        	               });
                   }                
               });
               });
               $('#dgsppls').datagrid('unselectAll');
        }
           
    
function validate_sumberdana(){
	var kode_s   = document.getElementById('dn').value  ;
    var kode_keg = $('#rek_kegi').combogrid('getValue') ;
    var koderek = $('#rek_reke').combogrid('getValue') ;
	$.ajax({
			type     : "POST",
			dataType : "json",   
			data     : ({kegiatan:kode_keg,kd_skpd:kode_s,kdrek5:koderek}), 
			url      : '<?php echo base_url(); ?>index.php/tukd/real_sumber_dana',
			success  : function(data){
				  $.each(data, function(i,n){
					$("#nil_pad_trans").attr("Value",n['nil_pad']);
					$("#nil_dak_trans").attr("Value",n['nil_dak']);
                    $("#nil_daknf_trans").attr("Value",n['nil_daknf']);
					$("#nil_dau_trans").attr("Value",n['nil_dau']);
					$("#nil_dbhp_trans").attr("Value",n['nil_dbhp']);
					$("#nil_did_trans").attr("Value",n['nil_did']);
					var pad_trans = n['nil_pad'];
					var dak_trans = n['nil_dak'];
					var daknf_trans = n['nil_daknf'];
					var dau_trans = n['nil_dau'];
					var dbhp_trans = n['nil_dbhp'];
					var did_trans = n['nil_did'];
					var nil_pad_murni = document.getElementById('nil_pad').value;
					var nil_pad_semp = document.getElementById('nil_pad_semp').value;
					var nil_pad_ubah = document.getElementById('nil_pad_ubah').value;
					var nil_dak_murni = document.getElementById('nil_dak').value;
					var nil_dak_semp = document.getElementById('nil_dak_semp').value;
					var nil_dak_ubah = document.getElementById('nil_dak_ubah').value;
					var nil_daknf_murni = document.getElementById('nil_daknf').value;
					var nil_daknf_semp = document.getElementById('nil_daknf_semp').value;
					var nil_daknf_ubah = document.getElementById('nil_daknf_ubah').value;
					var nil_dau_murni = document.getElementById('nil_dau').value;
					var nil_dau_semp = document.getElementById('nil_dau_semp').value;
					var nil_dau_ubah = document.getElementById('nil_dau_ubah').value;
					var nil_dbhp_murni = document.getElementById('nil_dbhp').value;
					var nil_dbhp_semp = document.getElementById('nil_dbhp_semp').value;
					var nil_dbhp_ubah = document.getElementById('nil_dbhp_ubah').value;
					var nil_did_murni = document.getElementById('nil_did').value;
					var nil_did_semp = document.getElementById('nil_did_semp').value;
					var nil_did_ubah = document.getElementById('nil_did_ubah').value;
					var sisa_pad_murni = angka(nil_pad_murni) - angka(pad_trans);
					var sisa_pad_semp = angka(nil_pad_semp) - angka(pad_trans);
					var sisa_pad_ubah = angka(nil_pad_ubah)- angka(pad_trans);
					var sisa_dak_murni = angka(nil_dak_murni) - angka(dak_trans);
					var sisa_dak_semp = angka(nil_dak_semp) - angka(dak_trans);
					var sisa_dak_ubah = angka(nil_dak_ubah) - angka(dak_trans);
					var sisa_daknf_murni = angka(nil_daknf_murni) - angka(daknf_trans);
					var sisa_daknf_semp = angka(nil_daknf_semp) - angka(daknf_trans);
					var sisa_daknf_ubah = angka(nil_daknf_ubah) - angka(daknf_trans);
                    var sisa_dau_murni = angka(nil_dau_murni) - angka(dau_trans);
					var sisa_dau_semp = angka(nil_dau_semp) - angka(dau_trans);
					var sisa_dau_ubah = angka(nil_dau_ubah) - angka(dau_trans);
					var sisa_dbhp_murni = angka(nil_dbhp_murni) - angka(dbhp_trans);
					var sisa_dbhp_semp = angka(nil_dbhp_semp) - angka(dbhp_trans);
					var sisa_dbhp_ubah = angka(nil_dbhp_ubah) - angka(dbhp_trans);
					var sisa_did_murni = angka(nil_did_murni) - angka(did_trans);
					var sisa_did_semp = angka(nil_did_semp) - angka(did_trans);
					var sisa_did_ubah = angka(nil_did_ubah) - angka(did_trans);

					$("#pad_sisa").attr("Value",number_format(sisa_pad_murni,2,'.',','));
					$("#pad_sisa_semp").attr("Value",number_format(sisa_pad_semp,2,'.',','));
					$("#pad_sisa_ubah").attr("Value",number_format(sisa_pad_ubah,2,'.',','));
					$("#dak_sisa").attr("Value",number_format(sisa_dak_murni,2,'.',','));
					$("#dak_sisa_semp").attr("Value",number_format(sisa_dak_semp,2,'.',','));
					$("#dak_sisa_ubah").attr("Value",number_format(sisa_dak_ubah,2,'.',','));
					$("#daknf_sisa").attr("Value",number_format(sisa_daknf_murni,2,'.',','));
					$("#daknf_sisa_semp").attr("Value",number_format(sisa_daknf_semp,2,'.',','));
					$("#daknf_sisa_ubah").attr("Value",number_format(sisa_daknf_ubah,2,'.',','));
					$("#dau_sisa").attr("Value",number_format(sisa_dau_murni,2,'.',','));
					$("#dau_sisa_semp").attr("Value",number_format(sisa_dau_semp,2,'.',','));
					$("#dau_sisa_ubah").attr("Value",number_format(sisa_dau_ubah,2,'.',','));
					$("#dbhp_sisa").attr("Value",number_format(sisa_dbhp_murni,2,'.',','));
					$("#dbhp_sisa_semp").attr("Value",number_format(sisa_dbhp_semp,2,'.',','));
					$("#dbhp_sisa_ubah").attr("Value",number_format(sisa_dbhp_ubah,2,'.',','));
					$("#did_sisa").attr("Value",number_format(sisa_did_murni,2,'.',','));
					$("#did_sisa_semp").attr("Value",number_format(sisa_did_semp,2,'.',','));
					$("#did_sisa_ubah").attr("Value",number_format(sisa_did_ubah,2,'.',','));

				});
			}                                     
	   });
		
	}
       
    
        
       

        
    function validate_spd(kode){
           $(function(){
            $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1/'+kode,  
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true
                });
           });
        }
    
    
    function validate_kegi(spd,jnis){
		var cskpd   = document.getElementById('dn').value;
        if(cskpd != ''){ //hapus jika selesai 
			$(function(){
            $('#kg').combogrid({  
                panelWidth:500,                          
                url: '<?php echo base_url(); ?>/index.php/tukd/kegiatan_spd_tu',
                 queryParams:({spd:spd,jnis:jnis}),  
                    idField:'kd_kegiatan',  
                    textField:'kd_kegiatan',
                    mode:'remote',  
                    fitColumns:true
                });
            });
		}else{ //hapus jika selesai 
			$(function(){
            $('#kg').combogrid({  
                panelWidth:500,                          
                url: '<?php echo base_url(); ?>/index.php/tukd/kegiatan_spd',
                 queryParams:({spd:spd}),  
                    idField:'kd_kegiatan',  
                    textField:'kd_kegiatan',
                    mode:'remote',  
                    fitColumns:true
                });
            });
			
		}
    }

   
    function det(){   
          $(function(){            
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data2',
                queryParams:({giat:kegi}),
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){
                      detail1();                                           
                    },                      
                 onClickRow:function(rowIndex, rowData){                                
								keg=rowData.kd_kegiatan;
								rk=rowData.kd_rek5;
                                nkeg=rowData.nm_kegiatan;
                                nrek=rowData.nm_rek5;
                                ang=rowData.a;
                                kel=rowData.b;
                                sisa=ang - kel;
								simpan(keg,rk,nkeg,nrek,sisa);
                                detail1();
							 },				 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
                     {field:'pilih',
					 title:'pilih',
					 width:20,
                     align:'center',
					 checkbox:true,
                     hidden:true
                     },
                     {field:'kd_kegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					},                    
					{field:'kd_rek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:300
					},
                    {field:'a',
					 title:'Nilai Anggaran',
					 width:100,
                     align:'right',
                      hidden:true
                     },
                    {field:'b',
					 title:'SPP Lalu',
					 width:100,
                     align:'right',
                     hidden:true
                     },
                    {field:'nilai',
					 title:'Nilai Anggaran',
					 width:100,
                     align:'right'
                     },
                    {field:'total',
					 title:'SPP Lalu',
					 width:100,
                     align:'right'
                     }
				]]	
			});
            });
        }

        
        function det_baru(){   
	   	  var kegi='';
          $(function(){            
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data2',
                queryParams:({giat:kegi}),
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,                 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
                     {field:'pilih',
					 title:'pilih',
					 width:20,
                     align:'center',
					 checkbox:true,
                     hidden:true
                     },
                     {field:'kd_kegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					},                    
					{field:'kd_rek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:300
					},
                    {field:'a',
					 title:'Nilai Anggaran',
					 width:100,
                     align:'right'
                     },
                    {field:'b',
					 title:'SPP Lalu',
					 width:100,
                     align:'right'
                     }
				]]	
			});
            });
        }


        function detail1(){
        $(function(){
	   	    var spp = document.getElementById('no_spp').value;            
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({spp:spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      //load_sum_spp();                        
                    },
                onSelect:function(rowIndex,rowData){
                kd = rowIndex;                                               
                },   
                 onAfterEdit:function(rowIndex, rowData, changes){								
								kegiatan=rowData.kdkegiatan;
                                nkegiatan=rowData.nmkegiatan;
								rekeing=rowData.kdrek5;
                                nrekeing=rowData.nmrek5;
                                nilai=rowData.nilai1;
                                si=rowData.sis;
                                kd=rowIndex;
								dsimpan(kegiatan,rekeing,nkegiatan,nrekeing,nilai,si,kd);       	                                  
							 },                			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                     
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					},
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:300
					},
                    {field:'sisa',
					 title:'Sisa',
					 width:100,
                     align:'right'					 
                     },
                    {field:'nilai1',
					 title:'Nilai',
					 width:100,
                     align:'right',                    
					 editor:{type:"numberbox"					     
							}
                     }
				]]	
			});
		});
        }


     function get(urut,no_spp,kd_skpd,no_spd,tgl_spp,bulan,jns_spp,keperluan,bank,rekening,status,giat,nmgiat,prog,nmprog,tot_spp,sts_setuju,sp2d_batal,ket_batal){
		$("#dd_spp").attr("value",urut);
		$("#no_spp").attr("value",no_spp);
        $("#no_spp_hide").attr("value",no_spp);
		$("#no_simpan").attr("value",no_spp);
        $("#sp").combogrid("setValue",no_spd);
        $("#dd").datebox("setValue",tgl_spp);
        $("#kebutuhan_bulan").attr("Value",bulan);
        $("#ketentuan").attr("Value",keperluan);
        $("#jns_beban").attr("Value",jns_spp);
        $("#bank1").combogrid("setValue",bank);
        $("#rekening").attr("Value",rekening);
        $("#kg").combogrid("setValue",giat);
        $("#nm_kg").attr("Value",nmgiat);
        $("#kp").attr("setValue",prog);
        $("#nm_kp").attr("Value",nmprog);
		$("#ket_batal").attr("Value",ket_batal);

		//validate_jenis_edit(jns_bbn);
		//validate_tombol();
		 tombol(status,sts_setuju); 
         status_batal(sp2d_batal); 
         $('#rnospp').linkbutton('disable');         
        }

    function status_batal($status1){
        if($status1=='1'){
            $('#save').linkbutton('disable');
            $('#del').linkbutton('disable');
            $('#rnospp').linkbutton('disable');
            $('#batal').linkbutton('disable');
            $('#cetak').linkbutton('disable');
            document.getElementById("p2").innerHTML="SPP dalam Status Batal";
        }else{
			$('#cetak').linkbutton('enable');
            document.getElementById("p2").innerHTML="";
        }        
    }

		function kunci_sementara(){
		var kode_s   = document.getElementById('dn').value  ;
		var kunci ='';
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/ckunci_sementara',
                data: ({kode_s:kode_s}),
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
  				kunci = data.kode;
				//alert(kunci);
				if(kunci!=''){
					$('#save').linkbutton('disable');
				}else{
					$('#save').linkbutton('enable');
				}
        	}  
            });
        }
	
    
    function kosong(){
		kunci_sementara();
		$('#cetak1a').linkbutton('disable');
		$('#cetak2a').linkbutton('enable');
		$('#cetak3a').linkbutton('disable');
		$('#cetak4a').linkbutton('disable');
		$('#cetak5a').linkbutton('disable');
		$('#cetak6a').linkbutton('disable');
		$('#cetak1b').linkbutton('disable');
		$('#cetak2b').linkbutton('enable');
		$('#cetak3b').linkbutton('disable');
		$('#cetak4b').linkbutton('disable');
		$('#cetak5b').linkbutton('disable');
		$('#cetak6b').linkbutton('disable');
        $("#no_spp").attr("value",'');
        $("#no_spp_hide").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#sp").combogrid("setValue",'');
        $("#dd").datebox("setValue",'');
        $("#tgl_mulai").datebox("setValue",'');
        $("#tgl_akhir").datebox("setValue",'');
        $("#tglspd").datebox("setValue",'');
        $("#kebutuhan_bulan").attr("Value",'');
        $("#ketentuan").attr("Value",'');
        $("#npwp").attr("Value",'');
        $("#rekanan").combogrid("setValue",'');
        $("#dir").attr("Value",'');
        $("#bank1").combogrid("setValue",'');
        $("#rekening").attr("Value",'');
        $("#kg").combogrid("setValue",'');
        $("#nm_kg").attr("Value",'');
        $("#kg").combogrid("setValue",'');
        $("#nm_kg").attr("Value",'');
        $("#nama_bank").attr("Value",'');
        $("#kontrak").attr("Value",'');
        $("#lanjut").attr("Value",'');
        $("#alamat").attr("Value",'');
        $("#kp").attr("setValue",'');
        $("#nm_kp").attr("Value",'');
        document.getElementById("p1").innerHTML="";        
        $("#sp").combogrid("clear");
        $("#kg").combogrid("clear");
		$("#cc").combobox("setValue",'');
		$("#notagih").combogrid("clear");
        //det_baru();
        tombolnew();
        detail_kosong(); 
        validate_kegiatan(); 
		get_spp();	
		detail_spd();

        var pidx  = 0   ;     
        edit      = 'F' ;
		data_notagih();
        $("#rektotal_ls").attr("Value",0);
        $("#rektotal1_ls").attr("Value",0);
        
        lcstatus = 'tambah';
		//$("#notagih").combogrid("setValue",'');
        $("#tgltagih").attr("value",'');
        //$("#nmskpd").attr("value",'');
        $("#nil").attr("value",'');
        $("#ni").attr("value",'');
        $("#status").attr("checked",false);                  
        $("#tagih").hide();
		
		//hapus jika
		/*
		var cskpd   = document.getElementById('dn').value;
		if(cskpd=='1.01.01.01'){
			$('#save').linkbutton('enable');
		}else{
			$('#save').linkbutton('disable');
		}*/
		$('#save').linkbutton('enable');
        $('#rnospp').linkbutton('enable');
        }


	
    function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		}  


    function detail_spd(){                
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/sisa_spd_global",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#total_spd1").attr('value',number_format(n['spd'],2,'.',','));
                    $("#trans_spd").attr('value',number_format(n['transaksi'],2,'.',','));
                    $("#sisa_spd").attr('value',number_format(n['sisa_spd'],2,'.',','));

                });
            }
         });
        });
    }
	
    function simpan(giat,reke,nkeg,nrek,sisa){		
		var spp   = document.getElementById('no_spp').value;
		var cskpd = kode;
        var cspd  = spd;

        $(function(){      
            $.ajax({
            type: 'POST',
            data: ({cskpd:cskpd,cspd:spd,cspp:spp,cgiat:giat,crek:reke,cnmgiat:nkeg,cnmrek:nrek,sspp:sisa}),
            dataType:"json",
            url:'<?php echo base_url(); ?>/index.php/tukd/tsimpan'
         });
        });
		}       
        
       
    function cetak(){
        var nom=document.getElementById("no_spp").value;
        $("#cspp").combogrid("setValue",nom);
        $("#dialog-modal").dialog('open');
    } 
    
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    } 
    
    
    function keluar_rek(){
        $("#dialog-modal-rek").dialog('close');
        $("#dgsppls").datagrid("unselectAll");

        $("#rek_nilai").attr("Value",0);
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
    
    
    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#spp').edatagrid({
	       url: '<?php echo base_url(); ?>/index.php/tukd/load_spp_tu',
         queryParams:({cari:kriteria})
        });        
     });
    }
        
    
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
    }
     
     
    function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });
    }
     
     
    function section3(){
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
    }

     
    function hsimpan(){        
        var a      	 = (document.getElementById('no_spp').value).split(" ").join("");
        var a_hide   = document.getElementById('no_spp_hide').value;
		var a_dd    = document.getElementById('dd_spp').value;
        var b        = $('#dd').datebox('getValue');      
        var c        = document.getElementById('jns_beban').value; 
        var d        = document.getElementById('kebutuhan_bulan').value;
        var e        = document.getElementById('ketentuan').value;
        var g        = $("#bank1").combogrid("getValue") ; 
        var i        = document.getElementById('rekening').value;
        var j        = document.getElementById('nmskpd').value;
        var k1       = document.getElementById('rektotal1_ls').value;
        var l        = document.getElementById('nm_kg').value;
        var m        = document.getElementById('kp').value;
        var n        = document.getElementById('nm_kp').value;
        var z        = $("#sp").combogrid("getValue") ; 
        var y        = $("#kg").combogrid("getValue") ; 
        var k 	     = angka(k1);       
        var kdskpd   = document.getElementById('dn').value;
		var sisa_spd = angka(document.getElementById('sisa_spd').value);
        if ( a == '' ){
			swal("Error", "Isi Nomor SPP Terlebih Dahulu...!!!", "error");
            exit();
        }
		if (kdskpd == ''){
			swal("Error", "Isi SKPD Terlebih Dahulu...!!!", "error");
            exit();
		}
		if ( z == '' ){
			swal("Error", "Isi Nomor SPD Terlebih Dahulu...!!!", "error");
            exit();
        }
        
        if ( b == '' ){
			swal("Error", "Isi Tanggal Terlebih Dahulu...!!!", "error");
            exit();
        }
		
		var tahun_input = b.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			swal("Error", "Tahun tidak sama dengan tahun Anggaran", "error");
			exit();
		}
        
        if ( c == '' ){
			swal("Error", "Isi Beban Terlebih Dahulu...!!!", "error");
            exit();
        }
        
        if ( d == '' ){
			swal("Error", "Isi Kebutuhan Bulan Terlebih Dahulu...!!!", "error");
            exit();
        }
        
        if ( y == '' ){
			swal("Error", "Isi Kode Kegiatan Terlebih Dahulu...!!!", "error");
            exit();
        }
		var len_y = y.length;
		if ( len_y != 21 ){
			swal("Error", "Kode Kegiatan Salah!", "error");
            exit();
        }
		
		
		if ( l == '' ){
			swal("Error", "Pilih Kegiatan Terlebih Dahulu...!!!", "error");
            exit();
        }
		
		var lenket = e.length;
		if ( lenket>1000 ){
			swal("Error", "Keterangan Tidak boleh lebih dari 1000 karakter", "error");
            exit();
        }
		
		if ( sisa_spd< k ){
			swal("Error", "Sisa SPD Tidak cukup untuk Pengajuan SPP TU", "error");
            exit();
        }
		
		//Cek Datagrid
		var ctot_det=0;
		 $('#dgsppls').datagrid('selectAll');
            var rows = $('#dgsppls').datagrid('getSelections');           
			for(var x=0;x<rows.length;x++){
			cnilai3     = angka(rows[x].nilai1);
            ctot_det = ctot_det + cnilai3;
			} 
		if (k != ctot_det){
			swal("Error", "Nilai Rincian tidak sama dengan Total, Silakan Refresh kembali halaman ini!", "error");
			exit();
		}
		
		if (ctot_det==0){
			swal("Error", "Rincian Rekening Tidak Boleh Kosong", "error");
			exit();
		}
		
		
		
		if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'trhspp',field:'no_spp'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan_spp',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						swal("Error", "Nomor Telah Dipakai!", "error");
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
		
		//---------
		
            lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd,    jns_spp, jns_beban, bank,    nmrekan,  no_rek,  npwp,    nm_skpd,  tgl_spp, status, username,     last_update,   nilai,    no_bukti,     kd_kegiatan,  nm_kegiatan,  kd_program,  nm_program,  pimpinan,  no_tagih,    tgl_tagih,  sts_tagih, no_bukti2, no_bukti3, no_bukti4, no_bukti5, no_spd2, no_spd3, no_spd4 , alamat, kontrak, lanjut, tgl_mulai, tgl_akhir,urut)"; 
            lcvalues = "('"+a+"', '"+kdskpd+"', '"+e+"',   '"+d+"', '"+spd+"', '"+c+"', '1', '"+g+"', '',  '"+i+"', '', '"+j+"',  '"+b+"', '0',    '',           '',            '"+k+"',  '',           '"+y+"',   '"+l+"',      '"+m+"',     '"+n+"',     '',  '',     '',    '',    '',       '',        '',        '',        '',      '',      '',      '', '','','','','"+a_dd+"' )";
            //lcupdate = " UPDATE trhtagih SET sts_tagih='1' where no_bukti='"+p+"' "; 
			
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_tukd_tu',
                    data     : ({tabel:'trhspp',kolom:lcinsert,nilai:lcvalues,cid:'no_spp'}),
                    dataType : "json",
                    beforeSend:function(xhr){
					$("#loading").dialog('open');
						},
					success  : function(data){
                        status = data;
                        if (status=='0'){
							swal("Error", "Gagal Simpan..!!", "error");
                            exit();
                        } else if(status=='1'){
								  swal("Error", "Data Sudah Ada..!!", "error");
                                  exit();
                               } else {
								   $('#dgsppls').datagrid('selectAll');
									var rows = $('#dgsppls').datagrid('getSelections');
									
									for(var i=0;i<rows.length;i++){            
										cidx      = rows[i].idx;
										ckdgiat   = rows[i].kdkegiatan;
										ckdrek    = rows[i].kdrek5;
										cnmrek    = rows[i].nmrek5;
										cnilai    = angka(rows[i].nilai1);
										cpad      = angka(rows[i].pad);
										cdak      = angka(rows[i].dak);
										cdaknf      = angka(rows[i].daknf);
										cdau      = angka(rows[i].dau);
										cdbhp    = angka(rows[i].dbhp);
										cdid    = angka(rows[i].did);
										no        = i + 1 ;    
											if (i>0) {
												csql = csql+","+"('"+a+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kdskpd+"','"+ckdgiat+"','"+spd+"','"+cpad+"','"+cdak+"','"+cdau+"','"+cdbhp+"','"+cdaknf+"','"+cdid+"')";
											} else {
												csql = "values('"+a+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kdskpd+"','"+ckdgiat+"','"+spd+"','"+cpad+"','"+cdak+"','"+cdau+"','"+cdbhp+"','"+cdaknf+"','"+cdid+"')";                 
												}                                             
											}   	                  
											$(document).ready(function(){
												//alert(csql);
												//exit();
												$.ajax({
													type: "POST",   
													dataType : 'json',                 
													data: ({no:a,sql:csql}),
													url: '<?php echo base_url(); ?>/index.php/tukd/dsimpan_ag',
													success:function(data){                        
														status = data.pesan;   
														 if (status=='1'){
															$("#loading").dialog('close');
															swal("Berhasil", "Data Berhasil Tersimpan...!!!", "success");
															$("#no_spp_hide").attr("value",a);
															lcstatus='edit';
															section1();
														} else{ 
															$("#loading").dialog('close');
															lcstatus='tambah';
															swal("Error", "Gagal Simpan..!!", "error");
														}                                             
													}
												});
												});            
											}
                    }
                });
            });   
           
		//----------
		
		}
		}
		});
		});
		
        
            
        } else {
//alert(z);
			$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'trhspp',field:'no_spp'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan_spp',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && a!=a_hide){
						swal("Error", "Nomor Telah Dipakai!", "error");
						exit();
						} 
						if(status_cek==0 || a==a_hide){
						swal({
							  title: "Nomor Bisa Dipakai",
							  text: "Harap Tunggu sampai muncul pesan tersimpan!",
							  timer: 2000,
							  showConfirmButton: false
							});			
			
		//---------
		lcquery = " UPDATE trhspp SET kd_skpd='"+kdskpd+"', keperluan='"+e+"', bulan='"+d+"', no_spd='"+z+"', jns_spp='"+c+"',jns_beban='1', bank='"+g+"', nmrekan='', no_rek='"+i+"', npwp='', nm_skpd='"+j+"', tgl_spp='"+b+"', status='0', nilai='"+k+"', kd_kegiatan='"+kegi+"', nm_kegiatan='"+l+"', kd_program='"+m+"', nm_program='"+n+"', pimpinan='', no_tagih='', tgl_tagih='', sts_tagih='', no_spp='"+a+"',alamat ='', kontrak='',lanjut='',tgl_mulai='',tgl_akhir='' where no_spp='"+a_hide+"' AND kd_skpd='"+kdskpd+"' "; 

//			alert(lcquery);
//exit();
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/update_tukd',
                data     : ({st_query:lcquery,tabel:'trhspp',cid:'no_spp',lcid:a,lcid_h:a_hide}),
                dataType : "json",
                beforeSend:function(xhr){
					$("#loading").dialog('open');
						},
				success  : function(data){
                           status=data ;
                                                		
                        if ( status=='1' ){
							//alert("aaaa");
                            alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                            exit();
                        }
                        
                        if ( status=='2' ){
								   $('#dgsppls').datagrid('selectAll');
									var rows = $('#dgsppls').datagrid('getSelections');
									
									for(var i=0;i<rows.length;i++){            
										cidx      = rows[i].idx;
										ckdgiat   = rows[i].kdkegiatan;
										ckdrek    = rows[i].kdrek5;
										cnmrek    = rows[i].nmrek5;
										cnilai    = angka(rows[i].nilai1);
										cpad      = angka(rows[i].pad);
										cdak      = angka(rows[i].dak);
										cdaknf      = angka(rows[i].daknf);
										cdau      = angka(rows[i].dau);
										cdbhp    = angka(rows[i].dbhp);
										cdid    = angka(rows[i].did);

										no        = i + 1 ;    
											if (i>0) {
												csql = csql+","+"('"+a+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kdskpd+"','"+ckdgiat+"','"+spd+"','"+cpad+"','"+cdak+"','"+cdau+"','"+cdbhp+"','"+cdaknf+"','"+cdid+"')";
											} else {
												csql = "values('"+a+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kdskpd+"','"+ckdgiat+"','"+spd+"','"+cpad+"','"+cdak+"','"+cdau+"','"+cdbhp+"','"+cdaknf+"','"+cdid+"')";                 
												}                                             
											}   	                  
											$(document).ready(function(){
												//alert(csql);
												//exit();
												$.ajax({
													type: "POST",   
													dataType : 'json',                 
													data: ({no:a,sql:csql,no_hide:a_hide}),
													url: '<?php echo base_url(); ?>/index.php/tukd/dsimpan_ag_edit',
													success:function(data){                        
														status = data.pesan;   
														 if (status=='1'){
															$("#loading").dialog('close');
															swal("Berhasil", "Data Berhasil Tersimpan...!!!", "success");
															$("#no_spp_hide").attr("value",a);
															lcstatus='edit';
															data_notagih();
														} else{ 
															$("#loading").dialog('close');
															lcstatus='tambah';
															swal("Error", "Detail Gagal Tersimpan...!!!", "error");
														}                                             
													}
												});
												});            
											}
                        
                        if ( status=='0' ){
							swal("Error", "Gagal Tersimpan...!!!", "error");
                            exit();
                        }
                        
                    }
            });
            });
		
		//-----------
				}
			}
		});
     });
		
        }
        
    }
    
    
    function dsimpan(kegiatan,rekening,nkegiatan,nrekening,nilai,sis,kd){
        var a = document.getElementById('no_spp').value;
        $jak  = eval(sis);
        $son  = eval(nilai);       
        if ($son > $jak){
            alert('nilai melebihi anggaran')
        } else {
        $(function(){      
         $.ajax({
            type     : 'POST',
            data     : ({cno_spp:a,cskpd:kode,cgiat:kegiatan,crek:rekening,ngiat:nkegiatan,nrek:nrekening,nilai:nilai,sis:sis,kd:kd}),
            dataType :"json",
            url      :"<?php echo base_url(); ?>index.php/tukd/dsimpan"            
         });
        });
        }
    } 
    
    
    function detsimpan(){

        var a         = document.getElementById('no_spp').value; 
        var kode      = $("#rek_skpd").combogrid("getValue") ;
        var cnmgiat   = document.getElementById('nm_rek_kegi').value;
        var cnobukti1 = '' ;
        var a_hide    = document.getElementById('no_spp_hide').value; 
        
        $(document).ready(function(){      
           $.ajax({
           type     : 'POST',
           url      : "<?php  echo base_url(); ?>index.php/tukd/dsimpan_hapus",
           data     : ({cno_spp:a_hide,lcid:a,lcid_h:a_hide}),
           dataType : "json",
           success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Hapus Detail Old');
                            exit();
                        } 
                        }
                        });
        });
        
        
        $('#dgsppls').datagrid('selectAll');
        var rows = $('#dgsppls').datagrid('getSelections');
        
        for(var i=0;i<rows.length;i++){            
            cidx      = rows[i].idx;
            ckdgiat   = rows[i].kdkegiatan;
            ckdrek    = rows[i].kdrek5;
            cnmrek    = rows[i].nmrek5;
            cnilai    = angka(rows[i].nilai1);
                       
            no        = i + 1 ;      
            $(document).ready(function(){      
                $.ajax({
                type     : 'POST',
                url      : "<?php  echo base_url(); ?>index.php/tukd/dsimpan",
                data     : ({cno_spp:a,cskpd:kode,cgiat:ckdgiat,crek:ckdrek,ngiat:cnmgiat,nrek:cnmrek,nilai:cnilai,kd:no,no_bukti1:cnobukti1}),
                dataType : "json"
                });
            });
        }
        $("#no_spp_hide").attr("Value",a) ;
        $('#dgsppls').edatagrid('unselectAll');
    } 
    
    
    function hapus(){				
                var spp = document.getElementById("no_spp").value;                
                var nospp =spp.split("/").join("123456789");
				var giat=getSelections();
                var rek=getSelections1();
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus rekening '+rek+' kegiatan'+giat+ ' ?');
				if  (del==true){
					$(function(){
						$('#dg1').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/tukd/thapus/'+nospp+'/'+giat+'/'+rek,
							 idField:'id',
							 toolbar:"#toolbar",              
							 rownumbers:"true", 
							 fitColumns:"true",
							 singleSelect:"true"
						});
					});
				
				}
				}
		}
          
        
        function hhapus(){				
            
            var spp = document.getElementById("no_spp").value;  
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hhapus';             			    
         	if (spp !=''){
				var del=confirm('Anda yakin akan menghapus SPP '+spp+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:spp}),function(data){
                    status = data;                        
                    });
                    });				
				}
				} 
		}
        
        
        function getSelections(idx){
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kdkegiatan);
			}
			return ids.join(':');
		}
        
        function getSelections1(idx){
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kdrek5);
			}
			return ids.join(':');
		}
    
    function kembali(){
        $('#kem').click();
    }                
    

    function load_sum_spp(){                
		var nospp = document.getElementById('no_spp').value;
        //var nospp =spp.split("/").join("123456789");       
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spp:nospp}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_spp",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal_ls").attr('value',number_format(n['rektotal'],2,'.',','));
                    $("#rektotal1_ls").attr('value',number_format(n['rektotal'],2,'.',','));
                });
            }
         });
        });
    }

    function seting_tombol(){
		$('#tambah').linkbutton('disable');
		$('#save').linkbutton('disable');
        $('#del').linkbutton('disable');
        document.getElementById("p1").innerHTML="Batas Pembuatan SPP TU sudah selesai";
	}
    
    function	tombol(st,sts_setuju){ 
    if (st==1){
    $('#save').linkbutton('disable');
    $('#del').linkbutton('disable');
	$('#cetak1a').linkbutton('enable');
	$('#cetak2a').linkbutton('enable');
	$('#cetak3a').linkbutton('enable');
	$('#cetak4a').linkbutton('enable');
	$('#cetak5a').linkbutton('enable');
	$('#cetak6a').linkbutton('enable');
	$('#cetak1b').linkbutton('enable');
	$('#cetak2b').linkbutton('enable');
	$('#cetak3b').linkbutton('enable');
	$('#cetak4b').linkbutton('enable');
	$('#cetak5b').linkbutton('enable');
	$('#cetak6b').linkbutton('enable');
    $('#rnospp').linkbutton('disable');
    $('#batal').linkbutton('disable');
	document.getElementById("p1").innerHTML="Sudah di Buat SPM...!!!";
    } else if (sts_setuju==1){
    $('#save').linkbutton('disable');
    $('#del').linkbutton('disable');
	$('#cetak1a').linkbutton('enable');
	$('#cetak2a').linkbutton('enable');
	$('#cetak3a').linkbutton('enable');
	$('#cetak4a').linkbutton('enable');
	$('#cetak5a').linkbutton('enable');
	$('#cetak6a').linkbutton('enable');
	$('#cetak1b').linkbutton('enable');
	$('#cetak2b').linkbutton('enable');
	$('#cetak3b').linkbutton('enable');
	$('#cetak4b').linkbutton('enable');
	$('#cetak5b').linkbutton('enable');
	$('#cetak6b').linkbutton('enable');
    $('#rnospp').linkbutton('disable');
    $('#batal').linkbutton('disable');
    document.getElementById("p1").innerHTML="Sudah disahkan BPKPD ...!!!";
    }else {
	 $('#save').linkbutton('enable');
     $('#del').linkbutton('enable'); 
	$('#cetak1a').linkbutton('disable');
	$('#cetak2a').linkbutton('disable');
	$('#cetak3a').linkbutton('enable');
	$('#cetak4a').linkbutton('disable');
	$('#cetak5a').linkbutton('disable');
	$('#cetak6a').linkbutton('disable');
	$('#cetak1b').linkbutton('disable');
	$('#cetak2b').linkbutton('disable');
	$('#cetak3b').linkbutton('enable');
	$('#cetak4b').linkbutton('disable');
	$('#cetak5b').linkbutton('disable');
	$('#cetak6b').linkbutton('disable');
    $('#batal').linkbutton('enable');
    document.getElementById("p1").innerHTML="";
    }
    }
    
    
    function tombolnew(){  
     $('#save').linkbutton('enable');
	/*	var cskpd   = document.getElementById('dn').value;
		if(cskpd=='1.01.01.01'){
			$('#save').linkbutton('enable');
		}else{
			$('#save').linkbutton('disable');
		}*/
	 
     $('#del').linkbutton('enable');
     $('#det').linkbutton('enable');     
     $('#sav').linkbutton('enable');
     $('#dele').linkbutton('enable');
    }
		

    function cetak_spp3(){ 
            var urll= '<?php echo base_url(); ?>/index.php/tukd/cetakspp3';             			    
         	if (spp !=''){
				var del=confirm('Anda yakin akan mencetak SPP '+nomer+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:nomer}),function(data){
                    status = data;                        
                    });
                    });				
				}
				} 
	}
        
   
   function cetak_spp( url )
        {
		var spasi  = document.getElementById('spasi').value;
		var nomer   = $("#cspp").combogrid('getValue');
        var jns = document.getElementById('jns_beban').value; 
        var no =nomer.split("/").join("123456789");
		var ttd1   = $("#ttd1").combogrid('getValue');
		var ttd2   = $("#ttd2").combogrid('getValue');
		var ttd4   = $("#ttd4").combogrid('getValue');
		var tanpa       = document.getElementById('tanpa_tanggal').checked; 
		if ( tanpa == false ){
           tanpa=0;
        }else{
           tanpa=1;
        }
		if ( ttd1 =='' ){
			alert("Bendahara Pengeluaran tidak boleh kosong!");
			exit();
		}
		if ( ttd2 =='' ){
			alert("PPTK tidak boleh kosong!");
			exit();
		}
		if ( ttd4 =='' ){
			alert("PPKD tidak boleh kosong!");
			exit();
		}
        var ttd_1 =ttd1.split(" ").join("123456789");
        var ttd_2 =ttd2.split(" ").join("123456789");
        var ttd_4 =ttd4.split(" ").join("123456789");

        window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_1+'/'+ttd_2+'/'+ttd_4+'/'+spasi+'/'+tanpa, '_blank');
        window.focus();
        }
    

	function cetak_spp_2( url )
        {
		var spasi  = document.getElementById('spasi').value;
		var nomer   = $("#cspp").combogrid('getValue');
        var jns = document.getElementById('jns_beban').value; 
        var no =nomer.split("/").join("123456789");
		var ttd3   = $("#ttd3").combogrid('getValue');
		var tanpa       = document.getElementById('tanpa_tanggal').checked; 
		if ( tanpa == false ){
           tanpa=0;
        }else{
           tanpa=1;
        }
		if ( ttd3 =='' ){
			alert("Bendahara Pengeluaran tidak boleh kosong!");
			exit();
		}
		
        var ttd_3 =ttd3.split(" ").join("123456789");

       // window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_3+'/'+tanda, '_blank');
        window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_3+'/'+tanpa+'/'+spasi, '_blank');
        window.focus();
        }    
   
   function detail(){
        var lcno = document.getElementById('no_spp').value;
            if(lcno !=''){
               section3();               
            } else {
                alert('Nomor SPP Tidak Boleh kosong')
                document.getElementById('no_spp').focus();
                exit();
            }
    }    
    function validate_jenis_edit(){
        var beban   = document.getElementById('jns_beban').value;
		$('#cc').combobox({url:'<?php echo base_url(); ?>/index.php/tukd/load_jenis_beban/'+beban,
		});
		$('#sp').combogrid({url:'<?php echo base_url(); ?>/index.php/tukd/spd1_ag/'+beban,
		});
		if (beban=='6'){
			$("#npwp").attr('disabled',false);
			$("#tgl_mulai").datebox('enable');
			$("#tgl_akhir").datebox('enable');
			$("#rekanan").combogrid('enable');
			$("#dir").attr('disabled',false);
			$("#alamat").attr('disabled',false);
			$("#kontrak").attr('disabled',false);
			$("#bank1").combogrid('enable');
			$("#rekening").attr('disabled',false);
		} else {
			$("#npwp").attr('disabled',false);
			$("#tgl_mulai").datebox('disable');
			$("#tgl_akhir").datebox('disable');
			$("#rekanan").combogrid('disable');
			$("#dir").attr('disabled',true);
			$("#alamat").attr('disabled',true);
			$("#kontrak").attr('disabled',true);
			$("#bank1").combogrid('enable');
			$("#rekening").attr('disabled',false);
		
		}
		$('#cc').combobox('setValue', jns_bbn);
	}
    function validate_jenis(){
		var tanggal_spp = $('#dd').datebox('getValue');
		if(tanggal_spp == ''){
			alert("Isi Tanggal SPP Terlebih Dahulu!");
			$("#jns_beban").attr("Value",'');
			exit();
		}
        var beban   = document.getElementById('jns_beban').value;
		$('#cc').combobox({url:'<?php echo base_url(); ?>/index.php/tukd/load_jenis_beban/'+beban,
		});
		$('#sp').combogrid({url:'<?php echo base_url(); ?>/index.php/tukd/spd1_ag/'+beban+'/'+tanggal_spp,
		});
		if (beban=='6'){
			$("#npwp").attr('disabled',false);
			$("#tgl_mulai").datebox('enable');
			$("#tgl_akhir").datebox('enable');
			$("#rekanan").combogrid('enable');
			$("#dir").attr('disabled',false);
			$("#alamat").attr('disabled',false);
			$("#kontrak").attr('disabled',false);
			$("#bank1").combogrid('enable');
			$("#rekening").attr('disabled',false);
		} else {
			$("#npwp").attr('disabled',false);
			$("#tgl_mulai").datebox('disable');
			$("#tgl_akhir").datebox('disable');
			$("#rekanan").combogrid('disable');
			$("#dir").attr('disabled',true);
			$("#alamat").attr('disabled',true);
			$("#kontrak").attr('disabled',true);
			$("#bank1").combogrid('enable');
			$("#rekening").attr('disabled',false);
		}
	} 
	
	 function validate_tombol(){
        var beban   = document.getElementById('jns_beban').value;
		var jenis   = $("#cc").combobox('getValue');
		if ((beban=='6') && (jenis=='3')){
			$("#npwp").attr('disabled',false);
			$("#tgl_mulai").datebox('enable');
			$("#tgl_akhir").datebox('enable');
			$("#rekanan").combogrid('enable');
			$("#dir").attr('disabled',false);
			$("#alamat").attr('disabled',false);
			$("#kontrak").attr('disabled',false);
			$("#bank1").combogrid('enable');
			$("#rekening").attr('disabled',false);
		} else {
			$("#npwp").attr('disabled',false);
			$("#tgl_mulai").datebox('disable');
			$("#tgl_akhir").datebox('disable');
			$("#rekanan").combogrid('disable');
			$("#dir").attr('disabled',true);
			$("#alamat").attr('disabled',true);
			$("#kontrak").attr('disabled',true);
			$("#bank1").combogrid('enable');
			$("#rekening").attr('disabled',false);
		}
    }
    function runEffect() {
        var selectedEffect = 'explode';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
        $("#notagih").combogrid("setValue",'');
        $("#tgltagih").attr("value",'');
        $("#nmskpd").attr("value",'');
        $("#nil").attr("value",'');
        $("#ni").attr("value",'');
    };        
    
    
    function detail_trans_3(){
        $(function(){
			$('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams    : ({ spp:no_spp }),
                 idField       : 'idx',
                 toolbar       : "#toolbar",              
                 rownumbers    : "true", 
                 fitColumns    : false,
                 autoRowHeight : "false",
                 singleSelect  : "true",
                 nowrap        : "true",
                 onLoadSuccess : function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                    
                    kd          = rowIndex ;  
                    idx         =  rowData.idx ;
                    tkdkegiatan = rowData.kdkegiatan ;
                    tkdrek5     = rowData.kdrek5 ;
                    tnmrek5     = rowData.nmrek5 ;
                    tnilai1     = rowData.nilai1 ;
                                                               
                },
                 columns:[[
                     {field:'idx',
					 title:'idx',
					 width:10,
					 align:'left',
                     hidden:'true'
					 },               
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					 },
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					 },
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:100
					 },
                    {field:'pad',
					 title:'P A D',
					 width:100,
                     align:'right'
                     },
					 {field:'dak',
					 title:'DAK FISIK',
					 width:100,
                     align:'right'
                     },
					 {field:'daknf',
					 title:'DAK NON FISIK',
					 width:100,
                     align:'right'
                     },
					 {field:'dau',
					 title:'D A U',
					 width:100,
                     align:'right'
                     },
					 {field:'dbhp',
					 title:'DBHP',
					 width:100,
                     align:'right'
                     },
					 {field:'did',
					 title:'DID',
					 width:100,
                     align:'right'
                     },
					 {field:'nilai1',
					 title:'Total',
					 width:100,
                     align:'right'
                     },
                    {field:'hapus',title:'Hapus',width:100,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
				]]	
			});
		});
        }
        
        
        function detail_kosong(){
            
        var no_spp = '' ; 
        $(function(){
			$('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({ spp:no_spp }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 onLoadSuccess:function(data){   
                 },
                onSelect:function(rowIndex,rowData){
                kd  = rowIndex ;  
                idx =  rowData.idx ;                                           
                },
                 columns:[[
                     {field:'idx',
					 title:'idx',
					 width:10,
					 align:'left',
                     hidden:'true'
					 },               
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					 },
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					 },
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:100
					 },
                    {field:'pad',
					 title:'P A D',
					 width:100,
                     align:'right'
                     },
					 {field:'dak',
					 title:'DAK FISIK',
					 width:100,
                     align:'right'
                     },
					 {field:'daknf',
					 title:'DAK NON FISIK',
					 width:100,
                     align:'right'
                     },
					 {field:'dau',
					 title:'D A U',
					 width:100,
                     align:'right'
                     },
					 {field:'dbhp',
					 title:'DBHP',
					 width:100,
                     align:'right'
                     },
					 {field:'did',
					 title:'DID',
					 width:100,
                     align:'right'
                     },
					 {field:'nilai1',
					 title:'Total',
					 width:100,
                     align:'right'
                     },
                    {field:'hapus',title:'Hapus',width:100,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
				]]	
			});
		});
        }
        
        
        function tambah(){
           var cek_kegi = $("#kg").combogrid('getValue');
           if ( cek_kegi == '') {
                alert('Isi Kode Kegiatan Terlebih Dahulu....!!!') ;
                exit() ;
           }
		   
           $("#dialog-modal-rek").dialog('open'); 
           $("#rek_skpd").combogrid("disable");
           $("#rek_kegi").combogrid("disable");
           $("#rek_kegi").combogrid("setValue",'');
           $("#nm_rek_kegi").attr("Value",'');
           $("#rek_reke").combogrid("setValue",'');
           $("#nm_rek_reke").attr("Value",'');
           
           var kegi_tmb    = $("#kg").combogrid('getValue') ;
		   var nm_kegi_tmb = document.getElementById('nm_kg').value ;
           $("#rek_kegi").combogrid("setValue",kegi_tmb);
           $("#nm_rek_kegi").attr("Value",nm_kegi_tmb);
           //alert(cek_kegi);
           $("#rek_nilai").attr("Value",0);
		   $("#rek_pad").attr("Value",0);
           $("#rek_dak").attr("Value",0);
           $("#rek_daknf").attr("Value",0);
           $("#rek_dau").attr("Value",0);
           $("#rek_dbhp").attr("Value",0);
           $("#rek_did").attr("Value",0);
           $("#rek_nilai_ang").attr("Value",0);
           $("#rek_nilai_spp").attr("Value",0);
           $("#rek_nilai_sisa").attr("Value",0);
		   $("#rek_nilai_ang_semp").attr("Value",0);
           $("#rek_nilai_spp_semp").attr("Value",0);
           $("#rek_nilai_sisa_semp").attr("Value",0);
		   $("#rek_nilai_ang_ubah").attr("Value",0);
           $("#rek_nilai_spp_ubah").attr("Value",0);
           $("#rek_nilai_sisa_ubah").attr("Value",0);
           $("#total_spd").attr("Value",0);
           $("#nilai_spd_lalu").attr("Value",0);
           $("#nilai_sisa_spd").attr("Value",0);
        
        }
        
       function hitung(){
        var a = angka(document.getElementById('rek_pad').value);
        var b = angka(document.getElementById('rek_dak').value);
        var c = angka(document.getElementById('rek_dau').value); 
        var d = angka(document.getElementById('rek_dbhp').value); 
        var f = angka(document.getElementById('rek_daknf').value); 
        var g = angka(document.getElementById('rek_did').value); 

		var e = a+b+c+d+f+g;
        $("#rek_nilai").attr("value",number_format(e,2,'.',','));
		   
	   }
	   
       function append_save() {
        
            $('#dgsppls').datagrid('selectAll');
            var rows  = $('#dgsppls').datagrid('getSelections') ;
                jgrid = rows.length ;
        
            var jumtotal  = document.getElementById('rektotal_ls').value ;
                jumtotal  = angka(jumtotal);
        
            var vrek_skpd = $('#rek_skpd').combobox('getValue');
            var vrek_kegi = $('#rek_kegi').combobox('getValue');
            var vrek_reke = $('#rek_reke').combobox('getValue');
            var cnil      = document.getElementById('rek_nilai').value;
            var cnil_pad  = document.getElementById('rek_pad').value;
            var cnil_dak  = document.getElementById('rek_dak').value;
            var cnil_daknf  = document.getElementById('rek_daknf').value;
            var cnil_dau  = document.getElementById('rek_dau').value;
            var cnil_dbhp = document.getElementById('rek_dbhp').value;
            var cnil_did = document.getElementById('rek_did').value;
			var cnilai    = cnil;      
			var cnil_pad_input    = angka(document.getElementById('rek_pad').value);
            var cnil_dak_input    = angka(document.getElementById('rek_dak').value);
            var cnil_daknf_input    = angka(document.getElementById('rek_daknf').value);
            var cnil_dau_input    = angka(document.getElementById('rek_dau').value);
            var cnil_dbhp_input   = angka(document.getElementById('rek_dbhp').value);
            var cnil_did_input   = angka(document.getElementById('rek_did').value);
			var cpad_sisa_murni   = angka(document.getElementById('pad_sisa').value) ;
			var cpad_sisa_semp    = angka(document.getElementById('pad_sisa_semp').value) ;
			var cpad_sisa_ubah    = angka(document.getElementById('pad_sisa_ubah').value) ;
			var cdak_sisa_murni   = angka(document.getElementById('dak_sisa').value) ;
			var cdak_sisa_semp    = angka(document.getElementById('dak_sisa_semp').value) ;
			var cdak_sisa_ubah    = angka(document.getElementById('dak_sisa_ubah').value) ;
			var cdaknf_sisa_murni   = angka(document.getElementById('daknf_sisa').value) ;
			var cdaknf_sisa_semp    = angka(document.getElementById('daknf_sisa_semp').value) ;
			var cdaknf_sisa_ubah    = angka(document.getElementById('daknf_sisa_ubah').value) ;
			var cdau_sisa_murni   = angka(document.getElementById('dau_sisa').value) ;
			var cdau_sisa_semp    = angka(document.getElementById('dau_sisa_semp').value) ;
			var cdau_sisa_ubah    = angka(document.getElementById('dau_sisa_ubah').value) ;
			var cdbhp_sisa_murni  = angka(document.getElementById('dbhp_sisa').value) ;
			var cdbhp_sisa_semp   = angka(document.getElementById('dbhp_sisa_semp').value) ;
			var cdbhp_sisa_ubah   = angka(document.getElementById('dbhp_sisa_ubah').value) ;
			var cdid_sisa_murni  = angka(document.getElementById('did_sisa').value) ;
			var cdid_sisa_semp   = angka(document.getElementById('did_sisa_semp').value) ;
			var cdid_sisa_ubah   = angka(document.getElementById('did_sisa_ubah').value) ;
            var cnil_sisa   = angka(document.getElementById('rek_nilai_sisa').value) ;
            var cnil_sisa_spd   = angka(document.getElementById('nilai_sisa_spd').value) ;
			var cnil_sisa_semp   = angka(document.getElementById('rek_nilai_sisa_semp').value) ;
			var cnil_sisa_ubah   = angka(document.getElementById('rek_nilai_sisa_ubah').value) ;
            var cnil_input  = angka(document.getElementById('rek_nilai').value) ;
            var status_ang  = document.getElementById('status_ang').value ;
			var tot_input =  angka(document.getElementById('rektotal1_ls').value);
				akumulasi = cnil_input+tot_input;
            if ((status_ang=='')){
				 swal("Error", "Pilih Tanggal Dahulu", "error");
                 exit();
            }
			  if ((akumulasi > cnil_sisa_spd)){
				 swal("Error", "Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!", "error");
			 exit();
            }
            if (cnil_input == 0 ){
				swal("Error", "Nilai Nol.....!!!, Cek Lagi...!!!", "error");
                exit();
            }
			//PAD
			if ((status_ang=='Perubahan')&&(cnil_pad_input > cpad_sisa_ubah)){
				swal("Error", "Nilai PAD Melebihi Sisa Anggaran Perubahan PAD...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			
			if ((status_ang=='Penyempurnaan')&&(cnil_pad_input > cpad_sisa_ubah)){
				swal("Error", "Nilai PAD Melebihi Sisa Anggaran Rencana Perubahan PAD...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			if ((status_ang=='Penyempurnaan')&&(cnil_pad_input > cpad_sisa_semp)){
				swal("Error", "Nilai PAD Melebihi Sisa Anggaran Penyempurnaan PAD...!!! , Cek Lagi...!!!", "error");
				exit();
            }
			
			if ( (status_ang=='Penyusunan')&&(cnil_pad_input > cpad_sisa_ubah)){
				swal("Error", "Nilai PAD Melebihi Sisa Anggaran Rencana Perubahan PAD...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			 if ( (status_ang=='Penyusunan')&&(cnil_pad_input > cpad_sisa_semp)){
				swal("Error", "Nilai PAD Melebihi Sisa Anggaran Rencana Penyempurnaan PAD...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			if ( (status_ang=='Penyusunan')&&(cnil_pad_input > cpad_sisa_murni)){
				swal("Error", "Nilai PAD Melebihi Sisa Anggaran Penyusunan PAD...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			
			//DAK
			if ((status_ang=='Perubahan')&&(cnil_dak_input > cdak_sisa_ubah)){
				swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Perubahan DAK...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			
			if ((status_ang=='Penyempurnaan')&&(cnil_dak_input > cdak_sisa_ubah)){
				swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Rencana Perubahan DAK...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			if ((status_ang=='Penyempurnaan')&&(cnil_dak_input > cdak_sisa_semp)){
				swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Penyempurnaan DAK...!!! , Cek Lagi...!!!", "error");
				exit();
            }
			
			if ( (status_ang=='Penyusunan')&&(cnil_dak_input > cdak_sisa_ubah)){
				swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Rencana Perubahan DAK...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			 if ( (status_ang=='Penyusunan')&&(cnil_dak_input > cdak_sisa_semp)){
				swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Rencana Penyempurnaan DAK...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			if ( (status_ang=='Penyusunan')&&(cnil_dak_input > cdak_sisa_murni)){
				swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Penyusunan DAK...!!!, Cek Lagi...!!!", "error");
				exit();
            }

			//DAK NF
			if ((status_ang=='Perubahan')&&(cnil_daknf_input > cdaknf_sisa_ubah)){
				swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Perubahan daknf...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			
			if ((status_ang=='Penyempurnaan')&&(cnil_daknf_input > cdaknf_sisa_ubah)){
				swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Rencana Perubahan daknf...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			if ((status_ang=='Penyempurnaan')&&(cnil_daknf_input > cdaknf_sisa_semp)){
				swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Penyempurnaan daknf...!!! , Cek Lagi...!!!", "error");
				exit();
            }
			
			if ( (status_ang=='Penyusunan')&&(cnil_daknf_input > cdaknf_sisa_ubah)){
				swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Rencana Perubahan daknf...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			 if ( (status_ang=='Penyusunan')&&(cnil_daknf_input > cdaknf_sisa_semp)){
				swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Rencana Penyempurnaan daknf...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			if ( (status_ang=='Penyusunan')&&(cnil_daknf_input > cdaknf_sisa_murni)){
				swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Penyusunan daknf...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			
			//DAU
			if ((status_ang=='Perubahan')&&(cnil_dau_input > cdau_sisa_ubah)){
				swal("Error", "Nilai dau Melebihi Sisa Anggaran Perubahan dau...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			
			if ((status_ang=='Penyempurnaan')&&(cnil_dau_input > cdau_sisa_ubah)){
				swal("Error", "Nilai DAU Melebihi Sisa Anggaran Rencana Perubahan DAU...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			if ((status_ang=='Penyempurnaan')&&(cnil_dau_input > cdau_sisa_semp)){
				swal("Error", "Nilai DAU Melebihi Sisa Anggaran Penyempurnaan DAU...!!! , Cek Lagi...!!!", "error");
				exit();
            }
			
			if ( (status_ang=='Penyusunan')&&(cnil_dau_input > cdau_sisa_ubah)){
				swal("Error", "Nilai DAU Melebihi Sisa Anggaran Rencana Perubahan DAU...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			 if ( (status_ang=='Penyusunan')&&(cnil_dau_input > cdau_sisa_semp)){
				swal("Error", "Nilai DAU Melebihi Sisa Anggaran Rencana Penyempurnaan DAU...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			if ( (status_ang=='Penyusunan')&&(cnil_dau_input > cdau_sisa_murni)){
				swal("Error", "Nilai DAU Melebihi Sisa Anggaran Penyusunan DAU...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			
			//DBHP
			if ((status_ang=='Perubahan')&&(cnil_dbhp_input > cdbhp_sisa_ubah)){
				swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Perubahan DBHP...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			
			if ((status_ang=='Penyempurnaan')&&(cnil_dbhp_input > cdbhp_sisa_ubah)){
				swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Rencana Perubahan DBHP...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			if ((status_ang=='Penyempurnaan')&&(cnil_dbhp_input > cdbhp_sisa_semp)){
				swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Penyempurnaan DBHP...!!! , Cek Lagi...!!!", "error");
				exit();
            }
			
			if ( (status_ang=='Penyusunan')&&(cnil_dbhp_input > cdbhp_sisa_ubah)){
				swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Rencana Perubahan DBHP...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			 if ( (status_ang=='Penyusunan')&&(cnil_dbhp_input > cdbhp_sisa_semp)){
				swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Rencana Penyempurnaan DBHP...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			if ( (status_ang=='Penyusunan')&&(cnil_dbhp_input > cdbhp_sisa_murni)){
				swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Penyusunan DBHP...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			
			//DID
			if ((status_ang=='Perubahan')&&(cnil_did_input > cdid_sisa_ubah)){
				swal("Error", "Nilai DID Melebihi Sisa Anggaran Perubahan did...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			
			if ((status_ang=='Penyempurnaan')&&(cnil_did_input > cdid_sisa_ubah)){
				swal("Error", "Nilai DID Melebihi Sisa Anggaran Rencana Perubahan did...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			if ((status_ang=='Penyempurnaan')&&(cnil_did_input > cdid_sisa_semp)){
				swal("Error", "Nilai DID Melebihi Sisa Anggaran Penyempurnaan did...!!! , Cek Lagi...!!!", "error");
				exit();
            }
			
			if ( (status_ang=='Penyusunan')&&(cnil_did_input > cdid_sisa_ubah)){
				swal("Error", "Nilai DID Melebihi Sisa Anggaran Rencana Perubahan did...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			 if ( (status_ang=='Penyusunan')&&(cnil_did_input > cdid_sisa_semp)){
				swal("Error", "Nilai DID Melebihi Sisa Anggaran Rencana Penyempurnaan did...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			if ( (status_ang=='Penyusunan')&&(cnil_did_input > cdid_sisa_murni)){
				swal("Error", "Nilai DID Melebihi Sisa Anggaran Penyusunan did...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			
			
			//total
			if ((status_ang=='Perubahan')&&(cnil_input > cnil_sisa_ubah)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!", "error");
                exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_ubah)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_semp)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_ubah)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
				exit();
            }
			if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_semp)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                exit();
            }
			if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa)){
				swal("Error", "Nilai Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!", "error");
                exit();
            }

            var vnm_rek_reke = document.getElementById('nm_rek_reke').value;
            
            if ( edit == 'F' ){
                pidx = pidx + 1 ;
                }
                
            if ( edit == 'T' ){
                pidx = jgrid ;
                pidx = pidx + 1 ;
                }

            $('#dgsppls').edatagrid('appendRow',{kdkegiatan:vrek_kegi,kdrek5:vrek_reke,nmrek5:vnm_rek_reke,nilai1:cnilai,pad:cnil_pad,dak:cnil_dak,daknf:cnil_daknf,dau:cnil_dau,dbhp:cnil_dbhp,did:cnil_did,idx:pidx});
            $("#dialog-modal-rek").dialog('close'); 
            
            jumtotal = jumtotal + angka(cnil) ;
            $("#rektotal_ls").attr('value',number_format(jumtotal,2,'.',','));
            $("#rektotal1_ls").attr('value',number_format(jumtotal,2,'.',','));
            $("#dgsppls").datagrid("unselectAll");
            
       }
       
       
       function hapus_detail(){
        
        var a          = document.getElementById('no_spp').value;
        var rows       = $('#dgsppls').edatagrid('getSelected');
        var ctotalspp  = document.getElementById('rektotal_ls').value ;
        
        bkdrek      = rows.kdrek5;
        bkdkegiatan = rows.kdkegiatan;
        bnilai      = rows.nilai1;
        bbukti      = '';
        alert(ctotalspp);
        ctotalspp   = angka(ctotalspp) - angka(bnilai) ;
        
        var idx = $('#dgsppls').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Rekening : '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#dgsppls').datagrid('deleteRow',idx);     
            $('#dgsppls').datagrid('unselectAll');
            $("#rektotal_ls").attr("Value",number_format(ctotalspp,2,'.',','));
            $("#rektotal1_ls").attr("Value",number_format(ctotalspp,2,'.',','));
              
             var urll = '<?php  echo base_url(); ?>index.php/tukd/dsimpan_spp';
             $(document).ready(function(){
             $.post(urll,({cnospp:a,ckdgiat:bkdkegiatan,ckdrek:bkdrek,cnobukti:bbukti}),function(data){
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
        }     
        }

	function get_spp(){
		var jns ="";var $jns2 = "";
        $("#no_spp").attr("value",'');
		var kode   = document.getElementById('dn').value;
		jns = "TU";
		jns2 = 'BL';
		
    	$.ajax({
    		url:'<?php echo base_url(); ?>index.php/tukd/config_spp/'+jns2,
    		type: "POST",
    		dataType:"json",                         
    		success:function(data){
    			no_spp = data.nomor;
				var inisial = no_spp + "/SPP/"+jns+"/"+kode+"/"+tahun_anggaran;
				$("#no_spp").attr('disabled',true);
                $("#no_spp").attr("value",inisial);
                $("#dd_spp").attr("value",no_spp);
    			}                                     
    	});
    }		

     function form_batal(){
        $("#no_spp_batal").attr('disabled',true);		
		$("#no_spp_batal").attr("value",document.getElementById("no_spp").value);		
        
        $("#dialog-batal").dialog('open');
    } 
 
    function keluar_batal(){
        $("#dialog-batal").dialog('close');
    }   
	
    function batal(){
        var no_spp = document.getElementById("no_spp_batal").value;
        var ket = document.getElementById("ket_batal").value;
     	if (no_spp !=''){
			var del=confirm('Anda yakin akan Membatalkan SPP: '+no_spp+'  ?');
			if  (del==true){
				/*ini untuk delete
				$(document).ready(function(){
                $.post(urll,({no:sp2d,spm:no_spm}),function(data){
                status = data;
                spm_combo(); */
				if (ket==''){ 
				    alert('Keterangan harus diisi');
					exit();
				}
                
                
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/batal_spp',
                data     : ({nospp:no_spp,ket:ket}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        if ( status=='1' ){
                            keluar_batal();
                            alert('SPP Berhasil Dibatalkan');
                        }else{
							keluar_batal();  
                            alert('SPP Gagal Dibatalkan');       
                         }
                    }
            });
            });			
			}
		} 
	}
 		
    </script>
    
    <STYLE TYPE="text/css"> 

         input.right{ 
         text-align:right; 
         } 

         .satu{
                width: 150px;
         }

         .dua{
                width: 130px;
         }
         
    .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }
    
    .tooltip .tooltiptext {
        visibility: hidden;
        width: 160px;
        background-color: black;
        color: #fff;
        text-align: left;
        border-radius: 6px;
        padding: 5px 0;
        
        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        top: -5px;
        left: 105%;
    }
    
    .tooltip:hover .tooltiptext {
        visibility: visible;
    }  
    		

		 
	</STYLE> 

</head>
<body>

<div id="content">
<div id="accordion">
<h3><a href="#" id="section1" onclick="javascript:$('#spp').edatagrid('reload')">List SPP</a></h3>
    <div style="height:350px;">

    <p align="right">         
        <a id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>
   <!---     <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>    --->          
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="spp" title="List SPP" style="width:870px;height:650px;" >  
        </table>
    </p> 
    </div>

<h3><a href="#" id="section2">Input SPP</a></h3>
   
   <div  style="height:350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
    <p id="p2" style="font-size: x-large;color: red;"></p> 
   <fieldset style="width:850px;height:950px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">            
   <table border='1' style="font-size:11px">
  
  <tr>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/></td>
				<td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;">&nbsp;&nbsp;</td>
				<td style="border-bottom: double 1px red;border-top: double 1px red;" colspan = "2"><i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr> 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='12%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
 </tr>  

 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >   
   <td width="12%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >No SPP</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="text" name="no_spp" id="no_spp"  style="width:200px" onkeyup="this.value=this.value.toUpperCase()"/>
   <a id="rnospp" class="tooltip easyui-linkbutton" iconCls="icon-reload" plain="true"  onclick="javascript:get_spp();"><span class="tooltiptext">Refresh No SPP</span></a>* No Otomatis<input type="hidden" name="no_spp_hide" id="no_spp_hide" style="width:140px"/></td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tanggal</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input id="dd" name="dd" type="text" /><input id="dd_spp" name="dd_spp" type="hidden" /></td>   
 </tr>
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='12%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">SKPD</td>
   <td width="53%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >     
      &nbsp;<input id="dn" name="dn"  readonly="true" style="width:130px; border: 0;"/> </td> 
   <td width='12%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Bulan</td>
   <td width="31%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select  name="kebutuhan_bulan" id="kebutuhan_bulan" >
     <option value="">...Pilih Kebutuhan Bulan... </option>
     <option value="1">1  | Januari</option>
     <option value="2">2  | Februari</option>
     <option value="3">3  | Maret</option>
     <option value="4">4  | April</option>
     <option value="5">5  | Mei</option>
     <option value="6">6  | Juni</option>
     <option value="7">7  | Juli</option>
     <option value="8">8  | Agustus</option>
     <option value="9">9  | September</option>
     <option value="10">10 | Oktober</option>
     <option value="11">11 | November</option>
     <option value="12">12 | Desember</option>
   </select></td> 
 </tr>
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='12%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><textarea name="nmskpd" id="nmskpd" cols="40" rows="3" style="border:0" readonly="true" ></textarea></td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Keperluan</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><textarea name="ketentuan" id="ketentuan" cols="40" rows="3" ></textarea></td>
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Beban</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><select name="jns_beban" id="jns_beban" style="height: 27px; width:190px;">
     <option value="3">TU</option>     
   </td>
   <td colspan ="2" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
 </tr>

 
 <tr>
   <td width='12%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">No SPD</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input id="sp" name="sp" style="width:190px" />&nbsp;&nbsp; <input id="tglspd" name="tglspad" type="text" disabled /></td></td>
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Kegiatan</td>
   <td colspan="3" style="border-top:hidden;border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" >&nbsp;<input id="kg" name="kg" style="width:190px" />
   &nbsp;<input type ="hidden" id="kp" name="kp" style="width:160px" />
    &nbsp;&nbsp;<input id="nm_kg" name="nm_kg" style="width:500px;border: 0;"/>
      <input type ="hidden" id="nm_kp" name="nm_kp" /></td>
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
  <td width="12%" style="border-right:hidden;border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >BANK</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" name="bank1" id="bank1" />
    &nbsp;&nbsp;<input type ="input" readonly="true" style="border:hidden" id="nama_bank" name="nama_bank" style="width:300" /></td>
   <td width='12%'  style="border-left:hidden;border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" name="rekening" id="rekening"  value="" style="width:190px"/></td>
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
       <td width='12%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
	</tr> 
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
       <td width='12%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Total SPD</td>
       <td width='53%' colspan="3"><input class="right" id="total_spd1" name="total_spd1" type="text" style="border:0;width:150px"  align="right"  readonly="true"/></td>
	</tr> 

	<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
       <td width='12%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">SPD Terpakai</td>
       <td width='53%' colspan="3"><input class="right" id="trans_spd" name="trans_spd" type="text" style="border:0;width:150px"  align="right"  readonly="true"/></td>
	</tr> 
	
     <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
       <td width='12%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Sisa SPD</td>
       <td width='53%' colspan="3"><input class="right" id="sisa_spd" name="sisa_spd" type="text" style="border:0;width:150px"  align="right"  readonly="true"/></td>
	</tr>  
	
	<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
       <td width='12%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='53%' align="right" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
	</tr> 

       <tr style="border-spacing: 3px;padding:3px 3px 3px 3px;">
                <td colspan="4" align='right' style="border-bottom-color:black;border-spacing: 3px;padding:3px 3px 3px 3px;" >
                <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>-->
                <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true"  onclick="javascript:hsimpan();">Simpan</a>
                <!--<a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section1();">Hapus</a>-->
                <a id="batal" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:form_batal();">Batal SPP</a>                
                <!--<a id="det" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:detail();">Detail</a>-->
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>
                <a id="cetak" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a></td>              
       </tr>
</table>

    
        <!------------------------------------------------------------------------------------------------------------------>
        
        <table id="dgsppls" title="Input Detail SPP" style="width:850%;height:300%;" >  
        </table>
        
        <div id="toolbar" align="left">
    		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Rekening</a>
        </div>
  
        <table border='0' style="width:100%;height:5%;"> 
             <td width='39%'></td>
             <td width='15%'><input class="right" type="hidden" name="rektotal1_ls" id="rektotal1_ls"  style="width:140px" align="right" readonly="true" ></td>
             <td width='9%'><B>Total</B></td>
             <td width='32%'><input class="right" type="text" name="rektotal_ls" id="rektotal_ls"  style="width:140px" align="right" readonly="true" ></td>
        </table>
        </fieldset>
        <!------------------------------------------------------------------------------------------------------------------>
   </div>

</div>
</div> 
			<div id="loading" title="Loading...">
			<table align="center">
			<tr align="center"><td><img id="search1" height="50px" width="50px" src="<?php echo base_url();?>/image/loadingBig.gif"  /></td></tr>
			<tr><td>Loading...</td></tr>
			</table>
			</div>


<div id="dialog-modal-rek" title="Input Rekening">
    <p class="validateTips"></p>  
    <fieldset>
   <table align="center" style="width:100%;" border="0">
       
            <tr>
                <td width='15%'>SKPD</td>
                <td width='3%'>:</td>
                <td colspan="8" width='82%'><input id="rek_skpd" name="rek_skpd" style="width: 157px;" /><input type="text" id="rek_nmskpd" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>

            <tr>
                <td>KEGIATAN</td>
                <td>:</td>
                <td colspan="8"><input id="rek_kegi" name="rek_kegi" style="width: 157px;" /><input type="text" id="nm_rek_kegi" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>

            <tr>
                <td>REKENING</td>
                <td>:</td>
                <td colspan="8"><input id="rek_reke" name="rek_reke" style="width: 157px;" /><input type="text" id="nm_rek_reke" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
			
			<tr>
                <td>STATUS</td>
                <td>:</td>
                <td colspan="8"><input type="text" id="status_ang" style="border:0; text-align: left;" readonly="true"/></td> 
			</tr>

            <tr>
                <td>TOTAL SPD</td>
                <td>:</td>
                <td><input type="text" id="total_spd" class="satu" style="background-color:#99FF99;text-align: right;" readonly="true" /></td> 
				<td >SPD TERPAKAI</td>
                <td>:</td>
                <td><input type="text" id="nilai_spd_lalu" class="dua" style="background-color:#99FF99; text-align: right; " readonly="true" /></td> 
				 <td>SISA</td>
                <td>:</td>
                <td><input type="text" id="nilai_sisa_spd" class="dua" style="background-color:#99FF99;text-align: right; " readonly="true" /></td>
                <td></td>
            </tr>
            
            <tr>
                <td>ANGGARAN</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_ang" class="satu" style="background-color:#e374ff;text-align: right;" readonly="true" /></td> 
                <td>JUMLAH SPP LALU</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_spp" class="dua"  style="background-color:#e374ff;text-align: right; " readonly="true" /></td>
				<td>SISA</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_sisa" class="dua" style="background-color:#e374ff;text-align: right; " readonly="true" /></td>				
                <td></td>
            </tr>
			
			<tr>
                <td>PENYEMPURNAAN</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_ang_semp" class="satu" style="background-color:#51ffd4;text-align: right;" readonly="true" /></td> 
                <td>JUMLAH SPP LALU</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_spp_semp" class="dua"  style="background-color:#51ffd4;text-align: right; " readonly="true" /></td>
				<td>SISA</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_sisa_semp" class="dua" style="background-color:#51ffd4;text-align: right; " readonly="true" /></td>				
                <td></td>
            </tr>
			<tr>
                <td>PERUBAHAN</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_ang_ubah" class="satu" style="background-color:#ffcb94;text-align: right;" readonly="true" /></td> 
                <td>JUMLAH SPP LALU</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_spp_ubah" class="dua"  style="background-color:#ffcb94;text-align: right; " readonly="true" /></td>
				<td>SISA</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_sisa_ubah" class="dua" style="background-color:#ffcb94;text-align: right; " readonly="true" /></td>				
                <td></td>
            </tr>
            
                <td></td>
                <td></td>
				<td align="center" ><b>PAD</b></td>
                <td colspan="2" align="center"><b>DAK FISIK</b></td> 
                <td align="center"><b>DAK NON FISIK</b></td> 
                <td colspan="2" align="center"><b>DAU</b></td>
                <td align="center"><b>DBHP</b></td>
                <td align="center"><b>DID</b></td>
            </tr>
			<tr style="background-color:#b2ffaf">
                <td>PENYUSUNAN</td>
                <td>:</td>
				<td><input type="text" id="nil_pad" class="satu" style="text-align: right;" readonly="true" /></td>
                <td colspan="2"><input type="text" class="dua" id="nil_dak" style="text-align: right; " readonly="true" /></td> 
                <td ><input type="text" id="nil_daknf" class="dua" style="text-align: right; " readonly="true" /></td>
                <td colspan="2"><input type="text" class="dua" id="nil_dau" style="text-align: right; " readonly="true" /></td>
                <td ><input type="text" class="dua" id="nil_dbhp" style="text-align: right; " readonly="true" /></td>
                <td ><input type="text" class="dua" id="nil_did" style="text-align: right; " readonly="true" /></td>
            </tr>
            
           
			<tr style="background-color:#b2ffaf">
                <td>PENYEMPURNAAN</td>
                <td>:</td>
				<td><input type="text" id="nil_pad_semp" class="satu" style="text-align: right; " readonly="true" /></td>
                <td colspan="2"><input type="text" id="nil_dak_semp" class="dua" style="text-align: right; " readonly="true" /></td>
                <td ><input type="text" id="nil_daknf_semp" class="dua" style="text-align: right; " readonly="true" /></td> 
                <td colspan="2"><input type="text" id="nil_dau_semp" class="dua" style="text-align: right; " readonly="true" /></td>
                <td><input type="text" id="nil_dbhp_semp" class="dua" style="text-align: right; " readonly="true" /></td>
                <td ><input type="text" id="nil_did_semp" class="dua" style="text-align: right; " readonly="true" /></td>
            </tr>
			<tr style="background-color:#b2ffaf">
                <td>PERUBAHAN</td>
                <td>:</td>
				<td><input type="text" id="nil_pad_ubah" class="satu" style="text-align: right; " readonly="true" /></td>
                <td colspan="2"><input type="text" id="nil_dak_ubah" class="dua" style="text-align: right; " readonly="true" /></td> 
                <td><input type="text" id="nil_daknf_ubah" class="dua" style="text-align: right; " readonly="true" /></td>
                <td colspan="2"><input type="text" id="nil_dau_ubah" class="dua" style="text-align: right; " readonly="true" /></td>
                <td><input type="text" id="nil_dbhp_ubah" class="dua" style="text-align: right; " readonly="true" /></td>
                <td><input type="text" id="nil_did_ubah" class="dua" style="text-align: right; " readonly="true" /></td>
             </tr>
			<tr style="background-color:#ffe928">
                <td>TRANSAKSI</td>
                <td>:</td>
				<td><input type="text" id="nil_pad_trans" class="satu" style="text-align: right; " readonly="true" /></td>
                <td colspan="2"><input type="text" id="nil_dak_trans" class="dua" style="text-align: right; " readonly="true" /></td>
                <td><input type="text" id="nil_daknf_trans" class="dua" style="text-align: right; " readonly="true" /></td>  
                <td colspan="2"><input type="text" id="nil_dau_trans" class="dua" style="text-align: right; " readonly="true" /></td>
                <td><input type="text" id="nil_dbhp_trans" class="dua" style="text-align: right; " readonly="true" /></td>
                <td><input type="text" id="nil_did_trans" class="dua" style="text-align: right; " readonly="true" /></td>
            </tr>
			<tr style="background-color:#ff4759">
                <td>SISA MURNI</td>
                <td>:</td>
				<td><input type="text" id="pad_sisa" class="satu" style="text-align: right; " readonly="true" /></td>
                <td colspan="2"><input type="text" id="dak_sisa" class="dua" style="text-align: right; " readonly="true" /></td> 
                <td><input type="text" id="daknf_sisa" class="dua" style="text-align: right; " readonly="true" /></td> 
                <td colspan="2"><input type="text" id="dau_sisa" class="dua" style="text-align: right; " readonly="true" /></td>
                <td><input type="text" id="dbhp_sisa" class="dua" style="text-align: right; " readonly="true" /></td>
                <td colspan="2"><input type="text" id="did_sisa" class="dua" style="text-align: right; " readonly="true" /></td>
            </tr>
			<tr style="background-color:#ff4759">
                <td>SISA SEMPURNA</td>
                <td>:</td>
				<td><input type="text" id="pad_sisa_semp" class="satu" style="text-align: right; " readonly="true" /></td>
                <td colspan="2"><input type="text" id="dak_sisa_semp" class="dua" style=" text-align: right; " readonly="true" /></td>
                <td><input type="text" id="daknf_sisa_semp" class="dua" style=" text-align: right; " readonly="true" /></td> 
                <td colspan="2"><input type="text" id="dau_sisa_semp" class="dua" style="text-align: right; " readonly="true" /></td>
                <td><input type="text" id="dbhp_sisa_semp" class="dua" class="dua" style="text-align: right; " readonly="true" /></td>
                <td colspan="2"><input type="text" id="did_sisa_semp" class="dua" style="text-align: right; " readonly="true" /></td>
            </tr>
			<tr style="background-color:#ff4759">
                <td>SISA UBAH</td>
                <td>:</td>
				<td><input type="text" id="pad_sisa_ubah" class="satu" style="text-align: right; " readonly="true" /></td>
                <td colspan="2"><input type="text" id="dak_sisa_ubah" class="dua" style="text-align: right; " readonly="true" /></td>
                <td><input type="text" id="daknf_sisa_ubah" class="dua" style="text-align: right; " readonly="true" /></td> 
                <td colspan="2"><input type="text" id="dau_sisa_ubah" class="dua" style="text-align: right; " readonly="true" /></td>
                <td><input type="text" id="dbhp_sisa_ubah" class="dua" style="text-align: right; " readonly="true" /></td>
                <td colspan="2"><input type="text" id="did_sisa_ubah" class="dua" style="text-align: right; " readonly="true" /></td>
            </tr>
			<tr>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td> 
            </tr>
			<tr>
                <td>NILAI</td>
                <td>:</td>
				<td><input type="text" id="rek_pad" onkeyup="javascript:hitung();" class="satu" style="text-align: right; " onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td colspan="2"><input type="text" id="rek_dak" onkeyup="javascript:hitung();" class="dua" style="text-align: right; " onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td><input type="text" id="rek_daknf" onkeyup="javascript:hitung();"  class="dua" style="text-align: right; " onkeypress="return(currencyFormat(this,',','.',event))"/></td>  
                <td colspan="2"><input type="text" id="rek_dau" onkeyup="javascript:hitung();"  class="dua" style="text-align: right; " onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td><input type="text" id="rek_dbhp" onkeyup="javascript:hitung();"  class="dua" style="text-align: right; " onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td colspan="2"><input type="text" id="rek_did" onkeyup="javascript:hitung();"  class="dua" style="text-align: right; " onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            </tr>
			<tr>
                <td>TOTAL</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai" class="satu" style="text-align: right; "  readonly="true"/></td> 
				<td colspan="7"> <i><b>--> Nilai ini akan terjumlah Otomatis </b></i></td>
			<tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td> 
            </tr>

            <tr>
                <td colspan="10" align="center">
                <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_rek();">Keluar</a>  
                </td>
            </tr>
			
            
    </table>  
    </fieldset>
	
</div>

<div id="dialog-modal" title="CETAK SPP">
    <p class="validateTips">SILAHKAN PILIH SPP</p>  
    <fieldset>
    <table>
        <tr>            
            <td width="110px">NO SPP:</td>
            <td><input id="cspp" name="cspp" style="width: 170px;" disabled />  &nbsp; &nbsp; &nbsp; <input type="checkbox" id="tanpa_tanggal"> Tanpa Tanggal</td>
        </tr>
       
		<tr>
            <td width="110px">Bend. Pengeluaran:</td>
            <td><input id="ttd1" name="ttd1" style="width: 170px;" />  &nbsp; &nbsp; &nbsp;  <input id="nmttd1" name="nmttd1" style="width: 170px;border:0" /></td>
        </tr>
		<tr>
            <td width="110px">PPTK:</td>
            <td><input id="ttd2" name="ttd2" style="width: 170px;" />  &nbsp; &nbsp; &nbsp;  <input id="nmttd2" name="nmttd2" style="width: 170px;border:0" /></td>
        </tr>
		<tr>
            <td width="110px">PA:</td>
            <td><input id="ttd3" name="ttd3" style="width: 170px;" />  &nbsp; &nbsp; &nbsp;  <input id="nmttd3" name="nmttd3" style="width: 170px;border:0" /></td>
        </tr>
		<tr>
            <td width="110px">PPKD:</td>
            <td><input id="ttd4" name="ttd4" style="width: 170px;" />  &nbsp; &nbsp; &nbsp;  <input id="nmttd4" name="nmttd4" style="width: 170px;border:0" /></td>
        </tr>
		<tr>
            <td width="110px">SPASI:</td>
            <td><input type="number" id="spasi" style="width: 100px;" value="1"/></td>
        </tr>
    </table>  
    </fieldset>
    <div>
    </div>    
	<a id="cetak1a" href="<?php echo site_url(); ?>/tukd/cetakspp1/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Pengantar</a>
	<a id="cetak2a" href="<?php echo site_url(); ?>/tukd/cetakspp2/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Ringkasan</a>
	<a id="cetak3a" href="<?php echo site_url(); ?>/tukd/cetakspp3/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Rincian</a>
	<a id="cetak4a" href="<?php echo site_url(); ?>/tukd/cetakspp4/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp_2(this.href);return false;">Pernyataan</a>
	<a id="cetak5a" href="<?php echo site_url(); ?>/tukd/cetakspp5/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Permintaan</a>
	<a id="cetak6a" href="<?php echo site_url(); ?>/tukd/cetakspp6/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp_2(this.href);return false;">SPTB/Kontrak</a>
	<br/>
	<a id="cetak1b" href="<?php echo site_url(); ?>/tukd/cetakspp1/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Pengantar</a>
	<a id="cetak2b" href="<?php echo site_url(); ?>/tukd/cetakspp2/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Ringkasan</a>
	<a id="cetak3b" href="<?php echo site_url(); ?>/tukd/cetakspp3/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Rincian</a>
	<a id="cetak4b" href="<?php echo site_url(); ?>/tukd/cetakspp4/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp_2(this.href);return false;">Pernyataan</a>
	<a id="cetak5b" href="<?php echo site_url(); ?>/tukd/cetakspp5/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Permintaan</a>
	<a id="cetak6b" href="<?php echo site_url(); ?>/tukd/cetakspp6/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp_2(this.href);return false;">SPTB/Kontrak</a>
	&nbsp;&nbsp;&nbsp;<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>  
</div>

<div id="dialog-batal" title="KETERANGAN PEMBATALAN SPP">
    <p class="validateTips">KETERANGAN PEMBATALAN SPP</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">NO SPP:</td>
            <td><input id="no_spp_batal" name="no_spp_batal" style="width: 170px;" readonly="true"/></td>
        </tr>
        <tr>
            <td width="110px">KETERANGAN PEMBATALAN SPM:</td>
            <td><textarea name="ket_batal" id="ket_batal" cols="70" rows="2" ></textarea></td>
        </tr>
    </table>  
    </fieldset>
    <a id="del1" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:batal();javascript:section1();">BATAL</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_batal();">Keluar</a>  
</div>
 	
</body>
</html>