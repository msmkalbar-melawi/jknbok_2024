	<div id="content">        
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
        <a href="<?php echo site_url(); ?>/master/kegiatan"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a> </h1>
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>Kode Kegiatan</th>
                <th>Kode Program</th>
                <th>Nama Kegiatan</th>
                <th>Jenis</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $kegiatan) : ?>
            <tr>
            	<td><?php echo $kegiatan->kd_kegiatan; ?></td>
                <td><?php echo $kegiatan->kd_program; ?></td>
                <td><?php echo $kegiatan->nm_kegiatan; ?></td>
                <td><?php echo $kegiatan->jns_kegiatan; ?></td>
                <td><a href="<?php echo site_url(); ?>/master/edit_kegiatan/<?php echo $kegiatan->kd_kegiatan; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/hapus_kegiatan/<?php echo $kegiatan->kd_kegiatan; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Dari Total Record <?php echo $this->master_model->get_count('m_giat'); ?></span>
        <div class="clear"></div>
	</div>