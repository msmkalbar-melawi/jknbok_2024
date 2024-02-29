<script>
function PopupCenter(pageURL, title,w,h) {
var left = (screen.width/2)-(w/2);
var top = (screen.height/2)-(h/2);
var targetWin = window.open (pageURL, title, 'directories=no,titlebar=no,toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
} 
function PopupCenterhapus(pageURL, title,w,h) {
if (confirm("Anda yakin ingin menghapus data ini ?")) 
		{
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		var targetWin = window.open (pageURL, title, 'directories=no,titlebar=no,toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		}
} 
</script>

	<div id="content">        
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo site_url(); ?>/fungsi/tambah"><img src="<?php echo base_url(); ?>asset/images/icon/add.jpg" width="25" height="23" title="Tambah Data"/></a>
        <img src="<?php echo base_url(); ?>asset/images/icon/print_pdf.png" width="25" height="23" title="Cetak Pdf"
        onclick="PopupCenter('<?php echo site_url(); ?>/fungsi/preview', 'myPop1',900,800);"/> </h1>
        <?php echo form_open('fungsi/cari', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="nm_fungsi" id="nm_fungsi" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>                            
        <?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>Kode Fungsi</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $fungsi) : ?>
            <tr>
            	<td><?php echo $fungsi->kd_fungsi; ?></td>
                <td><?php echo $fungsi->nm_fungsi; ?></td>
                <td><a href="<?php echo site_url(); ?>/fungsi/edit/<?php echo $fungsi->kd_fungsi; ?>" title="Edit"><img src="<?php echo base_url(); ?>asset/images/icon/edit.png" /></a>&nbsp;&nbsp;
                    <a href="<?php echo site_url(); ?>/fungsi/hapus/<?php echo $fungsi->kd_fungsi; ?>" title="Hapus"><img src="<?php echo base_url(); ?>asset/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $this->master_model->get_count('ms_fungsi'); ?></span>
        <div class="clear"></div>
	</div>