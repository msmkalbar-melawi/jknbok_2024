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
    //var lcstatus = '';
                    
    $(document).ready(function() {
            $("#accordion").accordion({
            height: 500
            });
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal" ).dialog({
            height: 300,
            width: 700,
            modal: true,
            autoOpen:false
        });
        $( "#dialog-modal-rek" ).dialog({
            height: 320,
            width: 900,
            modal: true,
            autoOpen:false
        });
            $("#tagih").hide();
            get_skpd();
			get_tahun();

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
			
			$('#rek3').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/tukd/load_rek3_lamp_aset_input',  
                    idField:'kd_rek3',  
                    textField:'kd_rek3',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_rek3',title:'Kode',width:30},
                           {field:'nm_rek3',title:'Nama Rekening 3',width:150} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    kdrek3 = rowData.kd_rek3;
                    $("#nm_rek3").attr("value",rowData.nm_rek3);
					$("#rek5").combogrid("setValue",'');
					$("#nm_rek5").attr("Value",'');
					$("#rek6").combogrid("setValue",'');
					$("#nm_rek6").attr("Value",'');
					$('#rek5').combogrid({url:'<?php echo base_url(); ?>/index.php/tukd/load_rek5_lamp_aset/'+kdrek3,
					});
					validate_input(kdrek3);
                    }   
                });
			
			
			
			$('#rek5').combogrid({  
                panelWidth:500,  
                //url: '<?php echo base_url(); ?>/index.php/tukd/load_rek3_lamp_aset',  
                    idField:'kd_rek5',  
                    textField:'kd_rek5',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_rek5',title:'Kode',width:100},
                           {field:'nm_rek5',title:'Nama Rekening',width:300} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    kdrek5 = rowData.kd_rek5;
                    $("#nm_rek5").attr("value",rowData.nm_rek5);
					$("#rek6").combogrid("setValue",'');
					$("#nm_rek6").attr("Value",'');
					$('#rek6').combogrid({url:'<?php echo base_url(); ?>/index.php/tukd/load_rek6_lamp_aset/'+kdrek5,});
                    }   
                });
		
		
			$('#rek6').combogrid({  
                panelWidth:500,  
                //url: '<?php echo base_url(); ?>/index.php/tukd/load_rek3_lamp_aset',  
                    idField:'kd_rek6',  
                    textField:'kd_rek6',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_rek6',title:'Kode',width:100},
                           {field:'nm_rek6',title:'Nama Rekening',width:300} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //kdrek3 = rowData.kd_rek3;
                    $("#nm_rek6").attr("value",rowData.nm_rek6);
					//runEffect(kdrek3);
                    }   
                });
			  
                    $('#spp').edatagrid({
            		url: '<?php echo base_url(); ?>/index.php/tukd/load_lamp_aset',
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
                	    {field:'no_lamp',
                		title:'Nomor LAMP',
                		width:25},
                        {field:'nm_rek5',
                		title:'Rekening',
                		width:60},
						{field:'nm_rek6',
                		title:'Rek. Rinci',
                		width:60},
                        {field:'sal_awal',
                		title:'Saldo Awal',
                		width:40,
                        align:"left"},
                        {field:'keterangan',
                		title:'Keterangan',
                		width:50,
                        align:"left"}
                    ]],
                    onSelect:function(rowIndex,rowData){
                        no_lamp = rowData.no_lamp;
                        kd_rek3 = rowData.kd_rek3;
                        nm_rek3 = rowData.nmrek3;
                        kd_rek5 = rowData.kd_rek5;    
                        nm_rek5 = rowData.nm_rek5;
                        kd_rek6 = rowData.kd_rek6;
                        nm_rek6 = rowData.nm_rek6;
                        tahun = rowData.tahun;
                        bulan = rowData.bulan;
						merk = rowData.merk;
                        no_polisi = rowData.no_polisi;
                        fungsi = rowData.fungsi;
                        hukum = rowData.hukum;
                        lokasi = rowData.lokasi;
                        alamat = rowData.alamat;
                        sert = rowData.sert;
                        luas = rowData.luas;
                        jumlah = rowData.jumlah;
                        satuan = rowData.satuan;
                        harga_satuan = rowData.harga_satuan;
                        piutang_awal = rowData.piutang_awal;
                        piutang_koreksi = rowData.piutang_koreksi;
                        piutang_sudah = rowData.piutang_sudah;
                        investasi_awal = rowData.investasi_awal;
                        sal_awal = rowData.sal_awal;
                        kurang = rowData.kurang;
                        tambah = rowData.tambah;
                        tahun_n = rowData.tahun_n;
                        kondisi_b = rowData.kondisi_b;     
                        kondisi_rr = rowData.kondisi_rr;     
                        kondisi_rb = rowData.kondisi_rb;     
                        keterangan = rowData.keterangan;
                        rincian_beban = rowData.rincian_beban;
                        no_polis = rowData.no_polis;
                        kepemilikan = rowData.kepemilikan;
                      get(no_lamp,kd_rek3,nm_rek3,kd_rek5,nm_rek5,kd_rek6,nm_rek6,tahun,bulan,merk,no_polisi,fungsi,hukum,lokasi,alamat,sert,luas,satuan,harga_satuan,piutang_awal,piutang_koreksi,piutang_sudah,investasi_awal,sal_awal,kurang,tambah,tahun_n,kondisi_b,kondisi_rr,kondisi_rb,keterangan,jumlah,no_polis,rincian_beban,kepemilikan);
                      validate_input(kd_rek3) ;
                      status_input = 'edit';
                    },
                    onDblClickRow:function(rowIndex,rowData){
                        section2();   
                    }
                });
                
                $('#lamp_aset').edatagrid({
            		url: '<?php echo base_url(); ?>/index.php/tukd/load_lamp_aset_all',
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
                	    {field:'no_lamp',
                		title:'Nomor LAMP',
                		width:25},
                        {field:'nm_rek5',
                		title:'Rekening',
                		width:60},
                        {field:'sal_awal',
                		title:'Saldo Awal',
                		width:40,
                        align:"left"},
                        {field:'keterangan',
                		title:'Keterangan',
                		width:50,
                        align:"left"}
                    ]],
                    onSelect:function(rowIndex,rowData){
                        no_lamp = rowData.no_lamp;
                        kd_rek3 = rowData.kd_rek3;
                        nm_rek3 = rowData.nmrek3;
                        kd_rek5 = rowData.kd_rek5;    
                        nm_rek5 = rowData.nm_rek5;
                        kd_rek6 = rowData.kd_rek6;
                        nm_rek6 = rowData.nm_rek6;
                        tahun = rowData.tahun;
						bulan = rowData.bulan;
                        merk = rowData.merk;
                        no_polisi = rowData.no_polisi;
                        fungsi = rowData.fungsi;
                        hukum = rowData.hukum;
                        lokasi = rowData.lokasi;
                        alamat = rowData.alamat;
                        sert = rowData.sert;
                        luas = rowData.luas;
                        jumlah = rowData.jumlah;
                        satuan = rowData.satuan;
                        harga_satuan = rowData.harga_satuan;
                        piutang_awal = rowData.piutang_awal;
                        piutang_koreksi = rowData.piutang_koreksi;
                        piutang_sudah = rowData.piutang_sudah;
                        investasi_awal = rowData.investasi_awal;
                        sal_awal = rowData.sal_awal;
                        kurang = rowData.kurang;
                        tambah = rowData.tambah;
                        tahun_n = rowData.tahun_n;
                        kondisi_b = rowData.kondisi_b;     
                        kondisi_rr = rowData.kondisi_rr;     
                        kondisi_rb = rowData.kondisi_rb;     
                        keterangan = rowData.keterangan;
                        rincian_beban = rowData.rincian_beban;
                        no_polis = rowData.no_polis;
                        kepemilikan = rowData.kepemilikan;
                      get(no_lamp,kd_rek3,nm_rek3,kd_rek5,nm_rek5,kd_rek6,nm_rek6,tahun,bulan,merk,no_polisi,fungsi,hukum,lokasi,alamat,sert,luas,satuan,harga_satuan,piutang_awal,piutang_koreksi,piutang_sudah,investasi_awal,sal_awal,kurang,tambah,tahun_n,kondisi_b,kondisi_rr,kondisi_rb,keterangan,jumlah,no_polis,rincian_beban,kepemilikan);
                      validate_input(kd_rek3) ;
                      status_input = 'edit';
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
                    validate_kegi(spd);                                                        
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
                    {field:'nilai1',title:'Nilai',width:140,align:'right'},
                    {field:'hapus',title:'Hapus',width:100,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
				]]	
           }); 
            
           
           $('#rek_skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/skpd_2',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd ;               
               $("#rek_nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
           }  
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
        
        function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/no_urut_lamp_neraca',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#nomor").attr("value",data.no_urut);
        							  }                                     
        	});  
        }
        function get_skpd()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
  								      $("#dn").attr("value",data.kd_skpd);
        							  $("#nmskpd").attr("value",data.nm_skpd);
                                      $("#rek_skpd").combogrid("setValue",data.kd_skpd);
                                      $("#rek_nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
                                      kode = data.kd_skpd;
                                      //validate_spd(kode);
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
        
    
     function get(no_lamp,kd_rek3,nm_rek3,kd_rek5,nm_rek5,kd_rek6,nm_rek6,tahun,bulan,merk,no_polisi,fungsi,hukum,lokasi,alamat,sert,luas,satuan,harga_satuan,piutang_awal,piutang_koreksi,piutang_sudah,investasi_awal,sal_awal,kurang,tambah,tahun_n,kondisi_b,kondisi_rr,kondisi_rb,keterangan,jumlah,no_polis,rincian_beban,kepemilikan){
		$("#nomor").attr("value",no_lamp);
		$("#no_simpan").attr("value",no_lamp);
        $("#rek3").combogrid("setValue",kd_rek3);
        $("#nm_rek3").attr("Value",nm_rek3);
		$("#rek5").combogrid("setValue",kd_rek5);
        $("#nm_rek5").attr("Value",nm_rek5);
		$("#rek6").combogrid("setValue",kd_rek6);
        $("#nm_rek6").attr("Value",nm_rek6);
		$("#tahun").combobox("setValue",tahun);
		$("#bulan").combobox("setValue",bulan);
        $("#merk").attr("Value",merk);
        $("#no_polisi").attr("Value",no_polisi);
        $("#fungsi").attr("Value",fungsi);
        $("#hukum").attr("Value",hukum);
		$("#lokasi").combobox("setValue",lokasi);
        $("#alamat").attr("Value",alamat);
        $("#sert").attr("Value",sert);
        $("#luas").attr("Value",luas);
        $("#satuan").attr("Value",satuan);
        $("#harga_satuan").attr("Value",harga_satuan);
        $("#piutang_awal").attr("Value",piutang_awal);
        $("#piutang_koreksi").attr("setValue",piutang_koreksi);
        $("#piutang_sudah").attr("Value",piutang_sudah);
        $("#sal_awal").attr("Value",sal_awal);
        $("#investasi_awal").attr("Value",investasi_awal);
        $("#kurang").attr("Value",kurang);
        $("#tambah").attr("Value",tambah);
        $("#tahun_n").attr("Value",tahun_n);
        $("#kondisi_b").attr("Value",kondisi_b);
        $("#kondisi_rr").attr("Value",kondisi_rr);
        $("#kondisi_rb").attr("Value",kondisi_rb);
        $("#keterangan").attr("Value",keterangan);
        $("#jumlah").attr("Value",jumlah);
        $("#milik").attr("Value",kepemilikan);
        $("#rincian_bebas").attr("Value",rincian_beban);
        $("#no_polis").attr("Value",no_polis);
        $("#harga_awal").attr("Value",'');

                 
        }
    
    function kosong(){
        status_input = "tambah";
        $("#nomor").attr("value",'');
		$("#no_simpan").attr("value",'');
        $("#rek3").combogrid("setValue",'');
        $("#nm_rek3").attr("Value",'');
		$("#rek5").combogrid("setValue",'');
        $("#nm_rek5").attr("Value",'');
		$("#rek6").combogrid("setValue",'');
        $("#nm_rek6").attr("Value",'');
		$("#tahun").combobox("setValue",'');
		$("#bulan").combobox("setValue",'');
        $("#merk").attr("Value",'');
        $("#no_polisi").attr("Value",'');
        $("#fungsi").attr("Value",'');
        $("#hukum").attr("Value",'');
        $("#lokasi").attr("Value",'');
        $("#alamat").attr("Value",'');
        $("#sert").attr("Value",'');
        $("#luas").attr("Value",'');
        $("#harga_awal").attr("Value",'');
        $("#satuan").attr("Value",'');
        $("#harga_satuan").attr("Value",'');
        $("#piutang_awal").attr("Value",'');
        $("#piutang_koreksi").attr("setValue",'');
        $("#piutang_sudah").attr("Value",'');
        $("#sal_awal").attr("Value",'');
        $("#investasi_awal").attr("Value",'');
        $("#kurang").attr("Value",'');
        $("#tambah").attr("Value",'');
        $("#tahun_n").attr("Value",'');
        $("#kondisi_b").attr("Value",'');
        $("#kondisi_rr").attr("Value",'');
        $("#kondisi_rb").attr("Value",'');
        $("#keterangan").attr("Value",'');
		$("#milik").attr("Value",'');
        $("#rincian_bebas").attr("Value",'');
        $("#no_polis").attr("Value",'');
        $("#jumlah").attr("Value",'');
		get_nourut();
        }


	
    function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
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
    }     
    
    
    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#spp').edatagrid({
	       url: '<?php echo base_url(); ?>/index.php/tukd/load_lamp_aset',
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
     
     
    function section3(){
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
    }

     
    function hsimpan(){        
        var a       = document.getElementById('nomor').value;
        var a_hide  = document.getElementById('no_simpan').value;
        var b       = $("#rek3").combogrid("getValue") ;     
        var c       = document.getElementById('nm_rek3').value; 
        var d       = $("#rek5").combogrid("getValue") ; 
        var e       = document.getElementById('nm_rek5').value;
        var f       = $("#rek6").combogrid("getValue") ; 
        var g       = document.getElementById('nm_rek6').value;
        var h       = $("#tahun").combobox("getValue") ;
		var h_b     = $("#bulan").combobox("getValue") ;
        var i       = document.getElementById('merk').value;
        var j       = document.getElementById('no_polisi').value;
        var k       = document.getElementById('fungsi').value;
        var l       = document.getElementById('hukum').value;
        var m       = $("#lokasi").combobox("getValue") ;
        var n       = document.getElementById('alamat').value;
		var o       = document.getElementById('sert').value;
        var p       = document.getElementById('luas').value;
        var q       = document.getElementById('satuan').value;
        var r       = document.getElementById('harga_satuan').value;
        var s       = document.getElementById('piutang_awal').value;
        var t       = document.getElementById('piutang_koreksi').value;
		var u       = document.getElementById('piutang_sudah').value;
        var v       = document.getElementById('investasi_awal').value;
        var w       = document.getElementById('sal_awal').value;
        var x       = document.getElementById('kurang').value;
        var y       = document.getElementById('tambah').value;
        var z       = document.getElementById('tahun_n').value;
		var aa       = 0;
        var bb       = document.getElementById('kondisi_b').value;
        var cc       = document.getElementById('kondisi_rr').value;
        var dd       = document.getElementById('kondisi_rb').value;
        var ee       = document.getElementById('keterangan').value;
        var ff       = document.getElementById('dn').value;
        var gg       = document.getElementById('jumlah').value;
        var hh       = document.getElementById('milik').value;
        var ii       = document.getElementById('rincian_bebas').value;
        var jj       = document.getElementById('no_polis').value;

		if (p==''){
			p=0;
		}else{
			p=angka(p);
		}
		
		if (r==''){
			r=0;
		}else{
			r=angka(r);
		}
        
		if (s==''){
			s=0;
		}else{
			s=angka(s);
		}
		if (t==''){
			t=0;
		}else{
			t=angka(t);
		}
		if (u==''){
			u=0;
		}else{
			u=angka(u);
		}
		if (v==''){
			v=0;
		}else{
			v=angka(v);
		}
		if (w==''){
			w=0;
		}else{
			w=angka(w);
		}
		if (x==''){
			x=0;
		}else{
			x=angka(x);
		}
		
		if (y==''){
			y=0;
		}else{
			y=angka(y);
		}
		
		if (z==''){
			z=0;
		}else{
			z=angka(z);
		}
		if (gg==''){
			gg=0;
		}else{
			gg=angka(gg);
		}
		
        if ( a == '' ){
            alert("Isi Nomor Terlebih Dahulu") ;
            exit();
        }
		 if ( h == '' ){
            alert("Isi Tahun Terlebih Dahulu") ;
            exit();
        }
        
		if ( h_b == '' ){
            h_b=0;
        }else{
			h_b=angka(h_b);
		}
	   
		if(status_input == "tambah"){
			$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:"lamp_aset",field:"no_lamp"}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
		
		//---------
            lcinsert = "(no_lamp, kd_rek3, nm_rek3, kd_rek5, nm_rek5, kd_rek6, nm_rek6, tahun, bulan, merk, no_polisi, fungsi, hukum, lokasi, alamat, sert, luas, satuan, harga_satuan, piutang_awal, piutang_koreksi, piutang_sudah, investasi_awal, sal_awal, kurang, tambah, tahun_n, akhir, kondisi_b, kondisi_rr, kondisi_rb, keterangan,kd_skpd,jumlah,kepemilikan,rincian_beban,no_polis)"; 
			lcvalues = "('"+a+"', '"+b+"', '"+c+"', '"+d+"', '"+e+"', '"+f+"', '"+g+"','"+h+"','"+h_b+"','"+i+"','"+j+"','"+k+"','"+l+"','"+m+"','"+n+"','"+o+"',"+p+",'"+q+"',"+r+",     "+s+",        "+t+" ,    "+u+",       "+v+",      "+w+", "+x+" ,"+y+", "+z+",'"+aa+"','"+bb+"','"+cc+"',     '"+dd+"', '"+ee+"', '"+ff+"', "+gg+", '"+hh+"', '"+ii+"', '"+jj+"')";
			$(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_lamp_aset',
                    data     : ({tabel:'lamp_aset',kolom:lcinsert,nilai:lcvalues,cid:'no_lamp',lcid:a}),
                    dataType : "json",
                    success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        } else if(status=='1'){
                                  alert('Data Sudah Ada..!!');
                                  exit();
                               } else {
                                  alert('Data Tersimpan..!!');
								  $("#no_simpan").attr("value",a);
                                  status_input = 'edit';
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
		
        
            
        } else {
//alert(z);
			$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'lamp_aset',field:'no_lamp'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && a!=a_hide){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || a==a_hide){
						alert("Nomor Bisa dipakai");
			
			
		//---------
		lcquery = " UPDATE lamp_aset SET no_lamp ='"+a+"', kd_rek3='"+b+"', nm_rek3='"+c+"', kd_rek5='"+d+"', nm_rek5='"+e+"', kd_rek6='"+f+"', nm_rek6='"+g+"', tahun='"+h+"', bulan='"+h_b+"', merk='"+i+"', no_polisi='"+j+"', fungsi='"+k+"', hukum='"+l+"', lokasi='"+m+"', alamat='"+n+"', sert='"+o+"', luas='"+p+"', satuan='"+q+"', harga_satuan='"+r+"', piutang_awal='"+s+"', piutang_koreksi='"+t+"', piutang_sudah='"+u+"', investasi_awal='"+v+"', sal_awal='"+w+"', kurang='"+x+"', tambah='"+y+"', tahun_n='"+z+"', kondisi_b='"+bb+"', kondisi_rr='"+cc+"', kondisi_rb='"+dd+"', keterangan='"+ee+"',kd_skpd ='"+ff+"',jumlah ='"+gg+"',kepemilikan ='"+hh+"',rincian_beban ='"+ii+"',no_polis ='"+jj+"' where no_lamp='"+a_hide+"' AND kd_skpd ='"+ff+"' "; 

//			alert(lcquery);
//exit();
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/update_tukd',
                data     : ({st_query:lcquery,tabel:'trhspp',cid:'no_spp',lcid:a,lcid_h:a_hide}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                                                		
                        if ( status=='1' ){
							//alert("aaaa");
                            alert('Nomor  Sudah Terpakai...!!!,  Ganti Nomor ...!!!');
                            exit();
                        }
                        
                        if ( status=='2' ){
							alert('Data Tersimpan...!!!');
                            status_input = 'edit';
							$("#no_simpan").attr("value",a);
                            exit();
                        }
                        
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
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
    
  
    function hapus(){				
                var spp = document.getElementById("no_simpan").value;                
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
            
            var nomor = document.getElementById("no_simpan").value;  
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hhapus_lamp';             			    
         	if (spp !=''){
				var del=confirm('Anda yakin akan menghapus ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:nomor}),function(data){
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

    
    
    function tombol(st){ 
    if (st==1){
    $('#save').linkbutton('disable');
    $('#del').linkbutton('disable');
    document.getElementById("p1").innerHTML="Sudah di Buat SPM...!!!";
    } else {
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
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

        window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_1+'/'+ttd_2+'/'+ttd_4+'/'+tanpa, '_blank');
        window.focus();
        }
    

	function openWindow2( url )
        {
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
        window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_3+'/'+tanpa, '_blank');
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
			$("#npwp").attr('disabled',true);
			$("#tgl_mulai").datebox('disable');
			$("#tgl_akhir").datebox('disable');
			$("#rekanan").combogrid('disable');
			$("#dir").attr('disabled',true);
			$("#alamat").attr('disabled',true);
			$("#kontrak").attr('disabled',true);
			$("#bank1").combogrid('disable');
			$("#rekening").attr('disabled',true);
		
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
			$("#npwp").attr('disabled',true);
			$("#tgl_mulai").datebox('disable');
			$("#tgl_akhir").datebox('disable');
			$("#rekanan").combogrid('disable');
			$("#dir").attr('disabled',true);
			$("#alamat").attr('disabled',true);
			$("#kontrak").attr('disabled',true);
			$("#bank1").combogrid('disable');
			$("#rekening").attr('disabled',true);
		
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
			$("#npwp").attr('disabled',true);
			$("#tgl_mulai").datebox('disable');
			$("#tgl_akhir").datebox('disable');
			$("#rekanan").combogrid('disable');
			$("#dir").attr('disabled',true);
			$("#alamat").attr('disabled',true);
			$("#kontrak").attr('disabled',true);
			$("#bank1").combogrid('disable');
			$("#rekening").attr('disabled',true);
		}
    }
	
	 function validate_input(kdrek3){
		//var options = {}; 
		if (kdrek3==131){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',false);
			$("#lokasi0").attr('hidden',false);
			$("#alamat1").attr('hidden',false);
			$("#alamat0").attr('hidden',false);
			$("#sert1").attr('hidden',false);
			$("#sert0").attr('hidden',false);
			$("#luas1").attr('hidden',false);
			$("#luas0").attr('hidden',false);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
	
		} else if ((kdrek3==151) || (kdrek3==152) || (kdrek3==153) || (kdrek3==154)){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',false);
			$("#merk0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',false);
			$("#no_polisi0").attr('hidden',false);
			$("#fungsi1").attr('hidden',false);
			$("#fungsi0").attr('hidden',false);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',false);
			$("#lokasi0").attr('hidden',false);
			$("#alamat1").attr('hidden',false);
			$("#alamat0").attr('hidden',false);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',false);
			$("#luas0").attr('hidden',false);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);

		}else if (kdrek3==141){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',false);
			$("#hukum0").attr('hidden',false);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
			
		}else if (kdrek3==136) {
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',false);
			$("#fungsi0").attr('hidden',false);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',false);
			$("#lokasi0").attr('hidden',false);
			$("#alamat1").attr('hidden',false);
			$("#alamat0").attr('hidden',false);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#luas1").attr('hidden',false);
			$("#luas0").attr('hidden',false);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
			
		} else if (kdrek3==135) {
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',false);
			$("#merk0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);	
			
		} else if ((kdrek3==134) || (kdrek3==133)) {
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',false);
			$("#fungsi0").attr('hidden',false);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',false);
			$("#lokasi0").attr('hidden',false);
			$("#alamat1").attr('hidden',false);
			$("#alamat0").attr('hidden',false);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#luas1").attr('hidden',false);
			$("#luas0").attr('hidden',false);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
		} else if(kdrek3==132){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',false);
			$("#merk0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',false);
			$("#no_polisi0").attr('hidden',false);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
			} else if(kdrek3==313){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',true);
			$("#jumlah0").attr('hidden',true);
			$("#harga_satuan1").attr('hidden',true);
			$("#harga_satuan0").attr('hidden',true);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',true);
			$("#tahun_n0").attr('hidden',true);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',true);
			$("#keterangan0").attr('hidden',true);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',true);
			$("#harga_awal1").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
		}else if(kdrek3==117){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',false);
			$("#merk0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
		} else if (kdrek3==116){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polis1").attr('hidden',false);
			$("#no_polis0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',false);
			$("#rincian_bebas0").attr('hidden',false);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',false);
			$("#no_polis").attr('hidden',false);

			
		} else if(kdrek3 ==114){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',false);
			$("#bulan_oleh0").attr('hidden',false);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',false);
			$("#piutang_awal0").attr('hidden',false);
			$("#piutang_koreksi1").attr('hidden',false);
			$("#piutang_koreksi0").attr('hidden',false);
			$("#piutang_sudah1").attr('hidden',false);
			$("#piutang_sudah0").attr('hidden',false);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);

			
		} else if (kdrek3 == 113){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',false);
			$("#bulan_oleh0").attr('hidden',false);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',false);
			$("#lokasi0").attr('hidden',false);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',false);
			$("#piutang_awal0").attr('hidden',false);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);

			
		} else if (kdrek3==111){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);

		} else if (kdrek3==112){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#milik1").attr('hidden',false);
			$("#milik0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',false);
			$("#hukum0").attr('hidden',false);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',false);
			$("#investasi_awal0").attr('hidden',false);
			$("#sal_awal1").attr('hidden',false);
			$("#sal_awal0").attr('hidden',false);
			$("#kurang1").attr('hidden',false);
			$("#kurang0").attr('hidden',false);
			$("#tambah1").attr('hidden',false);
			$("#tambah0").attr('hidden',false);
			$("#tahun_n1").attr('hidden',true);
			$("#tahun_n0").attr('hidden',true);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);

		}
		else {
			alert("Belum ada form input");
			$("#tahun_oleh1").attr('hidden',true);
			$("#tahun_oleh0").attr('hidden',true);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#no_polis1").attr('hidden',true);
			$("#no_polis0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',true);
			$("#jumlah0").attr('hidden',true);
			$("#harga_satuan1").attr('hidden',true);
			$("#harga_satuan0").attr('hidden',true);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',true);
			$("#tahun_n0").attr('hidden',true);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',true);
			$("#keterangan0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',true);
			$("#harga_awal1").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
		}
	}
	
	
    function runEffect(kdrek3) {
        var selectedEffect = 'explode';            
        var options = {}; 
		if (kdrek3 == 131){
        $( "#tahun_oleh" ).toggle( selectedEffect, options, 500 );
		$( "#bulan_oleh" ).toggle( selectedEffect, options, 500 );
        $("#notagih").combogrid("setValue",'');
        $("#tgltagih").attr("value",'');
        $("#nmskpd").attr("value",'');
        $("#nil").attr("value",'');
        $("#ni").attr("value",'');}
		else {
			alert("Belum ada Form Input");
			exit();
		}
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
					 width:100,
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
					 width:280
					 },
                    {field:'nilai1',
					 title:'Nilai',
					 width:140,
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
					 width:100,
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
					 width:280
					 },
                    {field:'nilai1',
					 title:'Nilai',
					 width:140,
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
            
           var cek_kegi = $("#kg").combogrid('getValue') ;
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
           
           $("#rek_nilai").attr("Value",0);
           $("#rek_nilai_ang").attr("Value",0);
           $("#rek_nilai_spp").attr("Value",0);
           $("#rek_nilai_sisa").attr("Value",0);
        
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
            var cnilai    = cnil;      
            
            
            var cnil_sisa   = angka(document.getElementById('rek_nilai_sisa').value) ;
            var cnil_sisa_spd   = angka(document.getElementById('nilai_sisa_spd').value) ;
            var cnil_input  = angka(document.getElementById('rek_nilai').value) ;
            
            if ( cnil_input > cnil_sisa ){
                 alert('Nilai Melebihi Sisa Anggaran...!!!, Cek Lagi...!!!') ;
                 exit();
            }
			 if ( cnil_input > cnil_sisa_spd){
                 alert('Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( cnil_input == 0 ){
                 alert('Nilai Nol.....!!!, Cek Lagi...!!!') ;
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

            $('#dgsppls').edatagrid('appendRow',{kdkegiatan:vrek_kegi,kdrek5:vrek_reke,nmrek5:vnm_rek_reke,nilai1:cnilai,idx:pidx});
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
    
	function formatangka(objek) {
		a = objek.value;
		b = a.replace(/\$|\,/g,"");
		c = "";
		panjang = b.length;
		j = 1;
		for (i = panjang; i > 0; i--) {
		j = j + 1;
		if (((j % 3) == 1) && (j != 1))
		{c = b.substr(i-1,1) + "," + c;} 
		else 
		{c = b.substr(i-1,1) + c;}
		}
		//objek.value = trimNumber(c);
				return c;

	}
	function replaceChars(entry) {
		out = "."; // replace this
		add = ""; // with this
		temp = "" + entry; // temporary holder
		while (temp.indexOf(out)>-1) {
		pos= temp.indexOf(out);
		temp = "" + (temp.substring(0, pos) + add + 
		temp.substring((pos + out.length), temp.length));
		}
		document.f.uang.value = temp;
	}

	function trimNumber(s) {
		decimal=false;
		while (s.substr(0,1) == '0' && s.length>1) { s = s.substr(1,9999); }
		while (s.substr(0,1) == '.' && s.length>1) { s = s.substr(1,9999); }
		return s;
	}
	
	function hitung_saldo_awal(){
		
        var jumlah = document.getElementById('jumlah').value;
		if(jumlah==''){
			jumlah=0;
		} else {
			jumlah=jumlah;
		}
		var jum=angka(jumlah);
		
        var harga_satuan = document.getElementById('harga_satuan').value;
		if(harga_satuan==''){
			harga_satuan=0;
		} else {
			harga_satuan=harga_satuan;
		}
		var hrg_satuan=angka(harga_satuan);
        saldo_awal=jum*hrg_satuan;
		$("#harga_awal").attr("Value",number_format(saldo_awal,2,'.',','))		
        
     }
	 
	 function hitung_piutang_koreksi(){
		
        var piutang_awal = document.getElementById('piutang_awal').value;
		if(piutang_awal==''){
			piutang_awal=0;
		} else {
			piutang_awal=piutang_awal;
		}
		var piutang=angka(piutang_awal);
		
        var piutang_koreksi = document.getElementById('piutang_koreksi').value;
		if(piutang_koreksi==''){
			piutang_koreksi=0;
		} else {
			piutang_koreksi=piutang_koreksi;
		}
		var koreksi=angka(piutang_koreksi);
        piutang_sesudah=piutang-koreksi;
		$("#piutang_sudah").attr("Value",number_format(piutang_sesudah,2,'.',','))		
        
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
<h3><a href="#" id="section1" onclick="javascript:$('#spp').edatagrid('reload')">List Lamp. Neraca</a></h3>
    
    <div style="height:350px;">
    <p align="right">   
		<a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:section3();">Lihat Data Keseluruhan</a>
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>
   <!---     <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>    --->          
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="spp" title="List Lamp. Aset" style="width:870px;height:650px;" >  
        </table>
    </p> 
    </div>

<h3><a href="#" id="section2">Input Lampiran Neraca</a></h3>
   
   <div  style="height:350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>

   <fieldset style="width:850px;height:950px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">            
   <table border='1' style="font-size:11px"  >
  <tr>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true;"/></td>
				<td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;">&nbsp;&nbsp;</td>
				<td style="border-bottom: double 1px red;border-top: double 1px red;" colspan = "2"><i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr> 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
 </tr>  

 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">SKPD</td>
   <td colspan ="3" width="53%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >     
      &nbsp;<input id="dn" name="dn"  readonly="true" style="width:130px; border: 0;"/> &nbsp;&nbsp;&nbsp; <input id="nmskpd" name="nmskpd"  readonly="true" style="width:300px; border: 0; " /> </td> 
 </tr>

 
<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Nomor Lamp.</td>
     <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > <input id="nomor" name="nomor" style="width: 150px;" > 

 </tr> 

  <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rek. Kelompok</td>
     <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > <input id="rek3" name="rek3" style="width: 190px;" > 
   &nbsp;&nbsp;&nbsp; <input id="nm_rek3" name="nm_rek3"  readonly="true" style="width:300px; border: 0;"/> </td> 
 </tr> 
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening</td>
     <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > <input id="rek5" name="rek5" style="width: 190px;" > 
   &nbsp;&nbsp;&nbsp; <input id="nm_rek5" name="nm_rek5"  readonly="true" style="width:300px; border: 0;"/> </td> 
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening Rinci</td>
     <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > <input id="rek6" name="rek6" style="width: 190px;" > 
   &nbsp;&nbsp;&nbsp; <input id="nm_rek6" name="nm_rek6"  readonly="true" style="width:300px; border: 0;"/> </td> 
 </tr>
 

 <tr style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;">
 
                <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;" colspan="5">
                    <div>
                        <table>
                            <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "tahun_oleh1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Tahun Perolehan</td>
                                <td id = "tahun_oleh0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"> <?php $thang =  date("Y");
																																								$thang_maks = $thang + 3 ;
																																								$thang_min = $thang - 15 ;
																																								echo '<select id="tahun" class="easyui-combobox" name="tahun" style="width:140px;">';
																																								echo "<option value=''> Pilih Tahun</option>";
																																								for ($th=$thang_min ; $th<=$thang_maks ; $th++)
																																								{
																																									echo "<option value=$th>$th</option>";
																																								}
																																								echo '</select>';?>																																				
																																								</td>
								<td id = "bulan_oleh1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Bulan Perolehan</td>
                                <td id = "bulan_oleh0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><select name="bulan" id="bulan" class="easyui-combobox" style=" width:140px;">
																																							 <option value=""> Pilih Bulan </option>     
																																							 <option value="1"> Januari</option>
																																							 <option value="2"> Februari</option>
																																							 <option value="3"> Maret</option>
																																							 <option value="4"> April</option>
																																							 <option value="5"> Mei</option>
																																							 <option value="6"> Juni</option>
																																							 <option value="7"> Juli</option>
																																							 <option value="8"> Agustus</option>
																																							 <option value="9"> September</option>
																																							 <option value="10"> Oktober</option>
																																							 <option value="11"> November</option>
																																							 <option value="12"> Desember</option>
																																						   </td>
                                <td id = "merk1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Merk/Type</td>
                                <td id = "merk0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="merk" style="width: 140px;" /></td>
							</tr>
							 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "no_polis1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Nama & No. Polis Asuransi</td>
                                <td id = "no_polis0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="no_polis"/></td>
                               </tr>
							 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "no_polisi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">No. Polisi</td>
                                <td id = "no_polisi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="no_polisi"/></td>
                                <td id = "fungsi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Fungsi</td>
                                <td id = "fungsi0"style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="fungsi" style="width: 140px;" /></td>
							</tr>
							 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "hukum1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Dasar Hukum</td>
                                <td id = "hukum0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="hukum"/></td>
                                <td id = "lokasi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Lokasi</td>
                                <td id = "lokasi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><select name="lokasi" id="lokasi" class="easyui-combobox" style=" width:200px;">
																																							 <option value=""> Pilih Lokasi </option>     
																																							 <option value="Kota Pontianak"> Kota Pontianak</option>
																																							 <option value="Kota Singkawang"> Kota Singkawang</option>
																																							 <option value="Kabupaten Mempawah"> Kabupaten Mempawah</option>
																																							 <option value="Kabupaten Sanggau"> Kabupaten Sanggau</option>
																																							 <option value="Kabupaten Sintang"> Kabupaten Sintang</option>
																																							 <option value="Kabupaten Kapuas Hulu"> Kabupaten Kapuas Hulu</option>
																																							 <option value="Kabupaten Ketapang"> Kabupaten Ketapang</option>
																																							 <option value="Kabupaten Landak"> Kabupaten Landak</option>
																																							 <option value="Kabupaten Bengkayang"> Kabupaten Bengkayang</option>
																																							 <option value="Kabupaten Sambas"> Kabupaten Sambas</option>
																																							 <option value="Kabupaten Sekadau"> Kabupaten Sekadau</option>
																																							 <option value="Kabupaten Melawi"> Kabupaten Melawi</option>
																																							 <option value="Kabupaten Kayong Utara"> Kabupaten Kayong Utara</option>
																																							 <option value="Kabupaten Kubu Raya"> Kabupaten Kubu Raya</option>
																																							 <option value="Lain-lain"> Lain-lain</option>
																																						   </td>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "alamat1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">ALamat</td>
                                <td id = "alamat0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="alamat" style="width: 140px;" /></td>   
								<td id = "sert1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">No. Sertifikat</td>
                                <td id = "sert0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="sert" style="width: 140px;" /></td>   
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "jumlah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jumlah</td>
                                <td id = "jumlah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="number" id="jumlah"  style="width: 140px;text-align: right;" onkeyup="formatangka(this);" /></td>   
								<td id = "luas1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Luas</td>
                                <td id = "luas0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="luas"  style="width: 140px;text-align: right;" onkeyup="formatangka(this);replaceChars(document.nilai.a.value);" /></td>   
								</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "harga_satuan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Harga Satuan</td>
                                <td id = "harga_satuan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="harga_satuan"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_saldo_awal();" /></td>   
								<td id = "satuan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Satuan</td>
                                <td id = "satuan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="satuan" style="width: 140px;" /></td>   
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
								<td id = "rincian_bebas1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Rincian Beban</td>
                                <td id = "rincian_bebas0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="rincian_bebas" style="width: 140px;" /></td>   
								<td id = "piutang_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Piutang Awal</td>
                                <td id = "piutang_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="piutang_awal"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td> 
							</tr>
							
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "piutang_koreksi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Piutang Koreksi</td>
                                <td id = "piutang_koreksi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="piutang_koreksi"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_piutang_koreksi();"/> </td>  
								<td id = "piutang_sudah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Piutang Setelah Koreksi</td>
                                <td id = "piutang_sudah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="piutang_sudah"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
								<td id = "milik1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kepemilikan (%)</td>
                                <td id = "milik0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="milik"  style="width: 140px;text-align: right;" /></td>
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "harga_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jumlah x Harga Satuan</td>
                                <td id = "harga_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="harga_awal" style="width: 140px;text-align: right; background-color:yellow;" readonly="true;"  /></td>   
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "investasi_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Investasi Awal</td>
                                <td id = "investasi_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="investasi_awal"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
								<td id = "sal_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Saldo Awal</td>
                                <td id = "sal_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="sal_awal" style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"  /></td>   
							</tr>
							
							
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "kurang1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Berkurang</td>
                                <td id = "kurang0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="kurang"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
								<td id = "tambah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Bertambah</td>
                                <td id = "tambah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="tambah"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
								</tr>																														
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "tahun_n1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Dana/Pengadaan Tahun </td>
                                <td id = "tahun_n0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="tahun_n"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
								<td id = "akhir1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Saldo Akhir</td>
                                <td id = "akhir0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="akhir" style="width: 140px;" onkeypress="return(currencyFormat(this,',','.',event))"  /></td>   
								</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "kondisi_b1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi B </td>
                                <td id = "kondisi_b0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="kondisi_b" style="width: 140px;" /></td>   
								<td id = "kondisi_rr1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi RR</td>
                                <td id = "kondisi_rr0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="kondisi_rr" style="width: 140px;" /></td>   
								</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "kondisi_rb1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi RB</td>
                                <td id = "kondisi_rb0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="kondisi_rb" style="width: 140px;" /></td>   
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "keterangan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Keterangan</td>
                                <td colspan ="3" id = "keterangan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><textarea name="keterangan" id="keterangan" cols="30" rows="1" ></textarea></td>   
								</tr>
                        </table> 
                    </div>
                
                </td>                
   </tr>
 
     <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
     </tr>  

       <tr style="border-spacing: 3px;padding:3px 3px 3px 3px;">
                <td colspan="4" align='right' style="border-bottom-color:black;border-spacing: 3px;padding:3px 3px 3px 3px;" >
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true"  onclick="javascript:hsimpan();">Simpan</a>
                <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section1();">Hapus</a>
                <!--<a id="det" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:detail();">Detail</a>-->
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>
                <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a></td>              
       </tr>
</table>

    
       
   </div>
  
   <h3><a href="#" id="section3" onclick="javascript:$('#lamp_aset').edatagrid('reload')">List Lamp. Neraca</a></h3>
    
    <div style="height:350px;">
    <p align="right">         
        <table id="lamp_aset" title="List Lamp. Aset" style="width:870px;height:650px;" >  
        </table>
    </p> 
    </div>

</div>
</div> 





</body>
</html>