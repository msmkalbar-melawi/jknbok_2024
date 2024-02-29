<div id="content">
	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/skpd"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a></h1>

	<?php echo form_open('master/edit_skpd/'.$skpd->kd_skpd, array('class' => 'basic')); ?>
    <table class="form">
    	 <tr>
            <td><label>Kode Urusan</label><br />
             <?php
                $lctam = '<select name="kd_urusan">';
                $lctam1 = '';
                foreach($kdurus as $ckdurus){                    
                    if ($ckdurus->kd_urusan==$skpd->kd_urusan){
                            $lctam1 = $lctam1."<option value=\"$ckdurus->kd_urusan\" selected >$ckdurus->kd_urusan | $ckdurus->nm_urusan </option>";                    
                    }else{ 
                        $lctam1 = $lctam1."<option value=\"$ckdurus->kd_urusan\" >$ckdurus->kd_urusan | $ckdurus->nm_urusan </option>";
                    }
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
            
        </tr>
        <tr>
        	<td><label>Kode SKPD</label><br /><input name="kd_skpd" type="text" id="kd_skpd" value="<?php echo $skpd->kd_skpd; ?>" size="20" /> <?php echo form_error('kd_skpd'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nama SKPD</label><br />
            <input name="nm_skpd" type="text" id="nm_skpd" value="<?php echo $skpd->nm_skpd; ?>" size="60" />
            <?php echo form_error('nm_skpd'); ?>
            </td>
        </tr>               
        <tr>
        	<td><label>NPWP SKPD</label><br /><input name="npwp" type="text" id="npwp" value="<?php echo $skpd->npwp; ?>" size="20" /> <?php echo form_error('npwp'); ?>
            </td>
        </tr>             
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>