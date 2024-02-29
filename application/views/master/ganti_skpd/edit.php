  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
	<script type="text/javascript">
    $(function(){
        $('#pcskpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/skpd',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                urusan = rowData.kd_skpd;
                $("#nm_skpd").attr("value",rowData.nm_skpd);
               // validate_skpd();
                
            }  
        }); 
		 $("#pcskpd").combogrid("setValue",'<?php echo $user->kd_skpd; ?>');
      });

function CheckAll()
		{
			if(document.getElementById("allbox").checked == true)
			{
			 
				var formobj = document.forms[0];
				var counter = 0;
				for (var j = 0; j < formobj.elements.length; j++)
				{if (formobj.elements[j].type == "checkbox"){counter++;}}
				for (var i=0; i<counter; i++) 
				{
					document.getElementById(i).checked = true;
				}
			}
			else
			{

            var formobj = document.forms[0];
				var counter = 0;
				for (var j = 0; j < formobj.elements.length; j++)
				{if (formobj.elements[j].type == "checkbox"){counter++;}}
				for (var i=0; i<counter; i++) 
				{
					document.getElementById(i).checked = false;
				}
			}
		}
		

</script>
<div id="content">
	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/master/ganti_skpd">Kembali</a></span></h1>

	<?php echo form_open('master/edit_user_2/'.$user->id_user, array('class' => 'basic')); ?>
    <table class="form">
    	<tr>
        	<td><label>id</label><br /><input name="id_user" type="text" id="id_user" value="<?php echo $user->id_user; ?>" size="10" /> <?php echo form_error('id_user'); ?>
            </td>
        </tr>
        <tr>
            <td><label>user name</label><br />
            <input name="user_name" type="text" id="user_name" value="<?php echo $user->user_name; ?>" size="40" />
            <?php echo form_error('user'); ?>
            </td>
        </tr>
        <tr>
            <td><label>password</label><br />
            <input name="password_before" type="hidden" id="password_before" value="<?php echo $user->password; ?>" size="40" /><br />
            <input name="password" type="password" id="password" value="<?php //echo $user->password; ?>" size="40" />
            <?php echo form_error('user'); ?>
            </td>
        </tr>
        <tr>
            <td><label>Nama</label><br />
            <input name="nama" type="text" id="nama" value="<?php echo $user->nama; ?>" size="40" />
            <?php echo form_error('user'); ?>
            </td>
        </tr>
        <tr>
            <td><label>type</label><br />
            <input name="type" type="text" id="type" value="<?php echo $user->type; ?>" size="40" />
            <?php echo form_error('type'); ?>
            </td>
        </tr>
        <tr>
            <td>
            <input id="pcskpd" name="pcskpd" style="width: 100px;" /> <input id="nm_skpd" name="nm_skpd" style="width:200px;border: 0;"/>
            </td>
        </tr>
        <tr>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
        </tr>
    </table>
    <table class="narrow">
        	<tr>
            	<th>ID </th>
                <th>menu</th>
                <th>Aksi : Centang semua <input type="checkbox" name="allbox" id="allbox" value="check" onchange="CheckAll();" /></th>
            </tr>
             <?php 
			$i = 0;
			foreach($list->result() as $menu) : 
			?>
            <tr>
            	<td><?php echo $menu->id; ?></td>
                <td><?php echo $menu->title; ?></td>
                <td><input type="checkbox" name="otori_id[]" value="<?php echo $menu->id; ?>" id="<?php echo "$i" ?>" 
				<?php
				 if($menu->user_id == $user->id_user){echo "checked='checked'";}
				  ?> />
                </td>
            </tr>
            <?php 
			$i++;
			endforeach; 
			?>
        </table>

    <?php echo form_close(); ?>
</div>