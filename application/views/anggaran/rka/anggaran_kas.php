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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
   
    <script>
    var kode='';
    var kegiatan='';
		
/*
     $(document).ready(function() {
            $("#accordion").accordion();
            $("#kegi").hide();			
			giat(kode);
        });
  
      $(function(){
        $('#cc').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/skpd2',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                $("#kegi").hide();
                kode = rowData.kd_skpd;
                $("#ck").combogrid("clear"); 
                giat(kode);  
                setTimeout(tampil,1000);
                rek('');

            }  
        }); 
      });
      
      var kode = $("#cc").combogrid("getValue");
*/


     $(document).ready(function() {
            $("#accordion").accordion();
			 get_skpd();
        });
		
	  
	   function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#cc").attr("value",data.kd_skpd);
        								//$("#nmskpd").attr("value",data.nm_skpd);
                                        //$("#skpd").attr("value",data.kd_skpd);
        								//$("#nmskpd").attr("value",data.nm_skpd);
        								kode = data.kd_skpd;
                                        sta = data.statu;
										sta2    = data.status_rancang;
										tombol(sta,sta2);
                                         giat(kode);
                                        
        							  }                                     
        	});
             
        }
		
      $(function(){ 
           $('#ck').combogrid({
                panelWidth:700,  
                idField:'kd_kegiatan',  
                textField:'kd_kegiatan',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/load_giat',  
                columns:[[  
                    {field:'kd_kegiatan',title:'Kode Kegiatan',width:130},  
                    {field:'nm_kegiatan',title:'Nama Kegiatan',width:570}    
                ]] 
            });          
         }); 
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/rka/select_rka',
        idField:'id',
        toolbar:"#toolbar",              
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        columns:[[
    	    {field:'kd_rek5',
    		title:'Kode Rekening',
    		width:50},
            {field:'nm_rek5',
    		title:'Nama Rekening',
    		width:200},
            {field:'nilai',
    		title:'Nilai',
    		width:100,
            align:"right"}
        ]]
    });
    });

     function tampil(){
        $("#kegi").show();
     }
     function giat(kode){
            //alert(''+kode);
            
          $(function(){  
            $('#ck').combogrid({
                panelWidth:700,  
                idField:'kd_kegiatan',  
                textField:'kd_kegiatan',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/load_giat/'+kode,
                columns:[[  
                    {field:'kd_kegiatan',title:'Kode Kegiatan',width:130},  
                    {field:'nm_kegiatan',title:'Nama Kegiatan',width:570}    
                ]], 
                onSelect:function(rowIndex,rowData){
                    kegiatan=rowData.kd_kegiatan;
                    total=rowData.total;
                    $("#jumlah").attr("value",total);  
                    rek(kegiatan);                    
                }
            });          
            
         }); 
     }
     
      function rek(kegiatan){
        $(function(){  
            $('#dg').edatagrid({
    		  url: '<?php echo base_url(); ?>/index.php/rka/select_rka/'+kegiatan
            });
        });
        load();                
      }
     
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
     }
     function section2(){

		 if(kegiatan==''){
            alert('Kode Kegiatan Belum dipilih');
            return;   
         }
		
		$(document).ready(function(){    
             $('#section2').click();                                               
         });
         kosongkan();
         load();
     }
     

    
	function hitung(){    
        var jumlah = angka(document.getElementById('jumlah').value);
        var a = angka(document.getElementById('jan').value);
        var b = angka(document.getElementById('feb').value);
        var c = angka(document.getElementById('mar').value); 
        var d = angka(document.getElementById('apr').value);
        var e = angka(document.getElementById('mei').value);
        var f = angka(document.getElementById('jun').value);
        var g = angka(document.getElementById('jul').value);
        var h = angka(document.getElementById('ags').value);
        var i = angka(document.getElementById('sep').value); 
        var j = angka(document.getElementById('okt').value);
        var k = angka(document.getElementById('nov').value);
        var l = angka(document.getElementById('des').value);  
        tr1=eval(a+'+'+b+'+'+c);
        tr2=eval(d+'+'+e+'+'+f);
        tr3=eval(g+'+'+h+'+'+i);
        tr4=eval(j+'+'+k+'+'+l);
        
        $("#tr1").attr("value",number_format(tr1,2,'.',',')); 
        $("#tr2").attr("value",number_format(tr2,2,'.',','));
        $("#tr3").attr("value",number_format(tr3,2,'.',','));
        $("#tr4").attr("value",number_format(tr4,2,'.',','));
        
        kas=tr1+tr2+tr3+tr4;
        $("#kas").attr("value",number_format(kas,2,'.',','));
        selisih=jumlah-kas;
        $("#selisih").attr("value",number_format(selisih,2,'.',','));
//        if (selisih < 0){
//            alert('Total Anggaran Kas lebih Besar Dari Anggaran Kegiatan....!!!!');        
//        }
        
     }
     
	function bagi(){
        var total = angka(document.getElementById('jumlah').value);
		var tot   = angka(document.getElementById('jumlah').value);
		var rata=Math.floor(total/12);
		var trata = rata*12;
		var selisih = total-trata;

        $("#jan").attr("value",number_format(rata,2,'.',','));
        $("#feb").attr("value",number_format(rata,2,'.',','));
        $("#mar").attr("value",number_format(rata,2,'.',','));
        $("#apr").attr("value",number_format(rata,2,'.',','));
        $("#mei").attr("value",number_format(rata,2,'.',','));
        $("#jun").attr("value",number_format(rata,2,'.',','));
        $("#jul").attr("value",number_format(rata,2,'.',','));
        $("#ags").attr("value",number_format(rata,2,'.',','));
        $("#sep").attr("value",number_format(rata,2,'.',','));
        $("#okt").attr("value",number_format(rata,2,'.',','));
        $("#nov").attr("value",number_format(rata,2,'.',','));
        $("#des").attr("value",number_format(rata,2,'.',','));
        $("#tr1").attr("value",number_format(rata*3,2,'.',','));
        $("#tr2").attr("value",number_format(rata*3,2,'.',','));
        $("#tr3").attr("value",number_format(rata*3,2,'.',','));
        $("#tr4").attr("value",number_format(rata*3,2,'.',','));
        $("#kas").attr("value",number_format(trata,2,'.',','));
        $("#selisih").attr("value",number_format(selisih,2,'.',','));		
	}

     function kosongkan(){
        $("#jan").attr("value",number_format(0,2,'.',','));
        $("#feb").attr("value",number_format(0,2,'.',','));
        $("#mar").attr("value",number_format(0,2,'.',','));
        $("#apr").attr("value",number_format(0,2,'.',','));
        $("#mei").attr("value",number_format(0,2,'.',','));
        $("#jun").attr("value",number_format(0,2,'.',','));
        $("#jul").attr("value",number_format(0,2,'.',','));
        $("#ags").attr("value",number_format(0,2,'.',','));
        $("#sep").attr("value",number_format(0,2,'.',','));
        $("#okt").attr("value",number_format(0,2,'.',','));
        $("#nov").attr("value",number_format(0,2,'.',','));
        $("#des").attr("value",number_format(0,2,'.',','));
        $("#tr1").attr("value",number_format(0,2,'.',','));
        $("#tr2").attr("value",number_format(0,2,'.',','));
        $("#tr3").attr("value",number_format(0,2,'.',','));
        $("#tr4").attr("value",number_format(0,2,'.',','));
        $("#kas").attr("value",number_format(0,2,'.',','));
        $("#selisih").attr("value",number_format(0,2,'.',','));
     }      
  
    function simpan(){

        var a = angka(document.getElementById('jan').value);
        var b = angka(document.getElementById('feb').value);
        var c = angka(document.getElementById('mar').value); 
        var d = angka(document.getElementById('apr').value);
        var e = angka(document.getElementById('mei').value);
        var f = angka(document.getElementById('jun').value);
        var g = angka(document.getElementById('jul').value);
        var h = angka(document.getElementById('ags').value);
        var i = angka(document.getElementById('sep').value); 
        var j = angka(document.getElementById('okt').value);
        var k = angka(document.getElementById('nov').value);
        var l = angka(document.getElementById('des').value);  
        var m = angka(document.getElementById('tr1').value);
        var n = angka(document.getElementById('tr2').value);
        var o = angka(document.getElementById('tr3').value);
        var p = angka(document.getElementById('tr4').value);
        var q = angka(document.getElementById('jumlah').value);
        var r = angka(document.getElementById('kas').value);
		
        var nselisih = angka(document.getElementById('selisih').value);
        
        if(nselisih<0){
			swal("Error", "Pembagian Anggaran Melebihi Total Anggaran...!!!", "error");
            return;
        }

        if(nselisih>0){
			swal("Error", "Masih ada sisa Anggaran yang belum dibagi...!!!", "error");
            return;
        }		
        
		if(r!=0){	
			if(q!=r){
				swal("Error", "Jumlah Anggaran Kas tidak sesuai dengan Total Anggaran", "error");
				return;
			}
		}	
		 
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({csts:'susun',cskpd:kode,cgiat:kegiatan,jan:a,feb:b,mar:c,apr:d,mei:e,jun:f,jul:g,ags:h,sep:i,okt:j,nov:k,des:l,tr1:m,tr2:n,tr3:o,tr4:p}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/rka/simpan_trskpd",
            success:function(data){
                if (data = 1){
					swal("Berhasil", "Data Berhasil Disimpan", "success");
                }else{
					swal("Error", "Data Gagal Tersimpan", "error");
                }
            }
         });
        });
    }
    
    function load(){ 
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({p:kegiatan,jns:'susun'}),
            url:"<?php echo base_url(); ?>index.php/rka/load_trdskpd",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    bulan = n['bulan'];
                     switch (bulan) {
                        case '1':
                             $("#jan").attr("value",n['nilai']);
                        break;
                        case '2':
                             $("#feb").attr("value",n['nilai']);
                        break;
                        case '3':
                             $("#mar").attr("value",n['nilai']);
                        break;
                        case '4':
                             $("#apr").attr("value",n['nilai']);
                        break;
                        case '5':
                             $("#mei").attr("value",n['nilai']);
                        break;
                        case '6':
                             $("#jun").attr("value",n['nilai']);
                        break;
                        case '7':
                             $("#jul").attr("value",n['nilai']);
                        break;
                        case '8':
                             $("#ags").attr("value",n['nilai']);
                        break;
                        case '9':
                             $("#sep").attr("value",n['nilai']);
                        break;
                        case '10':
                             $("#okt").attr("value",n['nilai']);
                        break;
                        case '11':
                             $("#nov").attr("value",n['nilai']);
                        break;
                        case '12':
                             $("#des").attr("value",n['nilai']);
                        break;
                     }
                     hitung();
                });
            }
         });
        });
    }
	
	function enter(ckey,_cid){
        if (ckey==13 || ckey==9){    	       	       	    	   
        	   document.getElementById(_cid).focus();            
        	}     
       
    }  


   function tombol(st,st2){ 
    if (st2=='0'){
			document.getElementById("s").disabled = true;          
			document.getElementById("p1").innerHTML="Anggaran Penyusunan Belum DI - SAH - KAN...!!!";        
     } else {

    if (st=='1'){
			document.getElementById("s").disabled = true;              
			document.getElementById("p1").innerHTML="APBD TELAH DI - SAH - KAN...!!!";
            
     } else {
			document.getElementById("s").disabled = false;             
            document.getElementById("p1").innerHTML="";
          
     }
	 
            
     }
	
    }
    


    </script>

</head>
<body>



<div id="content"> 
   
<div id="accordion">
<h3><a href="#" id="section1">Anggaran Kas</a></h3>
   
   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p><br />
   <p>
        <h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="cc" name="skpd" value="<?php echo $this->session->userdata('kdskpd')?>" style="width: 400px;" disabled/> </h3>
        <div id="kegi"><h3>KEGIATAN&nbsp;&nbsp;&nbsp;<input id="ck" name="kegiatan" style="width: 400px;" /> </h3>
        </div>
        <br /><input id="s" type="submit" name="submit" value="Input Anggaran Kas" onclick="javascript:section2();"/><br /><br />
        
        
        <table id="dg" title="Rekening Rencana Kegiatan Anggaran" style="width:870px;height:350px;" >  
        </table>  
   </p>
    </div>
    
<h3><a href="#" id="section2"></a></h3>
    <div>
    <p>
    <div class="result">
        <table align="center">
            <tr>
                <td><b>Total Anggaran</td>
                <td width="30%"><input type="decimal" disabled="true" id="jumlah" name="jumlah" style="text-align: right;" ></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><b>Januari</td>
                <td><input type="decimal" id="jan" name="jan" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;"  onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))"/></td>
                <td><b>April</td>
                <td><input type="decimal" id="apr" name="apr" value="0"  onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'mei');return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr>
                <td><b>Februari</td>
                <td><input type="decimal" id="feb" name="feb" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'mar');return(currencyFormat(this,',','.',event))"/></td>
                <td><b>Mei</td>
                <td><input type="decimal" id="mei" name="mei" value="0"  onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'jun');return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr>
                <td><b>Maret</td>
                <td><input type="decimal" id="mar" name="mar" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'apr');return(currencyFormat(this,',','.',event))" /></td>
                <td><b>Juni</td>
                <td><input type="decimal" id="jun" name="jun" value="0"  onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'jul');return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr>
                <td><b>Triwulan I</td>
                <td><input type="decimal" disabled="true" align="right" id="tr1" name="tr1" style="text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>
                <td><b>Triwulan II</td>
                <td><input type="decimal" disabled="true" align="right" id="tr2" name="tr2" style="text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><b>Juli</td>
                <td><input type="decimal" id="jul" name="jul" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'ags');return(currencyFormat(this,',','.',event))"/></td>
                <td><b>Oktober</td>
                <td><input type="decimal" id="okt" name="okt" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'nov');return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr>
                <td><b>Agustus</td>
                <td><input type="decimal" id="ags" name="ags" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'sep');return(currencyFormat(this,',','.',event))"/></td>
                <td><b>November</td>
                <td><input type="decimal" id="nov" name="nov" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'des');return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr>
                <td><b>September</td>
                <td><input type="decimal" id="sep" name="sep" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'okt');return(currencyFormat(this,',','.',event))"/></td>
                <td><b>Desember</td>
                <td><input type="decimal" id="des" name="des" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr>
                <td><b>Triwulan III</td>
                <td><input type="decimal" disabled="true" id="tr3" name="tr3" style="text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>
                <td><b>Triwulan IV</td>
                <td><input type="decimal" disabled="true" id="tr4" name="tr4" style="text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><b>Total Anggaran Kas</td>
                <td><input type="decimal" disabled="true" id="kas" name="kas" style="text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>
                <td><b>Selisih</td>
                <td><input type="decimal" disabled="true" id="selisih" name="selisih" style="text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4"><input type="submit" name="submit" value="Simpan" onclick="javascript:simpan();"/>
                    <input type="submit" name="submit" value="Kosongkan" onclick="javascript:kosongkan();"/>
                    <input type="submit" name="submit" value="Bagi Rata" onclick="javascript:bagi();"/>
                    <input type="submit" name="submit" value="Kembali" onclick="javascript:section1();"/></td>
            </tr>             
        </table>
        </div>
    </p> 
    </div>   

</div>

</div>  	
</body>

</html>