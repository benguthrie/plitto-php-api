<?php 
/* Load List - Builds all the stores for a list, or a subset. */

if( 
	array_key_exists('token', $_POST) == FALSE
	|| array_key_exists('userId', $_POST) == FALSE
	|| array_key_exists('makeRead', $_POST ) == FALSE 
	){

	$obj['error'] = true;
	$obj['errortxt'] = 'One of the inputs was incorrect.';
	break 1;
}

// echo 'Shared filter. Does it exist? ' . array_key_exists('sharedFilter', $_POST) . " : " . $_POST['sharedFilter'];

$token = $_POST['token'];
$userId = $_POST['userId'];
$makeRead = $_POST['makeRead'];

$debug = false;
if($debug === true){
	$obj['debugInputs'] = Array( "token"=> $token, "userId" => $userId, "makeRead" => $makeRead );
} 
else
{
	// Process $userId to be -1, for "all", 0 for "strangers" -- Not supported currently., or an integer of the requested userId.

	if( strlen($userId) === 0) {
		$userId = -1;
	} else {
		$userId = intval($userId);
	}

	// There will be two calls for the makeread commands.

	// $obj["makeRead"] = $makeRead;

	$readDitto = Array();
	$readChat = Array();

	foreach ($makeRead as &$var){
		if($var["type"] === "ditto"){
			$readDitto[] = $var['id'];
		} else 
		if($var["type"] === "chat"){
			$readChat[] = $var['id'];
		}
		$obj["makeRead"] = Array($readDitto, $readChat );
	}

	if( count($readDitto) > 0) {

		$obj['readCount'] = count($readDitto);
		$obj['qD'] = "call `v2.0_makeRead` ('ditto','". implode(", ", $readDitto ) . "');";

		$obj['resultsD'] = q($obj['qD']);

	} 

	if( count($readChat) > 0) {
		$obj['qC'] = "call `v2.0_makeRead` ('chat','". implode(", ", $readChat ) . "');";
		$obj['resultsC'] = q($obj['qC']);
	}


	
}

?>