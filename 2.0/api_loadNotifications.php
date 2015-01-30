<?php 
/* Load List - Builds all the stores for a list, or a subset. 
	This may never be used.
*/
$error = false;
if( 
	array_key_exists('token', $_POST)
	|| !array_key_exists('userId', $_POST)
){
  $error= true;
  $obj['error'] = true;
  $obj['errortxt'] = 'One of the inputs was missing or incorrect.';
}

if(!$error){

  // echo 'Shared filter. Does it exist? ' . !array_key_exists('sharedFilter', $_POST) . " : " . $_POST['sharedFilter'];

  $token = $_POST['token'];
  $userId = $_POST['userId'];

  $debug = false;
  if($debug === true){
    $obj['debugInputs'] = Array( "token"=> $token, "userId" => $userId );
  } 
  else
  {
    // Process $userId to be -1, for "all", 0 for "strangers" -- Not supported currently., or an integer of the requested userId.

    if( strlen($userId) === 0) {
      $userId = -1;
    } else {
      $userId = intval($userId);
    }

    $q = 'call `v2.0_notifications`("'. $token .'", '. $userId .')';

    $results = q($q);

    $sqlErrorCheck = tokenCheck($results);

    if($sqlErrorCheck['error'] === true){
      $obj =  $sqlErrorCheck;
    } else {
      // Debugging

      $obj['results'] = $results;
      // $obj['results'] = $data;

    }


  }
}

?>

