

	<div id="content">
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
        <a href="<?php echo site_url(); ?>/master/urusan"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a> </h1>
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>Kode Urusan</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $urusan) : ?>
            <tr>
            	<td><?php echo $urusan->kd_urusan; ?></td>
                <td><?php echo $urusan->nm_urusan; ?></td>
                <td><a href="<?php echo site_url(); ?>/master/edit_urusan/<?php echo $urusan->kd_urusan; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/hapus_urusan/<?php echo $urusan->kd_urusan; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?><span class="totalitem"> Dari Total Record <?php echo $this->master_model->get_count('ms_urusan'); ?></span> 
        <div class="clear"></div>
	</div>