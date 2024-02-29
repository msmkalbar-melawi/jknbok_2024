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
    <style>    
 
    @keyframes blink{
    0%{color:black}
    50%{color:white}
    100%{color:black}
    }
    @-webkit-keyframes blink{
    0%{color:black}
    50%{color:white}
    100%{color:black}
    }
    .blink{
    -webkit-animation:blink 2s linear infinite;
    -moz-animation:blink 2s linear infinite;
    animation:blink 2s linear infinite
    }
 
    </style> 
    <script type="text/javascript">


      function tarik_sis(){
        var cookie      = document.getElementById('cookie').value;
        var jenis    = document.getElementById('jenis_standar_harga').value;
        var url_tujuan = '<?php echo base_url(); ?>index.php/master/transfersipd2simakda';

        $(document).ready(function(){
        $("#total_sipd").attr("value","");
        $("#total_simakda").attr("value","");
        $("#keterangan").attr("value","Proses Penarikan..");
        document.getElementById('tombol').disabled=true;
        $.ajax({ url:url_tujuan,
                 dataType:'json',
                 type: "POST",    
                 data:({cookie:cookie,jenis:jenis}),
                 success:function(data){
                    $.each(data, function(i,n){
                      $("#total_sipd").attr("value",n['total_sipd']);
                      $("#total_simakda").attr("value",n['total_simakda']);
                      $("#tipe").attr("value",n['jenis']);
                      var sel=n['total_sipd']-n['total_simakda'];
                      if(sel==0){
                          $("#keterangan").attr("value","DONE!!!");
                      }else{
                          $("#keterangan").attr("value",'Selisih :'+sel+' TARIK LAGI');
                      }

                    document.getElementById('tombol').disabled=false;
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
<h3><a href="#" id="section1" >TRANSFER DATA :: STANDAR HARGA </a></h3>
    <div>
      <textarea type="text" name="cookie" id="cookie" placeholder="Cookie" style="width: 100%; height: 100px"></textarea><br>
       <select class="select" style="width: 100px; display: inline;" id="jenis_standar_harga">
         <option value="1">SSH</option>
         <option value="2">HSPK</option>
         <option value="3">ASB</option>
         <option value="4">SBU</option>
       </select>
       <button class="button" id="tombol" onclick="javascript:tarik_sis();"> TRANSFER</button>
    <p align="right"> <br><br>

          <fieldset>
            <input type="text" name="total_sipd" id="total_sipd" placeholder="total_records_sumber">
            <input type="text" name="total_simakda" id="total_simakda" placeholder="total_records_simakda">
            <input type="text" name="tipe" id="tipe" placeholder="jenis standar">
            <input type="text" class="blink" name="keterangan" id="keterangan" placeholder="keterangan">
          </fieldset>              
    </p> 
    </div>   

</div>
</body>
</html>