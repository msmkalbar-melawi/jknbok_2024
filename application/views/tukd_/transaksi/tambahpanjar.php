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
            height: 540,
            width: 900,
            modal: true,
            autoOpen:false,
        });
        get_skpd();
		get_tahun();
        });    
     
  
    
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_tpanjar',
         idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_panjar',
    		title:'Nomor Panjar',
    		width:50,
            align:"center"},
            {field:'tgl_panjar',
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
          nomor = rowData.no_panjar;
          tgl   = rowData.tgl_panjar;
          nokas = rowData.no_kas;
          tglkas   = rowData.tgl_kas;
          kode  = rowData.kd_skpd;
          lcket = rowData.keterangan;
          lcnilai = rowData.nilai;
          kd_kegiatan = rowData.kd_kegiatan;
          lcpay = rowData.pay;
		  status = rowData.status;
		  no_tambah = rowData.no_tambah;
          lcidx = rowIndex;
          get(nokas,tglkas,nomor,tgl,kode,lcket,lcnilai,lcpay,kd_kegiatan,status,no_tambah);
		  load_sum_panjar();
                                       
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
        
		$('#no_tpanjar').combogrid({  
                   panelWidth : 460,  
                   idField    : 'no_panjar',  
                   textField  : 'no_panjar',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/tukd/load_no_tpanjar',  
                   columns:[[  
                       {field:'no_panjar',title:'No. Panjar',width:100},  
                       {field:'tgl_panjar',title:'Tanggal',width:250},
					   {field:'nilai',title:'nilai',width:100}  
                   ]],  
                   onSelect:function(rowIndex,rowData){
				   $("#nilai_tpanjar").attr("value",number_format(rowData.nilai,2,'.',',')); 
				   no_tpanjar = rowData.no_panjar;               
				 $('#kd_giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ld_giat_tpanjar/'+no_tpanjar});                 
                   }  
                   });
		
		
		
		$('#kd_giat').combogrid({  
                   panelWidth : 650,  
                   idField    : 'kd_kegiatan',  
                   textField  : 'kd_kegiatan',  
                   mode       : 'remote',
                  // url        : '<?php echo base_url(); ?>index.php/tukd/ld_giat_panjar',  
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
    function get(nokas,tglkas,nomor,tgl,kode,lcket,lcnilai,lcpay,kd_kegiatan,status,no_tambah){

	   $("#no_kas").attr("value",nokas);
        $("#no_simpan").attr("value",nokas);
        $("#tanggal_kas").datebox("setValue",tglkas);
        $("#nomor").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        //$("#skpd").combogrid("setValue",kode); 
        $("#nilai").attr("value",lcnilai);
        $("#ket").attr("value",lcket);
        $("#jns_tunai").attr("value",lcpay);
        $("#kd_giat").combogrid("setValue",kd_kegiatan);
        $("#no_tpanjar").combogrid("setValue",no_tambah);
		if (status=='1'){
			$('#save').linkbutton('disable');
			$('#del').linkbutton('disable');
			document.getElementById("p1").innerHTML="   Sudah dipertanggungjawabkan!!";
			} else {
			 $('#save').linkbutton('enable');
			 $('#del').linkbutton('enable');
			document.getElementById("p1").innerHTML="";
			}
                
    }
    
    function kosong(){
        $("#no_kas").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#tanggal_kas").datebox("setValue",'');
        $("#nomor").attr("value",'');
        $("#total").attr("value",'');
        $("#nilai_tpanjar").attr("value",'');
        $("#nm_giat").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#kd_giat").combogrid("setValue",'');
        $("#no_tpanjar").combogrid("setValue",'');
        //$("#nmskpd").attr("value",'');
        $("#nilai").attr("value",'');        
        $("#ket").attr("value",''); 
        $("#jns_tunai").attr("value",'');
		document.getElementById("p1").innerHTML=" ";
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
		url: '<?php echo base_url(); ?>/index.php/tukd/load_tpanjar',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
	 function load_sum_panjar(){                
        var nomor   = $("#no_tpanjar").combogrid("getValue") ; 
			//alert(nokas);
        $(function(){      
         $.ajax({
            type      : 'POST',
            data      : ({no:nomor}),
            url       : "<?php echo base_url(); ?>index.php/tukd/load_sum_tpanjar",
            dataType  : "json",
            success   : function(data){ 
                $.each(data, function(i,n){
                    $("#total").attr("value",number_format(n['rektotal1'],2,'.',','));
                    //$("#totalrekpajak").attr("value",n['rektotal']);
                });
            }
         });
        });
    }
    
    
       function simpan_tetap(){
        var cno_kas = document.getElementById('nomor').value;
        var no_simpan = document.getElementById('no_simpan').value;
        var ctgl_kas = $('#tanggal').datebox('getValue');
        var cno = document.getElementById('nomor').value;
        var ctgl = $('#tanggal').datebox('getValue');
        var ckd_giat   = $("#kd_giat").combogrid("getValue") ; 
        var cno_tpanjar   = $("#no_tpanjar").combogrid("getValue") ; 
        var cskpd = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
        var lcket = document.getElementById('ket').value;
        var lctunai = document.getElementById('jns_tunai').value;
        var lntotal = angka(document.getElementById('nilai').value);
        var sisa_ang = angka(document.getElementById('sisa_ang').value);
        var sisa_tunai = angka(document.getElementById('sisa_tunai').value);
            //lctotal = number_format(lntotal,0,'.',',');
		if (sisa_tunai<lntotal && sisa_anggaran<lntotal){
			alert('Tidak boleh melebihi sisa Kas Tunai dan Anggaran');
            exit();
		}
		if (sisa_tunai<lntotal || sisa_anggaran<lntotal){
			alert('Tidak boleh melebihi sisa Kas Tunai atau Anggaran');
            exit();
		}
		
		
		
        if (cno==''){
            alert('Nomor  Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal  Tidak Boleh Kosong');
            exit();
        }
		var tahun_input = ctgl.substring(0, 4);
		
		if (tahun_input != tahun_anggaran){
			alert('Tahun tidak sama dengan tahun Anggaran');
			exit();
		}
        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        //alert(lcstatus)
       
       if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'tr_panjar',field:'no_panjar'}),
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
                    lcinsert = "(no_kas,tgl_kas,no_panjar,tgl_panjar,kd_skpd,pengguna,nilai,keterangan,pay,rek_bank,kd_kegiatan,status,jns,no_panjar_lalu)";
                    lcvalues = "('"+cno_kas+"','"+ctgl_kas+"','"+cno+"','"+ctgl+"','"+cskpd+"','','"+lntotal+"','"+lcket+"','"+lctunai+"','','"+ckd_giat+"','0','2','"+cno_tpanjar+"')";
        
                    $(document).ready(function(){
                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                            data: ({tabel:'tr_panjar',kolom:lcinsert,nilai:lcvalues,cid:'no_panjar',lcid:cno}),
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
                    data: ({no:cno,tabel:'tr_panjar',field:'no_panjar'}),
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
                    
                    lcquery = "UPDATE tr_panjar SET no_panjar ='"+cno+"',no_kas='"+cno_kas+"',tgl_kas='"+ctgl_kas+"',tgl_panjar='"+ctgl+"',keterangan='"+lcket+"',nilai='"+lntotal+"',pay='"+lctunai+"',no_panjar_lalu='"+cno_tpanjar+"' where no_panjar='"+no_simpan+"'";
                    //alert(lcquery);
                    $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/master/update_master2',
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
      //  var cnomor = document.getElementById('nomor').value;
//        var cskpd = $('#skpd').combogrid('getValue');
        
        
        alert(nomor+kode);
        var urll = '<?php echo base_url(); ?>index.php/tukd/hapus_panjar';
        $(document).ready(function(){
         $.post(urll,({no:nomor,skpd:kode}),function(data){
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
	
	
	function hitung(){   
        var nilai = angka(document.getElementById('nilai').value);
        var nilai_tpanjar = angka(document.getElementById('nilai_tpanjar').value);
       var total =nilai+nilai_tpanjar;
	$("#total").attr("value",number_format(total,2,'.',',')); 
       
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
                <td>No. Tambah Panjar</td>
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
                <td>No. Panjar </td>
                <td></td>
                <td><input id="no_tpanjar" name="no_tpanjar" style="width: 160px;" /> &nbsp;&nbsp; Nilai : &nbsp; <input type="text" id="nilai_tpanjar" style="border:0;width: 400px;" readonly="true"/></td>                            
            </tr>
            <tr>
                <td>Kegiatan</td>
                <td></td>
                <td><input id="kd_giat" name="kd_giat" style="width: 160px;" /> &nbsp;&nbsp; <input type="text" id="nm_giat" style="border:0;width: 400px;" readonly="true"/></td>                            
            </tr>  
			<tr>
                <td>Sisa Anggaran</td>
                <td></td>
                <td><input type="text" id="sisa_ang" style="border:0;width: 160px; text-align: right;"/></td> 
            </tr>
			<tr>
                <td>Sisa Kas Tunai</td>
                <td></td>
                <td><input type="text" id="sisa_tunai" style="border:0;width: 160px; text-align: right;"/></td> 
            </tr>
			
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 160px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung();"/></td> 
            </tr>
			<tr>
                <td>Total</td>
                <td></td>
                <td><input type="text" id="total" style="border:0;width: 160px; text-align: right;" /> </td> 
            </tr>
			
             <tr>
                <td>Pembayaran</td>
                <td></td>
                 <td>
                     <select name="jns_tunai" id="jns_tunai">
                         <option value="TUNAI">TUNAI</option>     
                     </select>
                 </td>
            </tr>
			
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea rows="2" cols="50" id="ket" style="width: 740px;"></textarea>
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