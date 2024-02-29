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
		url: '<?php echo base_url(); ?>/index.php/tukd/load_keluar_lain',
         idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",  
        columns:[[{field:'ck',
    		title:'',
    		width:5,
            checkbox:"true"},
    	    {field:'no_bukti',
    		title:'Nomor',
    		width:50,
            align:"center"},
            {field:'tgl_bukti',
    		title:'Tanggal',
    		width:30},
            {field:'kd_skpd',
    		title:'S K P D',
    		width:30,
            align:"center"},
            {field:'pay',
    		title:'Pembayaran',
    		width:50,
            align:"center"},
            {field:'nilai',
    		title:'Nilai',
    		width:50,
            align:"right"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor = rowData.no_bukti;
          tgl   = rowData.tgl_bukti;
          kode  = rowData.kd_skpd;
          lcket = rowData.ket;
          lcnilai = rowData.nilai;
          lcpay = rowData.pay;
          jns_bbn = rowData.jns_beban;
          lcidx = rowIndex;
          get(nomor,tgl,kode,lcket,lcnilai,lcpay,jns_bbn);
                                       
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
        
		
		
		
		
        $('#tanggal_kas').datebox({  
            required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
    
             
         

      
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
	       

    function  get(nomor,tgl,kode,lcket,lcnilai,lcpay,jns_bbn){

        $("#no_simpan").attr("value",nomor);
        $("#nomor").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        //$("#skpd").combogrid("setValue",kode); 
        $("#nilai").attr("value",lcnilai);
        $("#ket").attr("value",lcket);
        $("#pay").combobox("setValue",lcpay);
        $("#cc").combobox("setValue",jns_bbn);
		
                
    }
    
    function kosong(){
        $("#no_simpan").attr("value",'');
        $("#nomor").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#nilai").attr("value",0);
        //$("#nmskpd").attr("value",'');
        $("#ket").attr("value",''); 
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
		url: '<?php echo base_url(); ?>/index.php/tukd/load_terima_lain',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
	
    
    
       function simpan_lain(){
        var no_simpan = document.getElementById('no_simpan').value;
        var cno = document.getElementById('nomor').value;
        var ctgl = $('#tanggal').datebox('getValue');
        var cskpd = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
        var lcket = document.getElementById('ket').value;
        var lcbeban = $('#cc').combobox('getValue');
        var lcpay = $('#pay').combobox('getValue');
        var cnilai = document.getElementById('nilai').value;
		var lcnilai=angka(cnilai);
		
		if(cnilai==''){
			alert('Nilai tidak boleh kosong');
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
        
        //alert(lcstatus)
       
       if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'TRHOUTLAIN',field:'NO_BUKTI'}),
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
                    lcinsert = "(NO_BUKTI,TGL_BUKTI,nilai,KET,KD_SKPD,jns_beban,pay)";
                    lcvalues = "('"+cno+"','"+ctgl+"','"+lcnilai+"','"+lcket+"','"+cskpd+"','"+lcbeban+"','"+lcpay+"')";
        
                    $(document).ready(function(){
                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                            data: ({tabel:'TRHOUTLAIN',kolom:lcinsert,nilai:lcvalues,cid:'NO_BUKTI',lcid:cno}),
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
                    data: ({no:cno,tabel:'TRHOUTLAIN',field:'NO_BUKTI'}),
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
                    
                    lcquery = "UPDATE TRHOUTLAIN SET NO_BUKTI ='"+cno+"',TGL_BUKTI='"+ctgl+"',KET='"+lcket+"',nilai='"+lcnilai+"',pay='"+lcpay+"',KD_SKPD='"+cskpd+"',jns_beban='"+lcbeban+"' where NO_BUKTI='"+no_simpan+"' AND KD_SKPD='"+cskpd+"'";
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
        var urll = '<?php echo base_url(); ?>index.php/tukd/hapus_keluar_lain';
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
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN PENERIMAAN LAIN-LAIN</a></b></u></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>               
        <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="Listing Data Penerimaan Lain-Lain" style="width:870px;height:450px;" >  
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
                <td>Nomor</td>
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
                <td><input id="skpd" name="skpd" style="width: 160px;" /> &nbsp;&nbsp; <input type="text" id="nmskpd" style="border:0;width: 450px;" readonly="true"/></td>                            
            </tr>
			
            <tr>
                <td>Jenis Beban</td>
                <td></td>
                <td><select id="cc" class="easyui-combobox" name="dept" style="width:140px;">
					<option value="1"> UP</option>
					<option value="4"> LS Gaji</option>
					<option value="6"> LS Barang Jasa</option>
				     </select>                        
            </tr>  
			
			<tr>
                <td>Jenis Pembayaran</td>
                <td></td>
                <td><select id="pay" class="easyui-combobox" name="pay" style="width:140px;">
					<option value="TUNAI"> TUNAI</option>
					<option value="BANK"> BANK</option>
				     </select>                        
            </tr>  
			
			<tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 160px; text-align: right;" value="0" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
			
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea rows="2" cols="50" id="ket" style="width: 740px;"></textarea>
                </td> 
            </tr>
           
            <tr>
                <td colspan="3" align="center"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_lain();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>


  	
</body>

</html>