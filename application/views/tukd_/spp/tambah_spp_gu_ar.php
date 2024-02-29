<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
   
    var no_spp   = '';
    var kode     = '';
    var spd      = '';
    var st_12    = 'edit';
    var nidx     = 100
    var spd2     = '';
    var spd3     = '';
    var spd4     = '';
    var lcstatus = '';
    
    $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal").dialog({
             height: 300,
            width: 700,
            modal: true,
            autoOpen:false
        });
        $( "#dialog-modal-tr").dialog({
            height: 320,
            width: 500,
            modal: true,
            autoOpen:false
        });
        get_skpd();
        });
   
    
    $(function(){
//
   	     $('#dd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
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
					url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/PPKD',  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd4").attr("value",rowData.nama);
                    }  
  
				});


        $('#cspp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/load_spp_gu',  
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
                    val_ttd(kode);
                    }   
                });
       
                
          $('#spp').edatagrid({
    		url: '<?php echo base_url(); ?>/index.php/tukd/load_spp_gu',
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
        	    {field:'no_spp',
        		title:'NO SPP',
        		width:60},
                {field:'tgl_spp',
        		title:'Tanggal',
        		width:40},
                {field:'nm_skpd',
        		title:'Nama SKPD',
        		width:170,
                align:"left"},
                {field:'keperluan',
        		title:'Keterangan',
        		width:110,
                align:"left"}
            ]],
            onSelect:function(rowIndex,rowData){
              nomer     = rowData.no_spp;         
              kode      = rowData.kd_skpd;
              spd       = rowData.no_spd;
              tg        = rowData.tgl_spp;
              jn        = rowData.jns_spp;
              kep       = rowData.keperluan;
              np        = rowData.npwp;          
              bk        = rowData.bank;
              ning      = rowData.no_rek;
              status    = rowData.status;
              no_bukti  = rowData.no_bukti; 
              no_bukti2 = rowData.no_bukti2;          
              no_bukti3 = rowData.no_bukti3;          
              no_bukti4 = rowData.no_bukti4;          
              no_bukti5 = rowData.no_bukti5;
              spd2      = rowData.no_spd2;
              spd3      = rowData.no_spd3;
              spd4      = rowData.no_spd4; 
			  nlpj      = rowData.no_lpj;         
              get(nomer,kode,spd,tg,jn,kep,np,bk,ning,status,no_bukti,no_bukti2,no_bukti3,no_bukti4,no_bukti5,spd2,spd3,spd4,nlpj);		
              detail_trans_3();
              load_sum_sppp(); 
              lcstatus = 'edit';                                       
            },
            onDblClickRow:function(rowIndex,rowData){
                section1();
            }
        });
                
            $('#spdi').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1',  
                queryParams    :({dns:kode}),              
                    idField    : 'no_spd',  
                    textField  : 'no_spd',
                    mode       : 'remote',  
                    fitColumns : true,                                        
                    columns    : [[  
                        {field:'no_spd',title:'No SPD',width:50},  
                        {field:'tgl_spd',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd = rowData.no_spd;                                                                  
                    }    
                });
           
              
             $('#spd1').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1',  
                queryParams    :({dns:kode}),              
                    idField    : 'no_spd',  
                    textField  : 'no_spd',
                    mode       : 'remote',  
                    fitColumns : true,                                        
                    columns    : [[  
                        {field:'no_spd',title:'No SPD',width:50},  
                        {field:'tgl_spd',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd2 = rowData.no_spd;                                                                  
                    }    
                });
                
                
             $('#spd2').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1',  
                queryParams    :({dns:kode}),              
                    idField    : 'no_spd',  
                    textField  : 'no_spd',
                    mode       : 'remote',  
                    fitColumns : true,                                        
                    columns    : [[  
                        {field:'no_spd',title:'No SPD',width:50},  
                        {field:'tgl_spd',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd3 = rowData.no_spd;                                                                  
                    }    
                });
                
              $('#bank1').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/tukd/config_bank2',  
                    idField:'kd_bank',  
                    textField:'kd_bank',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_bank',title:'Kd Bank',width:40},  
                           {field:'nama_bank',title:'Nama',width:140}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //$("#kode").attr("value",rowData.kode);
                    $("#nama_bank").attr("value",rowData.nama_bank);
                    }   
                });
				
             $('#spd3').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1',  
                queryParams    :({dns:kode}),              
                    idField    : 'no_spd',  
                    textField  : 'no_spd',
                    mode       : 'remote',  
                    fitColumns : true,                                        
                    columns    : [[  
                        {field:'no_spd',title:'No SPD',width:50},  
                        {field:'tgl_spd',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd4 = rowData.no_spd;                                                                  
                    }    
                });
   
   


       $('#nlpj').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/nlpj',  
                queryParams    :({dns:kode}),              
                    idField    : 'no_lpj',  
                    textField  : 'no_lpj',
                    mode       : 'remote',  
                    fitColumns : true,                                        
                    columns    : [[  
                        {field:'no_lpj',title:'No LPJ',width:50},  
                        {field:'tgl_lpj',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    clpj = rowData.no_lpj;
                    detail_plj();  
					sum_total();
					
                    }    
                });

           
            
            $('#notrans').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/select_data_tran_5',                
                    idField   :'no_bukti',  
                    textField :'no_bukti',
                    mode      :'remote',  
                    fitColumns:true,                                        
                    columns:[[  
                        {field:'no_bukti',title:'No Bukti',width:50}  
                    ]]
                });
            
 			$('#dg1').edatagrid({
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
                    {field:'no_bukti',title:'No Bukti',width:100,align:'left'},                                          
                    {field:'kdkegiatan',title:'Kegiatan',width:150,align:'left'},
					{field:'kdrek5',title:'Rekening',width:70,align:'left'},
					{field:'nmrek5',title:'Nama Rekening',width:280},
                    {field:'nilai1',title:'Nilai',width:140,align:'right'}
					
//					,
//                    {field:'hapus',title:'',width:35,align:"center",
//                    formatter:function(value,rec){ 
//                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
//                    }
//                    }
				]]	
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
         

    
    function get_skpd()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#dn").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
                                            kode   = data.kd_skpd;
                                            validate_spd(kode);
        							  }                                     
        	});  
        }  



	function sum_total(){          
		var cnlpj	 = $('#nlpj').combogrid('getValue');	
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_lpj_ag",
            data:({lpj:cnlpj}),
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    //$("#rektotal").attr("value",n['rektotal']);
                    //$("#rektotal1").attr("value",n['rektotal1']);
                    $("#rektotal").attr('value',number_format(n['cjumlah'],2,'.',','));
                    $("#rektotal1").attr('value',number_format(n['cjumlah'],2,'.',','));
                });
            }
         });
        });
    }
    
    
    
     
     function validate_spd(kode){
           $(function(){
            $('#spdi').combogrid({ 
                queryParams    :({dns:kode}),
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1',  
                    idField    : 'no_spd',  
                    textField  : 'no_spd',
                    mode       : 'remote',  
                    fitColumns : true
                });
           });
        }
                 
                
        function get(no_spp,kd_skpd,no_spd,tgl_spp,jns_spp,keperluan,npwp,bank,rekening,status,no_bukti,no_bukti2,no_bukti3,no_bukti4,no_bukti5,no_spd2,no_spd3,no_spd4,nlpj){	  	
        $("#no_spp").attr("value",no_spp);
        $("#no_spp_hide").attr("value",no_spp);
        $("#dn").attr("Value",kd_skpd);
        $("#spdi").combogrid("setValue",no_spd);
        $("#spd1").combogrid("setValue",no_spd2);
        $("#spd2").combogrid("setValue",no_spd3);
        $("#spd3").combogrid("setValue",no_spd4);
		$("#nlpj").combogrid("setValue",nlpj);
	
	
        $("#dd").datebox("setValue",tgl_spp);        
        $("#ketentuan").attr("Value",keperluan);
        $("#jns_beban").attr("Value",jns_spp);
        $("#npwp").attr("Value",npwp);       
        $("#bank1").combogrid("setValue",bank);
        $("#rekening").attr("Value",rekening);
        
        $("#no1").attr("Value",no_bukti);
        $("#no2").attr("Value",no_bukti2);
        $("#no3").attr("Value",no_bukti3);
        $("#no4").attr("Value",no_bukti4);
        $("#no5").attr("Value",no_bukti5);
        tombol(status);           
        }
        
		
        function kosong(){
        $("#no_spp").attr("value",'');
        $("#no_spp_hide").attr("value",'');
        $("#spdi").combogrid("setValue",'');
        $("#spd1").combogrid("setValue",'');
        $("#spd2").combogrid("setValue",'');
        $("#spd3").combogrid("setValue",'');
		$("#nlpj").combogrid("setValue",'');
        $("#dd").datebox("setValue",'');
        $("#dd1").datebox("setValue",'');
        $("#dd2").datebox("setValue",'');        
        $("#ketentuan").attr("Value",'');
        $("#jns_beban").attr("Value",'');
        $("#npwp").attr("Value",'');        
        $("#bank1").combogrid("setValue",'');
        $("#rekening").attr("Value",'');
        document.getElementById("p1").innerHTML="";
        document.getElementById("no_spp").focus();
        $("#spdi").combogrid("clear");
        $("#spd1").combogrid("clear");
        $("#spd2").combogrid("clear");
        $("#spd3").combogrid("clear");
		 $("#nlpj").combogrid("clear");
        $("#notrans").combogrid("setValue",'');
        
        $("#no1").attr("Value",'');
        $("#no2").attr("Value",'');
        $("#no3").attr("Value",'');
        $("#no4").attr("Value",'');
        $("#no5").attr("Value",'');
        
        $('#save').linkbutton('enable');

        st_12 = 'baru';
        detail_trans_kosong();

        document.getElementById('rektotal1').value = 0 ;
        document.getElementById('rektotal').value  = 0 ;
        lcstatus = 'tambah'
        }

		
    function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		} 
       
    
    function cetak(){
        var nom=document.getElementById("no_spp").value;
        $("#dialog-modal").dialog('open');
        $("#cspp").combogrid("setValue",nom);
    } 
    
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    } 
    
    
    function keluar_no(){
        $("#dialog-modal-tr").dialog('close');
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
     
    
    function section4(){
         $(document).ready(function(){    
             $('#section4').click();                                               
         });
     }
     
     
     function section5(){
         $(document).ready(function(){    
             $("#dialog-modal-tr").click();                                               
         });
     }
     
    function tambah_no(){
        judul = 'Input Data No Transaksi';
        $("#dialog-modal-tr").dialog({ title: judul });
        $("#dialog-modal-tr").dialog('open');
        
        document.getElementById("no_spp").focus();
        
        if ( st_12 == 'baru' ){
        $("#no1").attr("value",'');
        $("#no2").attr("value",'');
        $("#no3").attr("value",'');
        $("#no4").attr("value",'');
        $("#no5").attr("value",'');
        }
     }
     
     function tambah_no2(){
        judul = 'Input Data No Transaksi';
        $("#dialog-modal-tr").dialog({ title: judul });
        $("#dialog-modal-tr").dialog('open');
        document.getElementById("no_spp").focus();
        
        if ( st_12 == 'baru' ){
        $("#no1").attr("value",'');
        $("#no2").attr("value",'');
        $("#no3").attr("value",'');
        $("#no4").attr("value",'');
        $("#no5").attr("value",'');
        }
     } 
     
	 
	 function list_lpj(){
	   $('#nlpj').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/nlpj',  
                queryParams    :({dns:kode}),              
                    idField    : 'no_lpj',  
                    textField  : 'no_lpj',
                    mode       : 'remote',  
                    fitColumns : true,                                        
                    columns    : [[  
                        {field:'no_lpj',title:'No LPJ',width:50},  
                        {field:'tgl_lpj',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    lpj = rowData.no_lpj;                                                                  
                    }    
                });
}
	 
	 
	 
     
     function hsimpan(){        

        var a      = (document.getElementById('no_spp').value).split(" ").join("");
        var a_hide = document.getElementById('no_spp_hide').value;
        var b      = $('#dd').datebox('getValue');      
        var c      = document.getElementById('jns_beban').value;               
        var e      = document.getElementById('ketentuan').value;       
        var g      = $("#bank1").combogrid("getValue") ; 
        var h      = document.getElementById('npwp').value;
        var i      = document.getElementById('rekening').value;
        var j      = document.getElementById('nmskpd').value;         
        var k      = angka(document.getElementById('rektotal1').value);
        var nno1   = document.getElementById('no1').value;
        var nno2   = document.getElementById('no2').value;
        var nno3   = document.getElementById('no3').value;
        var nno4   = document.getElementById('no4').value;       
		var nno5   = document.getElementById('no5').value;		
		var nolpj	 = $('#nlpj').combogrid('getValue');
        /*
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cskpd:kode,cspd:spd,cspd2:spd2,cspd3:spd3,cspd4:spd4,no_spp:a,bukti1:nno1,bukti2:nno2,bukti3:nno3,bukti4:nno4,bukti5:nno5,tgl_spp:b,jns_spp:c,keperluan:e,nmskpd:j,bank:g,npwp:h,rekening:i,nilai:k}),
            dataType:"json",
            url:"<?php //echo base_url(); ?>index.php/tukd/simpan",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan...!!!');
                }else{
                    alert('Data Gagal Berhasil Tersimpan...!!!');
                }
            }
         });
        });
        detsimpan() ;
        */
        
	if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'trhspp',field:'no_spp'}),
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
 //---------------------           
            // kurang nya bulan

            lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd,    jns_spp, bank,    nmrekan,  no_rek,  npwp,    nm_skpd,  tgl_spp, status, username,     last_update,   nilai,    no_bukti,    kd_kegiatan,  nm_kegiatan,  kd_program,  nm_program,  pimpinan,  no_tagih,    tgl_tagih,  sts_tagih, no_bukti2,  no_bukti3,  no_bukti4,  no_bukti5,  no_spd2,    no_spd3,    no_spd4,    no_lpj    )"; 
            lcvalues = "('"+a+"', '"+kode+"', '"+e+"',   '',      '"+spd+"', '"+c+"', '"+g+"', '',       '"+i+"', '"+h+"', '"+j+"',  '"+b+"', '0',    '',           '',            '"+k+"',  '"+nno1+"',  '',           '',           '',          '',          '',        '',          '',         '',        '"+nno2+"', '"+nno3+"', '"+nno4+"', '"+nno5+"', '"+spd2+"', '"+spd3+"', '"+spd4+"' , '"+nolpj+"' )";
            alert(lcvalues);
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_tukd',
                    data     : ({tabel:'trhspp',kolom:lcinsert,nilai:lcvalues,cid:'no_spp',lcid:a}),
                    dataType : "json",
                    success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        } else if(status=='1'){
                                  alert('Data Sudah Ada...!!!');
                                  exit();
                               } else {
                                  detsimpan() ;
                                  alert('Data Tersimpan...!!!');
								  $("#no_spp_hide").attr("value",a_hide);
                                  lcstatus = 'edit';
                                  exit();
                               }
                    }
                });
            });   
       //-----    
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
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && a!=a_hide){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || a==a_hide){
						alert("Nomor Bisa dipakai");
       //-------     
            //lcquery = " UPDATE trhspp SET kd_skpd='"+kode+"', keperluan='"+e+"', bulan='"+d+"', no_spd='"+spd+"', jns_spp='"+c+"', bank='"+g+"', nmrekan='"+f+"', no_rek='"+i+"', npwp='"+h+"', nm_skpd='"+j+"', tgl_spp='"+b+"', status='0', nilai='"+k+"', kd_kegiatan='"+kegi+"', nm_kegiatan='"+l+"', kd_program='"+m+"', nm_program='"+n+"', pimpinan='"+f1+"', no_tagih='"+p+"', tgl_tagih='"+q+"', sts_tagih='"+o+"', no_spp='"+a+"' where no_spp='"+a_hide+"' "; 
            lcquery = " UPDATE trhspp SET no_spp='"+a+"', kd_skpd='"+kode+"', keperluan='"+e+"',  no_spd='"+spd+"',  jns_spp='"+c+"', bank='"+g+"', no_rek='"+i+"', npwp='"+h+"', nm_skpd='"+j+"', tgl_spp='"+b+"', status='0', nilai='"+k+"', no_bukti='"+nno1+"', no_spd2='"+spd2+"', no_spd3='"+spd3+"', no_spd4='"+spd4+"' ,no_lpj='"+nolpj+"' where no_spp='"+a_hide+"' "; 
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/update_tukd',
                data     : ({st_query:lcquery,tabel:'trhspp',cid:'no_spp',lcid:a,lcid_h:a_hide}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        
                        if ( status=='1' ){
                            alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                            exit();
                        }
                        
                        if ( status=='2' ){
                            detsimpan() ;
						    $("#no_spp_hide").attr("value",a_hide);
                            alert('Data Tersimpan...!!!');
                            exit();
							$('#spp').edatagrid('reload')
                        }
                        
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                    }
            });
            });
        }
		//---------
		}
			
		});
     });
		
      }  
		list_lpj ();
    }
    
    
    function dsimpan(){
        var a = document.getElementById('no_spp').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cno_spp:a}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/dsimpan_spp"            
         });
        });
    } 
    
    
    function detsimpan(){

		var a      = (document.getElementById('no_spp').value).split(" ").join("");
		var a_hide = document.getElementById('no_spp_hide').value; 
		var nolpj	 = $('#nlpj').combogrid('getValue');

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
        
        $('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');
        
        for(var i=0;i<rows.length;i++){            
            
            cidx      = rows[i].idx;
            cnobukti1 = rows[i].no_bukti;
            ckdgiat   = rows[i].kdkegiatan;
            cnmgiat   = rows[i].nmkegiatan;
            ckdrek    = rows[i].kdrek5;
            cnmrek    = rows[i].nmrek5;
            cnilai    = angka(rows[i].nilai1);
                       
            no        = i + 1 ;      
            
            $(document).ready(function(){      
            $.ajax({
            type     : 'POST',
            url      : "<?php  echo base_url(); ?>index.php/tukd/dsimpan_gu",
            data     : ({cno_spp:a,cno_spphide:a_hide,cskpd:kode,cgiat:ckdgiat,crek:ckdrek,ngiat:cnmgiat,nrek:cnmrek,nilai:cnilai,kd:no,no_bukti1:cnobukti1,nolpj:nolpj}),
            dataType : "json"
        });
        });
        }
        $("#no_spp_hide").attr("Value",a) ;
        $('#dg1').edatagrid('unselectAll');
    } 
    
    
    function hhapus(){
		var nolpj	 = $('#nlpj').combogrid('getValue');
            var spp = document.getElementById("no_spp_hide").value;              
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hhapus_gu';             			    
         	if (spp !=''){
				var del=confirm('Anda yakin akan menghapus SPP '+spp+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:spp,nolpj:nolpj}),function(data){
                    status = data; 
					list_lpj ();                       
                    });
                    });				
				}
				} 
	}
        
    
    function kembali(){
        $('#kem').click();
    }                
    
    
     function load_sum_sppp(){          
        var nom = document.getElementById('no_spp').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_spp",
            data:({spp:nom}),
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    //$("#rektotal").attr("value",n['rektotal']);
                    //$("#rektotal1").attr("value",n['rektotal1']);
                    $("#rektotal").attr('value',number_format(n['rektotal'],2,'.',','));
                    $("#rektotal1").attr('value',number_format(n['rektotal'],2,'.',','));
                });
            }
         });
        });
    }
    
    
    function load_sum_tran(){                
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({no_bukti:no_bukti}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_tran",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    //$("#rektotal").attr("value",n['rektotal']);
                    //$("#rektotal1").attr("value",n['rektotal1']);
                    $("#rektotal").attr('value',number_format(n['rektotal'],2,'.',','));
                    $("#rektotal1").attr('value',number_format(n['rektotal'],2,'.',','));

                });
            }
         });
        });
    }
   
   
    function tombol(st){  
    if (st=='1') {
        $('#save').linkbutton('disable');
        $('#del').linkbutton('disable');
        $('#sav').linkbutton('disable');
        $('#dele').linkbutton('disable');   
        $('#load').linkbutton('disable');
        $('#load_kosong').linkbutton('disable'); 
        document.getElementById("p1").innerHTML="Sudah di Buat SPM...!!!";
     } else {
         $('#save').linkbutton('enable');
         $('#del').linkbutton('enable');
         $('#sav').linkbutton('enable');
         $('#dele').linkbutton('enable');
         $('#load').linkbutton('enable');
         $('#load_kosong').linkbutton('enable'); 
         document.getElementById("p1").innerHTML="";
     }
    }	
    
        
    function openWindow(url)
    {
       var nomer   = $("#cspp").combogrid('getValue');
        var jns = document.getElementById('jns_beban').value; 
        var no =nomer.split("/").join("123456789");
		var ttd1   = $("#ttd1").combogrid('getValue');
		var ttd2   = $("#ttd2").combogrid('getValue');
		var ttd4   = $("#ttd4").combogrid('getValue');
		
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

        window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_1+'/'+ttd_2+'/'+ttd_4, '_blank');
        window.focus();
    }
    
        
    function detail_trans_3(){
        var nomer = document.getElementById('no_spp').value;
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({ spp:nomer }),
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
                     {field:'no_bukti',
					 title:'No Bukti',
					 width:100,
					 align:'left'
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
                     }
					 
//					 ,
//                    {field:'hapus',title:'',width:35,align:"center",
//                    formatter:function(value,rec){ 
//                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
//                    }
//                    }


				]]	
			});
		});
        }
        

        function detail_trans_kosong(){
        var no_kos = '' ;
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({ spp:no_kos }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 columns:[[
                     {field:'idx',
					 title:'idx',
					 width:100,
					 align:'left',
                     hidden:'true'
					 },               
                     {field:'no_bukti',
					 title:'No Bukti',
					 width:100,
					 align:'left'
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
                     }
					 
//					 ,
//                    {field:'hapus',title:'',width:35,align:"center",
//                    formatter:function(value,rec){ 
//                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
//                    }
//                    }
				]]	
			});
		});
        }
    
        
    
        function masuk_grid(){

        var i          = 0;
        var ctotal_spp = document.getElementById('rektotal').value;
        
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/select_data_tran_4',
                data: ({no_bukti1:vnobukti}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    xnobukti = n['no_bukti'];                                                                                        
                    xgiat    = n['kdkegiatan'];
                    xkdrek5  = n['kdrek5'];
                    xnmrek5  = n['nmrek5'];
                    xnilai   = n['nilai1'];
                    
                    ctotal_spp = angka(ctotal_spp) + angka(xnilai) ;
                    
                    if  ( i==0 ){
                        nidx     = nidx + i + 1
                    }else{
                        nidx     = nidx + i
                    }
                    $('#dg1').edatagrid('appendRow',{no_bukti:xnobukti,kdkegiatan:xgiat,kdrek5:xkdrek5,nmrek5:xnmrek5,nilai1:xnilai,idx:nidx}); 
                    $('#dg1').edatagrid('unselectAll');
                    $('#rektotal').attr('value',number_format(ctotal_spp,0,'.',','));
                    });
                 }
            });
            });   
    }
    
    
    function filter_nobukti(){
        var vvnobukti = '';
        $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
			for(var i=0;i<rows.length;i++){
				vvnobukti   = vvnobukti+"A"+rows[i].no_bukti+"A";
                if (i<rows.length && i!=rows.length-1){
                    vvnobukti = vvnobukti+'B';
                }
        }
        $('#dg1').datagrid('unselectAll');
        $('#notrans').combogrid({
            url         :'<?php echo base_url(); ?>index.php/tukd/select_data_tran_5',
            queryParams :({no_bukti1:vvnobukti})
            });
    }

    
    function set_grid(){
        $('#dg1').edatagrid({  
            columns:[[
                     {field:'idx',
					 title:'idx',
					 width:100,
					 align:'left',
                     hidden:'true'
					 },               
                     {field:'no_bukti',
					 title:'No Bukti',
					 width:100,
					 align:'left'
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
                     }
					 
					 
//					 ,
//                    {field:'hapus',title:'',width:35,align:"center",
//                    formatter:function(value,rec){ 
//                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
//                    }
//                    }
				]]	
        });    
    }
    
    
    function hapus_detail(){
        
        var a          = document.getElementById('no_spp').value;
        var rows       = $('#dg1').edatagrid('getSelected');
        var ctotal_spp = document.getElementById('rektotal').value;
        
        bbukti      = rows.no_bukti;
        bkdrek      = rows.kdrek5;
        bkdkegiatan = rows.kdkegiatan;
        bnilai      = rows.nilai1;
        ctotal_spp  = angka(ctotal_spp) - angka(bnilai) ;
        
        var idx = $('#dg1').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, No Bukti :  '+bbukti+'  Rekening :  '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#rektotal').attr('value',number_format(ctotal_spp,2,'.',','));
            $('#dg1').datagrid('deleteRow',idx);     
            $('#dg1').datagrid('unselectAll');
              
             var urll = '<?php echo base_url(); ?>index.php/tukd/dsimpan_spp';
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
    
    
     function detail_plj(){
		var cnlpj	 = $('#nlpj').combogrid('getValue');	
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/load_data_transaksi',
                queryParams:({ nolpj:cnlpj }),
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
                     {field:'no_bukti',
					 title:'No Bukti',
					 width:100,
					 align:'left'
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
                     }

				]]	
			});
		});
		
        }
    
    function load_data(clpj) {


        var ntotal_trans = document.getElementById('rektotal').value ; 
            ntotal_trans = angka(ntotal_trans) ;
			
		var	skpd = document.getElementById('dn').value ; 
			
		var cnlpj	 = $('#nlpj').combogrid('getValue');	
		

          
        $(document).ready(function(){
            
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/load_data_transaksi',
                data: ({kdskpd:skpd,nolpj:cnlpj}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    xnobukti = n['no_bukti'];                                                                                        
                    xgiat    = n['kdkegiatan']; 
                    xkdrek5  = n['kdrek5'];
                    xnmrek5  = n['nmrek5'];
                    xnilai   = n['nilai1'];
                    
				
					
                    ntotal_trans = ntotal_trans + angka(xnilai) ;
                    
                    $('#dg1').edatagrid('appendRow',{no_bukti:xnobukti,kdkegiatan:xgiat,kdrek5:xkdrek5,nmrek5:xnmrek5,nilai1:xnilai,idx:i}); 
                    $('#dg1').edatagrid('unselectAll');
                    $('#rektotal').attr('value',number_format(ntotal_trans,2,'.',','));
                    });
                 }
            });
            });   
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
<div id="accordion" style="width:970px;height=970px;" >
<h3><a href="#" id="section4" onclick="javascript:$('#spp').edatagrid('reload')">List SPP</a></h3>
<div>
    <p align="right">  
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section1();kosong();">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="spp" title="List SPP" style="width:910px;height:450px;" >  
        </table>
    </p> 
</div>

<h3><a href="#" id="section1">Input SPP</a></h3>

   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>

<fieldset style="width:850px;height:850px;border-color: white;border-style:hidden;border-spacing:0; ">  
<table border='0' style="font-size:9px" cellspacing="0" >
 
 <tr style="height: 10px;" >   
   <td  width='20%'>No SPP</td>
   <td><input type="text" name="no_spp" id="no_spp" onclick="javascript:select();" style="width:225px"/> <input type="hidden" name="no_spp_hide" id="no_spp_hide" style="width:140px"/></td>
   <td>Tanggal</td>
   <td>&nbsp;<input id="dd" name="dd" type="text" style="width:95px"/></td>   
 </tr>
 
 <tr style="height: 10px;">
 
   <td width='20%'>SKPD</td>
   <td width="30%">     
        <input id="dn" name="dn"  readonly="true" style="width:130px; border: 0; " />
        <input name="nmskpd" type="text" id="nmskpd" size="60" readonly="true" style="border: 0;"/>
        </td> 
   <td width='15%'>Beban</td>
   <td width="35%" ><select name="jns_beban" id="jns_beban">
     <option value="2">GU</option>
   </select></td>
 
 </tr>
 
 <tr style="height: 5px;">
   <td width='20%'>No SPD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
   <td width='30%'><input id="spdi" name="spdi" style="width:225px" /></td>
   <td width='15%' rowspan="4">Keperluan</td>
   <td width='35%' rowspan="4"><textarea name="ketentuan" id="ketentuan" cols="30" rows="5" ></textarea></td>

 </tr>
 
 
  <tr style="height: 5px;">
   <td width='20%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
   <td width='30%'><input id="spd1" name="spd1" style="width:225px" /></td>
  </tr>
 
  <tr style="height: 5px;">
   <td width='20%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3</td>
   <td width='30%'><input id="spd2" name="spd2" style="width:225px" /></td>
  </tr>
 
 
  <tr style="height: 5px;">
   <td width='20%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4</td>
   <td width='30%'><input id="spd3" name="spd3" style="width:225px" /></td>
  </tr>
 
 <tr style="height: 10px;">
   
   <td width='20%'>NPWP</td>
   <td width='30%'><input type="text" name="npwp" id="npwp" value="" style="width:225px" /></td>
   
      <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >BANK</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="text" name="bank1" id="bank1" />
    &nbsp;<input type ="input" readonly="true" style="border:hidden" id="nama_bank" name="nama_bank" style="width:150" /></td>

 </tr>
 
 <tr style="height: 30px;">
 
   <td width='20%'>No LPJ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td width='30%'><input id="nlpj" name="nlpj" style="width:225px" /></td>
 

     <td width='15%'>Rekening</td>
     <td width='35%'>&nbsp;<input type="text" name="rekening" id="rekening"  value="" style="width:200px" /></td>
 </tr>
 
 <!--<tr style="height: 10px;">
    <td width='15%'>&nbsp;&nbsp;&nbsp;</td>
    <td width='35%'>&nbsp;&nbsp;&nbsp;</td>
    <td width='15%'>&nbsp;&nbsp;&nbsp;</td>
    <td width='35%'>&nbsp;&nbsp;&nbsp;</td>
 </tr>-->
     
 <tr style="height: 30px;">

      <!--<td colspan="1">
          <div align="left">
          </div>
      </td>-->                      
      
      <td colspan="4">
                  <div align="right">
                    <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                    <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true"  onclick="javascript:hsimpan();">Simpan</a>
                    <a id="del"class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section4();">Hapus</a>
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a> 
                    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section4();">Kembali</a>
                  </div>
        </td>                
  </tr>
        
  <!--<tr style="height: 10px;">
       <td>No Transaksi</td>
       <td><input type="text" name="notrans" id="notrans"  value="" style="width:200px"/></td>
       
       <td width='15%'>&nbsp;&nbsp;&nbsp;</td>
       <td width='35%'>&nbsp;&nbsp;&nbsp;</td>
       <td width='15%'>&nbsp;&nbsp;&nbsp;</td>
       <td width='35%'>&nbsp;&nbsp;&nbsp;</td>
  </tr>-->
  
    
<!-- <tr>
 

   <td colspan="4"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	  <div align="left">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="load" style="width:70px" class="easyui-linkbutton" iconCls="icon-add" plain="true"  onclick="javascript:load_data();" >Tampil</a>
		            <a id="load_kosong" style="width:70px" class="easyui-linkbutton" iconCls="icon-remove" plain="true"  onclick="javascript:detail_trans_kosong();" >Kosong</a>
       </div> 
		
		</td> 
 
</tr>-->



  </table>
   
        <table id="dg1" title="Input Detail SPP" style="width:900%;height:300%;" >  
        </table>
        
        <!--
        <div id="toolbar" align="right">
            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_detail();">Hapus Detail</a>               		
        </div>
        -->
        
        <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;-->
         
        <table border='0' style="width:100%;height:5%;"> 
             <td width='34%'></td>
             <td width='35%'><input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:140px" align="right" readonly="true" ></td>
             <td width='6%'><B>Total</B></td>
             <td width='25%'><input class="right" type="text" name="rektotal" id="rektotal"  style="width:140px" align="right" readonly="true" ></td>
        </table>

   </p>

</fieldset>     
</div>
</div>
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
    </table>  
    </fieldset>
    
    <div>
    </div>     

    <a href="<?php echo site_url(); ?>/tukd/cetakspp1/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(this.href);return false;">Pengantar</a>
	<a href="<?php echo site_url(); ?>/tukd/cetakspp2/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(this.href);return false;">Ringkasan</a>
	<a href="<?php echo site_url(); ?>/tukd/cetakspp3/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(this.href);return false;">Rincian</a>
	<br/>
	<a href="<?php echo site_url(); ?>/tukd/cetakspp1/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false;">Pengantar</a>
	<a href="<?php echo site_url(); ?>/tukd/cetakspp2/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false;">Ringkasan</a>
	<a href="<?php echo site_url(); ?>/tukd/cetakspp3/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false;">Rincian</a>
	&nbsp;&nbsp;&nbsp;<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>  
	     
</div>
 	
<div id="dialog-modal-tr" title="">
    <p class="validateTips">Pilih Nomor Transaksi</p> 
    <fieldset>
    <table align="center" style="width:100%;" border="0">
            
            <tr>
                <td>1. No Transaksi</td>
                <td></td>
                <td><input id="no1" name="no1" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>2. No Transaksi</td>
                <td></td>
                <td><input id="no2" name="no2" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>3. No Transaksi</td>
                <td></td>
                <td><input id="no3" name="no3" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>4. No Transaksi</td>
                <td></td>
                <td><input id="no4" name="no4" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>5. No Transaksi</td>
                <td></td>
                <td><input id="no5" name="no5" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                            
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                            
            </tr>
            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:detail_trans_2();">Pilih</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_no();">Kembali</a>
                </td>                
            </tr>
        
    </table>       
    </fieldset>
</div>
</body>
</html>