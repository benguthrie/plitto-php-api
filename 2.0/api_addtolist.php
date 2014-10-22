<?php
/* Used to add items to a list */

	// $thingname = cubrid_real_escape_string($_POST['thingName']);
	$thingName = sanitize($_POST['thingName']);
	// $thingName = $mysqli-> real_escape_string($_POST['thingName']);
	$q="call `v2.0_addtolist`('". $_POST['token']."','". $thingName ."','".
		$_POST['listnameid']."');";
	//$obj['q'] = $q;  
	$obj['results'] = q($q);
	
?>