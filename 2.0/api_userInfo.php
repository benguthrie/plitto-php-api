<?php 
/* Load List - Builds all the stores for a list, or a subset. */

if( array_key_exists('token', $_POST) == FALSE
	|| array_key_exists('uid', $_POST) == FALSE){

	$obj['error'] = true;
	$obj['errortxt'] = 'One of the inputs was incorrect.';
	break 2;
}

// echo 'Shared filter. Does it exist? ' . array_key_exists('sharedFilter', $_POST) . " : " . $_POST['sharedFilter'];

$token = $_POST['token'];
$uid = $_POST['uid'];

$debug = false;
if($debug === true){
	$obj['debugInputs'] = Array("token"=> $token, "uid" => $uid);
} 
else
{

	$q = 'call `v2.0_userInfo`("'.
		$token.'","'.
		$uid .
	'")';
	// 
	$results = q($q);

	$sqlErrorCheck = tokenCheck($results);
	if($sqlErrorCheck['error'] === true){
		$obj =  $sqlErrorCheck;
		break 1;
	} 


// 		$obj['q'] = $q;
	$obj['results'] = $results[0];
	// $obj['results'][$item] = $results;
	// $obj['results'][$item] = Array();

}

?>