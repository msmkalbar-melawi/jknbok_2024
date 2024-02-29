

	<div id="content">        
    	<h1><?php echo $page_title; ?> <span><a href="<?php echo site_url(); ?>/tukd/tambah_spp">Input Baru</a></span></h1>
        <?php echo form_open('tukd/cari_spp', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="pencarian" id="pencarian" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th width="10%">NO SPP</th>
                <th width="10%">Tanggal</th>
                <th width="10%">SKPD</th>
                <th width="40%">Keperluan</th>                
                <th width="10%">Beban</th>
                <th width="10%">Aksi</th>
            </tr>
            <?php foreach($list->result() as $spp) : ?>
            <tr>
            	<td width="10%"><?php echo $spp->no_spp; ?></td>
                <td width="10%"><?php echo $spp->tgl_spp; ?></td>
                <td width="10%"><?php echo $spp->kd_skpd; ?></td>
                <td width="40%"><?php echo $spp->keperluan; ?></td>                
                <td width="10%">
                <?php
                $a=str_replace('/','123456789',$spp->no_spp);
                 ?>
                <?php if($spp->jns_spp == 2) {echo "GU";}elseif($spp->jns_spp == 3) {echo "TU";}elseif($spp->jns_spp == 4) {echo "LS Gaji";}
                elseif($spp->jns_spp == 5) {echo "LS PPKD";} elseif($spp->jns_spp == 6) {echo "LS Barang jasa";} else {echo " UP";} ?></td>
                <td width="10%"><a href="<?php echo site_url(); ?>/tukd/edit_spp/<?php echo $a; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/tukd/hapus_spp/<?php echo $a ?>/<?php echo $spp->kd_skpd; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>
        <div class="clear"></div>
	</div>