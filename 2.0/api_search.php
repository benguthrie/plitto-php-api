<?php
	$q="call `v2.0_search`('".$_POST['token']."','".$_POST['search']."');";
	$obj['q'] = $q;
	// 
	$results = q($q);
	// 
	$obj['results'] = $results;
	
?>