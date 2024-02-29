<?php
	$result = array();
	
	include 'conn.php';
	
	$rs = mysql_query("select * from item where itemid in (select itemid from lineitem)");
	
	$items = array();
	while($row = mysql_fetch_object($rs)){
		array_push($items, $row);
	}
	
	echo json_encode($items);
?>