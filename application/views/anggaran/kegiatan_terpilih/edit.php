<div id="content">
	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/kegiatan_terpilih"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a></h1>

	<?php echo form_open('kegiatan_terpilih/edit/'.$kegiatan->kd_kegiatan, array('class' => 'basic')); ?>
    <table class="form">
        <tr>
    	  <td width="14%"><label>Skpd </label>         
          <td width="86%">
            
           <?php
                $lctam = '<select name="kd_skpd">';
                $lctam1 = '';
                $lc = "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
                $query = $this->db->query($lc);
                $data=$query->result();
                foreach($data as $ckdskpd){                    
                    if ($ckdskpd->kd_skpd==$kegiatan->kd_skpd){
                            $lctam1 = $lctam1."<option value=\"$ckdskpd->kd_skpd\" selected >$ckdskpd->kd_skpd | $ckdskpd->nm_skpd </option>";                    
                    }else{ 
                        $lctam1 = $lctam1."<option value=\"$ckdskpd->kd_skpd\" >$ckdskpd->kd_skpd | $ckdskpd->nm_skpd </option>";
                    }
                }
                $lctam1 = $lctam1."</select></label></td>";
                echo $lctam.$lctam1;
            ?>
        </tr>
        <tr>
    	  <td width="14%"><label>Urusan </label>        
          <td width="86%">
            
           <?php
                $lctam = '<select name="kd_urusan">';
                $lctam1 = '';
                $lc = "select kd_urusan,nm_urusan from ms_urusan order by kd_urusan";
                $query = $this->db->query($lc);
                $data=$query->result();
                foreach($data as $ckdurusan){    
                    if ($ckdurusan->kd_urusan==$kegiatan->kd_urusan){
                            $lctam1 = $lctam1."<option value=\"$ckdurusan->kd_urusan\" selected >$ckdurusan->kd_urusan | $ckdurusan->nm_urusan</option>";                    
                    }else{
                        $lctam1 = $lctam1."<option value=\"$ckdurusan->kd_urusan\" >$ckdurusan->kd_urusan | $ckdurusan->nm_urusan </option>";
                        }
                }
                $lctam1 = $lctam1."</select></label>
            </td>";
                echo $lctam.$lctam1;
            ?>
        </tr>        	
               
        <tr>        
    	  <td width="14%"><label>Kegiatan </label>         
          <td width="86%">            
           <?php                      
                $lctam = '<select name="kd_kegiatan">';
                $lctam1 = '';                
                $lc = "select kd_kegiatan,nm_kegiatan from m_giat order by kd_kegiatan";
                $query = $this->db->query($lc);
                $data=$query->result();
                foreach($data as $ckdgiat){    
                    if ($ckdgiat->kd_kegiatan==$kegiatan->kd_kegiatan1){
                            $lctam1 = $lctam1."<option value=\"$ckdgiat->kd_kegiatan\" selected >$ckdgiat->kd_kegiatan | $ckdgiat->nm_kegiatan</option>";                    
                    }else{
                        $lctam1 = $lctam1."<option value=\"$ckdgiat->kd_kegiatan\" >$ckdgiat->kd_kegiatan | $ckdgiat->nm_kegiatan </option>";
                        }
                }
                $lctam1 = $lctam1."</select></label>
            </td>";
                echo $lctam.$lctam1;
            ?>
        </tr>    
         <tr>
          <td>&nbsp;</td>
       	  <td><input name="kd_skpd1" type="text" id="kd_skpd1" value="<?php echo $kegiatan->kd_skpd; ?>" size="20" /> <?php echo form_error('kd_skpd1'); ?>
              <input name="kd_urusan1" type="text" id="kd_urusan1" value="<?php echo $kegiatan->kd_urusan; ?>" size="20" /> <?php echo form_error('kd_urusan1'); ?></td>  
            
        </tr>                
        <tr>
          <td>&nbsp;</td>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>