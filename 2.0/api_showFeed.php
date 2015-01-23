<?php
	/* This gets more content for lists or users when you open them. */
$debug = false;

$obj['thetoken'] = $_POST['token'];

// Prep the variables
if(!isset($_POST['newerOrOlder'])){
	$newerOrOlder = '';
}
else {
	$newerOrOlder = $_POST['newerOrOlder'];
}

$q = "call `v2.0_feed`('"
	.$_POST['token'] . "'"
	.",'".$_POST['theType'] ."'"
	.",'".$_POST['userFilter'] ."'"
	.",'".$_POST['listFilter'] ."'"
	.",'".$_POST['myState'] ."'"
	.",'".$_POST['continueKey']."'"
	.",'".$newerOrOlder."'"
	.");";
$results = q($q);

$sqlErrorCheck = tokenCheck($results);

if($sqlErrorCheck['error'] === true){
    $obj =  $sqlErrorCheck;
} else {
  if($debug == true){
    $obj['q'] = $q; 
    // $obj['results'] = $results;
    print_r($results);

    foreach($results as $row){
      echo "row" . $row['id']."
      ";
    }

  } else {
    
    // 
    $obj['results'] = resultsToObject($results);
      

    // $obj['results'] = q($q);

  }

}

   
   
?>