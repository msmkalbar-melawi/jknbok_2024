<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/rek2">Kembali</a></span></h1>

	<?php echo form_open('master/edit_rek2/'.$rek2->kd_rek2, array('class' => 'basic')); ?>
    <table class="form">
    	 <tr>
            <td><label>Kode Rekening Akun</label><br />
             <?php
                $lctam = '<select name="kd_rek1">';
                $lctam1 = '';
                foreach($kdrek as $ckdrek){                    
                    if ($ckdrek->kd_rek1==$rek2->kd_rek1){
                            $lctam1 = $lctam1."<option value=\"$ckdrek->kd_rek1\" selected >$ckdrek->kd_rek1 | $ckdrek->nm_rek1 </option>";                    
                    }else{ 
                        $lctam1 = $lctam1."<option value=\"$ckdrek->kd_rek1\" >$ckdrek->kd_rek1 | $ckdrek->nm_rek1 </option>";
                    }
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
            
        </tr>
        
        
        <tr>
        	<td><label>Kode Rekening Kelompok</label><br /><input name="kd_rek2" type="text" id="kd_rek2" value="<?php echo $rek2->kd_rek2; ?>" size="10" /> <?php echo form_error('kd_rek2'); ?>
            </td>
        </tr>

        <tr>
            <td><label>Nama Rekening Kelompok</label><br />
            <input name="nm_rek2" type="text" id="nm_rek2" value="<?php echo $rek2->nm_rek2; ?>" size="60" />
            <?php echo form_error('ms_rek2'); ?>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>