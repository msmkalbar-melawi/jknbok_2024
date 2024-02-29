<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/ttd">Kembali</a></span></h1>

	<?php echo form_open('master/edit_ttd/'.$ttd->nip, array('class' => 'basic')); ?>
    <table class="form">
    	<tr>
        	<td><label>NIP</label><br /><input name="nip" type="text" id="nip" value="<?php echo $ttd->nip; ?>" size="20" /> <?php echo form_error('nip'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nama</label><br />
            <input name="nama" type="text" id="nama" value="<?php echo $ttd->nama; ?>" size="50" />
            <?php echo form_error('ms_ttd'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Jabatan</label><br /><input name="jabatan" type="text" id="jabatan" value="<?php echo $ttd->jabatan; ?>" size="50"/> <?php echo form_error('jabatan'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Pangkat</label><br /><input name="pangkat" type="text" id="pangkat" value="<?php echo $ttd->pangkat; ?>" size="50"/> <?php echo form_error('pangkat'); ?>
            </td>
        </tr> 
        <tr>
            <td><label>Kode SKPD</label><br />
             <?php
                $lctam = '<select name="kd_skpd">';
                $lctam1 = '';
                foreach($skpd as $cskpd){                    
                    if ($cskpd->kd_skpd==$ttd->kd_skpd){
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
           <td><label>Kode</label><br />
            <select name="kode">
            <option value="">...pilih kode...</option>
            <option value="PA" <?php if("PA"=="$ttd->kode"){echo "selected";}?>>PA | Pengguna Anggaran</option>
            <option value="BP" <?php if("BP"=="$ttd->kode"){echo "selected";}?>>BP | Bendahara Pengeluaran</option>
			<option value="PT" <?php if("PT"=="$ttd->kode"){echo "selected";}?>>PT | PPTK</option>
            <option value="WK" <?php if("WK"=="$ttd->kode"){echo "selected";}?>>WK | Kuasa BUD</option>
            </select></label></td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>