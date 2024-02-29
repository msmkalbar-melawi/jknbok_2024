<html>
<head><title></title></head>
<body>
<?php echo form_open("login/usermasuk"); ?>
<input type="text" name="user">
<input type="password" name="pass">
<?php echo form_dropdown("level",$optionlist,"","id ='level'"); ?>
<input type="submit" value="submit"/>
</form>
</body>
</html>
