<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/urusan1">Kembali</a></span></h1>

	<?php echo form_open('urusan1/tambah', array('class' => 'basic')); ?>
    <table class="form">
    	<tr>
        	<td><label>Kode Urusan1</label><br /><input name="kd_urusan1" type="text" id="kd_urusan1" value="<?php echo set_value('kd_urusan1'); ?>" size="10" /> <?php echo form_error('kd_urusan1'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nama Urusan1</label><br />
            <input name="nm_urusan1" type="text" id="nm_urusan1" value="<?php echo set_value('nm_urusan1'); ?>" size="40" />
            <?php echo form_error('nm_urusan1'); ?>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>