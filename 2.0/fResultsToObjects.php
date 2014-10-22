<?php 

// Updated 9/16/2014 to include the ditto source information. This is the standard list data model.
function resultsToObject($results){
	$obj = Array();
	// echo $results;
	// Ensure that there are results
	if(count($results) > 0){

		// Users are the root array
	
		$uid = null;
		$lid = null;

		$u = Array();
		$l = Array();

		// Convert the results into a nice obect 
		for($i=0; $i < count($results); $i++){
			// Check to see if the user id is different.
			if($uid !== $results[$i]['uid'] ){
				
				// Add the last list to the user before appending the user to the list.
				$u['lists'][] = $l; 

				// put the user in the main list.
				if($i > 0){
					$theResults[] = $u;
				}	

				// Set the new list.
				$l = Array(
					'lid' => $results[$i]['lid'], 
					'listname' => $results[$i]['listname'], 
					'items'=> Array());

				// Update the user and list variables
				$uid = $results[$i]['uid'];				;
				$lid = $results[$i]['lid'];
			
				// Create a new section for the new user.
				$u = Array(
					'uid'=> $results[$i]['uid']
					, 'username' => $results[$i]['username'] 
					, 'fbuid' => $results[$i]['fbuid'] 
					,'lists' => Array()
				);
			}

			// See if this is a new list
			if($lid !== $results[$i]['lid'] ){
				// Should we add the last list to the current user?
				if(count($l['items']) !== 0){
					// Add the last list to the user.
					$u['lists'][] = $l;
				}
				// Create the new list
				$l = Array('lid' => $results[$i]['lid'], 
					'listname' => $results[$i]['listname'], 
					'items'=> Array());
				// Set the new list varialbe.
				$lid = $results[$i]['lid'];
			}

			// Add the item to the current list.
			$l['items'][] = Array(
				'added' => $results[$i]['added']
				, 'tid' => $results[$i]['tid']
				, 'thingname' =>$results[$i]['thingname']
				, 'mykey' => $results[$i]['mykey']
				// 'show' => $data[$i]['show'],
				,'dittokey' => $results[$i]['dittokey']
				,'dittouser' => $results[$i]['dittouser']
				,'dittofbuid' => $results[$i]['dittofbuid']
				,'dittousername' => $results[$i]['dittousername']
		);
		}

		// Finally, add the last list to the last user, and that to the recordset.
		$u['lists'][] =  $l;
		$theResults[] = $u;

		$obj = $theResults;		
	} else {
		$obj['error'] = true;
		$obj['errorTxt'] = 'No Activity from your friends';
	}
	return $obj;
}

?>