<?php
	/* This gets more content for lists or users when you open them.
		on 1/18/2015, this isn't used.
        on 1/29/2015, it now is.

	 */

// $obj['post'] = $_POST;
        
$error= false;
if( !array_key_exists('token', $_POST)
  || !array_key_exists('fromUserId', $_POST)
  || !array_key_exists('listId', $_POST)
  || !array_key_exists('filter', $_POST)
   || !array_key_exists('ignoreTids', $_POST)
   || !array_key_exists('moreRecords', $_POST)
){
  $error= true;
  $obj['error'] = true;
  $obj['errortxt'] = 'One of the inputs was missing or incorrect.';
}

$reqLimit = $_POST['moreRecords'];

if(!$error){
  $q = "call `v2.0_GetMore`('". $_POST['token']."','".$_POST['fromUserId']."' ,'".$_POST['listId']."' ,'".$_POST['filter']."' ,'".$_POST['ignoreTids']."', ". $reqLimit.")";
  
  $results = q($q);

  $sqlErrorCheck = tokenCheck($results);
  // $obj['q'] = $q;

  if($sqlErrorCheck['error'] === true){
    $obj =  $sqlErrorCheck;
  } else {
    // Debugging
    // $obj['data'] = $results;

    $obj['results'] = resultsToObject($results);
    // $obj['results'] = $data;
    $obj['resultSize'] = count($results);
    if( intval($reqLimit) === count($results)){
      $obj['isMore'] = true;
    } else {
      $obj['isMore'] = false;
    }
  }
  
  // $obj['q'] = $q;
   
  /* REMOVED 1/29/2015
  $obj['thetoken'] = $_POST['token'];

  $filters = array();

// echo 'Existing: ' .$_POST['existing'];
  if(isset($_POST['existing']) === false or $_POST['existing'] === null ){
    $existing = 0;
  } else {
    $existing = $_POST['existing'];
    // echo 'count Existing: '.count($existing);
    $maxCount = count($existing);
    if($maxCount > 200){
      $maxCount = 200;
    }
    for($i = 0; $i< $maxCount; $i++){

      $item = '(' . str_replace('|',',',$existing[$i]['ult']) .')';
      if(strpos($item,"undefined") === false){
          $filters[] = $item;
      }
    }
  }
  // The filter string is now built for user, list, thing
  $stringFilter = implode(",",$filters);

  if($_POST['type'] === 'user'){
    $userFilter = $_POST['id'];
  } else {
    // Default to my friends. The Token will handle this.
    $userFilter =  '';
  }

   // $q = "call spGetMore('".$_POST["type"]."','".$_SESSION['puid']. "','".$_POST['id']."','".implode($lists,",")."','".implode($things,",")."');";
  $q = "call `v2.0_GetMore`('".$_POST['token']."','". $userFilter ."','". $_POST['type'] 
    ."','". $_POST['id']."','". $stringFilter ."')";

  $obj['q'] = $q;

  $results = q($q);

  $sqlErrorCheck = tokenCheck($results);

  if($sqlErrorCheck['error'] === true){
    $obj =  $sqlErrorCheck;
  } else {
    // Debugging
    // $obj['data'] = $data;

    // 
    $obj['results'] = resultsToObject($results);
    // $obj['results'] = $data;
    $obj['resultSize'] = count($results);
  }
  */
}

?>