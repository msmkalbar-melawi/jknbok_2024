<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="jaka" />
    <script type="text/javascript" src="<?php base_url();?>script/jquery.js"></script>
    <script type="text/javascript">
    function addText() {
      var x = document.getElementById("kd_skpd");
      var y = document.getElementById("_skpd");
      getCmb = x.value;
      y.value = getCmb;
    }
    
    </script>
    
    
	
    <title>Untitled 1</title>
</head>

<body>


<div id="content">
    <?php echo form_open('master/tambah_kegiatan', array('class' => 'basic')); ?>
    <table class="form">
    	<tr>
    <tr>
         <td><label>Kode SKPD</label><br />
    <?php
        $lctam = '<select name="kd_skpd" id="kd_skpd" onchange="javascript: addText();">';
                $lctam1 = '';
                foreach($skpd as $ckdskpd){    
                 
                        $lctam1 = $lctam1."<option value=\"$ckdskpd->kd_skpd\" >$ckdskpd->kd_skpd| $ckdskpd->nm_skpd </option>";
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
    ?>
    </tr>       	
        <tr>
        	<td><label>SKPD</label><br />
            <input name="_skpd" type="text" id="_skpd" value="" size="20" /> <?php echo form_error('kd_skpd'); ?>            
            <!--<a href="<?php echo site_url(); ?>/rka/preview_perda1/"><img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a></td>-->
            <td><input name="cari" type="submit" id="cari" value="proses" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
  
</div>

</body>
</html>