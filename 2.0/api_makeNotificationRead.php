<?php 
/* Load List - Builds all the stores for a list, or a subset. */
// TODO2 - Improve the error handling.
$error = false;
if( 
	array_key_exists('token', $_POST)
	|| !array_key_exists('makeRead', $_POST ) 
	){

	$obj['error'] = true;
	$obj['errortxt'] = 'One of the inputs was incorrect.';
	$obj['post'] = $_POST;
	goto end;
}

// echo 'Shared filter. Does it exist? ' . !array_key_exists('sharedFilter', $_POST) . " : " . $_POST['sharedFilter'];
if(!$error){

  $token = $_POST['token'];
  $makeRead = $_POST['makeRead'];

  $debug = false;
  if($debug === true){
    $obj['debugInputs'] = Array( "token"=> $token, "makeRead" => $makeRead );
  } 
  else
  {
    // Process $userId to be -1, for "all", 0 for "strangers" -- Not supported currently., or an integer of the requested userId.

    // There will be two calls for the makeread commands.

    // $obj["makeRead"] = $makeRead;

    $readDitto = Array();
    $readChat = Array();

    foreach ($makeRead as &$var){
      if($var["type"] === "ditto"){
        $readDitto[] = $var['id'];
      } else 
      if($var["type"] === "chat"){
        $readChat[] = $var['id'];
      }
      $obj["makeRead"] = Array($readDitto, $readChat );
    }

    if( count($readDitto) > 0) {

      $obj['readCount'] = count($readDitto);
      $qD = "call `v2.0_makeRead` ('".$_POST['token']."',  ditto','". implode(", ", $readDitto ) . "');";

      // $obj['qD'] = $qD;
      $results = q($qD);

      $sqlErrorCheck = tokenCheck($results);

      if($sqlErrorCheck['error'] === true){
        $obj =  $sqlErrorCheck;
        break 1;
      } else {
        // Debugging

        $obj['resultsD'] = $results;
        // $obj['results'] = $data;

      }


    } 

    if( count($readChat) > 0) {
      $qC = "call `v2.0_makeRead` ('".$_POST['token']."','chat','". implode(", ", $readChat ) . "');";


      // $obj['qC'] = $qC;
      $results = q($qC);

      $sqlErrorCheck = tokenCheck($results);

      if($sqlErrorCheck['error'] === true){
        $obj =  $sqlErrorCheck;
        break 1;
      } else {
        // Debugging

        $obj['resultC'] = $results;
        // $obj['results'] = $data;

      }

    }



  }

}
end: 
 $obj['end'] = "Made to the end";

?>