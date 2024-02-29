<div id="content">
    	<h1><?php echo $page_title; ?> 
        </h1>
        <?php echo form_close(); ?>
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>ID </th>
                <th>Nama</th>
                <th>Kode SKPD</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $user) : ?>
            <tr>
            	<td><?php echo $user->id_user; ?></td>
                <td><?php echo $user->nama; ?></td>
                <td><?php echo $user->kd_skpd; ?></td>
                <td>
                
            <a href="<?php echo site_url(); ?>master/edit_user_2/<?php echo $user->id_user; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>
               
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <div class="clear"></div>
	</div>