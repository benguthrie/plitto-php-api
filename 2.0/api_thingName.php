<?php 
/* Populates the thing modal */
$error= false;
if( 
  !array_key_exists('token', $_POST)	
  || !array_key_exists('thingId', $_POST)	
){

  $obj['error'] = true;
  $obj['errortxt'] = 'Missing Token.';
	
}

// echo 'Shared filter. Does it exist? ' . !array_key_exists('sharedFilter', $_POST) . " : " . $_POST['sharedFilter'];
if(!$error){

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
}
?>