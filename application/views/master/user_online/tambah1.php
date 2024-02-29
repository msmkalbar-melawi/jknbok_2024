<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/user">Kembali</a></span></h1>

	<?php echo form_open('master/tambah_user', array('class' => 'basic')); ?>
    <table class="form">
    	<tr>
    <td><label>id user</label><br />
            <input name="id_user" type="text" id="id_user" value="<?php echo set_value('id_user'); ?>" size="10" />
			<?php echo form_error('id_user'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nama</label><br />
            <input name="nama" type="text" id="nama" value="<?php echo set_value('nama'); ?>" size="40" />
            <?php echo form_error('nama'); ?>
            </td>
        </tr>
        <tr>
            <td><label>User name </label><br />
            <input name="user_name" type="text" id="user_name" value="<?php echo set_value('user_name'); ?>" size="40" />
            <?php echo form_error('user_name'); ?>
            </td>
        </tr>
        <tr>
            <td><label>password</label><br />
            <input name="password" type="text" id="password" value="<?php echo set_value('password'); ?>" size="40" />
            <?php echo form_error('password'); ?>
            </td>
        </tr>
        <tr>
            <td><label>type user</label><br />
            <input name="type" type="text" id="type" value="<?php echo set_value('type'); ?>" size="40" />
            <?php echo form_error('type'); ?>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <table class="narrow">
        	<tr>
            	<th>ID </th>
                <th>menu</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $menu) : ?>
            <tr>
            	<td><?php echo $menu->id; ?></td>
                <td><?php echo $menu->title; ?></td>
                <td><input type="checkbox" name="otori_id[]" value="<?php echo $menu->id; ?>" />
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php echo form_close(); ?>
</div>