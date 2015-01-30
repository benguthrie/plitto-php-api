<?php


$error = false;
if( 
  !array_key_exists('token', $_POST)
  || !array_key_exists('searchFilter', $_POST) 
  || !array_key_exists('search', $_POST) 
){
  $error= true;
  $obj['error'] = true;
  $obj['errortxt'] = 'One of the inputs was missing or incorrect.';
}

if(!$error){

// PROCEDURE `v2.0_listSearch`(thetoken VARCHAR(36), searchTerm TEXT)
if( $_POST['searchFilter'] === "list"){
  $q="call `v2.0_listSearch`('".$_POST['token']."', '".sanitize($_POST['search'])."' );";	
} else {
  $q="call `v2.0_search`('".$_POST['token']."', '".sanitize($_POST['search'])."' );";
}

  $results = q($q);

  $sqlErrorCheck = tokenCheck($results);
  if($sqlErrorCheck['error'] === true){
    $obj =  $sqlErrorCheck;
  } else {

    // Build out the results
    $searchResults = Array();

    $tempGroup = Array();

    $groupId = 0;

    foreach($results as $row){
      // echo $row['name'];
      $searchResults[] = $row;
    }

  // $obj['results'] = $searchResults;
    $obj['results'] = searchProcess($searchResults);
  }
}
	

function searchProcess($r){
  $obj = Array();
  if(!isset($r[0]['id'])){
      return $r;
  } else {
    // Create the first section
    $section = Array('title'=>$r[0]['groupName'],'type'=> $r[0]['type'], 'results'=>Array());
    $groupId = $r[0]['group'];

    foreach($r as $record){
      if($record['group'] != $groupId){
        // Add the existing section to the last group.
        $obj[] = $section;
        // Start a new section with this title.
        $section = Array('title'=>$record['groupName'], 'type'=> $record['type'],'results'=>Array());
        $groupId = $record['group'];
      } 

      $section['results'][] = Array('nameId'=>$record['nameid'],'itemName'=>$record['name'], 'itemCount'=>$record['count']);	

    }

    // Add the final section
    $obj[] = $section;

    if(count($obj)> 0){
        return $obj;	
    }else{
        return 'no results';
    }

  }
}
?>