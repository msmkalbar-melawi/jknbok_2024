	<div id="content">
    	<h1><?php echo $page_title; ?> <span><a href="<?php echo site_url(); ?>/master/tambah_rek2">Tambah</a></span></h1>
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
 	            <th>Kode Rekening Akun</th>
            	<th>Kode Rekening Kelompok</th>
                <th>Nama Rekening Kelompok</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $rek2) : ?>
            <tr>
                <td><?php echo $rek2->kd_rek1; ?></td>
            	<td><?php echo $rek2->kd_rek2; ?></td>
                <td><?php echo $rek2->nm_rek2; ?></td>
                
                <td><a href="<?php echo site_url(); ?>/master/edit_rek2/<?php echo $rek2->kd_rek2; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/hapus_rek2/<?php echo $rek2->kd_rek2; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $this->master_model->get_count('ms_rek2'); ?></span>
        <div class="clear"></div>
	</div>