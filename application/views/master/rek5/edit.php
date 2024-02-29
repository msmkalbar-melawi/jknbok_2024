<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/rek5">Kembali</a></span></h1>

	<?php echo form_open('master/edit_rek5/'.$rek5->kd_rek5, array('class' => 'basic')); ?>
    <table class="form">
    	 <tr>
            <td><label>Kode Rekening Objek</label><br />
             <?php
                $lctam = '<select name="kd_rek4">';
                $lctam1 = '';
                foreach($kdrek as $ckdrek){                    
                    if ($ckdrek->kd_rek4==$rek5->kd_rek4){
                        $lctam1 = $lctam1."<option value=\"$ckdrek->kd_rek4\" selected >$ckdrek->kd_rek4 | $ckdrek->nm_rek4 </option>";                    
                    }else{ 
                        $lctam1 = $lctam1."<option value=\"$ckdrek->kd_rek4\" >$ckdrek->kd_rek4 | $ckdrek->nm_rek4 </option>";
                    }
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
            
        </tr>
        
        
        <tr>
        	<td><label>Kode Rekening Rincian Objek</label><br /><input name="kd_rek5" type="text" id="kd_rek5" value="<?php echo $rek5->kd_rek5; ?>" size="10" /> <?php echo form_error('kd_rek5'); ?>
            </td>
        </tr>

        <tr>
            <td><label>Nama Rekening Rincian Objek</label><br />
            <input name="nm_rek5" type="text" id="nm_rek5" value="<?php echo $rek5->nm_rek5; ?>" size="60" />
            <?php echo form_error('ms_rek5'); ?>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>