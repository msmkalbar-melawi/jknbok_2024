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
        get_skpd();
		get_tahun();
        });    
     
  
    
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_kpanjar',
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
        ]],
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
        
		$('#tgl_panjar').datebox({  
            required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
			
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
    
	function load_total(){
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
                    $("#sisa_panjar").attr("value",n['sisa']);
                   // $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
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
	
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_kpanjar',
        queryParams:({cari:kriteria})
        });        
     });
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
       
        //section1();
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Panjar';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        //document.getElementById("nomor").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Panjar';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        var urll = '<?php echo base_url(); ?>index.php/tukd/hapus_kpanjar';
        $(document).ready(function(){
         $.post(urll,({no:nomor,skpd:kode,nopanjar:no_panjar}),
		 function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                exit();
            }
         });
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
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN PANJAR</a></b></u></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>               
        <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="Listing data panjar" style="width:870px;height:450px;" >  
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
                <td>Tanggal </td>
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
                <td colspan="3" align="center"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_tetap();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>


  	
</body>

</html>