<div id="content">
    	<h1><?php echo $page_title; ?> 
        <span></span>
        </h1>
		<?php echo form_open('master/cari_user', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="pencarian" id="pencarian" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>ID </th>
                <th>Nama</th>
                <th>KODE SKPD</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $user) : ?>
            <tr>
            	<td><?php echo $user->id_user; ?></td>
                <td><?php echo $user->nama; ?></td>
                <td><?php echo $user->kd_skpd; ?></td>
                <td>
                
            <a href="<?php echo site_url(); ?>/master/edit_user/<?php echo $user->id_user; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>
        <div class="clear"></div>
	</div>