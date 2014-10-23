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

// $obj['results'] = q($q);
$obj['results'] = resultsToObject(q($q));

?>

