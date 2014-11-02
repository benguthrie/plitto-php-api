<?php
// this function expects the user id, email and name from Facebook.;

    if(!$_POST['fbFriends'] or !$_POST['fbMe']){
        $obj['error'] = true;
        $obj['errortxt'] = 'missing inputs';
    } else {

		// Step: Take the user's list of friends, and turn it into a string that the database can process.
		$friends = Array();
		for($i=0; $i<count($_POST['fbFriends']['data']); $i++){

			$friends[] = $_POST['fbFriends']['data'][$i]['id'];
		}

	 	// Returns an array of user IDs.
		$obj['friends'] = $friends;

		$q = "call `v2.0_fbLogin`('".$_POST['fbMe']['id']."', '".$_POST['fbMe']['name']."','" .$_POST['fbMe']['email']."','".implode(',',$friends)."')";	

		$debug = false;

		if($debug===true){
			$obj['q'] =$q;
			// q('call `spSqlLog`(0,"'.sanitize($q).'",0.7035,"giggle")');
		} else {
			// Log the login queries.
				// spSqlLog`(userId INT, thequery TEXT, logtime DECIMAL(12,5), sp VARCHAR(45))
			
			// 
			$obj['q'] = $q;
			// 


			$results = q($q);
			// $obj['resultstemp'] = $results;

			// PRINT_R($results[0]);

			$obj['me'] = $results[0];

			$token = $results[0]['token'];

			$q = "call `v2.0_friends`('".$token."')";
			$friendsResult = q($q);
			$obj['friends'] = $friendsResult;

			$qg = "call `v2.0_getSome`('','".$token."','','','ditto');";

			$obj['qg'] = $qg;
		 
			$getSome = q($qg); 
			$obj['getSome'] =  resultsToObject($getSome);
		
			

		}
	}
?>