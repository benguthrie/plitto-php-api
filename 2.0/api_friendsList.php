<?php 
/* Load List - Builds all the stores for a list, or a subset. */
$error= false;
if( !array_key_exists('token', $_POST)
){
  $error= true;
  $obj['error'] = true;
  $obj['errortxt'] = 'One of the inputs was missing or incorrect.';
}

if(!$error){
  // echo 'Shared filter. Does it exist? ' . !array_key_exists('sharedFilter', $_POST) . " : " . $_POST['sharedFilter'];

  $token = $_POST['token'];

  $q = "call `v2.0_friends`('".$token."')";

  $obj['results'] = q($q);
}
?>