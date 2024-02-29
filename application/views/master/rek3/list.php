	<div id="content">
    	<h1><?php echo $page_title; ?> <span><a href="<?php echo site_url(); ?>/master/tambah_rek3">Tambah</a></span></h1>
		<?php echo form_open('master/cari_rek3', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="pencarian" id="pencarian" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
 	            <th>Kode Rekening Kelompok</th>
            	<th>Kode Rekening Jenis</th>
                <th>Nama Rekening Jenis</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $rek3) : ?>
            <tr>
                <td><?php echo $rek3->kd_rek2; ?></td>
            	<td><?php echo $rek3->kd_rek3; ?></td>
                <td><?php echo $rek3->nm_rek3; ?></td>
                
                <td><a href="<?php echo site_url(); ?>/master/edit_rek3/<?php echo $rek3->kd_rek3; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/hapus_rek3/<?php echo $rek3->kd_rek3; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>
        <div class="clear"></div>
	</div>