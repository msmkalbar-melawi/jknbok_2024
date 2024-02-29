<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<link href="<?php echo base_url(); ?>asset/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>asset/superfish/css/superfish.css" media="screen">
<script type="text/javascript" src="<?php echo base_url(); ?>asset/superfish/js/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/superfish/js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/superfish/js/superfish.js"></script>
<script type="text/javascript">

// initialise plugins
jQuery(function(){
    jQuery('ul.sf-menu').superfish({ 
            delay:       500,                            // one second delay on mouseout 
            animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
            speed:       'fast',                          // faster animation speed 
        });
});

</script>
</head>
<body>
<div id="wrapper">
	<div id="header">
    	<div class="title">SIMAKDA</div>
        <div class="clear"></div>
    </div>
    <div id="menu">
        <ul class="sf-menu">
			<?php echo $isi; ?>
		</ul>
        <div class="clear"></div>
    </div>
    
    <?php echo $contents; ?>
    <div id="footer">
			<?php echo "@ 2012 MSM Consultant"; ?>
		</div>
</div>

</body>
</html>