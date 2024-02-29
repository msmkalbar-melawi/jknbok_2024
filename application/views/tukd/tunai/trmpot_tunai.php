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
    
    var kode  = '';
    var giat  = '';
    var jenis = '';
    var nomor = '';
	var nokas = '';
    var cid   = 0;
    var tahun_anggaran="<?php echo $this->session->userdata('pcThang'); ?>";
                      
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 650,
                width: 1000,
                modal: true,
                autoOpen:false                
            });              
            get_skpd();
          
			
        });    
     
     $(function(){ 
     $('#dg').edatagrid({
		rowStyler:function(index,row){
        if (row.status==1){
		//return {class:'r1', style:{'color:#fff'}};
            //return 'background-color:#6293BB;color:#fff;';
		   return 'color:green;font-weight:bold;';
			//font-weight:bold;
        }
    },
	 
	 
		url: '<?php echo base_url(); ?>/index.php/tunai_trmpot/load_pot_in_bnk',
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
    		width:40},
    	    {field:'no_bukti',
    		title:'Nomor',
    		width:20},
            {field:'tgl_bukti',
    		title:'Tanggal Bukti',
    		width:25},            
            {field:'ket',
    		title:'Keterangan',
    		width:120,
            align:"left"},
			{field:'status',
    		title:'ST',
    		width:8,
            align:"left"},
            {field:'status_upload',
    		title:'STU',
    		width:8,
            align:"left"},
			{field:'detail',title:'Detail',width:20,align:"center",
                        formatter:function(value,rec){ 
                        return '<button class="button" ondblclick="javascript:section3();"> Detail</button>';
                        }
                        },
        ]],
        onSelect:function(rowIndex,rowData){
          bukti = rowData.no_bukti;
          voucher = rowData.no_kas;
          bill = rowData.ebilling;
          tgl  = rowData.tgl_bukti;
          st   = rowData.status;
          ket = rowData.ket; 
		  npwp = rowData.npwp;
		  kd_giat = rowData.kd_giat;
		  nm_giat = rowData.nm_giat;
		  no_sp2d = rowData.no_sp2d;
		  kd_rek = rowData.kd_rek;
		  nm_rek = rowData.nm_rek;
		  rekanan = rowData.rekanan;
		  dir = rowData.dir;
		  alamat = rowData.alamat;
		  jns_spp = rowData.jns_beban;	
          
          getpot(bukti,voucher,tgl,ket,npwp,kd_giat,no_sp2d,nm_giat,kd_rek,nm_rek,rekanan,dir,alamat,jns_spp,st,bill);  
		  detail_potongan();
		  load_sum_pot();
		  

		},
        onDblClickRow:function(rowIndex,rowData){
bukti = rowData.no_bukti;
          voucher = rowData.no_kas;
          bill = rowData.ebilling;
          tgl  = rowData.tgl_bukti;
          st   = rowData.status;
          ket = rowData.ket; 
      npwp = rowData.npwp;
      kd_giat = rowData.kd_giat;
      nm_giat = rowData.nm_giat;
      no_sp2d = rowData.no_sp2d;
      kd_rek = rowData.kd_rek;
      nm_rek = rowData.nm_rek;
      rekanan = rowData.rekanan;
      dir = rowData.dir;
      alamat = rowData.alamat;
      jns_spp = rowData.jns_beban;  
          
          getpot(bukti,voucher,tgl,ket,npwp,kd_giat,no_sp2d,nm_giat,kd_rek,nm_rek,rekanan,dir,alamat,jns_spp,st,bill);  
      detail_potongan();
      load_sum_pot();
           section3();   
       }
    });
    
	$('#dgpajak').edatagrid({
                     //idField        : 'id',
                     toolbar        : "#toolbar",              
                     rownumbers     : "true", 
                     fitColumns     : "true",
                     autoRowHeight  : "true",
                     singleSelect   : false,
                     columns:[[
                        {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                        {field:'kd_rek_trans',title:'Rek. Trans',width:80,align:'left'},			
                        {field:'kd_rek5',title:'Rekening',width:80,align:'left'},			
    					{field:'nm_rek5',title:'Nama Rekening',width:250,align:"left"},
                        {field:'ebill',title:'E-Billing',width:120,align:'left'},
    					{field:'nilai',title:'Nilai',width:160,align:"right"},
                        {field:'hapus',title:'Hapus',width:50,align:"center",
                        formatter:function(value,rec){ 
                        return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_pot();" />';
                        }
                        }
        			]]		
        			});
	
	   
       	$('#no_voucher_tr').combogrid({  
                   panelWidth : 800,  
                   idField    : 'no_bukti',  
                   textField  : 'no_bukti',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/tunai_trmpot/load_trans_trmpot_bnk',  
                   columns:[[  
                       {field:'no_bukti',title:'No.',width:60,align:'center'},                                              
                       {field:'tgl_bukti',title:'Tanggal',width:75},
                       {field:'no_sp2d',title:'NO SP2D',width:110},
                       {field:'nm_kegiatan',title:'Kegiatan',width:200},
                       {field:'nm_rek5',title:'Rekening',width:200},
                       {field:'total',title:'Nilai',width:130,align:'right'},
                       					   					   
                   ]],  
                   onSelect:function(rowIndex,rowData){
                        csp2d = rowData.no_sp2d;
                        cgiat = rowData.kd_kegiatan;
                        cnmgiat = rowData.nm_kegiatan;
                        crek = rowData.kd_rek5;
                        cnmrek = rowData.nm_rek5;
                        cjns_spp = rowData.jns_spp;
                        										
                        $("#no_sp2d").combogrid("setValue",csp2d);                                                                    		
						$("#kd_giat").combogrid("setValue",cgiat);
						$("#nm_giat").attr("value",cnmgiat);
                        $("#kd_rek").combogrid("setValue",crek);
                        $("#nm_rek").attr("value",cnmrek);		
                        $("#beban").attr("value",cjns_spp);				  
                   }  
                   });	
         
        $('#npwp').combogrid({  
                   panelWidth : 215,  
                   idField    : 'npwp',  
                   textField  : 'npwp',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/tunai/config_npwp',  
                   columns:[[  
                       {field:'npwp',title:'NPWP',width:200},                       					   
					   
                   ]],  
                   onSelect:function(rowIndex,rowData){
                        cnpwp = rowData.npwp;                        			  
                   }  
                   });	           
       
		$('#no_sp2d').combogrid({  
                   panelWidth : 450,  
                   idField    : 'no_sp2d',  
                   textField  : 'no_sp2d',  
                   mode       : 'remote',
                   columns:[[  
                       {field:'no_sp2d',title:'No SP2D',width:250}					   
					   
                   ]],  
                   onSelect:function(rowIndex,rowData){
						$("#nm_giat").attr("value",'');
						$("#nm_rek").attr("value",'');
						$("#kd_giat").combogrid("setValue",'');
						$("#kd_rek").combogrid("setValue",'');
					   cskpd = document.getElementById('skpd').value;
					   nosp2d = rowData.no_sp2d;
					   sp2d = nosp2d.split("/").join("123456789");
                       $("#beban").attr("value",rowData.jns_spp); 

                   }  
                   });	

				   
		$('#kd_giat').combogrid({  
                   panelWidth : 450,  
                   idField    : 'kd_giat',  
                   textField  : 'kd_giat',  
                   mode       : 'remote',
                   columns:[[  
                       {field:'kd_giat',title:'Kode kegiatan',width:150},  
                       {field:'nm_giat',title:'Nama kegiatan',width:300}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                       $("#nm_giat").attr("value",rowData.nm_giat); 
					   kd_giat_pot = rowData.kd_giat;
                   }  
                   });	
		
		$('#kd_rek').combogrid({  
                   panelWidth : 450,  
                   idField    : 'kd_rek',  
                   textField  : 'kd_rek',  
                   mode       : 'remote',
                   columns:[[  
                       {field:'kd_rek',title:'Kode Rekening',width:150},  
                       {field:'nm_rek',title:'Nama Rekening',width:300}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
				    	nosp2d    = $('#no_sp2d').combogrid('getValue');
						sp2d = nosp2d.split("/").join("123456789");
                        $("#nm_rek").attr("value",rowData.nm_rek); 
                   }  
                   });

		$('#rekanan').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/tunai/perusahaan',  
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
   
	
			

        
        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();    
            	return y+'-'+m+'-'+d;
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
        
         $('#tgl_kas').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();    
            	return y+'-'+m+'-'+d;
            }
        });
                                     
       
        
         $('#rekpajak').combogrid({  
                   panelWidth : 700,  
                   idField    : 'kd_rek6',  
                   textField  : 'kd_rek6',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/Tunai/rek_pots',  
                   columns:[[  
                       {field:'kd_rek6',title:'Kode Rekening',width:100},  
                       {field:'nm_rek6',title:'Nama Rekening',width:700}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                       $("#nmrekpajak").attr("value",rowData.nm_rek6);
                   }  
                   });
     }); 

	 
	 function detail_potongan(){
        $(function(){
			$('#dgpajak').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tunai_trmpot/trdtrmpot_list_bnk/',
                queryParams    : ({ nomor:bukti }),
                 //idField       : 'idx',
                 toolbar       : "#toolbar",              
                 rownumbers    : "true", 
                 fitColumns    : true,
                 autoRowHeight : "false",
                 singleSelect  : "true",
                 nowrap        : "true",
                 onLoadSuccess : function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                    
                    kd          = rowIndex ;  
                    idx         =  rowData.idx ;
                    tkd_rek_trans = rowData.kd_rek_trans ;
                    tkdrek5     = rowData.kdrek5 ;
                    tnmrek5     = rowData.nmrek5 ;
                    tnilai1     = rowData.nilai ;
                                                               
                },
                 columns:[[
                        {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                        {field:'kd_rek_trans',title:'Rek. Trans',width:80,align:'left'},			
                        {field:'kd_rek5',title:'Rekening',width:80,align:'left'},			
    					{field:'nm_rek5',title:'Nama Rekening',width:250,align:"left"},
                        {field:'ebill',title:'E-Billing',width:120,align:'left'},
    					{field:'nilai',title:'Nilai',width:160,align:"right"},
                        {field:'hapus',title:'Hapus',width:50,align:"center",
                        formatter:function(value,rec){ 
                        return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_pot();" />';
                        }
                        }
        			]]	
			});
		});
        }
	 
	 
	 
	 
	 

	function  getpot(bukti,voucher,tgl,ket,npwp,kd_giat,no_sp2d,nm_giat,kd_rek,nm_rek,rekanan,dir,alamat,jns_spp,st,bill){
        $("#nokas").attr("value",bukti);
        $("#no_simpan").attr("value",bukti);     
        //$("#beban").combobox("setValues",jns_spp);   
        document.getElementById('beban').value = jns_spp;
        $("#tglkas").datebox("setValue",tgl);        
        $("#kete").attr("value",ket);    
        $("#nm_giat").attr("value",nm_giat);
        $("#nm_rek").attr("value",nm_rek);
        $("#dir").attr("value",dir);
        $("#alamat").attr("value",alamat);
        $("#no_voucher_tr").combogrid("setValue",voucher);
		$("#kd_giat").combogrid("setValue",kd_giat);
		$("#no_sp2d").combogrid("setValue",no_sp2d);
		$("#kd_rek").combogrid("setValue",kd_rek);
        $("#npwp").combogrid("setValue",npwp);
		$("#rekanan").combogrid("setValue",rekanan);
        $("#ebilling").attr("value",bill);
        
        lcstatus = 'edit'; 
		tombol(st);
        }
        
       
    
    function hapus_detail_pot(){
        
        var rows          = $('#dgpajak').edatagrid('getSelected');
        var ctotalpotspm  = document.getElementById('totalrekpajak').value ;
        
        bkdrek            = rows.kd_rek5;
        bnilai            = rows.nilai;
        
        var idx = $('#dgpajak').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Rekening : '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#dgpajak').datagrid('deleteRow',idx);     
            $('#dgpajak').datagrid('unselectAll');

             ctotalpotspm = angka(ctotalpotspm) - angka(bnilai) ;
             $("#totalrekpajak").attr("Value",number_format(ctotalpotspm,2,'.',','));
             validate_rekening();
             }     
        }    
               
    
    
	
	
	function set_grid(){
       $('#dgpajak').edatagrid({
    			     
                     columns:[[
                        {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                        {field:'kd_rek5',title:'Rekening',width:80,align:'left'},			
    					{field:'nm_rek5',title:'Nama Rekening',width:250,align:"left"},
                        {field:'ebill',title:'E-Billing',width:120,align:'left'},
    					{field:'nilai',title:'Nilai',width:160,align:"right"},
                        {field:'hapus',title:'Hapus',width:50,align:"center",
                        formatter:function(value,rec){ 
                        return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_pot();" />';
                        }
                        }
        			]]		
        			});                 
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
        							  }                                     
        	});  
        }
	
 
    function load_detail_pot(){//(nosts){
        //alert(nosts);
        $(function(){
			$('#dgpajak').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tunai_trmpot/trdtrmpot_list_bnk/',
                //queryParams:({no:nosts}),
                 //idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,                                			 				 
                 columns:[[
                            {field:'id',
                    		title:'ID',    		
                            hidden:"true"},                
                    	    {field:'kd_rek5',
                    		title:'Nomor Rekening',
                            width:120},
                            {field:'nm_rek',
                    		title:'Nama Rekening',
                            width:300,align:"right"}, 
                            {field:'ebill',title:'E-Billing',width:170,align:'left'},               
                            {field:'nilai',
                    		title:'Nilai',
                            align:'right',
                            width:200}               
                        ]]	
			});
		});
        }
    
    
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
         set_grid();
         reloaddata();         
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
     
   
    
    function tombol(st){  
    if (st=='1'){
    $('#tambah').hide();
    $('#hapus').hide();
     } else {
     $('#tambah').show();
     $('#hapus').show();
    
     }
    }
    
    function tombolnew(){  
    
     $('#tambah').show();
     $('#hapus').show();
    
    }
    
    function kosong(){
        cdate = '<?php echo date("Y-m-d"); ?>';        
        $("#nokas").attr("value",'');
        $("#tglkas").datebox("setValue",cdate);
        $("#kete").attr("value",'');        
        $("#total").attr("value",'0'); 
        $("#beban").attr("value",'');
        $("#nm_giat").attr("value",'');
        $("#nm_rek").attr("value",'');
        $("#ebilling").attr("value",'');
        $("#dir").attr("value",'');
        $("#alamat").attr("value",'');
		$("#rekpajak").combogrid("setValue",'');
        $("#no_voucher_tr").combogrid("setValue",'');
        $("#npwp").combogrid("setValue",'');
		$("#kd_giat").combogrid("setValue",'');
		$("#no_sp2d").combogrid("setValue",'');
		$("#kd_rek").combogrid("setValue",'');
		$("#rekanan").combogrid("setValue",'');
        $("#nmrekpajak").attr("value",''); 
        $("#nilairekpajak").attr("value",'0');
        $("#totalrekpajak").attr("value",'0');
        
		//datagrid_kosong();
        lcstatus = 'tambah';
        document.getElementById("nokas").focus();
		get_nourut();
    }
	function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/cms/no_urut',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        							// $("#no_kas").attr("value",data.no_urut);
        								$("#nokas").attr("value",data.no_urut);
        							  }                                     
        	});  
        }
	
	function datagrid_kosong(){
		$('#dgpajak').edatagrid('selectAll');
		var rows = $('#dgpajak').edatagrid('getSelections');
		for(var i = rows.length; i>=0; i--){
		var index = $('#dgpajak').edatagrid('getRowIndex',rows.id);
		$('#dgpajak').edatagrid('deleteRow',index);
		//alert(i);
		}
	}
	
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tunai_trmpot/load_pot_in_bnk',
        queryParams:({cari:kriteria})
        });        
     });
    } 
    
    function append_save_pot() {
            $('#dgpajak').datagrid('selectAll');
            var rows  = $('#dgpajak').datagrid('getSelections');
            jgrid     = rows.length ; 
            
            var cebill = document.getElementById('ebilling').value;
            var rek_pajak    = $("#rekpajak").combogrid("getValue") ;
            var kd_rek    	 = $("#kd_rek").combogrid("getValue") ;
            var nm_rek_pajak = document.getElementById("nmrekpajak").value ;
            var nilai_pajak  = document.getElementById("nilairekpajak").value ;
            var nil_pajak    = angka(nilai_pajak);
            //var dinas        = document.getElementById('skpd').value;
            //var vnospm       = document.getElementById('nomor').value;
            var cket         = '0' ;
            var jumlah_pajak = document.getElementById('totalrekpajak').value ;   
                jumlah_pajak = angka(jumlah_pajak);        
                    //alert("aaa");
            
            if (cebill == ''){
                alert("E-Billing Belum di Input...!!!");
                exit();
            }
            
            if (cebill.length > '15'){
                alert("E-Billing Kelebihan Digit...!!!");
                exit();
            }
            
            if ( rek_pajak == '' ){
                alert("Isi Rekening Terlebih Dahulu...!!!");
                exit();
                }
            
            if ( nilai_pajak == 0 ){
                alert("Isi Nilai Terlebih Dahulu...!!!");
                exit();
                }
            
            pidx = jgrid + 1 ;

            $('#dgpajak').edatagrid('appendRow',{kd_rek_trans:kd_rek,kd_rek5:rek_pajak,nm_rek5:nm_rek_pajak,nilai:nilai_pajak,id:pidx,ebill:cebill});
            
            $("#nilairekpajak").attr("value",0);
            jumlah_pajak = jumlah_pajak + nil_pajak ;
            $("#totalrekpajak").attr('value',number_format(jumlah_pajak,2,'.',','));
            //validate_rekening();
			//$("#rekpajak").combogrid("setValue",'');
			//$("#nmrekpajak").attr("value",''); 
			$("#ebilling").attr("value",'');
			$("#rekpajak").combogrid("setValue",'');
        $("#nmrekpajak").attr("value",''); 
        $("#nilairekpajak").attr("value",'0');
			
    
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
                   url         : '<?php echo base_url(); ?>index.php/spmc/rek_pot', 
                   queryParams :({kdrek:frek}), 
                   columns:[[  
                       {field:'kd_rek5',title:'Kode Rekening',width:100},  
                       {field:'nm_rek5',title:'Nama Rekening',width:700}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                       $("#nmrekpajak").attr("value",rowData.nm_rek5);
                   }  
                   });
                   });
          $('#dgpajak').datagrid('unselectAll');         
    }   
    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg2').edatagrid('reload');
        kosong2();                        
    }   
    
    function simpan_potongan(){
        var no_bku = document.getElementById('no_simpan').value;  
        var cnokas = document.getElementById('nokas').value;  
        var ctglkas = $('#tglkas').datebox('getValue');
		var cnpwp = $("#npwp").combogrid("getValue");          
        var cebill = document.getElementById('ebilling').value;        
		var cbeban = document.getElementById('beban').value;  		
        var ckdrekbank = '';//      
        var cnmrekbank = '';//document.getElementById('nm_rek_bank').value;        		        
        var cnm_giat = document.getElementById('nm_giat').value;  
		var cnm_rek = document.getElementById('nm_rek').value;  
		var calamat = document.getElementById('alamat').value;  
		var cdir = document.getElementById('dir').value;  
        var cno_sp2d   = $("#no_sp2d").combogrid("getValue");
        var cno_voucher= $("#no_voucher_tr").combogrid("getValue") ; 
        var ckd_giat   = $("#kd_giat").combogrid("getValue"); 
        var ckd_rek    = $("#kd_rek").combogrid("getValue"); 
        var crekanan   = $("#rekanan").combogrid("getValue"); 
        var cskpd = document.getElementById('skpd').value;
        var cnmskpd = document.getElementById('nmskpd').value;
        var ckete = document.getElementById('kete').value;
        var ctotal = angka(document.getElementById('totalrekpajak').value);    
        //alert(cnokas+'/'+ctglkas+'/'+cnpwp+'/'+cbeban+'/'+cskpd+'/'+cnmskpd+'/'+ckete+'/'+ctotal)
        
        var r = confirm("Yakin Data Potongan akan disimpan ?");
        if (r == true) {
        
        
        if (cnokas==''){
            alert('Nomor Bukti Tidak Boleh Kosong');
            exit();
        }        
        if (cno_voucher==''){
            alert('Nomor Transaksi Tidak Boleh Kosong');
            exit();
        } 
        if (ctglkas==''){
            alert('Tanggal Bukti Tidak Boleh Kosong');
            exit();
        }
		if (cbeban==''){
            alert('Beban Tidak Boleh Kosong');
            exit();
        } 
        if (ctotal==0){
            alert('Belum ada Rekening Potongan');
            exit();
        }
        
           
       var tahun_input = ctglkas.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			alert('Tahun tidak sama dengan tahun Anggaran');
			exit();
		}
        if (ckd_giat==''){
            alert('Belum ada Kegiatan');
            exit();
        }
        if (ckd_rek==''){
            alert('Belum ada Rekening');
            exit();
        }
        
      $('#tambah').hide();  
        
	  if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cnokas,tabel:'trhtrmpot',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tunai/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){

	   
	//awal tambah potongan   
	    
       $(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                data: ({tabel:'trhtrmpot',no:cnokas,novoucher:cno_voucher,tgl:ctglkas,skpd:cskpd,nmskpd:cnmskpd,ket:ckete,total:ctotal,beban:cbeban,npwp:cnpwp,kd_giat:ckd_giat,no_sp2d:cno_sp2d,nm_giat:cnm_giat,kd_rek:ckd_rek,nm_rek:cnm_rek,rekanan:crekanan,alamat:calamat,dir:cdir,kdbank:ckdrekbank,nmbank:cnmrekbank}),
                url: '<?php echo base_url(); ?>/index.php/tunai_trmpot/simpan_potongan_bnk',
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
				crek_trans = rows[q].kd_rek_trans;
                crek       = rows[q].kd_rek5;
                cnmrek     = rows[q].nm_rek5;
                cnilai     = angka(rows[q].nilai);
				cebil      = rows[q].ebill;
                
                if (q>0) {
                csql = csql+","+"('"+cnobukti+"','"+crek+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+crek_trans+"','"+cebil+"')";
				csqljur = csqljur+","+"('"+cnobukti+"','"+crek+"','"+cnmrek+"','0','"+cnilai+"','K','2','1')";

                } else {
                csql = "values('"+cnobukti+"','"+crek+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+crek_trans+"','"+cebil+"')";                                            
                csqljur = "values('"+cnobukti+"','"+crek+"','"+cnmrek+"','0','"+cnilai+"','K','2','1')"+","+"('"+cnobukti+"','1110301','Kas di Bendahara Pengeluaran','"+ctotal+"','0','D','1','1')";                                            
                }                                             
			}                     
            
            $(document).ready(function(){
                //alert(csql);
				//exit();
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({tabel:'trdtrmpot',no:cnokas,sql:csql,sqljur:csqljur,skpd:cskpd}),
                    url: '<?php echo base_url(); ?>/index.php/tunai_trmpot/simpan_potongan_bnk',
                    success:function(data){                        
                        status = data.pesan;   
                         if (status=='1'){               
                            alert('Data Berhasil Tersimpan...!!!');
							status_simpan='edit';
							$("#no_simpan").attr("value",cnokas);
                            section1();                            
                        } else{ 
                            alert('Data Gagal Tersimpan...!!!');
                        }                                             
                    }
                });
                });            
			}
		}
		//akhir proses simpan				
						
						}
						});
                });


} else{ 

	$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cnokas,tabel:'trhtrmpot',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tunai/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cnokas!=no_bku){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || cnokas==no_bku){

	// awal proses edit					
		
	$(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                data: ({tabel:'trhtrmpot',no:cnokas,novoucher:cno_voucher,tgl:ctglkas,skpd:cskpd,nmskpd:cnmskpd,ket:ckete,total:ctotal,beban:cbeban,npwp:cnpwp,kd_giat:ckd_giat,no_sp2d:cno_sp2d,nm_giat:cnm_giat,kd_rek:ckd_rek,nm_rek:cnm_rek,rekanan:crekanan,alamat:calamat,dir:cdir,no_bku:no_bku,kdbank:ckdrekbank,nmbank:cnmrekbank}),
                url: '<?php echo base_url(); ?>/index.php/tunai_trmpot/simpan_potongan_bnk',
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
			alert('Jumlah Rekening : '+rows.length);
			for(var q=0;q<rows.length;q++){
				cnobukti   = cnokas;
				crek_trans = rows[q].kd_rek_trans;
                crek       = rows[q].kd_rek5;
                cnmrek     = rows[q].nm_rek5;
                cnilai     = angka(rows[q].nilai);
				cebil      = rows[q].ebill;
                
                if (q>0) {
                csql = csql+","+"('"+cnobukti+"','"+crek+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+crek_trans+"','"+cebil+"')";
				csqljur = csqljur+","+"('"+cnobukti+"','"+crek+"','"+cnmrek+"','0','"+cnilai+"','K','2','1')";

                } else {
                csql = "values('"+cnobukti+"','"+crek+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+crek_trans+"','"+cebil+"')";                                            
                csqljur = "values('"+cnobukti+"','"+crek+"','"+cnmrek+"','0','"+cnilai+"','K','2','1')"+","+"('"+cnobukti+"','1110301','Kas di Bendahara Pengeluaran','"+ctotal+"','0','D','1','1')";                                            
                }                                             
			}                     
            $(document).ready(function(){
                //alert(csql);
				//exit();
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({tabel:'trdtrmpot',no:cnokas,sql:csql,sqljur:csqljur,no_bku:no_bku,skpd:cskpd}),
                    url: '<?php echo base_url(); ?>/index.php/tunai_trmpot/simpan_potongan_bnk',
                    success:function(data){                        
                        status = data.pesan;   
                         if (status=='1'){               
                            alert('Data Berhasil Tersimpan...!!!');
							$("#no_simpan").attr("value",cnokas);
                            section1();
                        } else{ 
                            alert('Data Gagal Tersimpan...!!!');
                        }                                             
                    }
                });
                });            
			}
//--------

			}
			}
			});
		});
		
	}
    }else{
        alert('Periksa kembali inputan...');
    }
//akhir fungsi            
$('#tambah').linkbutton('enabled');
    }        
    
      
     function load_sum_pot(){                
		var nokas = document.getElementById('nokas').value; 
			//alert(nokas);
        $(function(){      
         $.ajax({
            type      : 'POST',
            data      : ({bukti:nokas}),
            url       : "<?php echo base_url(); ?>index.php/tunai_trmpot/load_trm_pot_bnk",
            dataType  : "json",
            success   : function(data){ 
                $.each(data, function(i,n){
                    $("#totalrekpajak").attr("value",number_format(n['rektotal1'],2,'.',','));
                    //$("#totalrekpajak").attr("value",n['rektotal']);
                });
            }
         });
        });
    }
	
	
	function hapus(){
        var cnomor = document.getElementById('no_simpan').value;
        var cno_voucher= $("#no_voucher_tr").combogrid("getValue") ;  
        var urll = '<?php echo base_url(); ?>index.php/tunai_trmpot/hapus_trmpot_bnk';
        var tny = confirm('Yakin Ingin Menghapus Data, Nomor Bukti : '+cnomor);        
        if (tny==true){
        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({no:cnomor,novoucher:cno_voucher}),
                 success:function(data){
                        status = data.pesan;
                        if (status=='1'){
                            alert('Data Berhasil Terhapus');   
                            section1();      
                        } else {
                            alert('Gagal Hapus');
                        }        
                 }
                 
                });           
        });
        }     
    }
    
    function reloaddata(){
             $(function(){ 
     $('#dg').edatagrid({
		rowStyler:function(index,row){
        if (row.status==1){
		//return {class:'r1', style:{'color:#fff'}};
            //return 'background-color:#6293BB;color:#fff;';
		   return 'color:green;font-weight:bold;';
			//font-weight:bold;
        }
    },
	 
	 
		url: '<?php echo base_url(); ?>/index.php/tunai_trmpot/load_pot_in_bnk',
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
    		width:40},
    	    {field:'no_bukti',
    		title:'Nomor Bukti',
    		width:40},
            {field:'tgl_bukti',
    		title:'Tanggal Bukti',
    		width:40},            
            {field:'ket',
    		title:'Keterangan',
    		width:135,
            align:"left"},
			{field:'status',
    		title:'Status',
    		width:5,
            align:"left"},
			{field:'detail',title:'Detail',width:20,align:"center",
                        formatter:function(value,rec){ 
                        return '<button class="button" ondblclick="javascript:section3();"> Detail</button>';
                        }
                        },
        ]],
        onSelect:function(rowIndex,rowData){
          bukti = rowData.no_bukti;
          voucher = rowData.no_kas;
          bill = rowData.ebilling;
          tgl  = rowData.tgl_bukti;
          st   = rowData.status;
          ket = rowData.ket; 
		  npwp = rowData.npwp;
		  kd_giat = rowData.kd_giat;
		  nm_giat = rowData.nm_giat;
		  no_sp2d = rowData.no_sp2d;
		  kd_rek = rowData.kd_rek;
		  nm_rek = rowData.nm_rek;
		  rekanan = rowData.rekanan;
		  dir = rowData.dir;
		  alamat = rowData.alamat;
		  jns_spp = rowData.jns_beban;	
          getpot(bukti,voucher,tgl,ket,npwp,kd_giat,no_sp2d,nm_giat,kd_rek,nm_rek,rekanan,dir,alamat,jns_spp,st,bill);  
		  detail_potongan();
		  load_sum_pot();
		},
        //onDblClickRow:function(rowIndex,rowData){
//
         //   section3();   
       // }
    });});

    }

   
    </script>

</head>
<body>



<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1" >List Terima Potongan - Transaksi Pemindahbukuan Bank</a></h3>
    <div>
    <p align="right">                   
        <button class="button" onclick="javascript:$('#tambah').show();
             $('#hapus').show(); section3();kosong();datagrid_kosong();"><i class="fa fa-tambah"></i> Tambah</button>
        <input type="text" value="" id="txtcari" class="input" onkeyup="javascript:cari();" style="display: inline;" />
        <table id="dg" title="List Terima Potongan" style="width:870px;height:600px;" >  
        </table>                          
    </p> 
    <font><b>Note : ST = Status Setor Potongan Pajak <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    STU = Status Upload Terima Potongan Pajak </b></font>
    </div>   

   <h3><a href="#" id="section3" >Potongan</a></h3>

    <div>
          
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
       <fieldset style="border: 1; border-radius: 10px"> 
       <table border='0' width="100%" style="font-size:11px"> 
	   <tr>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
				<td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;">&nbsp;</td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/></td>
				<td style="border-bottom: double 1px red;border-top: double 1px red;">&nbsp;&nbsp;<i>Tidak Perlu diisi atau di Edit</i></td>
                    
           </tr> 
	       <tr>
                <td>No Terima</td>
                <td>:</td>
                <td><input type="text" id="nokas"  class="input" name="nokas" style="width:200px;" /></td>
                <td>Tanggal :  <input type="text" id="tglkas" name="tglkas" style="width:200px;"/></td>
           </tr>
		   
		   <tr>
                <td>SKPD</td>
                <td>:</td>
                <td><input readonly="true" class="input" border="0" type="text" id="skpd"   name="skpd" style="width:200px;"/></td>
                <td><input readonly="true"  border="0" type="text" id="nmskpd"   name="nmskpd" style="width:500px;border:0"/></td>
           </tr>
		   <tr>
                <td>No. BKU Transaksi</td>
                <td>:</td>
                <td colspan="2"><input readonly="true" border="0" type="text" id="no_voucher_tr"   name="no_voucher_tr" style="width:210px;"/> </td>
           </tr>		   
		   <tr>
                <td>No. SP2D</td>
                <td>:</td>
                <td colspan="2"><input readonly="true" border="0" type="text" id="no_sp2d"   name="no_sp2d" style="width:210px;"/></td>
           </tr>
		   <tr>
                <td>Kd Kegiatan</td>
                <td>:</td>
                <td><input type="text" id="kd_giat" name="kd_giat" style="width:210px;"/></td>
                <td><input type="text" id="nm_giat" name="nm_giat" readonly="true" style="width:500px; border:0"/></td>
           </tr>
		   <tr>
                <td>Kd Rekening</td>
                <td>:</td>
                <td><input type="text" id="kd_rek" name="kd_rek" style="width:210px;"/></td>
                <td><input type="text" id="nm_rek" name="nm_rek" readonly="true" style="width:500px; border:0"/></td>
           </tr>
		   <tr>
                <td>NPWP</td>
                <td>:</td>
                <td><input type="text" id="npwp"   name="npwp" style="width:210px;"/></td>
				<td>Beban &nbsp;&nbsp;&nbsp;&nbsp;:<select name="beban" class="select" style="width:210px; display: inline" id="beban" >
					 <option value=''>...Pilih Jenis Beban... </option>
					 <option value='1'>UP</option>
					 <option value='2'>GU</option>
					 <option value='3'>TU</option>
					 <option value='4'>LS GAJI</option>
					 <option value='5'>LS PPKD</option>
					 <option value='6'>LS Barang Jasa</option>
				   </select></td>
				
           </tr>
           <tr>
		   <tr>
                <td>Rekanan :</td>
                <td>:</td>
                <td><input type="text" id="rekanan"   name="rekanan" style="width:210px;"/></td>
                <td>Pimpinan :  <input type="text" id="dir" name="dir" class="input" style="width:200px; display: inline;"/></td>
           </tr>
		   
			<tr>
            <td>ALamat Perusahaan</td>
                <td>:</td><td colspan='2'><textarea class="textarea" id="alamat" style="width:600px; height: 30px;" /></textarea></td>
           </tr>           
           <tr>
                <td>Keterangan</td>
                <td>:</td><td colspan='2'><textarea class="textarea" id="kete" style="width:600px; height: 30px;" /></textarea></td>
           </tr>
		  
		   
           <tr>
                <td>Rekening Potongan</td>
                <td>:</td>
                <td><input type="text" id="rekpajak"   name="rekpajak" style="width:210px;"/></td>
                <td><input type="text" id="nmrekpajak" name="nmrekpajak" style="width:400px;border:0px;"/></td>
           </tr>
           <tr>
           <td>No. e-Billing</td>
                <td>:</td><td colspan='2'><input class="input" type="text" id="ebilling" name="ebilling" style="width:200px; display: inline" />&nbsp; *) Per Pasal</td>
           </tr>
           <tr>
                <td align="left">Nilai</td>
                <td>:</td>
                <td><input type="text" id="nilairekpajak" class="input" name="nilairekpajak" style="width:200px;text-align:right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td></td>
           </tr>
           <tr>
             <td colspan="4" align="center" > 
              <button class="button" onclick="javascript:append_save_pot();"><i class="fa fa-tambah"></i> Tambah Potongan</button>

             </td>
           </tr>
       </table>
       </fieldset>
       
      &nbsp;&nbsp; 
       <table border='0' style="font-size:11px;width:850px;height:30px;"> 
           <tr>
                <td colspan="2" align="left" width='50%'>
                  <button id="tambah" class="button-biru" onclick="javascript:simpan_potongan();"><i class="fa fa-tambah"></i> Simpan</button>
                  <button class="button-abu" onclick="javascript:section1();"><i class="fa fa-kiri"></i> Kembali</button>
                  <button id="hapus" class="button-merah" onclick="javascript:hapus();kosong();datagrid_kosong();section1();"><i class="fa fa-hapus"></i> Hapus</button>
           
                </td>
                <td width='50%' align="right">Total <input type="text" id="totalrekpajak" name="totalrekpajak" style="width:250px;text-align:right;"/></td>
           </tr>

       </table>
       <table id="dgpajak" title="List Potongan" style="width:870px;height:170px;">  
       </table>   
       

    </div>
   
</div>
</div>





</body>

</html>