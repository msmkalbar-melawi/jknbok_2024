	<div id="content">
    	<h1><?php echo $page_title; ?> <span><a href="<?php echo site_url(); ?>/master/tambah_rek5">Tambah</a></span></h1>
		<?php echo form_open('master/cari_rek5', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="pencarian" id="pencarian" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
 	            <th>Kode Rekening Objek</th>
            	<th>Kode Rekening Rincian Objek</th>
                <th>Nama Rekening Rincian Objek</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $rek5) : ?>
            <tr>
                <td><?php echo $rek5->kd_rek4; ?></td>
            	<td><?php echo $rek5->kd_rek5; ?></td>
                <td><?php echo $rek5->nm_rek5; ?></td>
                
                <td><a href="<?php echo site_url(); ?>/master/edit_rek5/<?php echo $rek5->kd_rek5; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/hapus_rek5/<?php echo $rek5->kd_rek5; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>
        <div class="clear"></div>
	</div>