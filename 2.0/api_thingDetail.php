<?php 
/* Populates the thing modal */


	$q = "call `v2.0_thingDetail`('".$_POST['token']."','".$_POST['thingid']."')";
	$obj['q'] = $q;
	$results = q($q);
	$obj['results'] = resultsToObject($results); 
	
?>