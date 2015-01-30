<?php 

$error = false;
if( !array_key_exists('token', $_POST)
  || !array_key_exists('searchTerm', $_POST)
){

    $obj['error'] = true;
    $obj['errortxt'] = 'One of the inputs was missing or incorrect.';
    $error = true;
}

if(!$error){

  $q = 'call `v2.0_listSearch`("'.$_POST['token'].'","'.$_POST['searchTerm'].'");';
  // $obj['q'] = $q;
  // 
  // $obj['results'] = q($q);

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
?>