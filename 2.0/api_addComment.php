<?php 
	/* This function allows a user to add a comment onto a specific user --> list-->thing */

$error= false;
if( !array_key_exists('token', $_POST)
  || !array_key_exists('uid', $_POST)
  || !array_key_exists('lid', $_POST)
  || !array_key_exists('tid', $_POST)
  || !array_key_exists('itemKey', $_POST)
  || !array_key_exists('comment', $_POST)
  || !array_key_exists('status', $_POST)
){
  $error= true;
  $obj['error'] = true;
  $obj['errortxt'] = 'One of the inputs was missing or incorrect.';
}

if(!$error){
  // 
  $q = 'call `v2.0_addComment`("'.$_POST['token'].'","'.$_POST['uid'].'","'.$_POST['lid'].'","'.$_POST['tid'].'","'.$_POST['itemKey'].'","'. sanitize( $_POST['comment'] ) .'","'. sanitize( $_POST['status'] ) .'" );';
  // $obj['q'] = $q;
  $results = q($q);

  $sqlErrorCheck = tokenCheck($results);

  if($sqlErrorCheck['error'] === true){
      $obj =  $sqlErrorCheck;
  } else {
      $obj['results'] = $results;
  }
}
?>