<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="djanu" />
    <script type="text/javascript" src="<?php base_url();?>script/jquery.js"></script>
    <script type="text/javascript">
    function addText() {
      var x = document.getElementById("kd_rek3");
      var y = document.getElementById("kd_rek4");
      getCmb = x.value;
      y.value = getCmb;
    }
    
    </script>
    

	<title>Untitled 1</title>
</head>

<body>


<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/rek4">Kembali</a></span></h1>

	<?php echo form_open('master/tambah_rek4', array('class' => 'basic')); ?>
    <table class="form">
        <tr>
         <td><label>Kode Rekening Kelompok</label><br />
            
            <?php
                $lctam = '<select name="kd_rek3" id="kd_rek3" onchange="javascript: addText();">';
                $lctam1 = '';
                foreach($kdrek as $ckdrek){    
                 
                        $lctam1 = $lctam1."<option value=\"$ckdrek->kd_rek3\" >$ckdrek->kd_rek3 | $ckdrek->nm_rek3 </option>";
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
            
        </tr>

    	<tr>
        	<td><label>Kode Rekening Objek </label><br /><input name="kd_rek4" type="text" id="kd_rek4" value="<?php echo set_value('kd_rek4'); ?>" size="10" /> <?php echo form_error('kd_rek4'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nama Rekening Objek </label><br />
            <input name="nm_rek4" type="text" id="nm_rek4" value="<?php echo set_value('nm_rek4'); ?>" size="60" />
            <?php echo form_error('nm_rek4'); ?>
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