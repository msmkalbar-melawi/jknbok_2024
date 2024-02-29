<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/rek3">Kembali</a></span></h1>

	<?php echo form_open('master/edit_rek3/'.$rek3->kd_rek3, array('class' => 'basic')); ?>
    <table class="form">
    	 <tr>
            <td><label>Kode Rekening Kelompok</label><br />
             <?php
                $lctam = '<select name="kd_rek2">';
                $lctam1 = '';
                foreach($kdrek2 as $ckdrek){                    
                    if ($ckdrek->kd_rek2==$rek3->kd_rek2){
                        $lctam1 = $lctam1."<option value=\"$ckdrek->kd_rek2\" selected >$ckdrek->kd_rek2 | $ckdrek->nm_rek2 </option>";                    
                    }else{ 
                        $lctam1 = $lctam1."<option value=\"$ckdrek->kd_rek2\" >$ckdrek->kd_rek2 | $ckdrek->nm_rek2 </option>";
                    }
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
            
        </tr>
        
        
        <tr>
        	<td><label>Kode Rekening Jenis</label><br /><input name="kd_rek3" type="text" id="kd_rek3" value="<?php echo $rek3->kd_rek3; ?>" size="10" /> <?php echo form_error('kd_rek3'); ?>
            </td>
        </tr>

        <tr>
            <td><label>Nama Rekening Kelompok</label><br />
            <input name="nm_rek3" type="text" id="nm_rek3" value="<?php echo $rek3->nm_rek3; ?>" size="60" />
            <?php echo form_error('ms_rek3'); ?>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>