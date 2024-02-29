<div id="content">
	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/program"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a></h1>

	<?php echo form_open('master/tambah_program', array('class' => 'basic')); ?>
    <table class="form">
    	<tr>
        	<td><label>Kd_program</label><br /><input name="kd_program" type="text" id="kd_program" value="<?php echo set_value('kd_program'); ?>" size="10" /> <?php echo form_error('kd_program'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nm_Program</label><br />
            <input name="nm_program" type="text" id="nm_program" value="<?php echo set_value('nm_program'); ?>" size="40" />
            <?php echo form_error('nm_program'); ?>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>