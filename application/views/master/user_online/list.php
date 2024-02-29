<div id="content">
    	<h1><?php echo $page_title; ?> </h1>
		<?php echo form_open('master/cari_user', array('class' => 'basic')); ?>
		<!--Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="pencarian" id="pencarian" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />-->
        <?php echo form_close(); ?>
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>ID </th>
                <th>Nama</th>
                <th>Kode SKPD</th>
				
				<th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $user) : ?>
            <tr>
            	<td><?php echo $user->id_user; ?></td>
                <td><?php echo $user->nama; ?></td>
				<td><?php echo $user->kd_skpd; ?></td>
				
                <td>Log Out &nbsp;
            <a href="<?php echo site_url(); ?>/master/logout_user/<?php echo $user->id_user; ?>" title="Log Out"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a>
                 
                </td>
            </tr>
            <?php endforeach; ?>
			<tr>
			<td colspan="4" align="center">Log Out Semua&nbsp;
            <a href="<?php echo site_url(); ?>/master/logout_user_all" title="Log Out Semua"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a>
                 
                </td>
			</tr>
        </table>
        <div class="clear"></div>
	</div>