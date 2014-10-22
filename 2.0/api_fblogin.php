<?php
// this function expects the user id, email and name from Facebook.;

    if(!$_POST['fbFriends'] or !$_POST['fbMe']){
        
    }

	// Step: Take the user's list of friends, and turn it into a string that the database can process.
	$friends = Array();
	for($i=0; $i<count($_POST['fbFriends']['data']); $i++){

		$friends[] = $_POST['fbFriends']['data'][$i]['id'];
	}

 	// Returns an array of user IDs.
	$obj['friends'] = $friends;

	$q = "call `v2.0_fbLogin` ('".$_POST['fbMe']['id']."', '".$_POST['fbMe']['name']."','" .$_POST['fbMe']['email']."','".implode(',',$friends)."')";	
	// $obj['q'] = $q;
	// 
	$results = q($q);
	$obj['me'] = $results[0];

	$token = $results[0]['token'];

	// Append with the list of their friends.
	$q = "call `v2.0_friends`('".$token."')";

	$friendsResult = q($q);
	$obj['friends'] = $friendsResult;
	
	$q = "call `v2.0_getSome`('','".$token."','','');";
 
	$getSome = q($q); 
	$obj['getSome'] =  resultsToObject($getSome);
?>