<?php

$error = false;
if( !array_key_exists('token', $_POST)
|| !array_key_exists('itemKey', $_POST) 
|| !array_key_exists('action', $_POST) 
){
  $error= true;
  $obj['error'] = true;
  $obj['errortxt'] = 'One of the inputs was missing or incorrect.';
}

if(!$error){
  $obj['temp'] = 'Made the Ditto call';
  // $q = 'call ditto("'.$_POST['action'].'","'.$_POST['userid'].'","'.$_POST['sourceUserID'].'","'
  // 		.$_POST['listnameid'].'","'.$_POST['thingid'].'","'.$_SESSION['testpuids'].'");';

  $q = "call `v2.0_ditto`('".$_POST['token']
      ."','". $_POST['itemKey']
      ."','". $_POST['action']
      ."');";

  $obj['q'] = $q;

  $results = q($q);

  $sqlErrorCheck = tokenCheck($results);

  if($sqlErrorCheck['error'] === true){
      $obj =  $sqlErrorCheck;
  } else {
      $obj['results'] = $results;

      if( count($obj['results'] ) === 0 ) {
      $obj['results'][0] = 
          Array( 
              "thekey" => "0", 
              "friendsWith" => "0",
              "tid" => "0",
              "thingname" => "",
              "lid" => "0",
              "uuid" => "0"

          );
      } 
  }


}

	/**/

?>