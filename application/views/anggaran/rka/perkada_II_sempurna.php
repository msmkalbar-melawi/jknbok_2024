<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script type="text/javascript">
    
    var kode = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
    var ctk = '';
        

    $(function(){
        $('#ttd2').combogrid({  
            panelWidth:400,  
            idField:'nip',  
            textField:'nama',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/load_ttd_gub',  
            columns:[[  
                {field:'nip',title:'NIP',width:200},  
                {field:'nama',title:'Nama',width:400}    
            ]]  
        });           
    })
 		
	function cek($ckdskpd,$cetak){
         //var ckdskpd = $kegiatan->kd_skpd;
         //var cgiat = $kegiatan->kd_skpd;
		if (document.getElementById("chkttd").checked == true){
            var ckttd = '1';
        }else{
            var ckttd   = '0';
        } 
        var  ttd_2 = $('#ttd2').combogrid('getValue');
        var ttd2 = ttd_2.split(" ").join("a");
			
        var cell = document.getElementById('cell').value;          
          
        url="<?php echo site_url(); ?>/rka/preview_perkadaII_unit_sempurna/"+$ckdskpd+'/'+$cetak+'/'+cell+'/'+ckttd+'/Report Lampiran II Penyempurnaan-'+$ckdskpd+'?&ttd2='+ttd2+'';
         
        openWindow( url );
    }
    
		
    function openWindow( url ){
         //var ckdskpd = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
         //var ckdskpd = cnoskpd.split("/").join("123456789");
             lc = '';
			window.open(url+lc,'_blank');
			window.focus();
	}
		  
      
	 
	 function alltrim(kata){
	 //alert(kata);
		b = (kata.split(' ').join(''));
		c = (b.replace( /\s/g, ""));
		return c
	 
	 }
	 

  
   </script>


	<div id="content">      
    	<h1><?php echo $page_title; ?> </h1>
        <!--<?php echo form_open('rka/cari_perkadaII', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="nm_skpd" id="nm_skpd" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>    -->

       <table>
            <tr>
                <td width="20%" style="border: none;">Penandatanganan</td>
                <td width="1%" style="border: none;">:</td>
                <td style="border: none;"><input type="text" id="ttd2" style="width: 200px;" />&nbsp;&nbsp; 
                </td>
            </tr> 
        </table>        
        <div style="display:none">
            <table id="dg_cunit"  style="width:875px;height:370px;"> 
            </table> 
        </div>
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
        <table>
            <tr>
               <td>&ensp;&ensp;Ukuran Baris  : &nbsp;<input type="number" id="cell" name="cell" style="width: 50px; border:1" value="1" /> &nbsp;&nbsp;</td>
                <td>&nbsp;</td>            
				<td><input type="checkbox" name="chkttd" id="chkttd" value="1" checked="checked" /> Penandatanganan di SKPD Terakhir</td>
			</tr>
			
        </table>
        <table class="narrow">
        	<tr>
 	            <th>Kode SKPD </th>            	
                <th>Nama SKPD</th>                
                <th>Aksi</th>
            </tr>
			
            <?php foreach($list->result() as $skpd) : ?>
            <tr>                
                <td><?php echo $skpd->kd_skpd; ?></td>            	
                <td><?php echo $skpd->nm_skpd; ?></td>  
                <td>                     

                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek('<?php echo $skpd->kd_skpd;?>','0');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek('<?php echo $skpd->kd_skpd;?>','1');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek('<?php echo $skpd->kd_skpd;?>','2');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="preview"/></a>

<!--
                    <a href="<?php echo site_url(); ?>/rka/preview_perkadaII_unit_sempurna/<?php echo $skpd->kd_skpd;?>/<?php echo '0';?>" ><img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="cetak" /></a>
                    <a href="<?php echo site_url(); ?>/rka/preview_perkadaII_unit_sempurna/<?php echo $skpd->kd_skpd; ?>/<?php echo '1';?>" target='_blank'><img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                    <a href="<?php echo site_url(); ?>/rka/preview_perkadaII_unit_sempurna/<?php echo $skpd->kd_skpd; ?>/<?php echo '2';?>"><img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a></td>
-->
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>
        <div class="clear"></div>
	</div>