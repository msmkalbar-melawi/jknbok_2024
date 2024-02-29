<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/unit">Kembali</a></span></h1>

	<?php echo form_open('master/edit_unit/'.$unit->kd_unit, array('class' => 'basic')); ?>
    <table class="form">
    	<tr>
        	<td><label>Kode Unit</label><br /><input name="kd_unit" type="text" id="kd_unit" value="<?php echo $unit->kd_unit; ?>" size="20" /> <?php echo form_error('kd_unit'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nama</label><br />
            <input name="nm_unit" type="text" id="nm_unit" value="<?php echo $unit->nm_unit; ?>" size="50" />
            <?php echo form_error('ms_ttd'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Kode SKPD</label><br />
             <?php
                $lctam = '<select name="kd_skpd">';
                $lctam1 = '';
                foreach($skpd as $cskpd){                    
                    if ($cskpd->kd_skpd==$unit->kd_skpd){
                            $lctam1 = $lctam1."<option value=\"$cskpd->kd_skpd\" selected >$cskpd->kd_skpd | $cskpd->nm_skpd </option>";                    
                    }else{ 
                        $lctam1 = $lctam1."<option value=\"$cskpd->kd_skpd\" >$cskpd->kd_skpd | $cskpd->nm_skpd </option>";
                    }
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
            
        </tr>
        
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>