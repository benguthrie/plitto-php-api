<?php 
/* Load List - Builds all the stores for a list, or a subset. */

if( 
	array_key_exists('token', $_POST) == FALSE	){

	$obj['error'] = true;
	$obj['errortxt'] = 'Missing Token.';
	
}

// echo 'Shared filter. Does it exist? ' . array_key_exists('sharedFilter', $_POST) . " : " . $_POST['sharedFilter'];

$token = $_POST['token'];

$q= "call `v2.0_counts`('". $token."')";
$results = q($q);
$sqlErrorCheck = tokenCheck($results);

if($sqlErrorCheck['error'] === true){
    $obj =  $sqlErrorCheck;
} else {
	$obj['results'] = $results[0]; 
}


?>