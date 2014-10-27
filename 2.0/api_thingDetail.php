<?php 
/* Populates the thing modal */


	$q = "call `v2.0_thingDetail`('".$_POST['token']."','".$_POST['thingId']."')";
	$obj['q'] = $q;
	$results = q($q);
	$obj['results'] = resultsToObject($results); 
	
?>