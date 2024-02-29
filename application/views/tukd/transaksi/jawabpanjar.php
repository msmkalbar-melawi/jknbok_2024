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
            height: 450,
            width: 900,
            modal: true,
            autoOpen:false,
        });
		get_tahun();
        });    
     
  
    
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_jpanjar',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_kas',
    		title:'Nomor Kas',
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
          lcpay = rowData.pay;
          lcidx = rowIndex;
          get(nokas,tglkas,nomor,tgl,kode,lcket,lcnilai,lcpay);   
                                       
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
                //return d+'-'+m+'-'+y;
            },
            onSelect: function(date){
		      jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
	       }
        });
        
        $('#tanggal_kas').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
                //return d+'-'+m+'-'+y;
            },
            onSelect: function(date){
				var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
				$("#tanggal").datebox("setValue",y+'-'+m+'-'+d);
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
       
       $('#nomor').combogrid({  
                panelWidth : 500,  
                url        : '<?php echo base_url(); ?>/index.php/tukd/ambil_panjar',  
                idField    : 'no_panjar',                    
                textField  : 'no_panjar',
                mode       : 'remote',  
                fitColumns : true,  
                columns:[[  
                        {field:'no_panjar',title:'No',width:60},  
                        {field:'nilai',title:'Nilai',align:'right',width:80} 
                    ]],
                     onSelect:function(rowIndex,rowData){
                        no_panjar = rowData.no_panjar;         
                        tgl   = rowData.tgl_panjar;
                        skpd     = rowData.kd_skpd;          
                        pengguna     = rowData.pengguna;
                        nilai     = rowData.nilai;
                        ket    = rowData.keterangan;
                        rek    = rowData.rek_bank;
                        $("#skpd").attr("Value",skpd); 
                        $("#nilai").attr("value",nilai); 
                        $("#tanggal").datebox("setValue",tgl);
                        $("#ket").attr("value",ket);
                        //loadpanjar(no_panjar,tgl,skpd,penguna,nilai,ket,rek);                                                           
                    }  
                });   
              
    });        

     function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
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
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
         });
     }
    
       
    function get(nokas,tglkas,nomor,tgl,kode,lcket,lcnilai,lcpay){

		$("#no_kas").attr("value",nokas);
		$("#no_simpan").attr("value",nokas);
        $("#tanggal_kas").datebox("setValue",tglkas);
        $("#nomor").combogrid("setValue",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#skpd").attr("value",kode);
        $("#nilai").attr("value",lcnilai);
        $("#ket").attr("value",lcket);
        //$("#jns_tunai").attr("value",lcpay);
                
    }
    
    function kosong(){
        $("#no_kas").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#tanggal_kas").datebox("setValue",'');
        $("#nomor").combogrid("setValue",'');
        $("#tanggal").datebox("setValue",'');
        $("#skpd").attr("value",'');
        $("#rek1").attr("Value",'');
        $("#nmskpd").attr("value",'');
        $("#nmrek").attr("value",'');
        $("#nilai").attr("value",'');        
        $("#ket").attr("value",''); 
       // $("#jns_tunai").attr("value",'');
        $("#rek_bank").attr("value",'');               
		get_nourut();
    }
	
	 function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/no_urut',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#no_kas").attr("value",data.no_urut);
        								$("#nomor").attr("value",data.no_urut);
        							  }                                     
        	});  
        }
	
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_jpanjar',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    
    
       function simpan_tetap(){
        var cno_kas = document.getElementById('no_kas').value;
        var no_simpan = document.getElementById('no_simpan').value;
        var ctgl_kas = $('#tanggal_kas').datebox('getValue');
        var cno = $('#nomor').combogrid('getValue');
        var ctgl = $('#tanggal').datebox('getValue');
        var cskpd = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
        var lcket = document.getElementById('ket').value;
        //var lctunai = document.getElementById('jns_tunai').value;
        var lntotal = angka(document.getElementById('nilai').value);
            lctotal = number_format(lntotal,0,'.',',');
        //alert(jaka);
		if (cno_kas==cno){
            alert('Nomor Panjar dan Pertanggungjawaban tidak boleh sama');
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
                    data: ({no:cno_kas,tabel:'tr_jpanjar',field:'no_kas'}),
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
            //----------------        
					lcinsert = "(no_kas,tgl_kas,no_panjar,tgl_panjar,kd_skpd,pengguna,nilai,keterangan,rek_bank,jns,no_panjar_lalu)";
                    lcvalues = "('"+cno_kas+"','"+ctgl_kas+"','"+cno+"','"+ctgl+"','"+cskpd+"','','"+lntotal+"','"+lcket+"','','1','"+cno+"')";
                    $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/tukd/simpan_jpanjar',
                            data: ({tabel:'tr_jpanjar',kolom:lcinsert,nilai:lcvalues,cid:'no_kas',lcid:cno_kas,nopanjar:cno}),
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
									$("#no_simpan").attr("value",cno_kas);
									$('#dg').edatagrid('reload');
                                    exit();
                                }
                            }
                    });
                    });
               //------------  
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
                    data: ({no:cno_kas,tabel:'tr_jpanjar',field:'no_kas'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cno_kas!=no_simpan){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || cno_kas==no_simpan){
						alert("Nomor Bisa dipakai");
			//-----------
                    
                    lcquery = "UPDATE tr_jpanjar SET no_kas='"+cno_kas+"',tgl_kas='"+ctgl_kas+"',tgl_panjar='"+ctgl+"',no_panjar='"+cno+"',keterangan='"+lcket+"',nilai='"+lntotal+"',no_panjar_lalu='"+cno+"' where no_kas='"+no_simpan+"' AND kd_skpd='"+cskpd+"'";
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
									$("#no_simpan").attr("value",cno_kas);
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
        }
        
        //alert("Data Berhasil disimpan");
       
        //section1();
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Panjar';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Pertanggungjawaban Panjar';
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
        var cnomor = document.getElementById('no_simpan').value;
//        var cskpd = $('#skpd').combogrid('getValue');
        alert(cnomor+kode);
        var urll = '<?php echo base_url(); ?>index.php/tukd/hapus_jpanjar';
        $(document).ready(function(){
         $.post(urll,({no:cnomor,skpd:kode,no_panjar:nomor}),function(data){
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
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN PERTANGGUNGJAWABAN PANJAR</a></b></u></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="Listing Pertanggungjawaban panjar" style="width:870px;height:450px;" >  
        </table>
 
    </p> 
    </div>   

</div>

</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"></td>
				<td style="border-bottom: double 1px red;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/> &nbsp;&nbsp;<i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr>
			
			<tr>
                <td>No. Kas</td>
                <td></td>
                <td><input type="text" id="no_kas" style="width: 200px;"/></td>  
            </tr>
            <tr>
                <td>Tanggal Kas </td>
                <td></td>
                <td><input type="text" id="tanggal_kas" style="width: 140px;" /></td>
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
                <td><input id="skpd" name="skpd" style="width: 140px;" />  <input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>            
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 200px; text-align: right;" readonly="true"/></td> 
            </tr>
             <!--<tr>
                <td>Pembayaran</td>
                <td></td>
                 <td>
                     <select name="jns_tunai" id="jns_tunai">
                         <option value="">......</option>     
                         <option value="TUNAI">TUNAI</option>
                         <option value="BANK">BANK</option>
                     </select>
                 </td>
            </tr>-->
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea rows="2" cols="50" id="ket" style="width: 740px;"></textarea>
                </td> 
            </tr>
           
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_tetap();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>


  	
</body>

</html>