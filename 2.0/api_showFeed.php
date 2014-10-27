<?php
	/* This gets more content for lists or users when you open them. */

$obj['thetoken'] = $_POST['token'];

$q = "call `v2.0_feed`('"
	.$_POST['token']."',  '"
	.$_POST['theType']."', '"
	.$_POST['userFilter']."', '"
	.$_POST['listFilter'] ."', '"
	.$_POST['myState']
	."','".$_POST['oldestKey']."');";

$obj['q'] = $q;

$results = q($q);

	$debug = false;

	if($debug == true){
		$obj['q'] = $q;	
		// $obj['results'] = $results;
		print_r($results);

		foreach($results as $row){
			echo "row" . $row['id']."
			";
		}

	} else {

		// $obj['results'] = q($q);
		$obj['results'] = resultsToObject($results);
	}

?>

