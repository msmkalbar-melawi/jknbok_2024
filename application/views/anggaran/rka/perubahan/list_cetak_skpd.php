<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>   
   
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
  


</head>
<body>

<div id="content"> 
   
    <h1> <center> LIST SKPD</center></h1>
    <table width="100%">
    	<tr>
    		<td width="15%" align="center">KODE</td>
    		<td width="60%" align="center">NAMA</td>
    		<td width="25%" align="center">#</td>
    	</tr>
    <?php $sql=$this->db->query("SELECT  left(kd_gabungan,22) kd_skpd, nm_skpd from trskpd where SUBSTRING(kd_gabungan,19,4)='0000' 
group by left(kd_gabungan,22) ,nm_skpd order by left(kd_gabungan,22) "); 
    	foreach($sql->result() as $abc) :
    ?>
    	<tr>
    		<td width="15%" align="center"><?php echo $abc->kd_skpd; ?></td>
    		<td width="60%" align="left"><?php echo $abc->nm_skpd; ?></td>
    		<td width="25%" align="center"><a class="button" href="<?php echo base_url(); ?>cetak_rka/list_cetak<?php echo $jenis ?>_belanja_rinci_perubahan/<?php  echo $abc->kd_skpd; ?>/<?php echo $jenis ?>">PILIH</a></td>
    	</tr>
    <?php endforeach; ?>
    </table>
</div>

</body>

</html>