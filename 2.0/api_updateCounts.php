<?php 
/* Load List - Builds all the stores for a list, or a subset. */

if( 
	array_key_exists('token', $_POST) == FALSE	){

	$obj['error'] = true;
	$obj['errortxt'] = 'Missing Token.';
	
}

// echo 'Shared filter. Does it exist? ' . array_key_exists('sharedFilter', $_POST) . " : " . $_POST['sharedFilter'];

$token = $_POST['token'];

$debug = false;
if($debug === true){
	$obj['debugInputs'] = Array( "token"=> $token );
} 
else
{
	// Process $userId to be -1, for "all", 0 for "strangers" -- Not supported currently., or an integer of the requested userId.

	$q= "call `v2.0_counts`('". $token."')";
	$obj['results'] = q($q)[0];

}

?>