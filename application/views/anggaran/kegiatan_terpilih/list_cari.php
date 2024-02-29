	<div id="content">        
    		<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/kegiatan_terpilih"><img src="<?php echo base_url(); ?>asset/images/icon/back.png" width="25" height="23" title="Kembali"/></a></h1>
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>Kode skpd</th>
                <th>Kode urusan</th>
                <th>Kode Kegiatan</th>
                <th>Nama Kegiatan</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $kegiatan) : ?>
            <tr>
            	<td><?php echo $kegiatan->kd_skpd; ?></td>
                <td><?php echo $kegiatan->kd_urusan; ?></td>
                <td><?php echo $kegiatan->giat; ?></td>
                <td><?php echo $kegiatan->nm_kegiatan; ?></td>
                <td><a href="<?php echo site_url(); ?>/kegiatan_terpilih/edit/<?php echo $kegiatan->giat; ?>" title="Edit"><img src="<?php echo base_url(); ?>asset/images/icon/edit.png" /></a>
                &nbsp;&nbsp;<a href="<?php echo site_url(); ?>/kegiatan_terpilih/hapus/<?php echo $kegiatan->giat; ?>" title="Hapus"><img src="<?php echo base_url(); ?>asset/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> 
	</div>