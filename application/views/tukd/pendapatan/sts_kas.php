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
    
    var kode     = '';
    var giat     = '1';
    var nomor    = '';
    var cid      = 0 ;
    var plrek    = '';
    var lcstatus = '';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $("#dialog-modal").dialog({
            height: 250,
            width: 700,
            modal: true,
            autoOpen:false
        });
         $("#dialog-modal_t").dialog({
            height: 500,
            width: 800,
            modal: true,
            autoOpen:false
        });
        $("#dialog-modal_cetak").dialog({
            height: 200,
            width: 400,
            modal: true,
            autoOpen:false
        });
        $("#dialog-modal_edit").dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
        get_skpd();
		get_tahun();
        });    
     
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd_kas/load_sts_kas',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_sts',
    		title:'Nomor STS',
    		width:50},
            {field:'tgl_sts',
    		title:'Tanggal',
    		width:30},
            {field:'kd_skpd',
    		title:'S K P D',
    		width:30,
            align:"left"},
            {field:'keterangan',
    		title:'Uraian',
    		width:50,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor     = rowData.no_sts;
          nomor_kas = rowData.no_kas;
          tgl       = rowData.tgl_sts;
          tgl_kas   = rowData.tgl_kas;
          kode      = rowData.kd_skpd;
          lckdbank  = rowData.kd_bank;
          lckdgiat  = rowData.kd_sub_kegiatan;
          lcket     = rowData.keterangan;
          lcjnskeg  = rowData.jns_trans;
          lcrekbank = rowData.rek_bank;
          lctotal   = rowData.total;
          jns_cp    = rowData.jns_cp;
          no_sp2d   = rowData.no_sp2d;
          pot_khusus = rowData.pot_khusus;
          pay   =   rowData.bank;
          get(nomor_kas,nomor,tgl,tgl_kas,kode,lckdbank,lckdgiat,lcket,lcjnskeg,lcrekbank,lctotal,no_sp2d,jns_cp,pot_khusus,pay);   
          load_detail(nomor);        
          lcstatus  = 'edit';
        },
        onDblClickRow:function(rowIndex,rowData){
            section2();   
        }
        });
        
        $('#dg_tetap').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd_kas/load_tetap_sts/'+kode+'/'+plrek,
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"false",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'ck',
    		title:'Pilih',
    		width:5,
            align:"center",
            checkbox:true                
            },
    	    {field:'no_tetap',
    		title:'Nomor Tetap',
    		width:10,
            align:"center"},
            {field:'tgl_tetap',
    		title:'Tanggal',
    		width:5,
            align:"center"},
            {field:'nilai',
    		title:'Nilai',
    		width:5,
            align:"center"}
        ]]
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
                    lnnilai = rowData.rupiah;
            },                                                     
            columns:[[
                {field:'id',
        		title:'ID',    		
                hidden:"true"},
                {field:'no_sts',
        		title:'No STS',    		
                hidden:"true"},                
        	    {field:'kd_rek6',
        		title:'Nomor Rekening',
                width:1},
                {field:'nm_rek',
        		title:'Nama Rekening',
                width:3},                
                {field:'rupiah',
        		title:'Rupiah',
                align:'right',
                width:1}               
            ]],
           onDblClickRow:function(rowIndex,rowData){
           idx = rowIndex; 
           lcrekedt   = rowData.kd_rek6;
           lcnmrekedt = rowData.nm_rek;
           lcnilaiedt = rowData.rupiah; 
           get_edt(lcrekedt,lcnmrekedt,lcnilaiedt); 
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
        
        $('#tanggalkas').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
			onSelect: function(date){
				var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
			$("#tanggal").datebox("setValue",y+'-'+m+'-'+d);
		
		}
        });
    
        
        $('#rek').combogrid({  
           panelWidth:700,  
           idField:'kd_rek6',  
           textField:'kd_rek6',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd_kas/ambil_rek_tetap/'+kode,             
           columns:[[  
               {field:'kd_rek6',title:'Kode Rekening',width:140},  
               {field:'nm_rek',title:'Uraian',width:700},
              ]],
              
               onSelect:function(rowIndex,rowData){
                plrek = rowData.kd_rek6;
               $("#nmrek1").attr("value",rowData.nm_rek.toUpperCase());
               $("#dg_tetap").edatagrid({url: '<?php echo base_url(); ?>/index.php/tukd_kas/load_tetap_sts/'+kode+'/'+plrek});
              }    
            });
            
          $('#cmb_sts').combogrid({  
           panelWidth:700,  
           idField:'no_sts',  
           textField:'no_sts',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd_kas/load_sts',  
           columns:[[  
               {field:'no_sts',title:'Nomor STS',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               nomor = rowData.no_sts;               
           } 
       });
       

        $('#jns_trans').combobox({  
        url:'<?php echo base_url(); ?>index.php/tukd_kas/load_jns_str',  
        valueField:'id',  
        textField:'text',
        onSelect:function(record){
               lcskpd=document.getElementById('skpd').value;
               lckode = record.id;
				$('#giat').combogrid('setValue','');
				$('#sp2d').combogrid('setValue','');
				$("#nmgiat").attr("value",'');
				$("#jns_cp").attr("value",'');
                //alert('<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lckode+'/'+lcskpd);  
               //$('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lckode+'/'+lcskpd});
			   $('#sp2d').combogrid({url:'<?php echo base_url(); ?>index.php/tukd_kas/load_sp2d_sts/'+lckode+'/'+lcskpd});
			   
                                
           }    
         });  
      
		$('#sp2d').combogrid({
           panelWidth:150,  
           idField:'no_sp2d',  
           textField:'no_sp2d',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lckode+'/'+lcskpd,
           //url:'<?php echo base_url(); ?>index.php/rka/load_trskpd/'+kode,             
           columns:[[  
               {field:'no_sp2d',title:'NO SP2D',width:140}  
           ]],  
           onSelect:function(rowIndex,rowData){
               $("#jns_cp").attr("value",rowData.jns_cp);
			   no_sp2d =(rowData.no_sp2d).split("/").join("123456789");
               $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd_kas/load_trskpd_sts_ag/'+no_sp2d});
           }
              
        });
       
      
          
       $('#cmb_rek').combogrid({  
           panelWidth:800,  
           idField:'kd_rek6',  
           textField:'kd_rek6',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/tukd/224dwasf/'+kode+'/'+giat,             
           columns:[[  
               {field:'kd_rek6',title:'Kode Rekening',width:120},  
               {field:'nm_rek6',title:'Uraian',width:200},
               {field:'nilai',title:'Nilai',width:150},
               {field:'transaksi',title:'Transaksi',width:150},
               {field:'sisa',title:'Sisa',width:150},
			   
              ]],
               onSelect:function(rowIndex,rowData){
               $("#nmrek").attr("value",rowData.nm_rek6);
               $("#nilai").attr("value",number_format(rowData.sisa,2,'.',','));
              }    
            });
  
                     
        $('#giat').combogrid({
           panelWidth:700,  
           idField:'kd_sub_kegiatan',  
           textField:'kd_sub_kegiatan',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lckode+'/'+lcskpd,
           url:'<?php echo base_url(); ?>index.php/tukd_kas/load_trskpd/'+kode,             
           columns:[[  
               {field:'kd_sub_kegiatan',title:'Kode Kegiatan',width:140},  
               {field:'nm_sub_kegiatan',title:'Nama Kegiatan',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               giat = rowData.kd_sub_kegiatan;
               $("#nmgiat").attr("value",rowData.nm_sub_kegiatan);                                      
           }
              
        });
        
        
    });  
    
    function get_skpd()
    {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka_penetapan/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#skpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
        								kode = data.kd_skpd;
                                        //lckode='4';
                                        get_rek(kode); 
                                        //$('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lckode+'/'+kode});
        							  }                                     
        	});
    }
	
	 function get_tahun() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd_kas/config_tahun',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
        	});
             
        }

   	
    
    
    function get_rek(kode){
            $('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd_kas/ambil_rek_t_sts/'+kode});
        }
    
    function openWindow(url)
        {
        var no =nomor.split("/").join("123456789");
        window.open(url+'/'+no, '_blank');
        window.focus();
        }     

    function loadgiat(){
        var lcjnsrek=document.getElementById("jns_trans").value;
        alert(lcjnsrek);
         $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd_kas/load_trskpd1/'+lcjnsrek});  
    }
    
    function load_detail(kk){        

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd_kas/load_dsts_sisa',
                data: ({no:kk}),
                dataType:"json",
                success:function(data){                                   
                                $.each(data,function(i,n){
                                id = n['id'];    
                                kdrek = n['kd_rek6'];                                                                    
                                lnrp = n['rupiah'];    
                                lcnmrek = n['nm_rek'];
                                lcnosts = n['no_sts'];
                                $('#dg1').datagrid('appendRow',{id:id,no_sts:lcnosts,kd_rek6:kdrek,rupiah:lnrp,nm_rek:lcnmrek});                         
                                });   
                                 
                }
            });
           });  
  
         set_grid();
                           
    }

 
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
         });
    }
    
    function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });   
         set_grid();      
     }
       
     
    function get(nomor_kas,nomor,tgl,tgl_kas,kode,lckdbank,lckdgiat,lcket,lcjnskeg,lcrekbank,lctotal,no_sp2d,jns_cp,pot_khusus,vpay){
        $("#no_kas").attr("value",nomor);
        $("#jns_cp").attr("value",jns_cp);
        $("#nomor_hide").attr("value",nomor_kas);
        $("#tanggalkas").datebox("setValue",tgl);
        //$("#bank").combogrid("setValue",lckdbank);
        $("#ket").attr("value",lcket)
        //$("#jns_trans").attr("value",lcjnskeg)
        $("#jns_trans").combobox("setValue",lcjnskeg)
        //$('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lcjnskeg+'/'+kode}); 
        $("#giat").combogrid("setValue",lckdgiat);
        $("#sp2d").combogrid("setValue",no_sp2d);
        //$("#rek_bank").attr("value",lcrekbank);
        $("#jumlahtotal").attr("value",lctotal);
		if (pot_khusus==1){
			$("#hkpg").attr("checked",true);                  
			$("#lainnya").attr("checked",false);                  
			$("#hkpg_lalu").attr("checked",false);                  
		} else if (pot_khusus==2){
			$("#hkpg").attr("checked",false);                  
			$("#lainnya").attr("checked",true);                  
			$("#hkpg_lalu").attr("checked",false);                  
		} else if (pot_khusus==3){
			$("#hkpg").attr("checked",false);                  
			$("#lainnya").attr("checked",false);                  
			$("#hkpg_lalu").attr("checked",true);                  
		}else{
			$("#hkpg").attr("checked",false);                  
			$("#lainnya").attr("checked",false);
			$("#hkpg_lalu").attr("checked",false);
		}
		$("#jns_tunai").attr("value",vpay);
    }
    
    function get_edt(lcrekedt,lcnmrekedt,lcnilaiedt){
        $("#rek_edt").attr("value",lcrekedt);
        $("#nmrek_edt").attr("value",lcnmrekedt);
        $("#nilai_edt").attr("value",lcnilaiedt);
        $("#nilai_edth").attr("value",lcnilaiedt);
        $("#dialog-modal_edit").dialog('open');
    } 
    
    
    function kosong(){
        lcstatus = 'tambah';
        $("#nomor").attr("value",'');
        $("#no_kas").attr("value",'');
        $("#nomor_hide").attr("value",'');
        get_nourut();
        $("#tanggal").datebox("setValue",'');
        $("#tanggalkas").datebox("setValue",'');
        $("#jns_trans").combobox("setValue",'');
        //$("#bank").combogrid("setValue",'');
        $("#ket").attr("value",'');
        $("#jumlahtotal").attr("value",0);
        var kode = '';
        var nomor = '';
        $('#giat').combogrid('setValue','');
        $('#sp2d').combogrid('setValue','');
		$("#nmgiat").attr("value",'');
		$("#jns_cp").attr("value",'');
        $("#jns_tunai").attr("value",'BNK','TNK');
    }
     function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/cms/no_urut',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
				$("#no_kas").attr("value",data.no_urut);
			  }                                     
        	});  
        }
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd_kas/load_sts_kas',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    
    function append_save(){        
            var ckdrek = $('#cmb_rek').combogrid('getValue');
            var lcno = document.getElementById('no_kas').value;
            var lcnm = document.getElementById('nmrek').value;
            var lcnl = angka(document.getElementById('nilai').value);
            var lstotal = angka(document.getElementById('jumlahtotal').value);
            var lcnl1 = number_format(lcnl,2,'.',',');
            var tunai = angka(document.getElementById('sisa_tunai').value);
		    var cjnsrek   = $('#jns_trans').combobox('getValue');
			total = lcnl+tunai;
			
			if(tunai<lcnl){
				alert("Melebihi Uang Kas");
				exit();
			}
            if (ckdrek != '' && lcnl != 0 ) {
                total = number_format(lstotal+lcnl,2,'.',',');
                cid = cid + 1;            
                $('#dg1').datagrid('appendRow',{id:cid,no_sts:lcno,kd_rek6:ckdrek,rupiah:lcnl1,nm_rek:lcnm});    
                $('#jumlahtotal').attr('value',total);    
                rek_filter(); 
            }
             
            $('#cmb_rek').combogrid('setValue','');
            $('#nilai').attr('value',0);
            $('#nmrek').attr('value','');
        
    }     
    
    
    function rek_filter(){
       
        var crek='';
         $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
			for(var i=0;i<rows.length;i++){
				crek   = crek+"A"+rows[i].kd_rek5+"A";
                if (i<rows.length && i!=rows.length-1){
                    crek = crek+'B';
                }
            }
               $('#dg1').datagrid('unselectAll');
          $('#cmb_rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd_kas/ambil_rek_sts/'+kode+'/'+giat+'/'+no_sp2d+'/'+crek});  
    }
    
    
    function rek_fil(){
       
        var crek='';
         $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
			for(var i=0;i<rows.length;i++){
				crek   = crek+"A"+rows[i].kd_rek5+"A";
                if (i<rows.length && i!=rows.length-1){
                    crek = crek+'B';
                }
            }
               $('#dg1').datagrid('unselectAll');
          $('#cmb_rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd_kas/ambil_rek1/'+crek});  
    }
    
    
    function set_grid(){
        $('#dg1').edatagrid({  
            columns:[[
                {field:'id',
        		title:'ID',    		
                hidden:"true"},
                {field:'no_sts',
        		title:'No STS',    		
                hidden:"true"},                
        	    {field:'kd_rek6',
        		title:'Nomor Rekening',
                width:2},
                {field:'nm_rek',
        		title:'Nama Rekening',
                width:4},                
                {field:'rupiah',
        		title:'Rupiah',
                align:'right',
                width:2}                
                
            ]]
        });    
    }
    
    
    function tambah(){
        var lcno = document.getElementById('no_kas').value;
        var cjnstetap = document.getElementById('jns_tetap').checked;
        // var cjenis_bayar = document.getElementById('jns_tunai').value; 
        var jns_pembayaran = document.getElementById('jns_tunai').value;
        // alert(jns_pembayaran);
        // return;
         var giat  = '1';
        if(cjnstetap==true){
            $("#dialog-modal_t").dialog('open');
        } else {
            if(lcno !='' && jns_pembayaran=='TNK'){
			load_sisa_tunai();
            $("#dialog-modal").dialog('open');
            $('#nilai').attr('value',0);
            $('#nmrek').attr('value','');
            var kode = document.getElementById('skpd').value;
            var giat = $('#giat').combogrid('getValue');
			   var no_sp2d =($('#sp2d').combogrid('getValue')).split("/").join("123456789");
            } else {
                if(lcno ==''){
                    alert('Nomor Sts Tidak Boleh kosong')
                document.getElementById('no_kas').focus();
                exit();
                } 
                if(lcno !='' && jns_pembayaran=='BNK'){
                    load_sisa_bank();
            $("#dialog-modal").dialog('open');
            $('#nilai').attr('value',0);
            $('#nmrek').attr('value','');
            var kode = document.getElementById('skpd').value;
            var giat = $('#giat').combogrid('getValue');
			   var no_sp2d =($('#sp2d').combogrid('getValue')).split("/").join("123456789");
                }else{
                    alert('Nomor Sts Tidak Boleh kosong')
                document.getElementById('no_kas').focus();
                exit();
                }
            } 
            
            if(giat !=''){
               rek_filter(); 
            }else{
               rek_fil();
            }
            
        }
                
    }
    
    function cetak(){
        $("#dialog-modal_cetak").dialog('open');             
    }
    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $("#dialog-modal_t").dialog('close');
        $("#dialog-modal_cetak").dialog('close');
        $("#dialog-modal_edit").dialog('close');
    }    
    
    
    function hapus_rek(){
        var lckurang = angka(lnnilai);
        var lstotal  = angka(document.getElementById('jumlahtotal').value);
        lntotal      =  number_format(lstotal - lckurang,0,'.',',');
        
        $("#jumlahtotal").attr("value",lntotal);
        $('#dg1').datagrid('deleteRow',idx);     
    }
    
    function hapus(){
        var cnomor = document.getElementById('no_kas').value;
        var urll   = '<?php echo base_url(); ?>index.php/tukd_kas/hapus_sts';
        $(document).ready(function(){
         $.post(urll,({no:cnomor}),function(data){
            status = data;
            status1 = data;
            if(status1==1){
                alert('Data Sudah divalidasi..!! Gagal Hapus');
                exit();
            } else {
                alert('Data Berhasil Dihapus..!!');
				get_nourut();
                section1();
                exit();
            }
         });
        });    
    }
    
    
    
    function simpan_sts(){
        var cno    = document.getElementById('no_kas').value;
        var ctgl   = $('#tanggalkas').datebox('getValue');
        var cno_hide  = document.getElementById('nomor_hide').value;
        var cbank     = '';//$('#bank').combogrid('getValue'); 
        var cskpd     = document.getElementById('skpd').value;
        var cnmskpd   = document.getElementById('nmskpd').value;
        var lcket     = document.getElementById('ket').value;
        var cjnsrek   = $('#jns_trans').combobox('getValue');
        var cgiat     = $('#giat').combogrid('getValue');
        var sp2d      = $('#sp2d').combogrid('getValue');
        var lcrekbank = '';//document.getElementById('rek_bank').value;
        var lntotal   = angka(document.getElementById('jumlahtotal').value);
        var cstatus   = document.getElementById('jns_tetap').checked;
        var jnscp     = document.getElementById('jns_cp').value;
		var hkpg      = document.getElementById('hkpg').checked; 
		var lain      = document.getElementById('lainnya').checked; 
		var hkpg_lalu = document.getElementById('hkpg_lalu').checked; 
		var cjenis_bayar = document.getElementById('jns_tunai').value; 
		
       
		if ((hkpg == true) && (lain == true)){
           alert('Tidak boleh memilih HKPG dan Pemotongan Lainnya sekaligus!');
		   exit();
		} else if ((hkpg == true) && (hkpg_lalu == true)){
           alert('Tidak boleh memilih HKPG dan HKPG lalu Lainnya sekaligus!');
		   exit();
        } else if ((lain == true) && (hkpg_lalu == true)){
           alert('Tidak boleh memilih Pemotongan Lainnya dan HKPG lalu Lainnya sekaligus!');
		   exit();
        } else if ((hkpg == true) && (lain == false) && (hkpg_lalu == false)){
           potlain=1;
        } else if ((hkpg == false) && (lain == true) && (hkpg_lalu == false)){
           potlain=2;
		} else if ((hkpg == false) && (lain == false) && (hkpg_lalu == true)){
           potlain=3;
		} else {
           potlain=0;
		}
		
		if((jnscp=="UP/GU/TU") || (jnscp=="UP") || (jnscp=="GU") || (jnscp=="TU") || (jnscp=="3")){
			jns_cp = '3';
		} else if ((jnscp=="LS GAJI")||(jnscp=="1")){
			jns_cp = "1";
		} else {
			jns_cp = "2"
		}
		
		if ((cjnsrek == '1') && (potlain != 0) ){
			alert('HKPG dan Pemotongan lainnya hanya untuk Belanja Gaji!');
			exit();
		}
		if ((cjnsrek == '5') && (jns_cp == '1') && (potlain == 0) ){
			alert('HKPG dan Pemotongan lainnya belum di pilih!');
			exit();
		}		
		
        if (cstatus==false){
           cstatus=0;
        }else{
            cstatus=1;
        }
                
       
        if (cno==''){
            alert('Nomor STS Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal STS Tidak Boleh Kosong');
            exit();
        }
        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        var tahun_input = ctgl.substring(0, 4);
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
                    data: ({no:cno,tabel:'trhkasin_pkd',field:'no_sts'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd_kas/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
            //----mulai
            $('#dg1').datagrid('selectAll');
            var rows  = $('#dg1').datagrid('getSelections');           
    		lcval_det = '';
            for(var i=0;i<rows.length;i++){
    			cnosts  = rows[i].no_sts;
                ckdrek  = rows[i].kd_rek6;              
                cnilai  = angka(rows[i].rupiah);  
                if(i>0){
    				lcval_det = lcval_det+",('"+cskpd+"','"+cno+"','"+ckdrek+"','"+cnilai+"','"+cgiat+"')";
    			}else{
    				lcval_det = lcval_det+"('"+cskpd+"','"+cno+"','"+ckdrek+"','"+cnilai+"','"+cgiat+"')";
    			}              
    		}
            $('#dg1').datagrid('unselectAll'); 
            

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/tukd_kas/simpan_sts_ar',
                    data: ({tabel:'trhkasin_pkd',cid:'no_sts',lckas:cno,lcid:cno,no:cno,bank:cbank,tgl:ctgl,tglkas:ctgl,skpd:cskpd,ket:lcket,jnsrek:cjnsrek,giat:cgiat,rekbank:lcrekbank,total:lntotal,value_det:lcval_det,sts:cstatus,sp2d:sp2d,jns_cp:jns_cp,potlain:potlain,cjenis_bayar:cjenis_bayar}),
                    dataType:"json",
                    success:function(data){
                        status = data ;
                        if (status=='0'){
                             alert('gagal');
                             exit();
                        } else  
                        if (status=='2'){
                             alert("Data Tersimpan...!!!");
                             $("#nomor_hide").attr("Value",cno);  
                             lcstatus = 'edit'; 
							 section1();
							//refresh();
                        }
                    }
                });
            });
	//AKhir
	
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
                    data: ({no:cno,tabel:'trhkasin_pkd',field:'no_sts'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd_kas/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cno!=cno_hide){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || cno==cno_hide){
						alert("Nomor Bisa dipakai");
			//mulai			
             $('#dg1').datagrid('selectAll');
            var rows  = $('#dg1').datagrid('getSelections');           
    		lcval_det = '';
            for(var i=0;i<rows.length;i++){
    			cnosts  = rows[i].no_sts;
                ckdrek  = rows[i].kd_rek6;              
                cnilai  = angka(rows[i].rupiah);  
                if(i>0){
    				lcval_det = lcval_det+",('"+cskpd+"','"+cno+"','"+ckdrek+"','"+cnilai+"','"+cgiat+"')";
    			}else{
    				lcval_det = lcval_det+"('"+cskpd+"','"+cno+"','"+ckdrek+"','"+cnilai+"','"+cgiat+"')";
    			}              
    		}
            $('#dg1').datagrid('unselectAll'); 
            

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/tukd_kas/update_sts_kas_ag',
                    data: ({tabel:'trhkasin_pkd',cid:'no_sts',lckas:cno,lcid:cno,no:cno,bank:cbank,tgl:ctgl,tglkas:ctgl,skpd:cskpd,ket:lcket,jnsrek:cjnsrek,giat:cgiat,rekbank:lcrekbank,total:lntotal,value_det:lcval_det,sts:cstatus,sp2d:sp2d,nohide:cno_hide,jns_cp:jns_cp,potlain:potlain,cjenis_bayar:cjenis_bayar}),
                    dataType:"json",
                    success:function(data){
                        status = data ;
                        if (status=='0'){
                             alert('gagal');
                             exit();
                        } else  
                        if (status=='2'){
                             alert("Data Tersimpan...!!!");
                             $("#nomor_hide").attr("Value",cno);  
                             lcstatus = 'edit'; 
              							 section1();
              							//refresh();
                        }
                    }
                });
            });
			//
			}
			}
		});
		});
        }
        //section1();
    }
    
    


    
    
    function jumlah(){

        var lcno = document.getElementById('nomor').value;
        var lcnm = document.getElementById('nmrek1').value;
        ckdrek = $('#rek').combogrid('getValue'); 
        var rows = $('#dg_tetap').datagrid('getChecked');
        cid = cid + 1;      
        
        var lstotal = angka(document.getElementById('jumlahtotal').value);
        
        
        var lnjm = 0;    
        	for(var i=0;i<rows.length;i++){
        	   ltmb = angka(rows[i].nilai);
               lnjm = lnjm + ltmb;
        	   }
  
            total = number_format(lstotal+lnjm,2,'.',',');
            $('#jumlahtotal').attr('value',total);    
            lcjm = number_format(lnjm,2,'.',',')               

            $('#dg1').datagrid('appendRow',{id:cid,no_sts:lcno,kd_rek5:ckdrek,rupiah:lcjm,nm_rek:lcnm});
             
          keluar();
    }
  
  
    function delCommas(nStr)
    {
        var no =nStr.split(",").join("");
        return no1 = eval(no);
    }
    
    function edit_detail(){
    
         var lnnilai = angka(document.getElementById('nilai_edt').value);
         var lnnilai_sb = angka(document.getElementById('nilai_edth').value);
         var lstotal = angka(document.getElementById('jumlahtotal').value);
         
         lcnilai = number_format(lnnilai,2,'.',',');
         total = lstotal - lnnilai_sb + lnnilai; 
         ftotal = number_format(total,2,'.',',');
         $('#dg1').datagrid('updateRow',{
            	index: idx,
            	row: {
            		rupiah: lcnilai                    
            	}
         });
         $('#jumlahtotal').attr('value',ftotal);  
         keluar();
    }
	function refresh(){
		   		window.location.reload();

	   }
	
	function load_sisa_tunai(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd_kas/load_sisa_tunai",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#sisa_tunai").attr("value",n['sisa']);
                   // $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
        });
    }

    // andika sisa bank
    function load_sisa_bank(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd_kas/load_sisa_bank",
            data:{
                tgl :$('#tanggalkas').datebox('getValue'),
            },
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#sisa_tunai").attr("value",n['sisa']);
                   // $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
        });
    }
    
    </script>

</head>
<body>


<div id="content"> 
<div id="accordion">
<h3><a href="#" id="section1">List STS</a></h3>
    
    <div>
    <p align="right"> 
 <a href="<?php echo site_url(); ?>tukd/register_cp" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:input_lengkap(this.href);return false">Register CP</a>	
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>               
        <!--<a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak</a>-->
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="List STS" style="width:870px;height:450px;" >  
        </table>
    </p> 
    </div>   

<h3><a href="#" id="section2" onclick="javascript:set_grid();">Surat Tanda Setoran</a></h3>

   <div  style="height: 350px;">

   <p>       
        <table align="center" style="width:100%;" border="0">
            <tr>
                <td>No. KAS</td>
                <td><input type="text" id="no_kas" style="width: 200px;"/><input  type="hidden" id="nomor_hide" style="width: 200px;"/></td>
                <td>Tanggal KAS</td>
                <td><input type="text" id="tanggalkas" style="width: 140px;" /></td>     
            </tr>            
            <tr>
                <td>S K P D</td>
                <td><input id="skpd" name="skpd" style="width: 140px;" readonly="true"/></td>
                <td colspan="2" align="left"><input type="text" id="nmskpd" style="border:0;width: 450px;" readonly="true"/></td>
                                
            </tr>
            <tr>
                <td>Uraian</td>
                <td colspan="3"><textarea name="ket" id="ket" cols="40" rows="1" style="border: 0;"  ></textarea></td>                
            </tr>            
            <tr>
                <td>Pembayaran</td>
                 <td>
                     <select name="jns_tunai" id="jns_tunai">
                         <option value="BNK">BANK</option>
                         <option value="TNK">TUNAI</option>
                     </select>
                 </td>
            </tr>

            <tr>
                <td>Jenis Transaksi</td>
                <td colspan="3">
                <input  id="jns_trans" name="jns_trans" style="border:0;width: 150px;"/>                 
                </td> 
            </tr>
			<td>SP2D</td>
            <td colspan="3"><input id="sp2d" name="sp2d" style="width: 200px;" />
			&nbsp; &nbsp; Jenis CP: &nbsp;<input id="jns_cp" name="jns_cp" readonly="true" style="width: 200px; border:0" /></td>
            </tr>
            <tr>
            <td>Kegiatan</td>
            <td><input id="giat" name="giat" style="width: 200px;" /></td>
            <td colspan="2"><input type="text" id="nmgiat" style="border:0;width: 450px;" readonly="true"/></td></tr>
            <tr>
            <td colspan="4"> <input id="jns_tetap" hidden = "true" type="checkbox"/></td>            
            </tr>
            <td colspan="4">
			<input type="checkbox" id="hkpg" > HKPG Tahun Ini &nbsp;&nbsp;
			<input type="checkbox" id="hkpg_lalu" > HKPG Tahun Lalu&nbsp;&nbsp;
			<input type="checkbox" id="lainnya"> Pemotongan Lainnya &nbsp;&nbsp;
			</td>
            </tr>
            <tr>
                <td colspan="4" align="right">
                <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Baru</a>-->               
                <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_sts();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
                <!--<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak</a>-->
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a></td>

            </tr>
        </table>          
        <table id="dg1" title="Detail STS" style="width:870px;height:350px;" >  
        </table>  
        <div id="toolbar">
    		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Rekening</a>
    		<a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_rek();">Hapus Rekening</a>    		
        </div>
        
                
   </p>
   <table border="0" align="right" style="width:100%;"><tr>
   <td style="width:75%;" align="right"><B>JUMLAH</B></td>
   <td align="right"><input type="text" id="jumlahtotal" readonly="true" style="border:0;width:200px;text-align:right;"/></td>
   </tr>
   </table>
   
   </div>
</div>
</div>


<div id="dialog-modal" title="Input Rekening">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">Kode Rekening:</td>
            <td><input id="cmb_rek" name="cmb_rek" style="width: 200px;" /></td>
        </tr>
        <tr>
            <td width="110px">Nama Rekening:</td>
            <td><input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>
		 <tr>
            <td width="110px">Sisa Kas Tunai:</td>
            <td><input type="text" id="sisa_tunai" readonly="true" style="border:0;text-align:right;" placeholder="Tunggu sampai Muncul"/></td>
        </tr>
        <tr> 
           <td width="110px">Nilai:</td>
           <td><input type="text" id="nilai" style="text-align:right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
        </tr>
    </table>  
    </fieldset>
    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>

<div id="dialog-modal_edit" title="Edit Rekening">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">Kode Rekening:</td>
            <td><input type="text" id="rek_edt" readonly="true" style="width: 200px;" /></td>
        </tr>
        <tr>
            <td width="110px">Nama Rekening:</td>
            <td><input type="text" id="nmrek_edt" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>
        <tr> 
           <td width="110px">Nilai:</td>
           <td><input type="text" id="nilai_edt" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/>
               <input type="hidden" id="nilai_edth"/> 
           </td>
        </tr>
    </table>  
    </fieldset>
    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:edit_detail();">Simpan</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>


<div id="dialog-modal_cetak" title="Input Rekening">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">No STS:</td>
            <td><input id="cmb_sts" name="cmb_sts" style="width: 200px;" /></td>
        </tr>
    </table>  
    </fieldset>
     <fieldset>
    <table border="0">
        <tr align="center">
            <td></td>
            <td width="100%" align="center"><a  href="<?php echo site_url(); ?>/tukd/cetak_sts" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false">Cetak</a>
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  </td>
        </tr>
    </table>  
    </fieldset>
    
	
</div>


<div id="dialog-modal_t" title="Checkbox Select">
<table border="0">
<tr>
<td>Rekening</td>
<td><input id="rek" name="rek" style="width: 140px;" />  <input type="text" id="nmrek1" style="border:0;width: 400px;" readonly="true"/></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr><td colspan="2">
    <table id="dg_tetap" style="width:770px;height:350px;" >  
        </table>
    </td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr><td colspan="2" align="center">
    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:jumlah();">Simpan</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a></td>
</tr>
</table>  
</div>
  	
</body>

</html>