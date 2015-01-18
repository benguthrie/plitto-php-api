<?php 
/* Populates the thing modal */


$q = "call `v2.0_thingDetail`('".$_POST['token']."','".$_POST['thingId']."')";
// $obj['q'] = $q;
$results = q($q);
$sqlErrorCheck = tokenCheck($results);

if($sqlErrorCheck['error'] === true){
    $obj =  $sqlErrorCheck;
} else {
	$obj['results'] = resultsToObject($results); 
}
?>