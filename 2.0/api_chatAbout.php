<?php 
/* Load List - Builds all the stores for a list, or a subset. */

if( array_key_exists('token', $_POST) == FALSE
	|| array_key_exists('userFilter', $_POST) == FALSE
	){

	$obj['error'] = true;
	$obj['errortxt'] = 'One of the inputs was incorrect.';
	break 2;
}

// echo 'Shared filter. Does it exist? ' . array_key_exists('sharedFilter', $_POST) . " : " . $_POST['sharedFilter'];

$token = $_POST['token'];
$userFilter = $_POST['userFilter'];

$debug = false;
if($debug === true){
	$obj['debugInputs'] = Array("token"=> $token, "userFilter" => $userFilter );
} 
else
{

	
		$q = 'call `v2.0_chatAbout`("'.$token.'","'. $userFilter .'" )';
		// 
		$results = q($q);

		$sqlErrorCheck = tokenCheck($results);

		if($sqlErrorCheck['error'] === true){
			$obj =  $sqlErrorCheck;
		} else {
			$obj['results'] = resultsToObject($results);
		}
	
	
}





	

?>

