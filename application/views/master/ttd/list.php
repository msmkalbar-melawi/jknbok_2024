	<div id="content">
    	<h1><?php echo $page_title; ?> <span><a href="<?php echo site_url(); ?>/master/tambah_ttd">Tambah</a></span></h1>
		<?php echo form_open('master/cari_ttd', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="pencarian" id="pencarian" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>NIP </th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Pangkat</th>
                <th>SKPD</th>                
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $ttd) : ?>
            <tr>
            	<td><?php echo $ttd->nip; ?></td>
                <td><?php echo $ttd->nama; ?></td>
                <td><?php echo $ttd->jabatan; ?></td>
                <td><?php echo $ttd->pangkat; ?></td>
                <td><?php echo $ttd->kd_skpd; ?></td>               
                <td><a href="<?php echo site_url(); ?>/master/edit_ttd/<?php echo $ttd->nip; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/hapus_ttd/<?php echo $ttd->nip; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>
        <div class="clear"></div>
	</div>