<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title>
    <?php echo $title; ?></title>
  <link rel="shortcut icon" href="<?= base_url(); ?>image/SIMAKDA.png" type="image/x-icon" />
  <link href="<?php echo base_url(); ?>assets/style.css" rel="stylesheet" type="text/css" />
  <base href="<?php echo base_url(); ?>" />
  <link type="text/css" href="<?php echo base_url(); ?>assets/menu.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets\font-awesome\css\font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets\font-awesome\css\font-awesome.css">
  <style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,800,700,600,300);

    /*body {
	background: #eee;
	margin:0;
	font-family: 'Open Sans', sans-serif;
}*/

    hr {
      border: 0;
      background: #dedede;
      height: 1px;
    }

    header {
      text-align: center;
      color: #232323;
    }

    header .judul {
      font-size: 17pt;
    }

    header .deskripsi {
      font-size: 11pt;
    }

    .wrap {
      width: 950px;
      margin: 25px auto;
    }

    nav.menu ul {
      overflow: hidden;
      color: #fff;
      list-style: none;
      float: left;
      padding: 0;
      width: 650px;
      margin: 0 0 0;
      background: #1abc9c;
      -webkit-box-shadow: 1px 1px 1px 0px rgba(204, 204, 204, 0.55);
      -moz-box-shadow: 1px 1px 1px 0px rgba(204, 204, 204, 0.55);
      box-shadow: 1px 1px 1px 0px rgba(204, 204, 204, 0.55);
    }

    nav.menu ul li {
      margin: 0;
      float: left;
    }

    nav.menu ul a {
      padding: 25px;
      display: block;
      font-weight: 600;
      font-size: 16px;
      color: #fff;
      text-transform: uppercase;
      transition: all 0.5s ease;
      text-decoration: none;
    }

    nav.menu ul a:hover {
      text-decoration: underline;
      background: #16a085;
    }

    .blog {
      float: left;
    }

    .conteudo {
      width: 600px;
      padding: 25px;
      margin: 25px auto;
      background: #fff;
      border: 1px solid #dedede;
      -webkit-box-shadow: 1px 1px 1px 0px rgba(204, 204, 204, 0.35);
      -moz-box-shadow: 1px 1px 1px 0px rgba(204, 204, 204, 0.35);
      box-shadow: 1px 1px 1px 0px rgba(204, 204, 204, 0.35);
    }

    .conteudo img {
      min-width: 650px;
      margin: 0 0 25px -25px;
      max-width: 650px;
    }

    .conteudo h1 {
      padding: 0;
      margin: 0 0 15px;
      font-weight: normal;
      color: #666;
      font-family: Georgia;
    }

    .conteudo p:last-child {
      margin: 0;
    }

    .conteudo .continue-lendo {
      color: #000;
      transition: all 0.5s ease;
      text-decoration: none;
      font-weight: 700;
    }

    .conteudo .continue-lendo:hover {
      margin-left: 10px;
    }

    .post-info {
      float: right;
      font-size: 12px;
      margin: -10px 0 15px;
      text-transform: uppercase;
    }

    @media screen and (max-width: 960px) {

      .header {
        position: inherit;
      }

      .wrap {
        width: 90%;
        margin: 25px auto;
      }

      nav.menu ul {
        width: 100%;
      }

      nav.menu ul {
        float: inherit;
      }

      nav.menu ul li {
        float: inherit;
        margin: 0;
      }

      nav.menu ul a {
        padding: 15px;
        font-size: 16px;
        border-top: 1px solid #1abf9f;
        border-bottom: 1px solid #16a085;
      }

      .blog {
        width: 90%;
      }

      .conteudo {
        float: inherit;
        margin: 0 auto 25px;
        width: 101%;
        border: 1px solid #dedede;
        padding: 5%;
        background: #fff;
      }

      .conteudo img {
        max-width: 110%;
        margin: 0 0 25px -5%;
        min-width: 110%;
      }

      .conteudo .continue-lendo:hover {
        margin-left: 0;
      }


    }

    @media screen and (max-width: 460px) {

      nav.menu ul a {
        padding: 15px;
        font-size: 14px;
      }

      .post-info {
        display: none;
      }

      .conteudo {
        margin: 25px auto;
      }

      .conteudo img {
        margin: -5% 0 25px -5%;
      }
    }

    button[type=submit] {
      background-color: #4CAF50;
      border: none;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      margin: 2px 2px;
      cursor: pointer;
    }

    button[type=submit]:hover {
      background: #ad3e4f;
    }

    button[type=submit]:disabled {
      border: 1px solid #999999;
      background-color: #cccccc;
      color: #666666;
    }

    button[type=edit] {
      background-color: #ffe83d;
      border: none;
      color: black;
      padding: 10px 20px;
      text-decoration: none;
      margin: 2px 2px;
      width: auto;
      cursor: pointer;
    }

    button[type=edit]:hover {
      background: #ad3e4f;
    }

    button[type=edit2] {
      background-color: #ffe83d;
      border: none;
      color: black;
      padding: 10px 20px;
      text-decoration: none;
      margin: 2px 2px;
      width: auto;
      cursor: pointer;
    }

    button[type=edit2]:hover {
      background: #ad3e4f;
    }

    button[type=pdf] {
      background-color: #ff471a;
      border: none;
      color: black;
      padding: 10px 20px;
      text-decoration: none;
      margin: 2px 2px;
      width: auto;
      cursor: pointer;
    }

    button[type=pdf]:hover {
      background: #ad3e4f;
    }

    button[type=delete] {
      background-color: #ff2003;
      border: none;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      margin: 2px 2px;
      width: auto;
      cursor: pointer;
    }

    button[type=delete]:hover {
      background: #ad3e4f;
    }

    button[type=primary] {
      background-color: #38a2ff;
      border: none;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      margin: 2px 2px;
      width: auto;
      cursor: pointer;
    }

    button[type=primary]:hover {
      background: #ad3e4f;
    }

    a[type=submit] {
      background-color: #4CAF50;
      border: none;
      color: white;
      padding: 2px 20px;
      text-decoration: none;
      margin: 2px 2px;
      cursor: pointer;
    }

    a[type=submit]:hover {
      background: #ad3e4f;
    }

    a[type=edit] {
      background-color: #ffe83d;
      border: none;
      color: black;
      padding: 2px 20px;
      text-decoration: none;
      margin: 2px 2px;
      width: auto;
      cursor: pointer;
    }

    a[type=edit]:hover {
      background: #ad3e4f;
    }

    a[type=edit2] {
      background-color: #ffe83d;
      border: none;
      color: black;
      padding: 2px 20px;
      text-decoration: none;
      margin: 2px 2px;
      width: auto;
      cursor: pointer;
    }

    a[type=edit2]:hover {
      background: #ad3e4f;
    }

    a[type=pdf] {
      background-color: #ff471a;
      border: none;
      color: black;
      padding: 2px 20px;
      text-decoration: none;
      margin: 2px 2px;
      width: auto;
      cursor: pointer;
    }

    a[type=pdf]:hover {
      background: #ad3e4f;
    }

    a[type=delete] {
      background-color: #ff2003;
      border: none;
      color: white;
      padding: 2px 20px;
      text-decoration: none;
      margin: 2px 2px;
      width: auto;
      cursor: pointer;
    }

    a[type=delete]:hover {
      background: #ad3e4f;
    }

    a[type=primary] {
      background-color: #38a2ff;
      border: none;
      color: white;
      padding: 2px 20px;
      text-decoration: none;
      margin: 2px 2px;
      width: auto;
      cursor: pointer;
    }

    a[type=primary]:hover {
      background: #ad3e4f;
    }

    p[type=primary] {
      background-color: #38a2ff;
      border: none;
      color: white;
      padding: 5px 2px;
      text-decoration: none;
      margin: 2px 2px;
      width: auto;
      cursor: pointer;
    }
  </style>
  <!-- set javascript base_url -->
  <script type="text/javascript">
    <![CDATA[
    var base_url = '<?php echo base_url(); ?>';
    ]]>
  </script>

  <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/menu.js"></script>

  <SCRIPT LANGUAGE="JavaScript">
    var secs;
    var timerID = null;
    var timerRunning = false;
    var delay = 2000;

    function InitializeTimer() {
      secs = 1;
      StopTheClock();
      StartTheTimer();
    }

    function StopTheClock() {
      if (timerRunning)
        clearTimeout(timerID);
      timerRunning = false;
    }

    function StartTheTimer() {
      if (secs == 0) {
        StopTheClock();
        ceklogin();
        secs = 1;
        timerRunning = true;
        timerID = self.setTimeout("StartTheTimer()", delay);
      } else {
        self.status = secs;
        secs = secs - 1;
        timerRunning = true;
        timerID = self.setTimeout("StartTheTimer()", delay);
      }
    }


    function ceklogin() {
      $(function() {
        $.ajax({
          type: 'POST',
          dataType: "json",
          url: "<?php echo base_url(); ?>index.php/welcome/ceklogin/",
          success: function(data) {
            if (data == 1) {
              document.location.href = '<?php echo base_url(); ?>index.php';
            }
          }
        });
      });
    }
  </SCRIPT>


</head>

<body onload="InitializeTimer(); StartTheTimer();">

  <script language=JavaScript>
    //Disable right mouse click Script
    //By Maximus (maximus@nsimail.com) w/ mods by DynamicDrive
    //For full source code, visit http://www.dynamicdrive.com

    //var message="Function Disabled!";
    //
    /////////////////////////////////////
    //function clickIE4(){
    //if (event.button==2){
    //alert(message);
    //return false;
    //}
    //}
    //
    //function clickNS4(e){
    //if (document.layers||document.getElementById&&!document.all){
    //if (e.which==2||e.which==3){
    //alert(message);
    //return false;
    //}
    //}
    //}
    //
    //if (document.layers){
    //document.captureEvents(Event.MOUSEDOWN);
    //document.onmousedown=clickNS4;
    //}
    //else if (document.all&&!document.getElementById){
    //document.onmousedown=clickIE4;
    //}
    //
    //document.oncontextmenu=new Function("alert(message);return false")
    //
    //// --> 
  </script>

  <div id="wrapper">
    <div id="header">
      <div class="title"></div>
      <div class="clear"></div>
    </div>

    <?php

    $otori = $this->session->userdata('pcOtoriName');
    echo $this->dynamic_menu->build_menu('dyn_menu', '1', $otori);

    ?>


    <?php echo $contents; ?>
    <div id="footer">
      @ <?php echo date("Y"); ?> MSM Consultant
    </div>
  </div>

</body>

</html>