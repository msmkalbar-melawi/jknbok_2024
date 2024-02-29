<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/rek1">Kembali</a></span></h1>

	<?php echo form_open('master/tambah_rek1', array('class' => 'basic')); ?>
    <table class="form">
    	<tr>
        	<td><label>Kode Rekening 1 </label><br /><input name="kd_rek1" type="text" id="kd_rek1" value="<?php echo set_value('kd_rek1'); ?>" size="10" /> <?php echo form_error('kd_rek1'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nama Rekening 1 </label><br />
            <input name="nm_rek1" type="text" id="nm_rek1" value="<?php echo set_value('nm_rek1'); ?>" size="60" />
            <?php echo form_error('nm_rek1'); ?>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>