<?php 
/* Load List - Builds all the stores for a list, or a subset. */

if( array_key_exists('token', $_POST) == FALSE
	|| array_key_exists('id', $_POST) == FALSE
	|| array_key_exists('userIdFilter', $_POST) == FALSE
	|| array_key_exists('oldestKey', $_POST) == FALSE
	|| array_key_exists('sharedFilter', $_POST) == FALSE){

	$obj['error'] = true;
	$obj['errortxt'] = 'One of the inputs was incorrect.';
	break 2;
}

// echo 'Shared filter. Does it exist? ' . array_key_exists('sharedFilter', $_POST) . " : " . $_POST['sharedFilter'];

$token = $_POST['token'];
$listId = $_POST['id'];
$userIdFilter = $_POST['userIdFilter'];
$oldestKey = $_POST['oldestKey'];
$sharedFilter = $_POST['sharedFilter'];

$debug = false;
if($debug === true){
	$obj['debugInputs'] = Array("token"=> $token, "listId" => $listId, "userIdFilter" => $userIdFilter, "oldestKey" => $oldestKey, "sharedFilter" => $sharedFilter);
} 
else
{

	if($_POST['type'] === "all"){
		$type = Array("ditto","shared","mine", "feed", "strangers");
		$obj['note'] = 'The type was all';
	} else {
		$type = Array($_POST['type']);
	}

	foreach($type as $item){

		

		

		

		$q = 'call `v2.0_loadList`("'.$token.'","'. $item .'", "'. $listId.'","' . $userIdFilter . '","' . $oldestKey .'","' . $sharedFilter. '")';
		// 
		$results = q($q);
		$sqlErrorCheck = tokenCheck($results);
		if($sqlErrorCheck['error'] === true){
			$obj =  $sqlErrorCheck;
			break 1;
		} 


// 		$obj['q'] = $q;
		$obj['results'][$item] = resultsToObject($results);
		// $obj['results'][$item] = $results;
		// $obj['results'][$item] = Array();
	}

	
}

?>

