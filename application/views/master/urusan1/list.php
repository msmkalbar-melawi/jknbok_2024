	<div id="content">
    	<h1><?php echo $page_title; ?> <span><a href="<?php echo site_url(); ?>/urusan1/tambah">Tambah</a></span></h1>
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>Kode Urusan1</th>
                <th>Nama Urusan1</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $urusan1) : ?>
            <tr>
            	<td><?php echo $urusan1->kd_urusan1; ?></td>
                <td><?php echo $urusan1->nm_urusan1; ?></td>
                <td><a href="<?php echo site_url(); ?>/urusan1/edit/<?php echo $urusan1->kd_urusan1; ?>" title="Edit"><img src="<?php echo base_url(); ?>asset/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/urusan1/hapus/<?php echo $urusan1->kd_urusan1; ?>" title="Hapus"><img src="<?php echo base_url(); ?>asset/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $this->urusan1_model->get_count(); ?></span>
        <div class="clear"></div>
	</div>