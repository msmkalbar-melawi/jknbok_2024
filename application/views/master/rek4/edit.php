<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/rek4">Kembali</a></span></h1>

	<?php echo form_open('master/edit_rek4/'.$rek4->kd_rek4, array('class' => 'basic')); ?>
    <table class="form">
    	 <tr>
            <td><label>Kode Rekening Kelompok</label><br />
             <?php
                $lctam = '<select name="kd_rek3">';
                $lctam1 = '';
                foreach($kdrek as $ckdrek){                    
                    if ($ckdrek->kd_rek3==$rek4->kd_rek3){
                        $lctam1 = $lctam1."<option value=\"$ckdrek->kd_rek3\" selected >$ckdrek->kd_rek3 | $ckdrek->nm_rek3 </option>";                    
                    }else{ 
                        $lctam1 = $lctam1."<option value=\"$ckdrek->kd_rek3\" >$ckdrek->kd_rek3 | $ckdrek->nm_rek3 </option>";
                    }
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
            
        </tr>
        
        
        <tr>
        	<td><label>Kode Rekening Objek</label><br /><input name="kd_rek4" type="text" id="kd_rek4" value="<?php echo $rek4->kd_rek4; ?>" size="10" /> <?php echo form_error('kd_rek4'); ?>
            </td>
        </tr>

        <tr>
            <td><label>Nama Rekening Objek</label><br />
            <input name="nm_rek4" type="text" id="nm_rek4" value="<?php echo $rek4->nm_rek4; ?>" size="60" />
            <?php echo form_error('ms_rek4'); ?>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>