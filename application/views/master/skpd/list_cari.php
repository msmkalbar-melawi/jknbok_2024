	<div id="content">        
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
        <a href="<?php echo site_url(); ?>/master/skpd"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a> </h1>
       
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
 	            <th>Kode SKPD </th>
            	<th>Kode Urusan</th>
                <th>Nama SKPD</th>
                <th>NPWP SKPD </th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $skpd) : ?>
            <tr>
                <td><?php echo $skpd->kd_skpd; ?></td>
            	<td><?php echo $skpd->kd_urusan; ?></td>
                <td><?php echo $skpd->nm_skpd; ?></td>                             
                <td><?php echo $skpd->npwp; ?></td> 
                <td><a href="<?php echo site_url(); ?>/master/edit_skpd/<?php echo $skpd->kd_skpd; ?>" title="Edit"><img src="<?php echo base_url(); ?>asset/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/hapus_skpd/<?php echo $skpd->kd_skpd; ?>" title="Hapus"><img src="<?php echo base_url(); ?>asset/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem"> Dari Total Record <?php echo $this->master_model->get_count('ms_skpd'); ?></span>
        <div class="clear"></div>
	</div>