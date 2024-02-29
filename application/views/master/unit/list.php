	<div id="content">
    	<h1><?php echo $page_title; ?> <span><a href="<?php echo site_url(); ?>/master/tambah_unit">Tambah</a></span></h1>
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>Kode Unit </th>
                <th>Nama Unit</th>
                <th>SKPD</th>                
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $unit) : ?>
            <tr>
            	<td><?php echo $unit->kd_unit; ?></td>
                <td><?php echo $unit->nm_unit; ?></td>                
                <td><?php echo $unit->kd_skpd; ?></td>               
                <td><a href="<?php echo site_url(); ?>/master/edit_unit/<?php echo $unit->kd_unit; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/hapus_unit/<?php echo $unit->kd_unit; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $this->master_model->get_count('ms_unit'); ?></span>
        <div class="clear"></div>
	</div>