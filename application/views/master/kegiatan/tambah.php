<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="jaka" />
    <script type="text/javascript" src="<?php base_url();?>script/jquery.js"></script>
    <script type="text/javascript">
    function addText() {
      var x = document.getElementById("kd_program");
      var y = document.getElementById("kd_kegiatan");
      getCmb = x.value;
      y.value = getCmb;
    }
    
    </script>
    

	<title>Untitled 1</title>
</head>

<body>
<div id="content">
	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/kegiatan"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a></h1>

	<?php echo form_open('master/tambah_kegiatan', array('class' => 'basic')); ?>
    <table class="form">
    	<tr>
         <td><label>Kode Program</label><br />
            
            <?php
                $lctam = '<select name="kd_program"id="kd_program" onchange="javascript: addText();">';
                $lctam1 = '';
                foreach($program as $ckdpog){    
                 
                        $lctam1 = $lctam1."<option value=\"$ckdpog->kd_program\" >$ckdpog->kd_program | $ckdpog->nm_program </option>";
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
        </tr>       	
        <tr>
        	<td><label>Kd Kegiatan</label><br />
            <input name="kd_kegiatan" type="text" id="kd_kegiatan" value="<?php echo set_value('kd_kegiatan'); ?>" size="20" /> <?php echo form_error('kd_kegiatan'); ?>
            </td>
        </tr>
        
        <tr>
            <td><label>Nama Kegiatan</label><br />
            <input name="nm_kegiatan" type="text" id="nm_kegiatan" value="<?php echo set_value('nm_kegiatan'); ?>" size="60" />
            <?php echo form_error('nm_kegiatan'); ?>
            </td>
        </tr>
        
        <tr>
            <td><label>Jenis</label><br />
            <select name="jns_kegiatan">
            <option value="">...pilih jenis...</option>
            <option value="4" > 4 | Pendapatan</option>
            <option value="51">51 | Belanja Tidak Langsung</option>
			<option value="52">52 | Belanja Langsung</option>
            </select></label></td>
        </tr>
        
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>


</body>
</html>