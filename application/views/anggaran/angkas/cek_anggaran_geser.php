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
  
  <script type="text/javascript">
    
    var kdstatus = '';
    var kd = '';
                        
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 420,
            width: 600,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){ 
        $('#kode').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/rka_penetapan/skpduser2',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kd = rowData.kd_skpd;               
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase()); 
               cetakbawah();           
           }  
       });

    });        
    
  function cetakbawah(){
      var ckdskpd = $('#kode').combogrid('getValue');
      var kdstatus = document.getElementById('status').value;
      if(ckdskpd=='' || kdstatus==''){

      }else{
          url="<?php echo site_url(); ?>rka_ro/preview_cetakan_cek_anggaran_geser/"+ckdskpd+'/0/'+kdstatus+'/Report-cek-anggaran';
          document.getElementById("demo").innerHTML="<embed src="+url+" width='900 px' height='500px'></embed>";
      }


  }
      

  
    function cek($cetak,$jns){
         var ckdskpd = $('#kode').combogrid('getValue');
         var status_ang = document.getElementById('status').value;
         
          url="<?php echo site_url(); ?>rka_ro/preview_cetakan_cek_anggaran_geser/"+ckdskpd+'/'+$cetak+'/'+status_ang+'/Report-cek-anggaran'
         
        openWindow( url,$jns );
    }
    
 
 function openWindow( url,$jns ){
        
            lc = '';
      window.open(url+lc,'_blank');
      window.focus();
      
     }  
  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>CEK NILAI ANGGARAN DAN ANGGARAN KAS PEREGSERAN</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:100%;" border="0">
        <tr>
                <td width="10%">SKPD</td>
                <td width="1%">:</td>
                <td colspan="2">&nbsp;&nbsp;<input  type="text" id="kode" style="width:200px;"/><input type="text" id="nmskpd" style="border:0;width:500px;"/></td>                
        </tr>			
        <tr>
                <td width="10%">Status</td>
                <td width="1%">:</td>
                <td >
                  <select id="status" onclick="javascript:cetakbawah()" style="cursor: pointer; width: 200px ">
                    <option value="" style="cursor: pointer; padding: 10px">Pilih Status Anggaran</option>
                    <!-- <option value="nilai" style="cursor: pointer; padding: 10px">Murni</option> -->
                    <option value="nilai_sempurna" style="cursor: pointer; padding: 10px">Pergeseran</option>
                    <!-- <option value="nilai_ubah" style="cursor: pointer; padding: 10px">Perubahan</option> -->
                  </select>
                <td align="right" style="color: red;"></td>
        </tr>
         <tr>
           <td width="10%">Cetak Laporan</td>
           <td width="1%">:</td>
           <td colspan="2"> 
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'skpd');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'skpd');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(2,'skpd');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a>
           </td>    
        </tr>        


    </table>    
    
    </p> 
    </div>   
    <label id="demo"></label>
</div>

</body>

</html>