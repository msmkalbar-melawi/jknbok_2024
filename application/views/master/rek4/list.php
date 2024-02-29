	<div id="content">
    	<h1><?php echo $page_title; ?> <span><a href="<?php echo site_url(); ?>/master/tambah_rek4">Tambah</a></span></h1>
		<?php echo form_open('master/cari_rek4', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="pencarian" id="pencarian" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
 	            <th>Kode Rekening Kelompok</th>
            	<th>Kode Rekening Objek</th>
                <th>Nama Rekening Objek</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $rek4) : ?>
            <tr>
                <td><?php echo $rek4->kd_rek3; ?></td>
            	<td><?php echo $rek4->kd_rek4; ?></td>
                <td><?php echo $rek4->nm_rek4; ?></td>
                
                <td><a href="<?php echo site_url(); ?>/master/edit_rek4/<?php echo $rek4->kd_rek4; ?>" title="Edit"><img src="<?php echo base_url(); ?>assetss/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/hapus_rek4/<?php echo $rek4->kd_rek4; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assetss/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>
        <div class="clear"></div>
	</div>