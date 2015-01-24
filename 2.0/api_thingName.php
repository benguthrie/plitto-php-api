<?php 
/* Populates the thing modal */


$q = "call `v2.0_thingName`('".$_POST['token']."','".$_POST['thingId']."')";
// $obj['q'] = $q;
$results = q($q);
// TODO2 - Token check this? $sqlErrorCheck = tokenCheck($results);

$sqlErrorCheck = false; // TODO3 This is sloppy. 
if($sqlErrorCheck['error'] === true){
    $obj =  $sqlErrorCheck;
} else {
	$obj['results'] = $results;
}
?>