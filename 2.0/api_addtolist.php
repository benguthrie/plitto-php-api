<?php
/* Used to add items to a list */

	// $thingname = cubrid_real_escape_string($_POST['thingName']);
	$thingName = sanitize($_POST['thingName']);
	// $thingName = $mysqli-> real_escape_string($_POST['thingName']);
	$q="call `v2.0_addtolist`('". $_POST['token']."','". $thingName ."','".
		$_POST['listnameid']."');";

	if(strlen($thingName) > 0){
		// The UI Shouldn't let this happen, so this must be an error.
		$results = q($q);

		$sqlErrorCheck = tokenCheck($results);

		if($sqlErrorCheck['error'] === true){
			$obj =  $sqlErrorCheck;
		} else {
			$obj['results'] = $results;
		}	

	} else {
		$obj['error'] = true;
		$obj['errortxt'] = 'Null value submitted.';
	}

	

	
?>