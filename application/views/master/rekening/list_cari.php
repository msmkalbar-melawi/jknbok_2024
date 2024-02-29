

	<div id="content">
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo site_url(); ?>/master/program"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a> </h1>
		  
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>Kode Program</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $program) : ?>
            <tr>
            	<td><?php echo $program->kd_program; ?></td>
                <td><?php echo $program->nm_program; ?></td>
                <td><a href="<?php echo site_url(); ?>/master/edit_program/<?php echo $program->kd_program; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/hapus_program/<?php echo $program->kd_program; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Dari Total Record <?php echo $this->master_model->get_count(m_prog); ?></span>
        <div class="clear"></div>
	</div>