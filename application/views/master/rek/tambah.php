<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/rek">Kembali</a></span></h1>

	<?php echo form_open('rek/tambah', array('class' => 'basic')); ?>
    <table class="form">
    	<tr>
        	<td><label>Kode Rekening  </label><br /><input name="kd_rek" type="text" id="kd_rek" value="<?php echo set_value('kd_rek'); ?>" size="10" /> <?php echo form_error('kd_rek'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nama Rekening  </label><br />
            <input name="nama" type="text" id="ama" value="<?php echo set_value('nama'); ?>" size="60" />
            <?php echo form_error('nama'); ?>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>