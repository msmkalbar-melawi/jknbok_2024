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
    
    var kode = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
                    
     $(document).ready(function() {
            
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 510,
            width: 900,
            modal: true,
            autoOpen:false,
        });
			$("#loading").dialog({
					resizable: false,
					width:200,
					height:130,
					modal: true,
					draggable:false,
					autoOpen:false,    
					closeOnEscape:false
			});

        get_skpd();
		get_tahun();
        
        });    
     
  
    
     
     $(function(){ 
        /* 
       $('#dg').edatagrid({
		url: '',
         idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        //pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_bukti',
    		title:'Nomor Bukti',
    		width:50,
            align:"center"},
            {field:'tgl_bukti',
    		title:'Tanggal',
    		width:30},
            {field:'kd_skpd',
    		title:'S K P D',
    		width:30,
            align:"center"},
            
            {field:'nilai',
    		title:'Nilai',
    		width:50,
            align:"center"}
        ]]
        });*/
 
 			$('#dg').edatagrid({
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
                columns:[[
					{field:'',
					 title:'',
					 width:140,
					 editor:{type:"text"}
					}
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


         $('#tanggal1').datebox({  
            required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });

         $('#tanggal2').datebox({  
            required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
			
        
		$('#tgl_panjar').datebox({  
            required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
		
        /*	
		$('#no_panjar').combogrid({  
                   panelWidth : 200,  
                   idField    : 'no_panjar',  
                   textField  : 'no_panjar',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/tukd/load_no_kpanjar',  
                   columns:[[  
                       {field:'no_panjar',title:'No Panjar',width:110},
                       {field:'tgl_panjar',title:'Tgl Panjar',width:90}   
                   ]],  
                   onSelect:function(rowIndex,rowData){
					$("#tgl_panjar").datebox("setValue",rowData.tgl_panjar);
				   load_total();
				   load_detail();
				  
                   }  
                   });
		*/
		
		
		$('#kd_giat').combogrid({  
                   panelWidth : 650,  
                   idField    : 'kd_kegiatan',  
                   textField  : 'kd_kegiatan',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/tukd/ld_giat_panjar',  
                   columns:[[  
                       {field:'kd_kegiatan',title:'Kode Kegiatan',width:170},  
                       {field:'nm_kegiatan',title:'Nama Kegiatan',width:250},
					   {field:'transaksi',title:'Transkasi Lalu',width:100},  
                       {field:'anggaran',title:'Anggaran',width:100}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
				   load_sisa_tunai();
				   sisa_anggaran = (rowData.anggaran)-(rowData.transaksi);               
				   $("#nm_giat").attr("value",rowData.nm_kegiatan); 
                   $("#sisa_ang").attr("value",number_format(sisa_anggaran,2,'.',',')); 
                   }  
                   });
		
		
		
        $('#tanggal_kas').datebox({  
            required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
    
        //$('#skpd').combogrid({  
//           panelWidth:700,  
//           idField:'kd_skpd',  
//           textField:'kd_skpd',  
//           mode:'remote',
//           url:'<?php echo base_url(); ?>index.php/tukd/skpd_2',  
//           columns:[[  
//               {field:'kd_skpd',title:'Kode SKPD',width:100},  
//               {field:'nm_skpd',title:'Nama SKPD',width:700}    
//           ]],  
//           onSelect:function(rowIndex,rowData){
//               kode = rowData.kd_skpd;               
//               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
//               $('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tetap/'+kode});                 
//           }  
//       });
         
                  
         

      
    });

    function dateToDMY(date) {
        var d = date.getDate();
        var m = date.getMonth() + 1;
        var y = date.getFullYear();
        return (d <= 9 ? '0' + d : d) + '-' + (m<=9 ? '0' + m : m) + '-' + y ;
    }


    function ambil(){
        var tgls = $('#tanggal1').datebox('getValue');
		var tgls2 = $('#tanggal2').datebox('getValue');
		$("#tanggal1").datebox('disable');
		$("#tanggal2").datebox('disable');
     
		var skpd =  document.getElementById('skpd').value;
		
		if(tgls==''){
			alert('Tanggal Terima tidak boleh kosong');
			return;
		}
		if(tgls2==''){
			alert('Tanggal Setor tidak boleh kosong');
			return;
		}		
		
		
        var d = new Date(tgls);
        var tgl2 = ("0" + d.getDate()).slice(-2) + "" + ("0"+(d.getMonth()+1)).slice(-2) + "" +d.getFullYear(); 

        var tgls2 = new Date(tgls2);
        var tgls2 = tgls2.getFullYear()+ "" + ("0"+(tgls2.getMonth()+1)).slice(-2) + "" +("0" + tgls2.getDate()).slice(-2) ; 
		
		
        $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/csamsat/load_samsat/'+tgl2+'/'+skpd+'/'+tgls+'/'+tgls2,
        idField:'id',            
        rownumbers:"true", 
        fitColumns:false,
        singleSelect:"true",
        //autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        //pagination:"true",
        nowrap: false, 
        fit:"true",                      
        columns:[[
            {field:'id',title:'id',checkbox:true,hidden:true},
            {field:'no_tetap',title:'No Penetapan',width:300,align:"left"},
            {field:'no_terima',title:'No Terima',width:300,align:"left"},
            {field:'no_sts',title:'No STS',width:300,align:"left"},
            {field:'tgl_samsat',title:'Tgl SAMSAT',width:100,align:"left"},
            {field:'kd_skpd',title:'KD SKPD',width:100,align:"left"},
            {field:'no_rek',title:'Kd Rekening',width:100,align:"right"},
            {field:'nm_rek5',title:'nm_rek5',width:80,align:"left",hidden:true},
            {field:'jenis',title:'Jenis',width:80,align:"left",hidden:true},
            {field:'kd_pengirim',title:'Kode Pengirim',width:100,align:"left",hidden:true},
            {field:'nm_pengirim',title:'nm_pengirim',width:100,align:"left"},
            {field:'nilai',title:'nilai',width:100,align:"right"},
            {field:'kd_rek_lo',title:'Rek LO',width:100,align:"right",hidden:true},
            {field:'keterangan',title:'Keterangan',width:500,align:"left"},
            {field:'kd_kegiatan',title:'Kegiatan',width:200,align:"left",hidden:true}
        ]]/*,
        onSelect:function(rowIndex,rowData){
          nomor = rowData.no_bukti;
          tgl   = rowData.tgl_bukti;
        
          kode  = rowData.kd_skpd;
          lcket = rowData.keterangan;
          lcnilai = rowData.nilai;
        
          no_panjar = rowData.no_panjar;
          tgl_panjar = rowData.tgl_panjar;
          lcidx = rowIndex;
          get(nomor,tgl,kode,lcket,lcnilai,no_panjar,tgl_panjar);   
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Penetapan'; 
           edit_data();   
        }
        */
        });   
    }
    
	function load_total(){
        var nopanjar   = $("#no_panjar").combogrid("getValue") ; 
         $.ajax({
            type: 'POST',
            data: ({no:nopanjar}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_total_kpanjar",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#total_panjar").attr("value",n['panjar']);
                    $("#trans").attr("value",n['trans']);
                    $("#sisa_panjar").attr("value",n['sisa']);
                   // $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
    }
	
	function load_total_edit(){
        var nopanjar   = $("#no_panjar").combogrid("getValue") ; 
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({no:nopanjar}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_total_kpanjar",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#total_panjar").attr("value",n['panjar']);
                    $("#trans").attr("value",n['trans']);
                    //$("#sisa_panjar").attr("value",n['sisa']);
                   // $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
        });
    }
	function load_detail(){
        var nopanjar   = $("#no_panjar").combogrid("getValue") ; 
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({no:nopanjar}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_detail_kpanjar",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#panjar_awal").attr("value",n['no_panjar']);
                    $("#panjar_tambah").attr("value",n['no_panjar2']);
                    $("#nilai_panjar_awal").attr("value",n['nilai']);
                    $("#nilai_panjar_tambah").attr("value",n['nilai2']);
                   // $("#rekspm1").attr("value",n['rekspm1']);
                });
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
        								$("#skpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
        								kode = data.kd_skpd;
                                        
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
     function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });   
     }

    
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
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
                   // $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
        });
    }   
    function get(nomor,tgl,kode,lcket,lcnilai,no_panjar,tgl_panjar)   {
        $("#no_simpan").attr("value",nomor);
        $("#nomor").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#tgl_panjar").datebox("setValue",tgl_panjar);
        //$("#skpd").combogrid("setValue",kode); 
        $("#sisa_panjar").attr("value",lcnilai);
        $("#ket").attr("value",lcket);
        $("#no_panjar").combogrid("setValue",no_panjar);
		load_total_edit();
		load_detail();
		lcstatus = 'edit';      
    }
    
    function kosong(){
        $("#no_simpan").attr("value",'');
        $("#nomor").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#no_panjar").combogrid("setValue",'');
        $("#sisa_panjar").attr("value",'');        
        $("#ket").attr("value",''); 
		lcstatus = 'tambah';
		get_nourut();

    }
	
	function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/no_urut',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        							// $("#no_kas").attr("value",data.no_urut);
        								$("#nomor").attr("value",data.no_urut);
        							  }                                     
        	});  
        }
	
    
    function cari(){/*
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_kpanjar',
        queryParams:({cari:kriteria})
        });        
     });*/
    }
     
    function simpan(){
		
		var tgls2 = $('#tanggal2').datebox('getValue');
		
		if(tgls2==''){
			alert('Tanggal Setor tidak boleh kosong');
			return;
		}
        var d = new Date(tgls2);
        var tgl3 = d.getFullYear()+ "" + ("0"+(d.getMonth()+1)).slice(-2) + "" +("0" + d.getDate()).slice(-2) ; 
		
		
        var data = $('#dg').datagrid('getData');
        var rows = data.rows;
        var cekrek = 0;
        var cekuptbyr = 0;
        for (i=0; i < rows.length; i++) {
            if(rows[i].nm_rek5==''){
                cekrek = 1;
                kdrek5 = rows[i].no_rek;
            }
        }

        for (i=0; i < rows.length; i++) {
            if(rows[i].nm_pengirim==''){
                cekuptbyr = 1;
                kduptbyr = rows[i].kd_pengirim;
            }
        }
        //alert(cekrek);

        if(cekrek==1){
            alert('Kode Rekening '+kdrek5+' belum ada di Simakda. Hubungi Konsultan. Trims');
            return;            
        }	


        if(cekuptbyr==1){
            alert('Kode Pengirim '+kduptbyr+' belum ada di Simakda. Hubungi Konsultan. Trims');
            return;            
        }	

        var csqltetap = ''; var csqlterima = ''; var csqlkasin = ''; var csqlkasin2 = '';
        $('#dg').datagrid('selectAll');
        var rows = $('#dg').datagrid('getSelections');
        for(var i=0;i<rows.length;i++){ 
            cno_tetap     = rows[i].no_tetap.trim();
            cno_terima    = rows[i].no_terima.trim();
            cno_sts       = rows[i].no_sts.trim();
            dtgl_samsat   = rows[i].tgl_samsat.trim();
			dtgl_setor   = rows[i].tgl_samsat.trim();			
            ckd_skpd      = rows[i].kd_skpd.trim();
            cno_rek       = rows[i].no_rek.trim();
            cjenis        = '1';//rows[i].jenis;
            ckd_pengirim  = rows[i].kd_pengirim.trim();
            nilai        = angka(rows[i].nilai);
            ckd_rek_lo    = rows[i].kd_rek_lo.trim();
            cketerangan   = rows[i].keterangan.trim();
            ckd_kegiatan  = rows[i].kd_kegiatan.trim();
			

			if(i>0){
				csqltetap = csqltetap+","+"('"+cno_tetap+"','"+dtgl_samsat+"','"+ckd_skpd+"','"+cno_rek+"','"+ckd_kegiatan+"','"+ckd_rek_lo+"','"+nilai+"',"+
                            "'"+cketerangan+"','samsat')";

				csqlterima = csqlterima+","+"('"+cno_terima+"','"+dtgl_samsat+"','"+cno_tetap+"','"+dtgl_samsat+"','1','"+ckd_skpd+"',"+
                            "'"+ckd_kegiatan+"','"+cno_rek+"','"+ckd_rek_lo+"','"+nilai+"','"+cketerangan+"',"+cjenis+",'samsat','"+ckd_pengirim+"','0')";

				csqlkasin = csqlkasin+","+"('"+cno_sts+"','"+ckd_skpd+"','"+tgls2+"','"+cketerangan+"','"+nilai+"','"+ckd_kegiatan+"','4','0','"+ckd_pengirim+"','"+cno_terima+"','samsat')";
                
                csqlkasin2 = csqlkasin2+","+"('"+ckd_skpd+"','"+cno_sts+"','"+cno_rek+"','"+nilai+"','"+ckd_kegiatan+"','"+cno_terima+"')";

			}else{
				csqltetap = "values('"+cno_tetap+"','"+dtgl_samsat+"','"+ckd_skpd+"','"+cno_rek+"','"+ckd_kegiatan+"','"+ckd_rek_lo+"','"+nilai+"',"+
                            "'"+cketerangan+"','samsat')";                

				csqlterima = "values('"+cno_terima+"','"+dtgl_samsat+"','"+cno_tetap+"','"+dtgl_samsat+"','1','"+ckd_skpd+"',"+
                            "'"+ckd_kegiatan+"','"+cno_rek+"','"+ckd_rek_lo+"','"+nilai+"','"+cketerangan+"',"+cjenis+",'samsat','"+ckd_pengirim+"','0')";                

				csqlkasin = "values('"+cno_sts+"','"+ckd_skpd+"','"+tgls2+"','"+cketerangan+"','"+nilai+"','"+ckd_kegiatan+"','4','0','"+ckd_pengirim+"','"+cno_terima+"','samsat')";                

                csqlkasin2 = "values('"+ckd_skpd+"','"+cno_sts+"','"+cno_rek+"','"+nilai+"','"+ckd_kegiatan+"','"+cno_terima+"')"; 
			}                                             
        }
        $('#save').linkbutton('disable');
		$(document).ready(function(){
			//alert(csql);
			//exit();
			$.ajax({
				type: "POST",   
				dataType : 'json',                 
				data: ({sqltetap:csqltetap,sqlterima:csqlterima,sqlkasin:csqlkasin,sqlkasin2:csqlkasin2,kdskpd:ckd_skpd,dtgl:dtgl_samsat,dtgl2:tgls2}),
				url: '<?php echo base_url(); ?>/index.php/csamsat/dsimpan_samsat',
				beforeSend:function(xhr){
					$("#loading").dialog('open');
				},
				success:function(data){                        
					status = data.pesan;   
					 if (status=='1'){
						$("#loading").dialog('close');  
						alert('Data Berhasil Tersimpan...!!!');
						$('#save').linkbutton('enable');
					} else{ 
						$("#loading").dialog('close'); 
						alert('Detail Gagal Tersimpan...!!!');
						$('#save').linkbutton('enable');
					}                                             
				}
			});
			});  
          $('#dg').datagrid('unselectAll');    
			
    }    
    
       function simpan_tetap(){
        var cno_kas = document.getElementById('nomor').value;
        var no_simpan = document.getElementById('no_simpan').value;
        var ctgl_kas = $('#tanggal').datebox('getValue');
        var ctgl_panjar = $('#tgl_panjar').datebox('getValue');
        var cno = document.getElementById('nomor').value;
        var ctgl = $('#tanggal').datebox('getValue');
        var cno_panjar   = $("#no_panjar").combogrid("getValue") ; 
        var cskpd = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
        var lcket = document.getElementById('ket').value;
        var sisa_panjar = angka(document.getElementById('sisa_panjar').value);
            //lctotal = number_format(lntotal,0,'.',',');
        //alert(jaka);
		
		
 
		
        if (cno==''){
            alert('Nomor  Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal  Tidak Boleh Kosong');
            exit();
        }
        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        //alert(lcstatus)
       var tahun_input = ctgl.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			alert('Tahun tidak sama dengan tahun Anggaran');
			exit();
		}
        /*
       if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'tr_panjar',field:'no_panjar',tabel2:'tr_jpanjar',field2:'no_kas'}),
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
                 //-------   
                    lcinsert = "(no_kas,tgl_kas,no_panjar,tgl_panjar, kd_skpd,nilai,keterangan,jns,no_panjar_lalu)";
                    lcvalues = "('"+cno+"','"+ctgl+"','"+cno_panjar+"','"+ctgl_panjar+"','"+cskpd+"','"+sisa_panjar+"','"+lcket+"','2','"+cno_panjar+"')";
                    $(document).ready(function(){
                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url(); ?>/index.php/tukd/simpan_kpanjar',
                            data: ({tabel:'tr_jpanjar',kolom:lcinsert,nilai:lcvalues,cid:'no_kas',lcid:cno,no_panjar:cno_panjar}),
                            dataType:"json",
                            success:function(data){
                                status = data;
                                if (status=='0'){
                                    alert('Gagal Simpan..!!');
                                    exit();
                                }else if(status=='1'){
                                    alert('Data Sudah Ada..!!');
                                    exit();
                                }else{
                                    alert('Data Tersimpan..!!');
									lcstatus='edit';
									$("#no_simpan").attr("value",cno);
									$('#dg').edatagrid('reload');
                                    exit();
                                }
                            }
                        });
                    });    
                 //-------
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
                    data: ({no:cno,tabel:'tr_panjar',field:'no_panjar',tabel2:'tr_jpanjar',field2:'no_kas'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cno!=no_simpan){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || cno==no_simpan){
						alert("Nomor Bisa dipakai");
			
			
		//---------
                    
                    lcquery = "UPDATE tr_jpanjar SET no_kas ='"+cno+"',tgl_kas='"+ctgl+"',tgl_panjar='"+ctgl_panjar+"',keterangan='"+lcket+"',nilai='"+sisa_panjar+"',no_panjar='"+cno_panjar+"',no_panjar_lalu='"+cno_panjar+"'where no_kas='"+no_simpan+"' AND kd_skpd='"+cskpd+"' ";
                 
				 //   lcquery2 = "UPDATE tr_kpanjar SET no_panjar ='"+cno+"',no_kas='"+cno_kas+"',tgl_kas='"+ctgl_kas+"',tgl_panjar='"+ctgl+"',keterangan='"+lcket+"',nilai='"+lntotal+"',pay='"+lctunai+"',no_tambah='"+cno+"' where no_panjar='"+no_simpan+"'";
                 //   alert(lcquery);
                    $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/tukd/update_kpanjar',
                        data: ({st_query:lcquery}),
                        dataType:"json",
                        success:function(data){
                                status = data;
                                if (status=='0'){
                                    alert('Gagal Simpan..!!');
                                    exit();
                                }else{
                                    alert('Data Tersimpan..!!');
									$("#no_simpan").attr("value",cno);
									$('#dg').edatagrid('reload');
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
        }
        
        //alert("Data Berhasil disimpan");
       
        //section1();*/
		$('#save').linkbutton('enable');
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Panjar';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        //document.getElementById("nomor").disabled=true;
        }    
        
    
     function tambah(){
		$("#tanggal1").datebox('enable');
		$("#tanggal2").datebox('enable');
		$('#dg').datagrid('loadData',[]);     
		//$('#dg').edatagrid('reload');   
     } 
	 
	 
	 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
		var tgls2 = $('#tanggal2').datebox('getValue');
		
		if(tgls2==''){
			alert('Tanggal Setor tidak boleh kosong');
			return;
		}
		/*
		var urll = '<?php echo base_url(); ?>index.php/csamsat/hapus_setor_samsat';
        $(document).ready(function(){
         $.post(urll,({dtgl:tgls2}),
		 function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                alert('Data Setor Berhasil Dihapus..!!');
                exit();
            }
         });
        });    */
			$.ajax({
				type: "POST",   
				dataType : 'json',                 
				data: ({dtgl:tgls2}),
				url: '<?php echo base_url(); ?>/index.php/csamsat/hapus_setor_samsat',
				success:function(data){                        
					status = data.pesan;   
					 if (status=='1'){
						alert('Data Berhasil Tersimpan...!!!');
					} else{ 
						alert('Detail Gagal Tersimpan...!!!');
					}                                             
				}
			});		
		
		
    } 
    
       
    function addCommas(nStr)
    {
    	nStr += '';
    	x = nStr.split(',');
        x1 = x[0];
    	x2 = x.length > 1 ? ',' + x[1] : '';
    	var rgx = /(\d+)(\d{3})/;
    	while (rgx.test(x1)) {
    		x1 = x1.replace(rgx, '$1' + '.' + '$2');
    	}
    	return x1 + x2;
    }
    
     function delCommas(nStr)
    {
    	nStr += ' ';
    	x2 = nStr.length;
        var x=nStr;
        var i=0;
    	while (i<x2) {
    		x = x.replace(',','');
            i++;
    	}
    	return x;
    }
  
    
  
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">DATA SAMSAT</a></b></u></h3>
    <div>
    <p align="left">         
        Tanggal Terima&nbsp;&nbsp;&nbsp;<input type="text" id="tanggal1" style="width: 140px;" />&nbsp;&nbsp;
		Tanggal Setor&nbsp;&nbsp;&nbsp;<input type="text" id="tanggal2" style="width: 140px;" />
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:ambil()">Ambil Data SAMSAT</a> 
        <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan()" >Simpan</a>  
		<a id="del" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Ubah Tgl</a>		
        <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>               
        <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>-->
        <table id="dg" title="Listing data samsat" style="width:870px;height:450px;" ">  
        </table>
 
    </p> 
    </div>   

</div>

</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
   <p id="p1" style="font-size:medium;color: red;"></p>
    <fieldset>
     <table align="center" style="width:100%;">
			<tr>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"></td>
				<td style="border-bottom: double 1px red;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/> &nbsp;&nbsp;<i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr>
	 
             <tr>
                <td>No. panjar</td>
                <td></td>
                <td><input type="text" id="nomor" style="width: 200px;"/></td>  
            </tr>             
            <tr>
                <td>Tanggal Terima</td>
                <td></td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>
            </tr>
            <tr>
                <td>S K P D</td>
                <td></td>
                <td><input id="skpd" name="skpd" style="width: 140px;" /> &nbsp;&nbsp; <input type="text" id="nmskpd" style="border:0;width: 400px;" readonly="true"/></td>                            
            </tr>
			
			<tr>
                <td>No Panjar</td>
                <td></td>
                <td><input id="no_panjar" name="no_panjar" style="width: 160px;" />&nbsp;&nbsp;<input type="text" id="tgl_panjar" style="width: 140px;" /></td>
                      
            </tr>
			<tr>
                <td>Panjar Awal</td>
                <td></td>
                <td><input id="panjar_awal" name="panjar_awal" style="border:0;width: 100px;" /> <input type="text" id="nilai_panjar_awal" style="border:0;width: 100px;text-align: right;" readonly="true"/></td>                            
            </tr> 
			<tr>
                <td>Tambahan Panjar</td>
                <td></td>
                <td><input id="panjar_tambah" name="panjar_tambah" style="border:0;width: 100px;" /> <input type="text" id="nilai_panjar_tambah" style="border:0;width: 100px;text-align: right;" readonly="true"/></td>                            
            </tr>
			<tr>
                <td>Total Panjar</td>
                <td></td>
                <td><input type="text" id="total_panjar" style="border:0;width: 205px; text-align: right;" readonly="true"/></td> 
            </tr>
			<tr>
                <td>Total Transaksi</td>
                <td></td>
                <td><input type="text" id="trans" style="border:0;width: 205px; text-align: right;"/></td> 
            </tr>
			
            <tr>
                <td>Sisa Panjar</td>
                <td></td>
                <td><input type="text" id="sisa_panjar" style="border:0;width: 205px; text-align: right;"/></td> 
            </tr>
          
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea rows="2" cols="50" id="ket" style="width: 600px;"></textarea>
                </td> 
            </tr>
           
            <tr>
                <td colspan="3" align="center"><a id="save1" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_tetap();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
<div id="loading" title="Loading...">
			<table align="center">
			<tr align="center"><td><img id="search1" height="50px" width="50px" src="<?php echo base_url();?>/image/loadingBig.gif"  /></td></tr>
			<tr><td>TUNGGU SEBENTAR. DATA SEDANG DIPROSES...</td></tr>
			</table>
</div>


  	
</body>

</html>