<div id="content">
<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/urusan"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a></h1>

	<?php echo form_open('master/tambah_urusan', array('class' => 'basic')); ?>
    <table class="form">
        <tr>
            <td><label>Kode Fungsi</label><br />
             <?php
                $lctam = '<select name="kd_fungsi">';
                $lctam1 = '';
                foreach($kdfungsi as $ckdfungsi){             
                   $lctam1 = $lctam1."<option value=\"$ckdfungsi->kd_fungsi\" >$ckdfungsi->kd_fungsi | $ckdfungsi->nm_fungsi </option>";
                 }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
            
        </tr>
    	<tr>
        	<td><label>Kd_urusan</label><br /><input name="kd_urusan" type="text" id="kd_urusan" value="<?php echo set_value('kd_urusan'); ?>" size="10" /> <?php echo form_error('kd_urusan'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nm_urusan</label><br />
            <input name="nm_urusan" type="text" id="nm_urusan" value="<?php echo set_value('nm_urusan'); ?>" size="40" />
            <?php echo form_error('nm_fungsi'); ?>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>