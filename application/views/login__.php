  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
	<script type="text/javascript">
    </script>



<div id="content">
<?php ini_set('memory_limit',"-1"); ?>
<?php $att=array('autocomplete'=>'off');?>
<?php echo form_open('',$att)?>
<?php echo isset($pesan) ? $pesan : ''?>
<table cellpadding="2px" 
cellspacing="1px" bgcolor="#F4F5F7" width="350px" class="tableBorder" align="center">
     <tr>
        <td colspan="3" bgcolor="#e33685">&nbsp;</td>
    </tr>
    
     <tr>
        <td align="center" colspan="3">
            <img src=" <?php echo base_url();?>image/gembok.png" border="0" align="absbottom"/>&nbsp;
            <span class="message">Silahkan Login Dahulu  </span>        </td>
    </tr>
    <tr>
        <td colspan="3" class="label">&nbsp;</td>
    </tr>

<tr>


<td class="label" align="right" >Username</td>
<td>
:</td>
<td>

<?php echo form_input('username')?></td>
</tr><tr>
 <td class="label" align="right">Password</td><td>
:
</td><td>
<?php echo form_password('password')?>
</td>
</tr>

<tr>
    <td class="label" align="right" >Tahun Anggaran</td>
    <td>
    :</td>
    <td>
    
    
    <?php $thang =  (date("Y")); 
        $thang_maks = $thang + 5 ;
        $thang_min = $thang - 5 ;
        echo '<select name ="pcthang">';
        
        for ($th=$thang_min ; $th<=$thang_maks ; $th++)
        {
            if ($th==$thang) {
                echo "<option selected value=$th>$thang</option>";
                }
            else {	
            echo "<option value=$th>$th</option>";
            }
        }
        echo '</select>';	
        
        
    ?>&nbsp;&nbsp;&nbsp;<?php echo form_submit('submit', 'Login')?>
    </td>
</tr>



</table>
<?php echo form_close()?>
</div>
