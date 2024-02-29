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
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
   
    <script>
    var kode='';
    var kegiatan='';
        
     $(document).ready(function() {
            $("#accordion").accordion();
        });
  
      $(function(){
        $('#cc').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/config_skpd_2',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                kode = rowData.kd_skpd;

                giat(kode);
            }  
        }); 
      });
      
      $(function(){  
            $('#ck').combogrid({  
                panelWidth:700,  
                idField:'kd_kegiatan',  
                textField:'kd_kegiatan',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/load_trskpd1',  
                columns:[[  
                    {field:'kd_kegiatan',title:'Kode Kegiatan',width:100},  
                    {field:'nm_kegiatan',title:'Nama Kegiatan',width:700}    
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
    		width:50,
            align:"right"},
            {field:'nilai_ubah',
    		title:'Perubahan',
    		width:50,
            align:"right"}
        ]]
    });
    });
    
     function giat(kode){
          $(function(){  
            $('#ck').combogrid({  
                panelWidth:700,  
                idField:'kd_kegiatan',  
                textField:'kd_kegiatan',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/load_trskpd1/'+kode,
                onSelect:function(rowIndex,rowData){
                    kegiatan=rowData.kd_kegiatan;
                    total=rowData.total_ubah
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
         $(document).ready(function(){    
             $('#section2').click();                                               
         });
         load();
     }
     
     function hitung(){    
        var jumlah = document.getElementById('jumlah').value;
        var a = document.getElementById('jan').value;
        var b = document.getElementById('feb').value;
        var c = document.getElementById('mar').value; 
        var d = document.getElementById('apr').value;
        var e = document.getElementById('mei').value;
        var f = document.getElementById('jun').value;
        var g = document.getElementById('jul').value;
        var h = document.getElementById('ags').value;
        var i = document.getElementById('sep').value; 
        var j = document.getElementById('okt').value;
        var k = document.getElementById('nov').value;
        var l = document.getElementById('des').value;  
        tr1=eval(a+'+'+b+'+'+c);
        tr2=eval(d+'+'+e+'+'+f);
        tr3=eval(g+'+'+h+'+'+i);
        tr4=eval(j+'+'+k+'+'+l);
        $("#tr1").attr("value",tr1);  
        $("#tr2").attr("value",tr2);
        $("#tr3").attr("value",tr3);
        $("#tr4").attr("value",tr4);
        
        kas=tr1+tr2+tr3+tr4;
        $("#kas").attr("value",kas);
        selisih=jumlah-kas;
        $("#selisih").attr("value",selisih);
        if (selisih < 0){
            alert('Total Anggaran Kas lebih Besar Dari Anggaran Kegiatan....!!!!');        
        }
        
     }
     
	function bagi(){
        var total = document.getElementById('jumlah').value;
		var rata=Math.round(total/12);
        $("#jan").attr("value",rata);
        $("#feb").attr("value",rata);
        $("#mar").attr("value",rata);
        $("#apr").attr("value",rata);
        $("#mei").attr("value",rata);
        $("#jun").attr("value",rata);
        $("#jul").attr("value",rata);
        $("#ags").attr("value",rata);
        $("#sep").attr("value",rata);
        $("#okt").attr("value",rata);
        $("#nov").attr("value",rata);
        $("#des").attr("value",rata);
        $("#tr1").attr("value",rata*3);
        $("#tr2").attr("value",rata*3);
        $("#tr3").attr("value",rata*3);
        $("#tr4").attr("value",rata*3);
        $("#kas").attr("value",total);
        $("#selisih").attr("value",0);		
	}

     function kosongkan(){
        $("#jan").attr("value",0);
        $("#feb").attr("value",0);
        $("#mar").attr("value",0);
        $("#apr").attr("value",0);
        $("#mei").attr("value",0);
        $("#jun").attr("value",0);
        $("#jul").attr("value",0);
        $("#ags").attr("value",0);
        $("#sep").attr("value",0);
        $("#okt").attr("value",0);
        $("#nov").attr("value",0);
        $("#des").attr("value",0);
        $("#tr1").attr("value",0);
        $("#tr2").attr("value",0);
        $("#tr3").attr("value",0);
        $("#tr4").attr("value",0);
        $("#kas").attr("value",0);
        $("#selisih").attr("value",0);
     }      
  
    function simpan(){
        var a = document.getElementById('jan').value;
        var b = document.getElementById('feb').value;
        var c = document.getElementById('mar').value; 
        var d = document.getElementById('apr').value;
        var e = document.getElementById('mei').value;
        var f = document.getElementById('jun').value;
        var g = document.getElementById('jul').value;
        var h = document.getElementById('ags').value;
        var i = document.getElementById('sep').value; 
        var j = document.getElementById('okt').value;
        var k = document.getElementById('nov').value;
        var l = document.getElementById('des').value;  
        var m = document.getElementById('tr1').value;
        var n = document.getElementById('tr2').value;
        var o = document.getElementById('tr3').value;
        var p = document.getElementById('tr4').value;      
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({csts:'ubah',cskpd:kode,cgiat:kegiatan,jan:a,feb:b,mar:c,apr:d,mei:e,jun:f,jul:g,ags:h,sep:i,okt:j,nov:k,des:l,tr1:m,tr2:n,tr3:o,tr4:p,jn:'ubah'}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/rka/simpan_trskpd",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan');
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                }
            }
         });
        });
    }
    
    function load(){                
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({p:kegiatan,jns:'ubah'}),
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
    </script>

</head>
<body>



<div id="content"> 
   
<div id="accordion">
<h3><a href="#" id="section1">Anggaran Kas Perubahan</a></h3>
   <div  style="height: 350px;">
   <p>
        <h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="cc" name="skpd" style="width: 400px;" /> </h3>
        <h3>KEGIATAN&nbsp;&nbsp;&nbsp;<input id="ck" name="kegiatan" style="width: 400px;"  /> </h3>
        
        <br /><input type="submit" name="submit" value="Input Anggaran Kas Perubahan" onclick="javascript:section2();"/><br /><br />
        
        
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
                <td width="30%"><input type="number" disabled="true" id="jumlah" name="jumlah"/></td>
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
                <td><input type="number" id="jan" name="jan" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
                <td><b>April</td>
                <td><input type="number" id="apr" name="apr" value="0"  onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
            </tr>
            <tr>
                <td><b>Februari</td>
                <td><input type="number" id="feb" name="feb" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
                <td><b>Mei</td>
                <td><input type="number" id="mei" name="mei" value="0"  onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
            </tr>
            <tr>
                <td><b>Maret</td>
                <td><input type="number" id="mar" name="mar" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
                <td><b>Juni</td>
                <td><input type="number" id="jun" name="jun" value="0"  onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
            </tr>
            <tr>
                <td><b>Triwulan I</td>
                <td><input type="number" disabled="true" align="right" id="tr1" name="tr1" /></td>
                <td><b>Triwulan II</td>
                <td><input type="number" disabled="true" align="right" id="tr2" name="tr2"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><b>Juli</td>
                <td><input type="number" id="jul" name="jul" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
                <td><b>Oktober</td>
                <td><input type="number" id="okt" name="okt" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
            </tr>
            <tr>
                <td><b>Agustus</td>
                <td><input type="number" id="ags" name="ags" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
                <td><b>November</td>
                <td><input type="number" id="nov" name="nov" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
            </tr>
            <tr>
                <td><b>September</td>
                <td><input type="number" id="sep" name="sep" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
                <td><b>Desember</td>
                <td><input type="number" id="des" name="des" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();"/></td>
            </tr>
            <tr>
                <td><b>Triwulan III</td>
                <td><input type="number" disabled="true" id="tr3" name="tr3"/></td>
                <td><b>Triwulan IV</td>
                <td><input type="number" disabled="true" id="tr4" name="tr4"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><b>Total Anggaran Kas</td>
                <td><input type="number" disabled="true" id="kas" name="kas"/></td>
                <td><b>Selisih</td>
                <td><input type="number" disabled="true" id="selisih" name="selisih"/></td>
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