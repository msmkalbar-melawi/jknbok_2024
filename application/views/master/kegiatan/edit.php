<div id="content">
	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/master/kegiatan"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a></h1>

	<?php echo form_open('master/edit_kegiatan/'.$kegiatan->kd_kegiatan, array('class' => 'basic')); ?>
    <table class="form">
        <tr>
            <td><label>Kode Program</label><br />
            
            <?php
                $lctam = '<select name="kd_program">';
                $lctam1 = '';
                foreach($program as $cprog){                    
                    if ($cprog->kd_program==$kegiatan->kd_program){
                            $lctam1 = $lctam1."<option value=\"$cprog->kd_program\" selected >$cprog->kd_program | $cprog->nm_program </option>";                    
                    }else{ 
                        $lctam1 = $lctam1."<option value=\"$cprog->kd_program\" >$cprog->kd_program | $cprog->nm_program </option>";
                    }
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
           </tr>
    	<tr>
        	<td><label>Kode Kegiatan</label><br />
            <input name="kd_kegiatan" type="text" id="kd_kegiatan" value="<?php echo $kegiatan->kd_kegiatan; ?>" size="20" /> <?php echo form_error('kd_kegiatan'); ?>
            </td>
        </tr>     
        <tr>
            <td><label>Nama Kegiatan</label><br />
            <input name="nm_kegiatan" type="text" id="nm_kegiatan" value="<?php echo $kegiatan->nm_kegiatan; ?>" size="60" />
            <?php echo form_error('nm_fungsi'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Jenis</label><br />
            <select name="jns_kegiatan">
            <option value="">...pilih jenis...</option>
            <option value="4" <?php if("4"=="$kegiatan->jns_kegiatan"){echo "selected";}?>> 4   | Pendapatan</option>
            <option value="51" <?php if("51"=="$kegiatan->jns_kegiatan"){echo "selected";}?>>51 | Belanja Tidak Langsung</option>
			<option value="52" <?php if("52"=="$kegiatan->jns_kegiatan"){echo "selected";}?>>52 | Belanja Langsung</option>
            <option value="61" <?php if("61"=="$kegiatan->jns_kegiatan"){echo "selected";}?>>61 | Pembiayaan Penerimaan</option>
            <option value="62" <?php if("62"=="$kegiatan->jns_kegiatan"){echo "selected";}?>>62 | Pembiayaan Pengeluaran</option>
            </select></label></td>
        </tr>
        
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>