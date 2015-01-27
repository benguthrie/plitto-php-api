<?php 
	/* GetSome gets some things that are either dittoable, or in common between two users, or one user and a group of their friends. */
	$q = "call `v2.0_getSome`('".$_POST['type']."','".$_POST['token']."','".$_POST['userFilter']."','".$_POST['listFilter']."','".$_POST['sharedFilter']."');";
	
	$results = q($q);

	$sqlErrorCheck = tokenCheck($results);

	if($sqlErrorCheck['error'] === true){
		$obj =  $sqlErrorCheck;
	} else {
		/* Begin the processing of the data results */
		$debug = false;

		if($debug == true){
			$obj['q'] = $q;	
			// $obj['results'] = $results;
			// print_r($results);

			foreach($results as $row){
				echo "row" . $row['id']." ".$row['username'];
			}

		} else {

			if(isset($results[0]['error'])){
				// $obj['results'] =$results;
				$obj['q'] = $q;
				$obj['errortxt'] = "No rows, or bad results. ";
				$obj['results'] = [];
			} else {
				// $obj['q'] = $q;	
				

				$obj['results'] = resultsToObject($results);
			}
		}
	
	}
	// $obj['post'] = $_POST;
?>

