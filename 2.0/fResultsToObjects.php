<?php 

// Updated 9/16/2014 to include the ditto source information. This is the standard list data model.
function resultsToObject($results){
	$obj = Array();

	// $obj['rowcount'] = count($results);

	// echo $results;
	// Ensure that there are results
	if(count($results) > 0){

		// Users are the root array
	
		$uid = null;
		$lid = null;

		$u = Array();
		$l = Array();

		// Does it have commentCount and resultCount - TODO1 - add to all responses.


		// Convert the results into a nice obect 
		for($i=0; $i < count($results); $i++){
			if(!isset($results[$i]['uid'])){
				$obj['error'] = true;
				$obj['errortxt'] = "unknown uid at row ".$i;
				break 1;
			}

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
					'showMore' => true,
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
					'listname' => json_decode( json_encode($results[$i]['listname']) ), 
					'showMore' => true,
					'items'=> Array());
				// Set the new list varialbe.
				$lid = $results[$i]['lid'];
			}

/*
			if( property_exists($results[$i], 'dittoCount')){
				$dittoCount = $results[$i]['dittoCount'];
			} else {
				$dittoCount = 0;
			}

			if( property_exists($results[$i], 'commentCount')){
				$commentCount = $results[$i]['commentCount'];
			} else {
				$commentCount = 0;
			}
*/
// Add the item to the current list.
      // convert timezone? 
      // date_default_timezone_set('America/Chicago');
      // $utc_time = new DateTimeZone('America/Los_Angeles');

      // Prepare the relative time.
			// $rowTime = 
          
			$l['items'][] = Array(
				"id" => $results[$i]['id'],
				// "added" => time_elapsed_string($results[$i]['added']), 
				// "added" =>  date('YYYY-MM-DD H:i:s', strtotime('+6 hours', '2015-02-03 22:32:00')),
				"added" =>  date( 'Y-m-d H:i:s' , strtotime( $results[$i]['added'] ) + 7200 ) , // Everything will be central time, until we get relative timezones.
				"otherTime" => $results[$i]['added'], 
				"tid" => $results[$i]['tid']
				, "dittokey" => $results[$i]['dittokey']
				, "mykey" => $results[$i]['mykey']
				, "dittouser" => $results[$i]['dittouser']
				, "dittofbuid" => $results[$i]['dittofbuid']
				, "dittousername" => $results[$i]['dittousername']
				, "thingname" => json_decode( json_encode($results[$i]['thingname']) )
				, "commentText" => $results[$i]['commentText']
				, "commentRead" => $results[$i]['commentRead']
				, "commentActive" => $results[$i]['commentActive']
				, "ik" => $results[$i]['uuid']
				, "friendsWith" => "" /* TODO1 - Update this later to include the actual number of friends with */
				// , "test1" => $lid === $results[$i]['lid']
				, "dittoCount" => $results[$i]['dittoCount']
				, "commentCount" => $results[$i]['commentCount']
			);
		}

		// Finally, add the last list to the last user, and that to the recordset.
		$u['lists'][] =  $l;
		$theResults[] = $u;

		$obj = $theResults;		
	} else {
		// $obj['error'] = true;
		// $obj['errorTxt'] = 'No Activity from your friends';
		// There are no items. Just return an empty array.
		$theResults = Array("message"=>"No Records");
	}
	return $obj;
}


?>