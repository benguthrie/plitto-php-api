<?php

	$obj['temp'] = 'Made the Ditto call';
	// $q = 'call ditto("'.$_POST['action'].'","'.$_POST['userid'].'","'.$_POST['sourceUserID'].'","'
	// 		.$_POST['listnameid'].'","'.$_POST['thingid'].'","'.$_SESSION['testpuids'].'");';

	
	$q = "call `v2.0_ditto`('".$_POST['token']
		."','". $_POST['itemKey']
		."','". $_POST['action']
		."');";

	// 
$obj['q'] = $q;

	$results = q($q);

	$sqlErrorCheck = tokenCheck($results);

	$obj['results'] = $results;

	if($sqlErrorCheck['error'] === true){
		$obj =  $sqlErrorCheck;
	} else {
		$obj['results'] = $results;

		if( count($obj['results'] ) === 0 ) {
		$obj['results'][0] = 
			Array( 
				"thekey" => "0", 
				"friendsWith" => "0",
				"tid" => "0",
				"thingname" => "",
				"lid" => "0",
				"uuid" => "0"

			);
		} 
	}
	
	


	/**/

?>