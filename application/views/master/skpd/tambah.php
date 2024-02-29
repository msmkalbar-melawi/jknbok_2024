<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="jaka" />
    <script type="text/javascript" src="<?php base_url();?>script/jquery.js"></script>
    <script type="text/javascript">
    function addText() {
      var x = document.getElementById("kd_urusan");
      var y = document.getElementById("kd_skpd");
      getCmb = x.value;
      y.value = getCmb;
    }
    
    </script>
    

	<title>Untitled 1</title>
</head>

<body>


<div id="content">
	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/skpd"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a></h1>

	<?php echo form_open('master/tambah_skpd', array('class' => 'basic')); ?>
    <table class="form">
        <tr>
         <td><label>Kode Urusan</label><br />
            
            <?php
                $lctam = '<select name="kd_urusan"id="kd_urusan" onchange="javascript: addText();">';
                $lctam1 = '';
                foreach($kdurus as $ckdurus){    
                 
                        $lctam1 = $lctam1."<option value=\"$ckdurus->kd_urusan\" >$ckdurus->kd_urusan | $ckdurus->nm_urusan </option>";
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
            
        </tr>    	
        <tr>
        	<td><label>Kode SKPD </label><br />
            <input name="kd_skpd" type="text" id="kd_skpd" value="<?php echo set_value('kd_skpd'); ?>" size="10" /> <?php echo form_error('kd_skpd'); ?>
            </td>
        </tr>        
        <tr>
            <td><label>Nama SKPD </label><br />
            <input name="nm_skpd" type="text" id="nm_skpd" value="<?php echo set_value('nm_skpd'); ?>" size="80" />
            <?php echo form_error('nm_skpd'); ?>
            </td>
        </tr>        
        <tr>
        	<td><label>NPWP </label><br /><input name="npwp" type="text" id="npwp" value="<?php echo set_value('npwp'); ?>" size="20" /> <?php echo form_error('npwp'); ?>
            </td>
        </tr>               
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>

</body>
</html>