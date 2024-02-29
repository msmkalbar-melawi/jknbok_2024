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
	$('#no_sp2d').combogrid({  
                   panelWidth : 450,  
                   idField    : 'no_sp2d',  
                   textField  : 'no_sp2d',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/tukd/load_sp2d',  
                   columns:[[  
                       {field:'no_sp2d',title:'No SP2D',width:250},  
                       {field:'tgl_sp2d',title:'Tanggal SP2D',width:200}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
						$("#nm_giat").attr("value",'');
						$("#nm_rek").attr("value",'');
						$("#kd_giat").combogrid("setValue",'');
						$("#kd_rek").combogrid("setValue",'');
					   nosp2d = rowData.no_sp2d;
					   sp2d = nosp2d.split("/").join("123456789");
                       $("#beban").attr("value",rowData.jns_spp); 
					   $("#kd_giat").combogrid({url: '<?php echo base_url(); ?>/index.php/tukd/load_kegiatan_pot/'+sp2d});
                   }  
                   });	
				});
		$(function(){		   
		$('#kd_giat').combogrid({  
                   panelWidth : 450,  
                   idField    : 'kd_giat',  
                   textField  : 'kd_giat',  
                   mode       : 'remote',
                   columns:[[  
                       {field:'kd_giat',title:'Kode Kegiatan',width:150},  
                       {field:'nm_giat',title:'Nama Kegiatan',width:300}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                       $("#nm_giat").attr("value",rowData.nm_giat); 
					   $("#kd_rek").combogrid({url: '<?php echo base_url(); ?>/index.php/tukd/load_rek_pot/'+sp2d});
                   }  
                   });	
		});
		$(function(){		   
		$('#kd_rek').combogrid({  
                   panelWidth : 450,  
                   idField    : 'kd_rek',  
                   textField  : 'kd_rek',  
                   mode       : 'remote',
                   columns:[[  
                       {field:'kd_rek',title:'Kode Kegiatan',width:150},  
                       {field:'nm_rek',title:'Nama Kegiatan',width:300}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
				    	nosp2d    = $('#no_sp2d').combogrid('getValue');
						sp2d = nosp2d.split("/").join("123456789");
                        $("#nm_rek").attr("value",rowData.nm_rek); 
					   //$("#kd_giat").edatagrid({url: '<?php echo base_url(); ?>/index.php/tukd/load_tetap_sts/'+kode+'/'+plrek}
                   }  
                   });
			});
		$(function(){	
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
	});
	
		$(function(){
            $('#trmpot1').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/trmpot__',  
                    idField:'no_bukti',                    
                    textField:'no_bukti',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_bukti',title:'No',width:60},  
                        {field:'tgl_bukti',title:'Tanggal',align:'left',width:30} 
                          
                    ]],
                     onSelect:function(rowIndex,rowData){
                         no_terima = rowData.no_bukti;                                                                       
                        dns  = rowData.kd_skpd;                        
                        jns  = rowData.jns_spp;
                        nm  = rowData.nm_skpd;
                        npwp = rowData.npwp;
                        kd_giat = rowData.kd_giat;
                        nm_giat = rowData.nm_giat;
                        no_sp2d = rowData.no_sp2d;
                        kd_rek = rowData.kd_rek;
                        nm_rek = rowData.nm_rek;
                        alamat = rowData.alamat;
                        dir = rowData.dir;
                        rekanan = rowData.rekanan;
                        ket = rowData.ket;       
                        get(dns,nm,jns,npwp,ket,kd_giat,nm_giat,no_sp2d,kd_rek,nm_rek,alamat,dir,rekanan); 
                        pot();                                                              
                    }  
                });
           });
	
        $(function(){
            $('#ctrmpot').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_trmpot',  
                    idField:'no_bukti',                    
                    textField:'no_bukti',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_bukti',title:'Bukti',width:60},  
                        {field:'tgl_bukti',title:'Tanggal',align:'left',width:60},
                        {field:'no_sp2d',title:'SP2D',width:60} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kode = rowData.no_bukti;
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
     $('#pot_out').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_pot_out',
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
    		width:40},
            {field:'tgl_bukti',
    		title:'Tanggal Bukti',
    		width:40},            
            {field:'ket',
    		title:'Keterangan',
    		width:140,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          bukti = rowData.no_bukti;
          trm = rowData.no_terima          
          tgl  = rowData.tgl_bukti;
          st   = rowData.status;
          ket = rowData.ket;   
		  no_sp2d = rowData.no_sp2d; 
		  jns_spp = rowData.jns_spp; 
		  kd_kegiatan = rowData.kd_kegiatan; 
		  nm_kegiatan = rowData.nm_kegiatan; 
		  nmrekan = rowData.nmrekan; 
		  pimpinan = rowData.pimpinan; 
		  alamat = rowData.alamat; 
		  npwp = rowData.npwp; 
          getpot(bukti,trm,tgl,st,ket,no_sp2d,jns_spp,kd_kegiatan,nm_kegiatan,nmrekan,pimpinan,alamat,npwp); 
		  detpotong(bukti);
		  load_sum_pot();
        },
        onDblClickRow:function(rowIndex,rowData){
            section2();   
        }
    });
    }); 
        
              
    $(function(){
            $('#trmpot').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/trmpot_',  
                    idField:'no_bukti',                    
                    textField:'no_bukti',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_bukti',title:'No',width:60},  
                        {field:'tgl_bukti',title:'Tanggal',align:'left',width:30} 
                          
                    ]],
                     onSelect:function(rowIndex,rowData){
						no_terima = rowData.no_bukti;
						$("#trmpot1").combogrid("setValue",no_terima);
                    }  
                });
           });

        $(function(){
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true"                                  
			});
		}); 
    
       
        
        
         function pot(){
         $(function(){	   	                    
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot_in',
                queryParams:({bukti:no_terima}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                 
                     // pot_pilih();                                 
                      },
                 onClickRow:function(rowIndex, rowData){
								rk=rowData.kd_rek5;
                                nrek=rowData.nm_rek5;
                                nila=rowData.nilai;                                
								dsimpan(rk,nrek,nila);
                                //pot_pilih();
							 },		                              			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
                     {field:'id',
    		          title:'ID',
                      hidden:true    		
                    },                    
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
                     align:'right'
                     }                      
				]]
			});
		load_sum_pot1();

		});
        }
		
		
		function load_sum_pot1(){                
		//var no_bukti = document.getElementById('no_bukti').value;              
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({bukti:no_terima}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_trm_pot",
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
		
		
		
		 function detpotong(bukti){
         $(function(){	   	                    
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot_setor',
                queryParams:({bukti:bukti}),
                 idField:'idx',
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
                     {field:'id',
    		          title:'ID',
                      hidden:true    		
                    },                    
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
                     align:'right'
                     }                      
				]]
			});
  	

		});
        }
		
		
		
		
        /*

        function pot1(){
        $(function(){
	   	    var bukti='';                              
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot_in',
                queryParams:({bukti:bukti}),
                 idField:'idx',
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
					{field:'kd_rek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:530
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
        
    */    
              
        function get(dns,nm,jns,npwp,ket,kd_giat,nm_giat,no_sp2d,kd_rek,nm_rek,alamat,dir,rekanan){
        $("#dn").attr("value",dns);
        $("#npwp").attr("Value",npwp);
        $("#nmskpd").attr("Value",nm);
        $("#beban").attr("value",jns);
        $("#ketentuan").attr("Value",ket);
        $("#nm_giat").attr("Value",nm_giat);
        $("#nm_rek").attr("Value",nm_rek);
        $("#alamat").attr("Value",alamat);
        $("#dir").attr("Value",dir);
        $("#no_sp2d").combogrid("setValue",no_sp2d);
        $("#kd_giat").combogrid("setValue",kd_giat);
        $("#kd_rek").combogrid("setValue",kd_rek);
        $("#rekanan").combogrid("setValue",rekanan);

        }
                  
          
        function getpot(bukti,trm,tgl,st,ket,no_sp2d,jns_spp,kd_kegiatan,nm_kegiatan,nmrekan,pimpinan,alamat,npwp){
            //alert(no_bukti+no_sp2d+tgl_bukti+status+ket);
        $("#no_bukti").attr("value",bukti);
        $("#no_simpan").attr("value",bukti);
        $("#trmpot").combogrid("setValue",trm);
        $("#trmpot1").combogrid("setValue",trm);
        $("#no_sp2d").combogrid("setValue",no_sp2d);
        $("#kd_giat").combogrid("setValue",kd_kegiatan);
        $("#rekanan").combogrid("setValue",nmrekan);
        $("#trmpot_lama").attr("value",trm);
        $("#dir").attr("value",pimpinan);
        $("#npwp").attr("value",npwp);
        $("#nm_giat").attr("value",nm_giat);
        $("#beban").attr("value",jns_spp);
        $("#dd").datebox("setValue",tgl);
        $("#ketentuan").attr("value",ket);
		lcstatus = 'edit';
        tombol(st);                   
        }
		
        function kosong(){
        $("#no_bukti").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#trmpot_lama").attr("value",'');
        $("#dd").datebox("setValue",'');
        $("#trmpot").combogrid("setValue",'');        
        $("#dn").attr("value",'');        
        $("#beban").attr("Value",'');
        $("#npwp").attr("Value",'');        
        $("#nmskpd").attr("Value",'');
        $("#ketentuan").attr("value",'');  
		$("#nm_giat").attr("Value",'');
        $("#nm_rek").attr("Value",'');
        $("#alamat").attr("Value",'');
        $("#dir").attr("Value",'');
        $("#no_sp2d").combogrid("setValue",'');
        $("#kd_giat").combogrid("setValue",'');
        $("#kd_rek").combogrid("setValue",'');
        $("#rekanan").combogrid("setValue",'');
		lcstatus='tambah';
		get_nourut();
		
        document.getElementById("p1").innerHTML="";        
        //pot1();
        $("#trmpot").combogrid("clear");
        //tombolnew();      
        }
		
		function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/no_urut',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        							// $("#no_kas").attr("value",data.no_urut);
        								$("#no_bukti").attr("value",data.no_urut);
        							  }                                     
        	});  
        }

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $("#dialog-modal").dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
		get_tahun();
        });
       
     function cetak(){
        $("#dialog-modal").dialog('open');
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
    function keluar(){
        $("#dialog-modal").dialog('close');
    }   
     function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#pot_out').edatagrid({
	       url: '<?php echo base_url(); ?>/index.php/tukd/load_pot_out',
         queryParams:({cari:kriteria})
        });        
     });
    }
        
        function hsimpan(){    
//alert("aaa");		
        var no_bku = document.getElementById('no_simpan').value;
		var trmpot_lama = document.getElementById('trmpot_lama').value;
        var a = document.getElementById('no_bukti').value;
        var b = $('#dd').datebox('getValue');  
        var c = document.getElementById('beban').value;       
        var d = document.getElementById('ketentuan').value;       
        var e = document.getElementById('nmskpd').value;
        var f = document.getElementById('dn').value;
        var g = document.getElementById('npwp').value;
        var h = angka(document.getElementById('rektotal1').value);
        var i = document.getElementById('nm_giat').value;
        var j = document.getElementById('nm_rek').value;
        var k = document.getElementById('alamat').value;
        var l = document.getElementById('dir').value;
        var m = $("#no_sp2d").combogrid("getValue") ; 
        var n = $("#kd_giat").combogrid("getValue") ; 
        var o = $("#kd_rek").combogrid("getValue") ; 
        var p = $("#rekanan").combogrid("getValue") ; 
		var no_terima = $("#trmpot").combogrid("getValue") ;
        // alert(no_terima+'/'+a+'/'+b+'/'+c+'/'+d+'/'+e+'/'+f+'/'+g+'/'+h);       
       var tahun_input = b.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			alert('Tahun tidak sama dengan tahun Anggaran');
			exit();
		}
if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'trhstrpot',field:'no_bukti'}),
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
//-----
	   $(function(){      
         $.ajax({
            type: 'POST',
            data: ({no_bukti:a,tgl_bukti:b,no_terima:no_terima,jns_spp:c,ket:d,kd_skpd:f,nm_skpd:e,npwp:g,nilai:h,nm_giat:i,nm_rek:j,alamat:k,dir:l,no_sp2d:m,kd_giat:n,kd_rek:o,rekanan:p}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/simpan_strpot",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan');
					$("#no_simpan").attr("value",a);
					$("#trmpot_lama").attr("value",no_terima);
                    $('#pot_out').edatagrid('reload');
					lcstatus='edit'
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                }
            }
         });
        });
	//------
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
                    data: ({no:a,tabel:'trhstrpot',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && a!=no_bku){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || a==no_bku){
						alert("Nomor Bisa dipakai");
	//------
		$(function(){      
         $.ajax({
            type: 'POST',
            data: ({no_bukti:a,tgl_bukti:b,no_terima:no_terima,jns_spp:c,ket:d,kd_skpd:f,nm_skpd:e,npwp:g,nilai:h,nm_giat:i,nm_rek:j,alamat:k,dir:l,no_sp2d:m,kd_giat:n,kd_rek:o,rekanan:p,no_bku:no_bku,trmpot_lama:trmpot_lama}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/simpan_strpot_edit",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan');
					$("#no_simpan").attr("value",a);
					$("#trmpot_lama").attr("value",no_terima);
                    $('#pot_out').edatagrid('reload');
					lcstatus = 'edit';
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
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
		
		
		
		
		
		
        
        function dsimpan(rek,nama,nilai){		
		var bukti = document.getElementById('no_bukti').value;
        //alert(rek+nama+nilai);
        if(bukti !=''){
           $(function(){      
            $.ajax({
            type: 'POST',
            data: ({bukti:bukti,kd_rek5:rek,nm_rek5:nama,nilai:nilai}),
            dataType:"json",
            url:'<?php echo base_url(); ?>/index.php/tukd/psimpan_str'
            });
          });            
            } else {
                alert('Nomor Bukti Tidak Boleh kosong')
                document.getElementById('no_bukti').focus();
                exit();
            }

		} 
              

                  
         function hhapus(){				
            var nbukti = document.getElementById("no_simpan").value;            
			var no_terima = $("#trmpot").combogrid("getValue") ;

            var urll= '<?php echo base_url(); ?>/index.php/tukd/hapus_strpot';             			    
         	if (nbukti !=''){
				var del=confirm('Anda yakin akan menghapus Setor Potongan NO  '+nbukti+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:nbukti,no_terima:no_terima}),function(data){
                      status = data;            
                    });
                    });
				
				}
				} 
		}
        
        
        
     
        
        function load_sum_pot(){                
		var no_bukti = document.getElementById('no_bukti').value;              
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({bukti:no_bukti}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_str_pot",
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
     
     
     function tombol(st){  
     if (st=='1'){
     $('#save').linkbutton('disable');
     $('#del').linkbutton('disable');
     $('#poto').linkbutton('disable');       
     document.getElementById("p1").innerHTML="Sudah di CAIRKAN!!";
     } else {
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
     $('#poto').linkbutton('enable');     
     document.getElementById("p1").innerHTML="";
     }
    }
    
    function tombolnew(){  
    
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');   
    }
    
    function openWindow( url )
        {
      
        var no =kode.split("/").join("123456789");
       // alert(no);
        window.open(url+'/'+no+'/'+dns, '_blank');
        window.focus();
        }
    function cek(){
        var lcno = document.getElementById('no_bukti').value;
		var b = $('#dd').datebox('getValue');

        //alert(lcno);
            if(lcno !='' && b !=''){
               hsimpan();
               //detsimpan();               
            } else {
                alert('Nomor Kas atau Tanggal Tidak Boleh kosong')
                document.getElementById('no_bukti').focus();
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

<h3><a href="#" id="section1" onclick="javascript:$('#pot_out').edatagrid('reload')">List Setor Potongan</a></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>                       
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="pot_out" title="List " style="width:870px;height:450px;" >  
        </table>
                  
        
    </p> 
    </div>

<h3><a href="#" id="section2" onclick="javascript:$('#dg').edatagrid('reload')" >Input Setor Potongan</a></h3>
   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>
  <!-- <?php echo form_open('tukd/simpan', array('class' => 'basic')); ?> -->
               
<table border='0' style="font-size:11px" >
 <tr>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/></td>
				<td colspan = '2' style="border-bottom: double 1px red;border-top: double 1px red;">&nbsp;&nbsp;<i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr> 
 <tr>
   <td >No Bukti </td>
   <td><input type="text" name="no_bukti" id="no_bukti" onclick="javascript:select();" /></td>
   <td>Tanggal </td>
   <td><input id="dd" name="dd" type="text" /></td>
 </tr>
 <tr>
   <td >No Terima </td>
   <td><input type="text" name="trmpot" id="trmpot"  /> &nbsp; <input readonly="true" type="text" name="trmpot_lama" id="trmpot_lama" style="border:0" /></td>
   <td>Beban</td>
   <td><select name="beban" id="beban" >
     <option value="">...Pilih Jenis Beban... </option>
     <option value="1">UP</option>
     <option value="2">GU</option>
     <option value="3">TU</option>
     <option value="4">LS GAJI</option>
     <option value="5">LS PPKD</option>
     <option value="6">LS Barang Jasa</option>
   </select></td>
 </tr>
 <tr>
                <td>No. SP2D</td>
                <td colspan = "3"><input readonly="true" border="0" type="text" id="no_sp2d"   name="no_sp2d" style="width:200px;"/></td>
           </tr>
		   <tr>
                <td>Kd Kegiatan</td>
                <td colspan = "3"><input type="text" id="kd_giat" name="kd_giat" style="width:200px;"/>&nbsp;&nbsp;
				<input type="text" id="nm_giat" name="nm_giat" readonly="true" style="width:300px; border:0"/></td>
           </tr>
		   <tr>
                <td>Rekening</td>
                <td colspan = "3"><input type="text" id="kd_rek" name="kd_rek" style="width:200px;"/>&nbsp;&nbsp;
				<input type="text" id="nm_rek" name="nm_rek" readonly="true" style="width:300px; border:0"/></td>
           </tr>
		   
		   
		   <tr>
                <td>Rekanan </td>
                <td><input type="text" id="rekanan"   name="rekanan" style="width:200px;"/></td>
                <td>Pimpinan </td> 
				<td><input type="text" id="dir" name="dir" style="width:200px;"/></td>
           </tr>
		   
		   <tr>
            <td>ALamat Perusahaan</td>
                <td colspan='3'><textarea  id="alamat" style="width:600px; height: 30px;" /></textarea></td>
           </tr>
		   
		    <tr>
                <td>SKPD</td>
                <td colspan = "3"><input type="text" id="dn" name="dn" style="width:200px;"/>&nbsp;&nbsp;
				<input type="text" id="nmskpd" name="nmskpd" readonly="true" style="width:300px; border:0"/></td>
           </tr>
		   
		   <tr>
                <td>NPWP </td>
                <td colspan="3"><input type="text" id="npwp"   name="npwp" style="width:200px;"/></td>
                
           </tr>
 
  <tr>
            <td>Katerangan</td>
                <td colspan='3'><textarea  id="ketentuan" style="width:600px; height: 30px;" /></textarea></td>
           </tr>      
 
    <tr>
                <td colspan="4" align="right">
                <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:cek();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section1();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>                
                </td> <!--<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:();">cetak</a>-->               
            </tr>
    </table>
    
        <table id="pot" title="List Potongan" style="width:850px;height:150px;" >  
        </table><br/>
       
    <!-- <?php echo form_close(); ?> -->
    
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Nomor Terima: 
		<input class="right" id="trmpot1" name="trmpot1" type="hidden" readonly="true"  /></td>
		&nbsp;&nbsp;&nbsp;
		<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal"  style="width:140px" align="right" readonly="true" >
        <input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:140px" align="right" readonly="true" >
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
            <td><input id="ctrmpot" name="ctrmpot" style="width: 170px;" /></td>
        </tr>
        <tr>
            <td width="110px">Penandatangan:</td>
            <td><input id="ttd" name="ttd" style="width: 170px;" /></td>
        </tr>
       
    </table>  
    </fieldset>
    <a href="<?php echo site_url(); ?>/tukd/cetak_sp2d" class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false;">Cetak</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>
 	
</body>

</html>