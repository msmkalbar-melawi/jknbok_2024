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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">		
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
    <script>
    
         $(function(){
            $('#skpd').combobox({
                width:150,
                valueField:'kd_skpd',
                textField:'nm_skpd',
                url:'<?php echo base_url(); ?>/index.php/rka/skpd',
                onSelect:function(rec){  
                            var url = '<?php echo base_url(); ?>/index.php/rka/giat/'+rec.kd_skpd;  
                            $('#giat').combobox('reload', url);  
                        }
            });
        });
        
        $(function(){
            $('#cg').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/rka/skpd',  
                    idField:'kd_skpd',                    
                    textField:'nm_skpd',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'kd_skpd',title:'kode',width:60},  
                        {field:'nm_skpd',title:'nama',align:'left',width:80} 
                          
                    ]]//,
//                    onSelect: function(){
//                        validate_keg(); 
//                    }
                       
                });
           });
           
        function validate_keg(){
        var cskpd1 = document.getElementById('kd_skpd').value;
        var x = document.getElementById("kd_skpd");
        var y = document.getElementById("kd_skpd1");
        getCmb = x.value;
        y.value = getCmb;        
         //alert(cskpd1);     
           $(function(){
            $('#gt').combogrid({  
                panelWidth:700,  
                url: '<?php echo base_url(); ?>/index.php/rka/kegiatan/'+cskpd1,  
                    idField:'kd_kegiatan',  
                    textField:'kd_kegiatan',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'kd_kegiatan',title:'kode',width:40},  
                        {field:'nm_kegiatan',title:'nama',align:'left',width:200} 
                          
                    ]]  
                });
           }); 
        }
        function validate_skpd(){
        var cskpd = document.getElementById('skpd').value;
        $(function(){
                $('#giat').combobox({
                    width:150,
                    valueField:'id',
                    textField:'kd_kegiatan',
                    url:'<?php echo base_url(); ?>/index.php/rka/giat/'+cskpd
                });
             });
        }
         
          $(function(){
            $('#gt').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/rka/kegiatan',  
                    idField:'kd_kegiatan',  
                    textField:'nm_kegiatan',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'kd_kegiatan',title:'kode',width:60},  
                        {field:'nm_kegiatan',title:'nama',align:'left',width:80} 
                          
                    ]]  
                });
           }); 
    </script>
	<div id="content">        
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo site_url(); ?>/kegiatan_terpilih/tambah"><img src="<?php echo base_url(); ?>assets/images/icon/add.jpg" width="25" height="23" title="Tambah Data"/></a>
        <a href="<?php echo site_url(); ?>/kegiatan_terpilih/preview" target='_blank'><img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a> </h1>
        <?php echo form_open('kegiatan_terpilih/cari', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="nm_kegiatan" id="nm_kegiatan" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>
         <?php
                $lctam = '<select name="kd_skpd" id="kd_skpd" onchange="javascript: validate_keg();">';
                $lctam1 = '<option value="">...Pilih Skpd...</option>';
                $lc = "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
                $query = $this->db->query($lc);
                $data=$query->result();
                foreach($data as $ckdskpd){    
                 
                        $lctam1 = $lctam1."<option value=\"$ckdskpd->kd_skpd\" >$ckdskpd->kd_skpd | $ckdskpd->nm_skpd </option>";
                }
                $lctam1 = $lctam1."</select></label>
            </td>";
                echo $lctam.$lctam1;
            ?>
        <table>
        <tr>
        <input id="cg" name="cg" style="width:150px" />
		<input id="skpd" name="skpd" />
        <input id="giat" class="easyui-combobox"  name="giat" data-options="valueField:'id',textField:'kd_kegiatan'"/>
        <td><input id="gt" name="gt" style="width:150px" /></td>
        <input name="kd_skpd1" type="text" id="kd_skpd1" value="<?php echo set_value('kd_skpd1'); ?>" size="20" /> <?php echo form_error('kd_skpd1'); ?>
        </tr>
        </table>
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>Kode skpd</th>
                <th>Kode urusan</th>
                <th>Kode Kegiatan</th>
                <th>Nama Kegiatan</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $kegiatan) : ?>
            <tr>
            	<td><?php echo $kegiatan->kd_skpd; ?></td>
                <td><?php echo $kegiatan->kd_urusan; ?></td>
                <td><?php echo $kegiatan->giat; ?></td>
                <td><?php echo $kegiatan->nm_kegiatan; ?></td>
                <td><a href="<?php echo site_url(); ?>/kegiatan_terpilih/edit/<?php echo $kegiatan->giat; ?>" title="Edit"><img src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a>
                &nbsp;&nbsp;<a href="<?php echo site_url(); ?>/kegiatan_terpilih/hapus/<?php echo $kegiatan->giat; ?>" title="Hapus"><img src="<?php echo base_url(); ?>assets/images/icon/cross.png" /></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>
        <div class="clear"></div>
	</div>