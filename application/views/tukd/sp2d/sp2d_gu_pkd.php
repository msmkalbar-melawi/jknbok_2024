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
   
    var nl =0;
	var tnl =0;
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var rek=0;
  
    $(function(){
   	     $('#dd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
   	});
    $(function(){
   	     $('#ddspm').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
   	});
    $(function(){
   	     $('#ddspp').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
   	});
        $(function(){
            $('#csp2d').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_sp2d',  
                    idField:'no_sp2d',                    
                    textField:'no_sp2d',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_sp2d',title:'SP2D',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:60},
                        {field:'no_spm',title:'SPM',width:60} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kode = rowData.no_sp2d;
                    dns = rowData.kd_skpd;
                    val_ttd(dns);
                    }   
                });
           });
           
        function val_ttd(dns){
           $(function(){
            $('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd/'+dns,  
                    idField:'nip',                    
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nip',title:'NIP',width:60},  
                        {field:'nama',title:'NAMA',align:'left',width:100}
                        
                        
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nip = rowData.nip;
                    
                    }   
                });
           });              
         }
         
    $(function(){ 
      var jenis='2';
     $('#spp').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_sp2d_pkd/'+jenis,
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        //loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_sp2d',
    		title:'Nomor SP2D',
    		width:40},
            {field:'tgl_sp2d',
    		title:'Tanggal',
    		width:40},
            {field:'no_spm',
    		title:'Nomor SPM',
    		width:40},
            {field:'no_spp',
    		title:'Nomor SPP',
    		width:40},
            {field:'kd_skpd',
    		title:'SKPD',
    		width:40,
            align:"left"},
            {field:'keperluan',
    		title:'Keterangan',
    		width:140,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          no_spp = rowData.no_spp;
          no_spm = rowData.no_spm;
          no_sp2d = rowData.no_sp2d;         
          kode  = rowData.kd_skpd;
          sp  = rowData.no_spd;          
          bl  = rowData.bulan;
          tg  = rowData.tgl_spp;
          tgspm  = rowData.tgl_spm;
          tgsp2d  = rowData.tgl_sp2d;
          jn  = rowData.jns_spp;
          kep  = rowData.keperluan;
          np  = rowData.npwp;
          rekan  = rowData.nmrekan;
          bk  = rowData.bank;
          ning  = rowData.no_rek;
          status  = rowData.status;          
          dir = rowData.dir;         
          get(no_spp,no_spm,no_sp2d,kode,sp,tg,tgspm,tgsp2d,bl,jn,kep,np,rekan,bk,ning,status,dir);
                                                  
        },
        onDblClickRow:function(rowIndex,rowData){
            section2();   
        }
    });
    }); 
    
    $(function(){
            $('#dn').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/skpd',  
                    idField:'kd_skpd',                    
                    textField:'kd_skpd',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'kd_skpd',title:'kode',width:60},  
                        {field:'nm_skpd',title:'nama',align:'left',width:80} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kode = rowData.kd_skpd;
                    $("#nmskpd").attr("value",rowData.nm_skpd);
                    validate_spd(kode);
                    }   
                });
           });           
              
            $(function(){
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
                    detail(spd);                                                      
                    }    
                });
           });
           
           
           
    $(function(){
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
                                  
			});
		}); 
    $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
                                  
			});
		});    
    $(function(){
			$('#dg2').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true"                                  
			});
		});     
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
     
        
     $(function(){
			$('#rekpot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/rek_pot',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
                                  
			});
		}); 
        
        $(function(){
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/get_pot',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
                                  
			});
		}); 
    
   function detail(spd){
    $(function(){
	   	    //var spp = document.getElementById('no_spp').value;            
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data',
                queryParams:({spd:spd}),
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
	   	    var spd='';
          $(function(){            
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data',
                queryParams:({spd:spd}),
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
                      load_sum_spp();                        
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
        
        function detailspp(){
        $(function(){
	   	    var spp = document.getElementById('no_spp').value;            
			$('#dg2').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({spp:spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_spp();                        
                    },
                onSelect:function(rowIndex,rowData){
                kd = rowIndex;                                               
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
                    {field:'nilai',
					 title:'Nilai',
					 width:100,
                     align:'right'				 
                     }
                      
				]]	
			
			});
  	

		});
        }
        
        function rk_pot(){
        $(function(){	   	               
			$('#rekpot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/rek_pot',                
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){
                      pot();                                           
                    },                      
                 onClickRow:function(rowIndex, rowData){							
								rk_pot=rowData.kd_rek5;                                
                                nrek=rowData.nm_rek5;                                
								simpanpot(rk_pot,nrek);
                                pot();
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
                     {field:'kd_rek5',
					 title:'Rekening',
					 width:150,
					 align:'left'
					},			
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:620
					}
				]]	
			
			});
  	

		});
        }
        
         function pot(){
        $(function(){
	   	    var spm = document.getElementById('no_spm').value;
               //alert(spm)            
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot',
                queryParams:({spm:spm}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_pot();
                      },                    
                 onAfterEdit:function(rowIndex, rowData, changes){						
								rekeing=rowData.kd_rek5;
                                nrekeing=rowData.nm_rek5;
                                nilai=rowData.nilai;
                                poto=rowData.pot;                                
								psimpan(rekeing,nrekeing,nilai,poto);       	                                      
							 },                			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                    
					{field:'kd_rek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:550
					},                    
                    {field:'nilai',
					 title:'Nilai',
					 width:100,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
                     {field:'pot',
					 title:'ket',
					 width:30,
                     align:'center',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     }
                      
				]]	
			
			});
  	

		});
        }

        function get(no_spp,no_spm,no_sp2d,kd_skpd,no_spd,tgl_spp,tgl_spm,tgl_sp2d,bulan,jns_spp,keperluan,npwp,rekanan,bank,rekening,status,pim){
        $("#no_spp").attr("value",no_spp);
        $("#no_spm").attr("value",no_spm);
        $("#no_sp2d").attr("value",no_sp2d);
        $("#dn").combogrid("setValue",kd_skpd);
        $("#sp").combogrid("setValue",no_spd);
        $("#dd").datebox("setValue",tgl_sp2d);
        $("#ddspm").datebox("setValue",tgl_spm);
        $("#ddspp").datebox("setValue",tgl_spp);
        $("#kebutuhan_bulan").attr("Value",bulan);
        $("#ketentuan").attr("Value",keperluan);
        $("#jns_beban").attr("Value",jns_spp);
        $("#npwp").attr("Value",npwp);
        $("#rekanan").attr("Value",rekanan);
        $("#dir").attr("Value",pim);
        $("#bank1").attr("Value",bank);
        $("#rekening").attr("Value",rekening);        
         tombol(status);           
        }
		
        function kosong(){
        cdate = '<?php echo date("Y-m-d"); ?>'; 
        $("#no_spp").attr("value",'');
        $("#no_spm").attr("value",'');
        $("#no_sp2d").attr("value",'');
        $("#dn").combogrid("setValue",'');
        $("#nmskpd").attr("value",'');
        $("#sp").combogrid("setValue",'');
        $("#dd").datebox("setValue",cdate);
        $("#ddspp").datebox("setValue",cdate);
        $("#ddspm").datebox("setValue",cdate);
        $("#kebutuhan_bulan").attr("Value",'');
        $("#ketentuan").attr("Value",'');
        $("#jns_beban").attr("Value",'');
        $("#npwp").attr("Value",'');
        $("#rekanan").attr("Value",'');
        $("#dir").attr("Value",'');
        $("#bank1").attr("Value",'');
        $("#rekening").attr("Value",'');        
        document.getElementById("p1").innerHTML="";        
        $("#dn").combogrid("clear");
        $("#sp").combogrid("clear");
        $("#kg").combogrid("clear");
        det_baru()
        tombolnew()            
        }

		function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		}  


        function simpan(giat,reke,nkeg,nrek,sisa){		
		var spp = document.getElementById('no_spp').value;
		var cskpd =kode;
        var cspd = spd;

        $(function(){      
            $.ajax({
            type: 'POST',
            data: ({cskpd:cskpd,cspd:spd,cspp:spp,cgiat:giat,crek:reke,cnmgiat:nkeg,cnmrek:nrek,sspp:sisa}),
            dataType:"json",
            url:'<?php echo base_url(); ?>/index.php/tukd/tsimpan'
         });
        });
		}       
		
       
        $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal" ).dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
        });
       
     function cetak(){
        var nom=document.getElementById("no_spp").value;
        $("#cspp").combogrid("setValue",nom);
        $("#dialog-modal").dialog('open');
    } 
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    }   
     function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#spp').edatagrid({
	       url: '<?php echo base_url(); ?>/index.php/tukd/load_spp',
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
     function section4(){
         $(document).ready(function(){    
             $('#section4').click();                                               
         });
     }
     
     
     function hsimpan(){        
        var a = document.getElementById('no_spp').value;
        var b = $('#ddspp').datebox('getValue');
        var a1 = document.getElementById('no_spm').value;
        var b1 = $('#ddspm').datebox('getValue');
        var a2 = document.getElementById('no_sp2d').value;
        var b2 = $('#dd').datebox('getValue');       
        var c = document.getElementById('jns_beban').value; 
        var d = document.getElementById('kebutuhan_bulan').value;
        var e = document.getElementById('ketentuan').value;
        var f = document.getElementById('rekanan').value;
        var f1 = document.getElementById('dir').value;
        var g = document.getElementById('bank1').value;
        var h = document.getElementById('npwp').value;
        var i = document.getElementById('rekening').value;
        var j = document.getElementById('nmskpd').value;
        var k = document.getElementById('rektotal1').value;
        var l = '';
        var m = '';
        var n = '';
        var o = '';
        //var crek    = $('#kg').combogrid('grid'); 
//        var grek    = crek.datagrid('getSelected'); 
//        var giat = grek.kd_kegiatan;
//        var nmgiat = grek.nm_kegiatan;
//        var prg = grek.kd_program;

        //alert(kode+spd+kegi+l+m+n);
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cskpd:kode,cspd:spd,no_spp:a,no_spm:a1,no_sp2d:a2,tgl_spp:b,tgl_spm:b1,tgl_sp2d:b2,jns_spp:c,bulan:d,keperluan:e,nmskpd:j,rekanan:f,bank:g,npwp:h,rekening:i,nilai:k,kegi:o,nmkegi:l,prog:m,nmprog:n,dir:f1}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/simpan_spp",
            success:function(data){
                if (data == 1){
                    alert('Data Berhasil Tersimpan');                    
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                }
            }
         });
        });
    }
    
    function dsimpan(kegiatan,rekening,nkegiatan,nrekening,nilai_baru,si,kd){
        var a = document.getElementById('no_spp').value;
        $jak = eval(si);
        $son = eval(nilai_baru);

        if ($son< $jak) {          
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cno_spp:a,cskpd:kode,cgiat:kegiatan,crek:rekening,ngiat:nkegiatan,nrek:nrekening,nilai:nilai_baru,sis:si,kd:kd}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/dsimpan"            
         });
        });
        }else{
            alert($son+'-nilai melebihi anggaran-'+$jak);
            exit();
        } 
    
    }
    function detsimpan(){
        var a = document.getElementById('no_spp').value;        
        $('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');
         //alert(rows); 
        for(var i=0;i<rows.length;i++){            
            ckdgiat  = rows[i].kdkegiatan;
            cnmgiat  = rows[i].nmkegiatan;
            ckdrek  = rows[i].kdrek5;
            cnmrek  = rows[i].nmrek5;
            cnilai   = rows[i].nilai1;
            cnilai_s   = rows[i].sis;           
            no=i+1;      
            $(document).ready(function(){      
            $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/dsimpan" ,
            data: ({cno_spp:a,cskpd:kode,cgiat:ckdgiat,crek:ckdrek,ngiat:cnmgiat,nrek:cnmrek,nilai:cnilai,sis:cnilai_s,kd:no}),
            dataType:"json"            
         });
        });
        }
    } 
    function simpanpot(reke,nrek){		
		var spm = document.getElementById('no_spm').value;
		//var cskpd =document.getElementById('dn').value;
        //alert(spm+kode+reke+nrek)
        $(function(){      
            $.ajax({
            type: 'POST',
            data: ({cskpd:kode,spm:spm,kd_rek5:reke,nmrek:nrek}),
            dataType:"json",
            url:'<?php echo base_url(); ?>/index.php/tukd/pot_simpan'
         });
        });
		}
        
    function psimpan(reke,nrek,nilai,ket){		
		var spm = document.getElementById('no_spm').value;
	
        //alert(spm+cskpd+reke+nrek+nilai+ket);
        $(function(){      
            $.ajax({
            type: 'POST',
            data: ({cskpd:kode,spm:spm,kd_rek5:reke,nmrek:nrek,nilai:nilai,ket:ket}),
            dataType:"json",
            url:'<?php echo base_url(); ?>/index.php/tukd/potsimpan'
         });
        });
		}
    
     function hapus(){				
                var spp = document.getElementById("no_spp").value;                
                var nospp =spp.split("/").join("123456789");
				var giat=getSelections();
                var rek=getSelections1();
                //alert(kd);
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
            var spm = document.getElementById("no_spm").value;
            var sp2d = document.getElementById("no_sp2d").value;              
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hhapus_pkd';             			    
         	if (spp !=''){
				var del=confirm('Anda yakin akan menghapus SPP '+spp+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({spp:spp,spm:spm,sp2d:sp2d}),function(data){
                    status = data;                        
                    });
                    });				
				}
				} 
		}
        
    //    function phapus(){				
//            var spm = document.getElementById("no_spm").value;
//            var rek=get();                       
//            var urll= '<?php echo base_url(); ?>/index.php/tukd/hapus_pot';             			    
//         	if (spm !=''){
//				var del=confirm('Anda yakin akan menghapus rek '+rek+'  ?');
//				if  (del==true){
//					$(document).ready(function(){
//                    $.post(urll,({no:spm,rek:rek}),function(data){
//                    status = data;
//                        
//                    });
//                    });
//				
//				}
//				} 
//		}  
//         
//        function get(idx){
//			//alert(idx);
//			var ids = [];
//			var rows = $('#pot').edatagrid('getSelections');
//			for(var i=0;i<rows.length;i++){
//				ids.push(rows[i].kd_rek5);
//			}
//			return ids.join(':');
//		}
        
          function getSelections(idx){
			//alert(idx);
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kdkegiatan);
			}
			return ids.join(':');
		}
        
        function getSelections1(idx){
			//alert(idx);
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
		var spp = document.getElementById('no_spp').value;
        var nospp =spp.split("/").join("123456789");       
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spp:nospp}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_spp",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal']);
                    $("#rektotal1").attr("value",n['rektotal1']);
                });
            }
         });
        });
    }
    
    function load_sum_pot(){                
		var spm = document.getElementById('no_spm').value;
        //alert(spm);              
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spm:spm}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_pot",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotalpot").attr("value",n['rektotal']);
                });
            }
         });
        });
    }
    function tombol(st){  
    if (st=='1'){
    $('#save').linkbutton('disable');
    $('#del').linkbutton('disable');
    $('#sav').linkbutton('disable');
    $('#dele').linkbutton('disable'); 
    $('#det').linkbutton('disable');
    
    document.getElementById("p1").innerHTML="SP2D Sudah Dicairkan!!";
     } else {
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
     $('#sav').linkbutton('enable');
     $('#dele').linkbutton('enable');
     $('#det').linkbutton('enable'); 
    document.getElementById("p1").innerHTML="";
     }
    }
    
    function tombolnew(){  
    
     $('#save').linkbutton('enable');
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
        
    function openWindow( url )
        {
      
        var no =kode.split("/").join("123456789");
       // alert(no);
        window.open(url+'/'+no+'/'+dns, '_blank');
        window.focus();
        }
        
    function cek1(){
        var lcno = document.getElementById('no_spp').value;
        var lcnospm = document.getElementById('no_spm').value;
        var lcnosp2d = document.getElementById('no_sp2d').value;       
            if(!(lcno =='' || lcnospm =='' || lcnosp2d =='')){                
                section3();                 
            } else {
                alert('Nomor SPP/SPM/SP2D Tidak Boleh kosong')
                document.getElementById('no_spp').focus();
                exit();
            }
    }
        
    function cek(){
        var lcno = document.getElementById('no_spp').value;       
            if(lcno !=''){
               cek1();               
            } else {
                alert('Nomor SPP Tidak Boleh kosong')
                document.getElementById('no_spp').focus();
                exit();
            }
    }
    function cek1(){        
        var lcnospm = document.getElementById('no_spm').value;
            if(lcnospm !=''){
               cek2();               
            } else {
                alert('Nomor SPM Tidak Boleh kosong')
                document.getElementById('no_spm').focus();
                exit();
            }
    }    
    function cek2(){        
        var lcnosp2d = document.getElementById('no_sp2d').value;        
            if(lcnosp2d !=''){              
               section3();               
            } else {
                alert('Nomor SP2D Tidak Boleh kosong')
                document.getElementById('no_sp2d').focus();
                exit();
            }
    }        
        
    </script>
    <STYLE TYPE="text/css"> 
input.right{ 
         text-align:right; 
         } 
</STYLE> 

</head>
<body>



<div id="content">

<div id="accordion">

<h3><a href="#" id="section1" onclick="javascript:$('#spp').edatagrid('reload')">List SP2D</a></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a></td>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="spp" title="List SP2D" style="width:870px;height:450px;" >  
        </table>
                  
        
    </p> 
    </div>

<h3><a href="#" id="section2" >Input SP2D PKD</a></h3>
   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>
  <!-- <?php echo form_open('tukd/simpan', array('class' => 'basic')); ?> -->
               
<table border='0' style="font-size:11px" >
 
 <tr>
   <td >No SP2D </td>
   <td><input type="text" name="no_sp2d" id="no_sp2d" onclick="javascript:select();" /></td>
   <td>Tgl SP2D </td>
   <td><input id="dd" name="dd" type="text" /></td>
 </tr>
 <tr>
   <td >No SPM </td>
   <td><input type="text" name="no_spm" id="no_spm" onclick="javascript:select();" /></td>
   <td>Tgl SPM </td>
   <td><input id="ddspm" name="ddspm" type="text" /></td>
 </tr>
 <tr>   
   <td width="8%" >No SPP</td>
   <td><input type="text" name="no_spp" id="no_spp" onclick="javascript:select();" /></td>
   <td>Tgl SPP</td>
   <td><input id="ddspp" name="ddspp" type="text" /></td>   
 </tr>
 <tr>
   <td width='8%'>SKPD</td>
   <td width="53%" >     
      <input id="dn" name="dn" style="width:130px" /></td> 
   <td width='8%'>Bulan</td>
   <td width="31%" ><select  name="kebutuhan_bulan" id="kebutuhan_bulan" >
     <option value="">...Pilih Kebutuhan Bulan... </option>
     <option value="1" >1 | Januari</option>
     <option value="2">2 | Februari</option>
     <option value="3">3 | Maret</option>
     <option value="4">4 | April</option>
     <option value="5">5 | Mei</option>
     <option value="6">6 | Juni</option>
     <option value="7">7 | Juli</option>
     <option value="8">8 | Agustus</option>
     <option value="9">9 | September</option>
     <option value="10">10 | Oktober</option>
     <option value="11">11 | November</option>
     <option value="12">12 | Desember</option>
   </select></td> 
 </tr>
 <tr>
   <td width='8%'>&nbsp;</td>
   <td width='53%'><textarea name="nmskpd" id="nmskpd" cols="40" rows="1" style="border: 0;"  readonly="true"></textarea></td>
   <td width='8%'>Keperluan</td>
   <td width='31%'><textarea name="ketentuan" id="ketentuan" cols="30" rows="2" ></textarea></td>
 </tr>
 <tr>
   <td width='8%'>No SPD</td>
   <td><input id="sp" name="sp" style="width:150px" /></td>
   <td width='8%'>Rekanan/Dir.</td>
   <td><input id="rekanan" name="rekanan" style="width:150px" />
   	   <input id="dir" name="dir" style="width:150px" /></td>
 </tr>
 
 <tr>
   <td>Beban</td>
   <td><select name="jns_beban" id="jns_beban">          
     <option value="2">GU</option>     
   </select></td>
   <td>Bank</td>
   <td><?php
								  		$bank1="select * from ms_bank ";
                                        $pagingquery1 = $bank1; //echo "edit  $pagingquery1<br />";
                                        $res = mysql_query($pagingquery1)or die("pagingquery gagal".mysql_error());
								?>
     <select name="bank1" id="bank1" style="height: 27px; width: 100px;">
       <option value="">...Bank.. </option>
       <?php
		 if($res)
          {
           while ($result = mysql_fetch_row($res)) 
             {
  		?>
       <option value="<?php echo $result[0]; ?>" <?php if($result[0]==$bank1){echo "selected";}?>> <?php echo $result[0]."-".$result[1]; ?> </option>
       <?php 
             }
           }
        ?>
     </select></td>
 </tr>
 <tr>
   <td width='8%'>NPWP</td>
   <td width='53%'><input type="text" name="npwp" id="npwp" value="" /></td>
   <td width='8%'>Rekening</td>
   <td width='31%'><input type="text" name="rekening" id="rekening"  value="" /></td>
 </tr>

        
         <!--<tr>
         <td width='8%'></td>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
            <td width='8%'></td>
            <td width='31%'></td>
        </tr>-->
    <tr>
                <td colspan="4">
                <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true"  onclick="javascript:hsimpan();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section2();">Hapus</a>
                <a id="det" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:cek();">Detail</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>
                <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a></td>                
            </tr>
    </table>
   <!-- <table id="dg2" title="Input Detail SPP" style="width:850%;height:250%;" >  
        
        </table>-->
    

   </p>
    </div>
    
<h3><a href="#" id="section3" onclick="javascript:$('#dg').edatagrid('reload')" ></a></h3>
    <div>
    <p> 
        
       <table id="dg" title="Input Detail SPP" style="width:850%;height:250%;" >  
        
        </table><br />
         <table id="dg1" title="Input Detail SPP" style="width:850%;height:250%;" >  
        
        </table>         

       <a id="dele" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
       <a id="sav" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg1').edatagrid('addRow');javascript:$('#dg1').edatagrid('reload');javascript:hsimpan();">SIMPAN</a>       
       <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section2();javascript:$('#dg2').edatagrid('reload');">kembali</a>
       <a id="poto" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:section4();javascript:rk_pot();">Potongan</a>
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal"  style="width:90px" align="rigth" readonly="true" >
            <input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:90px" align="rigth" readonly="true" >		
     
    </p> 
    </div>
<h3><a href="#" id="section4" ></a></h3>
    <div>
    <p align="right">      
        <table id="rekpot" title="Pilih Rekening Potongan" style="width:870px;height:200px;" >  
        </table><br /><br />
        <table id="pot" title="List Potongan" style="width:870px;height:200px;" >  
        </table>          
        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:phapus();javascript:$('#pot').edatagrid('reload');">Hapus</a>
       <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#pot').edatagrid('addRow');javascript:$('#pot').edatagrid('reload');">SIMPAN</a>
       <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">kembali</a>
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotalpot" id="rektotalpot"  style="width:100px" align="right" readonly="true" >
        
    </p> 
    </div>
</div>

</div> 

<div id="dialog-modal" title="CETAK SPP">
    <p class="validateTips">SILAHKAN PILIH SPP</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">NO SPP:</td>
            <td><input id="csp2d" name="csp2d" style="width: 170px;" /></td>
        </tr>
       
       
    </table>  
    </fieldset>
    <a href="<?php echo site_url(); ?>/tukd/cetak_sp2d" class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false;">Cetak</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>
 	
</body>

</html>