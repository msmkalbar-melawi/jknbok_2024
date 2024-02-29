
	<div id="content">  
    <?php 
    switch($sikap) {
        
    case "preview"; ?>
            <div class="scroll">
            <?php
                echo $prev;
            ?>
            
    <?php break;
        default; ?>
        	<h1><?php echo $page_title; ?></h1>
            <?php echo form_open('rka/cari_perdaIII_ubah', array('class' => 'basic')); ?>
    		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="nm_skpd" id="nm_skpd" value="<?php echo set_value('text'); ?>" />
            <input type='submit' name='cari' value='cari' class='btn' />
            <?php echo form_close(); ?>   
    		
    		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
            <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
            <?php endif; ?>
        
            <table class="narrow">
            	<tr>
     	            <th>Kode SKPD </th>            	
                    <th>Nama SKPD</th>                
                    <th>Aksi</th>
                </tr>
                <?php foreach($list->result() as $skpd) : ?>
                <tr>                
                    <td><?php echo $skpd->kd_skpd; ?></td>            	
                    <td><?php echo $skpd->nm_skpd; ?></td>  
                    <td>                     
                        <a href="<?php echo site_url(); ?>/rka/preview_perdaIII_ubah/<?php echo $skpd->kd_skpd;?>/0" ><img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="cetak" /></a>
                        <a href="<?php echo site_url(); ?>/rka/preview_perdaIII_ubah/<?php echo $skpd->kd_skpd; ?>/1" target='_blank'><img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                        <a href="<?php echo site_url(); ?>/rka/preview_perdaIII_ubah/<?php echo $skpd->kd_skpd; ?>/2"><img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a>                    
                        <a href="<?php echo site_url(); ?>/rka/preview_perdaIII_ubah/<?php echo $skpd->kd_skpd; ?>/3"><img src="<?php echo base_url(); ?>assets/images/icon/word.jpg" width="25" height="23" title="cetak"/></a>
                     
                </tr>
                <?php endforeach; ?>
            </table>
            <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>
            <div class="clear"></div>
    <?php break;
        }   ?>
	</div>