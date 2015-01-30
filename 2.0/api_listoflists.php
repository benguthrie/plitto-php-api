<?php 
  // This is to show a list of lists for a user, or all of a user's friends including that user.

$error = false;
if( 
  !array_key_exists('token', $_POST)
  || !array_key_exists('userfilter', $_POST)
){
  $obj['error'] = true;
  $obj['errortxt'] = 'One of the inputs was missing or incorrect.';
  $error = true;
}

if(!$error){
  $q = "call `v2.0_listoflists`('".
    $_POST["token"]."', '"
    . $_POST['userfilter'] ."','');";
  // 
  $obj['q'] = $q;

  // Break out the list of fbuids
  // for($i=0;$i<count($result);$i++){
  // 	// echo $result[$i]['fbuids'];
  // 	$result[$i]['fbuids'] =  explode(',',$result[$i]['fbuids']);
  // }

  // 
  $results = q($q); 
  // $obj['results'] = $results;
  
  // $results = q($q);

  $sqlErrorCheck = tokenCheck($results);

  if($sqlErrorCheck['error'] === true){
    $obj =  $sqlErrorCheck;
  } else {
    // Debugging
    $obj['results'] = $results;
    // $obj['results'] = $data;
  }
}
?>