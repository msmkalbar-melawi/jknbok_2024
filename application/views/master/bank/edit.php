<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/bank">Kembali</a></span></h1>

	<?php echo form_open('master/edit_bank/'.$bank->kode, array('class' => 'basic')); ?>
    <table class="form">
    	<tr>
        	<td><label>Kode</label><br /><input name="kode" type="text" id="kode" value="<?php echo $bank->kode; ?>" size="10" /> <?php echo form_error('kode'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nama</label><br />
            <input name="nama" type="text" id="nama" value="<?php echo $bank->nama; ?>" size="40" />
            <?php echo form_error('ms_bank'); ?>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>