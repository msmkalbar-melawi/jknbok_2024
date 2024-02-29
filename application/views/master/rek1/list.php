	<div id="content">
    	<h1><?php echo $page_title; ?> <span><a href="<?php echo site_url(); ?>/master/tambah_rek1">Tambah</a></span></h1>
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>Kode Rekening 1</th>
                <th>Nama Rekening 1</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $rek1) : ?>
            <tr>
            	<td><?php echo $rek1->kd_rek1; ?></td>
                <td><?php echo $rek1->nm_rek1; ?></td>
                <td><a href="<?php echo site_url(); ?>/master/edit_rek1/<?php echo $rek1->kd_rek1; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/hapus_rek1/<?php echo $rek1->kd_rek1; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $this->master_model->get_count('ms_rek1'); ?></span>
        <div class="clear"></div>
	</div>as