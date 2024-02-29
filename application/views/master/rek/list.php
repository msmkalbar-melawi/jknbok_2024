	<div id="content">
    	<h1><?php echo $page_title; ?> <span><a href="<?php echo site_url(); ?>/rek/tambah">Tambah</a></span></h1>
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>Kode Rekening </th>
                <th>Nama Rekening </th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $rek) : ?>
            <tr>
            	<td><?php echo $rek->kd_rek; ?></td>
                <td><?php echo $rek->nama; ?></td>
                <td><a href="<?php echo site_url(); ?>/rek/edit/<?php echo $rek->kd_rek; ?>" title="Edit"><img src="<?php echo base_url(); ?>asset/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/rek/hapus/<?php echo $rek->kd_rek; ?>" title="Hapus"><img src="<?php echo base_url(); ?>asset/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $this->master_model->get_count('ms_rek'); ?></span>
        <div class="clear"></div>
	</div>