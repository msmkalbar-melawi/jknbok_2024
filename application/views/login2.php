<div id="content">
<?=form_open()?>
<?=isset($pesan) ? $pesan : ''?>
<table cellpadding="2px" 
cellspacing="1px" bgcolor="#F4F5F7" width="400px" class="tableBorder" align="center">
     <tr>
        <td colspan="2" bgcolor="#0066FF">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="label">&nbsp;</td>
    </tr>
     <tr>
        <td align="center" colspan="2">
            <img src="images/locked.png" border="0" align="absbottom"/>&nbsp;
            <span class="message">Silahkan Login Dahulu</span>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="label">&nbsp;</td>
    </tr>

<tr>

<td>
<td class="label" align="right" width="40%">Username:</td>
<td>
:
</td><td>
<?=form_input('username')?>
</td>
</tr><tr>
 <td class="label" align="right">Password:</td><td>
:
</td><td>
<?=form_password('password')?>
</td>
</tr><tr>
<td colspan="3">
<?=form_submit('submit', 'Login')?>
</td>
</tr>
</table>
<?=form_close()?>
</div>
