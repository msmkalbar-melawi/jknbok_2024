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
   
    var no_lpj   = '';
    var kode     = '';
    var spd      = '';
    var st_12    = 'edit';
    var nidx     = 100
    var spd2     = '';
    var spd3     = '';
    var spd4     = '';
    var lcstatus = '';
    var tahun_anggaran="<?php echo $this->session->userdata('pcThang'); ?>"
    
    $(document).ready(function() {

        $("#accordion").accordion();
        $("#lockscreen").hide();                        
        $("#frm").hide();
        $( "#dialog-modal").dialog({
            height: 400,
            width: 700,
            modal: true,
            autoOpen:false
        });
        $( "#dialog-modal-tr").dialog({
            height: 270,
            width: 500,
            modal: true,
            autoOpen:false
        });
        get_skpd();
        get_tahun();

        $("#div1").hide();

        $("#loading").hide();

    });

    
    $(function(){


      $('#cspp').combogrid({  
        panelWidth:500,  
        url: '<?php echo base_url(); ?>/index.php/lpj/load_lpj',  
        idField:'no_lpj',                    
        textField:'no_lpj',
        mode:'remote',  
        fitColumns:true,  
        columns:[[  
        {field:'no_lpj',title:'NO LPJ',width:60},  
        {field:'kd_skpd',title:'SKPD',align:'left',width:60},
        {field:'tgl_lpj',title:'Tanggal',width:60} 

        ]],
        onSelect:function(rowIndex,rowData){
            nomer = rowData.no_lpj;
            kode = rowData.kd_skpd;
            pilih_giat(nomer);
        }   
        });

      $('#dd').datebox({  
        required:true,
        formatter :function(date){
           var y = date.getFullYear();
           var m = date.getMonth()+1;
           var d = date.getDate();
           return y+'-'+m+'-'+d;
       }
      });


      $('#dd1').datebox({  
        required:true,
        formatter :function(date){
           var y = date.getFullYear();
           var m = date.getMonth()+1;
           var d = date.getDate();
           return y+'-'+m+'-'+d;
       }
   });


      $('#dd2').datebox({  
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
        url:'<?php echo base_url(); ?>index.php/lpj/load_ttd/BK',  
        columns:[[  
        {field:'nip',title:'NIP',width:200},
        {field:'nama',title:'Nama',width:400}
        ]],  
        onSelect:function(rowIndex,rowData){
         $("#nm_ttd1").attr("value",rowData.nama);
     } 
 });




      $('#ttd2').combogrid({  
        panelWidth:600,  
        idField:'nip',  
        textField:'nip',  
        mode:'remote',
        url:'<?php echo base_url(); ?>index.php/lpj/load_tanda_tangan/PA',  
        columns:[[  
        {field:'nip',title:'NIP',width:200},
        {field:'nama',title:'Nama',width:400}
        ]],  
        onSelect:function(rowIndex,rowData){
         $("#nm_ttd2").attr("value",rowData.nama);
     } 
 });




      $('#spp').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/lpj/load_lpj',
          idField:'id',            
          rownumbers:"true", 
          fitColumns:"true",
          singleSelect:"true",
          autoRowHeight:"false",
          loadMsg:"Tunggu Sebentar....!!",
          pagination:"true",
          nowrap:"true",             
          rowStyler: function(index,row){
            if (row.status == "2"){
              return 'background-color:#03d3ff;';
          }
      },                                                      
      columns:[[
      {field:'no_lpj',
      title:'NO LPJ',
      width:60},
      {field:'tgl_lpj',
      title:'Tanggal',
      width:40},
      {field:'nm_skpd',
      title:'Nama SKPD',
      width:170,
      align:"left"},
      {field:'ket',
      title:'Keterangan',
      width:110,
      align:"left"}
      ]],
      onSelect:function(rowIndex,rowData){
          nomer     = rowData.no_lpj;         
          kode      = rowData.kd_skpd;
          tgllpj	= rowData.tgl_lpj;
          cket		= rowData.ket;
          status_lpj	= rowData.status;
          tgl_awal	= rowData.tgl_awal;
          tgl_akhir	= rowData.tgl_akhir;


          get(nomer,kode,tgllpj,cket,status_lpj,tgl_awal,tgl_akhir);
          detail_trans_3();
          load_sum_lpj(); 
          lcstatus = 'edit';                                       
      },
      onDblClickRow:function(rowIndex,rowData){
          nomer     = rowData.no_lpj;         
          kode      = rowData.kd_skpd;
          tgllpj    = rowData.tgl_lpj;
          cket      = rowData.ket;
          status_lpj    = rowData.status;
          tgl_awal  = rowData.tgl_awal;
          tgl_akhir = rowData.tgl_akhir;


          get(nomer,kode,tgllpj,cket,status_lpj,tgl_awal,tgl_akhir);
          detail_trans_3();
          load_sum_lpj(); 
          lcstatus = 'edit';     
        section1();
    }
});


//==grid view edit
        var nlpj      = document.getElementById('no_lpj').value;

        $('#dg1').edatagrid({
            url: '<?php echo base_url(); ?>/index.php/lpj/select_data1_lpj_ag',
            queryParams:({ lpj:nlpj }),
            idField:'idx',
            toolbar:"#toolbar",              
            rownumbers:"true", 
            fitColumns:false,
            autoRowHeight:"false",
            singleSelect:"true",
            nowrap:"false",
            columns:[[
            {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},  
            {field:'tgl_bukti',title:'Tanggal',width:80,align:'center'},             
            {field:'no_bukti',title:'No Bukti',width:80,align:'left'},                                          
            {field:'kdkegiatan',title:'Kegiatan',width:160,align:'left'},
            {field:'kdrek5',title:'Rekening',width:70,align:'left'},
            {field:'nmrek5',title:'Nama Rekening',width:260},
            {field:'nilai1',title:'Nilai',width:120,align:'right'},
            {field:'kd_bp_skpd',title:'SKPD',width:50,align:'center',
            hidden:'true'},
            {field:'hapus',title:'',width:35,align:"center",
            formatter:function(value,rec){ 
                return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_2();" />';
            }
        }
        ]]	
        }); 

    });
        
        function pilih_giat(nomer){
          $('#giat_print').combogrid({  
            panelWidth:600,  
            idField:'kd_kegiatan',  
            textField:'kd_kegiatan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/lpj/load_giat_lpj', 
            queryParams:({ lpj:nomer }),
            columns:[[  
                {field:'kd_kegiatan',title:'NIP',width:200},
                {field:'nm_kegiatan',title:'Nama',width:400}
                ]]
            });
        }
        function get_skpd(){
           $.ajax({
              url:'<?php echo base_url(); ?>index.php/lpj/config_skpd',
              type: "POST",
              dataType:"json",                         
              success:function(data){
                $("#dn").attr("value",data.kd_skpd);
                $("#nmskpd").attr("value",data.nm_skpd);
                kode   = data.kd_skpd;

                }                                     
            });  
        }         
    
    	function get_tahun() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/lpj/config_up',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			$("#total_up").attr("value",data.nilai_up);
                    $("#total_up1").attr("value",number_format(data.nilai_up,2,',','.'));

        			}                                     
        	});
        }
		
		
        function hapus_detail_2(){
            alert('Untuk Saat ini tidak dapat dihapus, silahkan ulangi penarikan data transaksi');
        }
        
		function tanggal_awal() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/lpj/tambah_tanggal',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
		$("#dd1").datebox("setValue",data.tgl_awal);
        			}                                     
        	});
             
        }
    
    
	    function get(nomer,kode,tgllpj,cket,status_lpj,tgl_awal,tgl_akhir){
        $("#no_lpj").attr("value",nomer);
        $("#no_simpan").attr("value",nomer);
        $("#dn").attr("Value",kode);		
		$("#dd").datebox("setValue",tgllpj);
		$("#dd1").datebox("setValue",tgl_awal);
		$("#dd2").datebox("setValue",tgl_akhir);
		$("#keterangan").attr("Value",cket);
        tombol(status_lpj);           
        }
                                 
   
        function kosong(){
        $("#no_lpj").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#dd").datebox("setValue",'');
        $("#dd2").datebox("setValue",'');
        $("#dd1").datebox("setValue",'');
		  $("#keterangan").attr("value",'');
		  $("#no_lpj").focus();
		  $("#rektotal").attr("value",0)
        tanggal_awal();
        $('#save').show();
		
		
        st_12 = 'baru';
        detail_trans_kosong();


        lcstatus = 'tambah'
        }

		
    function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		} 
       
    
    function cetak(){
        var nom=document.getElementById("no_simpan").value;
        $("#dialog-modal").dialog('open');
        $("#cspp").combogrid("setValue",nom);
		pilih_giat(nom);
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
	       url: '<?php echo base_url(); ?>/index.php/lpj/load_lpj',
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
     
     
     function simpan(){        
        var nlpj      = document.getElementById('no_lpj').value;
        var no_simpan = document.getElementById('no_simpan').value;
        var kode 	  = document.getElementById('dn').value;
   		var b		  = $('#dd').datebox('getValue');  
   		var c 		  = $('#dd1').datebox('getValue');  
   		var d      	  = $('#dd2').datebox('getValue');  
	    var nket      = document.getElementById('keterangan').value;
	    var total     = angka(document.getElementById('rektotal').value);
	    var total_up     = document.getElementById('total_up').value;
		if (nlpj == ''){
			alert('Nomor tidak Boleh Kosong!');
			exit();
			}
		if (b == ''){
			alert('Tanggal Tidak Boleh Kosong!');
			exit();
		}
		//alert(total);
		var batas = total_up*0.75;

		if ( lcstatus == 'tambah' ) {
			$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:nlpj,tabel:'trhlpj',field:'no_lpj'}),
                    url: '<?php echo base_url(); ?>/index.php/lpj/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
	
			//-------   
			
			$(document).ready(function(){
			$.ajax({
				type: "POST",       
				dataType : 'json',         
				url      : "<?php  echo base_url(); ?>index.php/lpj/simpan_hlpj",
				data     : ({nlpj:nlpj,tgllpj:b,ket:nket,tgl_awal:c,tgl_akhir:d}),
				beforeSend:function(xhr){
                $("#loading").show();
					},
				success:function(data){
				status = data;
				if (status == '0'){
				   $("#loading").hide();
				   alert('Gagal Simpan...!!');
				   exit();
				} else if (status !='0'){ 
				
		        $('#dg1').datagrid('selectAll');
				var rows = $('#dg1').datagrid('getSelections'); 
				for(var i=0;i<rows.length;i++){            
						cidx      = rows[i].idx;
						cnobukti1 = rows[i].no_bukti;
                        ckdskpd1 = rows[i].kd_bp_skpd;
						ckdgiat   = rows[i].kdkegiatan;
						cnmgiat   = rows[i].nmkegiatan;
						ckdrek    = rows[i].kdrek5;
						cnmrek    = rows[i].nmrek5;
						cnilai    = angka(rows[i].nilai1);
						no        = i + 1 ; 
                        cgiat = ckdgiat.substr(0,21);
                        
						if (i>0) {
							csql = csql+","+"('"+nlpj+"','"+cnobukti1+"','"+b+"','"+ckdgiat+"','"+nket+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdskpd1+"')";
						} else {
							csql = "values('"+nlpj+"','"+cnobukti1+"','"+b+"','"+ckdgiat+"','"+nket+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdskpd1+"')";                                            
							}                                             
						}   	                  
						$(document).ready(function(){

							$.ajax({
								type: "POST",   
								dataType : 'json',                 
								data: ({nlpj:nlpj,sql:csql}),
								url: '<?php echo base_url(); ?>/index.php/lpj/simpan_lpj',
								success:function(data){                        
									status = data.pesan;   
									 if (status=='1'){
										$("#loading").hide();
										alert('Data Berhasil Tersimpan...!!!');
										$("#no_simpan").attr("value",nlpj);
										lcstatus='edit';
										//$("#no_simpan").attr("value",cnokas);
									} else{ 
										$("#loading").hide();
										lcstatus='tambah';
										alert('Detail Gagal Tersimpan...!!!');
									}                                             
								}
							});
							});            
						}
		//Akhir
				}
			});
		});
		//----
		 }
		}
		});
		});
		
		}
		//else
		 else{
			
				$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:nlpj,tabel:'trhlpj',field:'no_lpj'}),
                    url: '<?php echo base_url(); ?>/index.php/lpj/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && nlpj!=no_simpan){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || nlpj==no_simpan){
						
		//---------			
			$(document).ready(function(){
			$.ajax({
				type: "POST",       
				dataType : 'json',         
				  url      : "<?php  echo base_url(); ?>index.php/lpj/update_hlpj_up",
				data     : ({nlpj:nlpj,tgllpj:b,ket:nket,tgl_awal:c,tgl_akhir:d,no_simpan:no_simpan}),
				beforeSend:function(xhr){
                $("#loading").show();
					},
				success:function(data){
				status = data;
				if (status=='0'){
				   $("#loading").hide();
				   alert('Gagal Simpan...!!');
				   exit();
				} else if (status !='0'){ 
				
		        $('#dg1').datagrid('selectAll');
				var rows = $('#dg1').datagrid('getSelections'); 
				for(var i=0;i<rows.length;i++){            
						cidx      = rows[i].idx;
						cnobukti1 = rows[i].no_bukti;
                        ckdskpd1 = rows[i].kd_bp_skpd;
						ckdgiat   = rows[i].kdkegiatan;
						cnmgiat   = rows[i].nmkegiatan;
						ckdrek    = rows[i].kdrek5;
						cnmrek    = rows[i].nmrek5;
						cnilai    = angka(rows[i].nilai1);
						no        = i + 1 ; 
						cgiat = ckdgiat.substr(0,21);
                        
						if (i>0) {
							csql = csql+","+"('"+nlpj+"','"+cnobukti1+"','"+b+"','"+ckdgiat+"','"+nket+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdskpd1+"')";
						} else {
							csql = "values('"+nlpj+"','"+cnobukti1+"','"+b+"','"+ckdgiat+"','"+nket+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdskpd1+"')";                                            
							}                                             
						}   	                  
						$(document).ready(function(){
							//alert(csql);
							//exit();
							$.ajax({
								type: "POST",   
								dataType : 'json',                 
								data: ({nlpj:nlpj,sql:csql,no_simpan:no_simpan}),
								url: '<?php echo base_url(); ?>/index.php/lpj/simpan_lpj_update',
								success:function(data){                        
									status = data.pesan;   
									 if (status=='1'){
										$("#loading").hide();
										alert('Data Berhasil Tersimpan...!!!');
										$("#no_simpan").attr("value",nlpj);
										lcstatus='edit';
										//$("#no_simpan").attr("value",cnokas);
									} else{ 
										$("#loading").hide();
										lcstatus='tambah';
										alert('Detail Gagal Tersimpan...!!!');
									}                                             
								}
							});
							});            
						}
		//Akhir
				}
			});
		});
		//-----
		}
			}
			});
		});
			
		}
	 }	
			
			

    
     function load_sum_lpj(){          
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/lpj/load_sum_lpj",
            data:({lpj:nomer}),
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){

                    $("#rektotal").attr('value',number_format(n['cjumlah'],2,'.',','));
                });
            }
         });
        });
    }
    
	
	function load_sum_rinci(){  
		var dtgl1        = $('#dd1').datebox('getValue') ;
        var dtgl2        = $('#dd2').datebox('getValue') ;	
        $(function(){      
         $.ajax({
            type: 'POST',
                url: '<?php echo base_url(); ?>/index.php/lpj/load_sum_data_transaksi_lpj',
                data: ({tgl1:dtgl1,tgl2:dtgl2}),
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){

                    $("#rektotal").attr('value',number_format(n['jumlah'],2,'.',','));
                  //  $("#rektotal1").attr('value',number_format(n['rektotal'],2,'.',','));
                });
            }
         });
        });
    }
    

   
   
    function tombol(status_lpj){
    if (status_lpj==2) {
        $('#save').hide();
        $('#del').hide();
        document.getElementById("p1").innerHTML="Sudah disetujui...!!!";
     } else if (status_lpj==1) {
        $('#save').hide();
        $('#del').hide();
        document.getElementById("p1").innerHTML="Sudah dibuat SPP...!!!";
	 } else {
         $('#save').show();
         $('#del').show();
         document.getElementById("p1").innerHTML="";
     }
    }	
    
        
    function openWindow(url)
    {
        var vnospp  =  $("#cspp").combogrid("getValue");
		 var vnospp =vnospp.split("/").join("123456789");
		 var vnospp =vnospp.split(" ").join("abcde");
		 
		        lc  =  "?nomerspp="+vnospp+"&kdskpd="+kode+"&jnsspp="+jns ;
        window.open(url+lc,'_blank');
        window.focus();
    }
    
        
    function detail_trans_3(){
        
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/lpj/select_data1_lpj_ag',
                queryParams:({ lpj:nomer }),
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
                     {field:'tgl_bukti',title:'Tanggal',width:80,align:'center'},
                     {field:'no_bukti',
					 title:'No Bukti',
					 width:80,
					 align:'left'
					 },                                          
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:160,
					 align:'left'
					 },
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					 },
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:260
					 },
                    {field:'nilai1',
					 title:'Nilai',
					 width:120,
                     align:'right'
                     },
                    {field:'kd_bp_skpd',title:'SKPD',width:50,align:'center',
                     hidden:'true'},
                    {field:'hapus',title:'',width:35,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_2();" />';
                    }
                    }
				]]	
			});
		});
        }
        

        function detail_trans_kosong(){
		$('#load').show();
		$('#load_kosong').hide();	
		$("#rektotal").attr("value",0)
        var no_kos = '' ;
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/lpj/select_data1_lpj',
                queryParams:({ lpj:no_kos }),
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
                               {field:'tgl_bukti',title:'Tanggal',width:80,align:'center'},             
                               {field:'no_bukti',
          					 title:'No Bukti',
          					 width:80,
          					 align:'left'
          					 },                                          
                    {field:'kdkegiatan',
          					 title:'Kegiatan',
          					 width:160,
          					 align:'left'
          					 },
          					{field:'kdrek5',
          					 title:'Rekening',
          					 width:70,
          					 align:'left'
          					 },
          					{field:'nmrek5',
          					 title:'Nama Rekening',
          					 width:260
          					 },
                              {field:'nilai1',
          					 title:'Nilai',
          					 width:120,
                     align:'right'
                     },
                    {field:'kd_bp_skpd',title:'SKPD',width:50,align:'center',
                     hidden:'true'},
                    {field:'hapus',title:'',width:35,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_grid();" />';
                    }
                    }
				]]	
			});
		});
        }
    

    function hhapus(){				
        var lpj = document.getElementById("no_lpj").value;              
        var urll= '<?php echo base_url(); ?>/index.php/lpj/hhapuslpj';             			    
        if (spp !=''){
            var del=confirm('Anda yakin akan menghapus LPJ '+lpj+'  ?');
            if  (del==true){
               $(document).ready(function(){
                $.post(urll,({no:lpj}),function(data){
                    status = data;

                });
                $('#spp').edatagrid('reload'); 
            });				
           }
       } 
   }
  
    function hapus_detail_grid(){
        
        var a          = document.getElementById('no_lpj').value;
        var rows       = $('#dg1').edatagrid('getSelected');
        var ctotal_lpj = document.getElementById('rektotal').value;
        
        bbukti      = rows.no_bukti;
        bkdrek      = rows.kdrek5;
        bkdkegiatan = rows.kdkegiatan;
        bnilai      = rows.nilai1;
        ctotal_lpj  = angka(ctotal_lpj) - angka(bnilai) ;
        
        var idx = $('#dg1').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, No Bukti :  '+bbukti+'  Rekening :  '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#rektotal').attr('value',number_format(ctotal_lpj,2,'.',','));
            $('#dg1').datagrid('deleteRow',idx);     
            $('#dg1').datagrid('unselectAll');
                
        }     
    }

  function cetakup(cetak)
        {
			var no_lpj = $('#cspp').combogrid('getValue');   
            var no_lpj = no_lpj.split("/").join("abcdefghij");				
            var no_lpj = no_lpj.split(" ").join("123456789");				
			var skpd   = kode; 
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
			var jns   = document.getElementById('jenis').value;
			var giat_lpj   = $("#giat_print").combogrid('getValue'); 
			var atas   =  document.getElementById('atas').value;
			var bawah   =  document.getElementById('bawah').value;
			var kanan   =  document.getElementById('kanan').value;
			var kiri   =  document.getElementById('kiri').value;


			if(ttd1==''){
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if(ttd2==''){
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 =ttd1.split(" ").join("a");
			var ttd_2 =ttd2.split(" ").join("a");
			if((jns=='0') || (jns=='1')){
				var url    = "<?php echo site_url(); ?>cetak_lpj/up";  
				window.open(url+'/'+ttd_1+'/'+skpd+'/'+cetak+'/'+ttd_2+'/'+no_lpj+'/'+jns+'/'+atas+'/'+bawah+'/'+kiri+'/'+kanan, '_blank');
				window.focus();
			}else{
				var url    = "<?php echo site_url(); ?>cetak_lpj/up_rinci";  
				window.open(url+'/'+ttd_1+'/'+skpd+'/'+cetak+'/'+ttd_2+'/'+no_lpj+'/'+giat_lpj+'/'+atas+'/'+bawah+'/'+kiri+'/'+kanan, '_blank');
				window.focus();
			}
        }
  
    
    function load_data() {
	   
		$('#load').hide();
		$('#load_kosong').show();
        var dtgl1        = $('#dd1').datebox('getValue') ;
        var dtgl2        = $('#dd2').datebox('getValue') ;
        //var ntotal_trans = document.getElementById('rektotal').value ; 
        //    ntotal_trans = angka(ntotal_trans) ;
        
        if ( dtgl1 == '' ) {
           alert('Isi Tanggal Awal Terlebih Dahulu...!!!'); 
           document.getElementById('dd1').focus() ;
           exit();
           }       
        if ( dtgl2 == '' ) {
           alert('Isi Tanggal S/D Terlebih Dahulu...!!!'); 
           document.getElementById('dd2').focus() ;
           exit();
           }
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/lpj/load_data_transaksi_lpj',
                data: ({tgl1:dtgl1,tgl2:dtgl2,kdskpd:kode}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){  
                    xtglbukti= n['tgl_bukti'];                             
                    xnobukti = n['no_bukti'];
                    xkdskpd  = n['kd_bp_skpd'];                                                                                         
                    xgiat    = n['kdkegiatan']; 
                    xkdrek5  = n['kdrek5'];
                    xnmrek5  = n['nmrek5'];
                    xnilai   = n['nilai1'];
                    
                    
                    $('#dg1').edatagrid('appendRow',{tgl_bukti:xtglbukti,no_bukti:xnobukti,kdkegiatan:xgiat,kdrek5:xkdrek5,nmrek5:xnmrek5,nilai1:xnilai,idx:i,kd_bp_skpd:xkdskpd}); 
                    $('#dg1').edatagrid('unselectAll');
                    });
                 }
            });
            });  
         load_sum_rinci(); 
			
    }
	
	
	function validate_jenis() {
			var jns   =  document.getElementById('jenis').value;
			 if (jns=='2') {
						$("#div1").show();
						} else {
						$("#div1").hide();
						}         	
        }
        	
	function cetak_reg_ali(cetak){			
			var skpd   = kode; 
			var cetak =cetak;
				var url    = "<?php echo site_url(); ?>/tukd/cetak_reg_lpj_ali_up"; 
				window.open(url+'/'+skpd+'/'+cetak);
				window.focus();				
        }
	
	function cetakup_nihil(cetak)
        {   
            var  ctglttd = $('#dd').datebox('getValue');
			var no_lpj = $('#cspp').combogrid('getValue');   
            var no_lpj = no_lpj.split("/").join("abcdefghij");				
            var no_lpj = no_lpj.split(" ").join("123456789");				
			var skpd   = kode; 
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
			var jns   = document.getElementById('jenis').value;
			var giat_lpj   = $("#giat_print").combogrid('getValue'); 
			var atas   =  document.getElementById('atas').value;
			var bawah   =  document.getElementById('bawah').value;
			var kanan   =  document.getElementById('kanan').value;
			var kiri   =  document.getElementById('kiri').value;

			
			if(ttd1==''){
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if(ttd2==''){
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 =ttd1.split(" ").join("a");
			var ttd_2 =ttd2.split(" ").join("a");
			if((jns=='0') || (jns=='1')){
				var url    = "<?php echo site_url(); ?>/tukd/cetaklpjup_ag_nihil";  
				window.open(url+'/'+ttd_1+'/'+skpd+'/'+cetak+'/'+ttd_2+'/'+no_lpj+'/'+jns+'/'+atas+'/'+bawah+'/'+kiri+'/'+kanan+'/'+ctglttd, '_blank');
				window.focus();
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
<h3><a href="#" id="section4" onclick="javascript:$('#spp').edatagrid('reload')">List LPJ </a></h3>
<div >
	<p align="left">
		<marquee direction="left" scrollamount="5" align="center" style="font-size:20px; color:#FF0000;">"Setelah Membuat LPJ Di Harapkan untuk Klik Icon Cetak di Bawah  (Cek Data Antara Transaksi SPJ dan Transaksi LPJ)"</marquee>	
	</p> 
    <p align="right">          
		<button class="button-abu" onclick="javascript:cetak_reg_ali(2);"><i class="fa fa-print" style="font-size:15px"></i> Cetak</button>
        <button class="button" onclick="javascript:section1();kosong();"><i class="fa fa-tambah" style="font-size:15px"></i> Tambah</button>      
        <input type="text" placeholder="Pencarian" class="input" onkeyup="javascript:cari();" style="display: inline;" value="" id="txtcari"/>
        <table id="spp" title="List LPJ" style="width:1024px;height:450px;" >  
        </table>
    </p> 
</div>

<h3><a href="#" id="section1">Input LPJ</a></h3>

   <div  >
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>

      

<table border='0' style="font-size:11px" >
 
  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
 
   <td width='20%'>SKPD</td>
   <td width="80%">     
        <input id="dn" name="dn"  readonly="true" style="width:200px; border: 0; " />  - <input name="nmskpd" id="nmskpd" style="width: 500px; border-style: none" ></textarea>     
        </td> 
		
		
 
 
 

  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
 
     <td width='20%'  style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"></td>
     <td width='31%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"></td>
		
		
		    <td width='20%'  style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"></td>
     <td width='31%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"></td>

		
		
</tr>



  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
   <td  width='20%'>No LPJ</td>
   <td width='80%'><input type="text" class="input"  name="no_lpj" id="no_lpj" onclick="javascript:select();" placeholder="Nomor LPJ Tanpa Spasi" style="width:225px" onkeypress="javascript:enter(event.keyCode,'dd');"/> 
   <input type="text" name="no_simpan" id="no_simpan" onclick="javascript:select();" style="border:0;width:225px" readonly="true" /> 
   
   </td>
   

   

 </tr>
 
 
  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"> 
   <td  width='20%'>Tanggal</td>
 <td>&nbsp;<input id="dd" name="dd" type="text" style="width:230px" onkeypress="javascript:enter(event.keyCode,'keterangan');"/></td>   
 


 </tr>
  <tr>
      <td width='20%'  style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">KETERANGAN </td>
     <td width='31%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><textarea  name="keterangan" id="keterangan" cols="30" rows="2" ></textarea></td>
  </tr>
<tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"> 
   <td  width='20%'>Nilai UP</td>
 <td>&nbsp;Rp. <input id="total_up1" name="total_up1" type="text" style="width:150px; text-align: right" disabled /> <input id="total_up" name="total_up" type="text" style="width:150px; text-align: right" hidden /></td>   
  </tr>
 
 <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
   <td width='20%'></td>
   <td width="80%">&nbsp; </td> 
 <tr style="height: 30px;">
      <td colspan="4">
                  <div align="right">
                    <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>-->
                    <button class="button-biru" onclick="javascript:simpan();"><i class="fa fa-save" style="font-size:15px"></i> SIMPAN</button>
                    <button class="button-merah" onclick="javascript:hhapus();section4();"><i class="fa fa-hapus" style="font-size:15px"></i> HAPUS</button>
                    <button class="button-abu" onclick="javascript:cetak();"><i class="fa fa-print" style="font-size:15px"></i> cetak</button>
                    <button class="button-cerah" onclick="javascript:section4();"><i class="fa fa-kiri" style="font-size:15px"></i> Kembali</button>
                  </div>
        </td>                
  </tr>

  
   <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"> 
  
     <td colspan='6' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal Transaksi</td>  
  </tr>

  <tr style="height: 10px;">
     <td colspan='4' >
     <input id="dd1" name="dd1" type="text" style="width:95px" />&nbsp;S/D&nbsp;<input id="dd2" name="dd2" type="text" style="width:95px"/>
     <button id="load" class="button-cerah"  onclick="javascript:load_data();"><i class="fa fa-data" style="font-size:15px"></i> TAMPILKAN DATA</button>
     <button id="load_kosong" class="button-cerah"  onclick="javascript:detail_trans_kosong();"><i class="fa fa-clear" style="font-size:15px"></i> KOSONGKAN DATA </button>
     </td>  
  </tr>


  </table>
   
        <table id="dg1" title="Input Detail LPJ" style="width:1024px;height:300%;" >  
        </table>
        

         
        <table border='0' style="width:100%;height:5%;"> 
             <td width='34%'></td>
             <td width='35%'><input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:140px" align="right" readonly="true" ></td>
             <td width='6%'><B>Total</B></td>
             <td width='25%'><input class="right" type="text" name="rektotal" id="rektotal"  style="width:140px" align="right" readonly="true" ></td>
        </table>

   </p>

    
</div>
</div>
			<div id="loading" class="loader1"> <div class="loader2"></div>

			</div>


</div> 

<div id="dialog-modal" title="CETAK LPJ">
    <p class="validateTips">SILAHKAN PILIH SPP</p>  
    <fieldset>
        <table>
		<tr>            
		<td width="200px" >NO LPJ:</td>
		<td><input id="cspp" name="cspp" style="width: 200px; " /></td>
		</tr>
		<tr>
		<td>Bendahara Pengeluaran</td>
		<td><input type="text" id="ttd1" style="width: 200px;" /></td> &nbsp;&nbsp; 
		<td><input type="text" id="nm_ttd1" readonly="true" style="width: 150px;border:0" /> </td>
		</tr>
		<tr>
		<td>Pengguna Anggaran</td>
		<td><input type="text" id="ttd2" style="width: 200px;" /></td> &nbsp;&nbsp;
		<td> <input type="nm_ttd2" id="nm_ttd2" readonly="true" style="width: 150px;border:0" /> </td>
		</tr>
		<tr >
		<td>Pilihan</td><td>
		<select name="jenis" id="jenis" onchange="javascript:validate_jenis();" >
		 <option value=" "> --Pilih--</option>     
		 <option value="0"> Rinci</option> 
		 <option value="1"> Rekap Rincian</option>
		 <option value="2"> Rincian Perkegiatan</option>
		</td>
		</tr>

		
		<tr>
		<td colspan="3">
		<div id="div1">
			<table style="width:100%;" border="0">
			<td width="200px"></td>
			<td><input id="giat_print" name="giat_print" style="width: 200px; " /></td>
			</td>
			</table>
		</div>
		</td>
        </tr>


     		<tr >
    			<td colspan='3'width="100%" height="40" ><strong>Ukuran Margin Untuk Cetakan PDF (Milimeter)</strong></td>
    		</tr>
    		<tr >
    			<td colspan='3'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    			Kiri  : &nbsp;<input type="number" id="kiri" name="kiri" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			Kanan : &nbsp;<input type="number" id="kanan" name="kanan" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			Atas  : &nbsp;<input type="number" id="atas" name="atas" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			Bawah : &nbsp;<input type="number" id="bawah" name="bawah" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			</td>
    		</tr>		
		
		
        </table>  
    </fieldset>
    
    <div>
    </div>     

    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetakup(0);">Cetak</a>
	<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetakup_nihil(0);">Cetak LPJ GU NIHIL</a>
	
	<br/>
	<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetakup(1);">Cetak </a>      
    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetakup_nihil(1);">Cetak LPJ GU NIHIL</a>         
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
	     

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