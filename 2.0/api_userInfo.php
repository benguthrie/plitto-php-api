<?php 
/* Load List - Builds all the stores for a list, or a subset. */
$error= false;
if( !array_key_exists('token', $_POST)
  || !array_key_exists('uid', $_POST)){

  $obj['error'] = true;
  $obj['errortxt'] = 'One of the inputs was incorrect.';
}

// echo 'Shared filter. Does it exist? ' . !array_key_exists('sharedFilter', $_POST) . " : " . $_POST['sharedFilter'];
if(!$error){

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
}

?>