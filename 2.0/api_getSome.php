<?php 

/*
	$obj['q'] = " call `spShowSome`( 'general' , '".$_SESSION['puid']."' , '".$_SESSION['puids']."' , null );";

	

	// 

	$obj['results'] = resultsToObject(q($q));
	// $obj['results'] = q($q);
	$obj['puids'] = $_SESSION['puids'];

	*/

	// 
	$q = "call `v2.0_getSome`('".$_POST['type']."','".$_POST['token']."','".$_POST['userFilter']."','".$_POST['listFilter']."');";
	

	$results = q($q);

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
			$obj['errortxt'] = "This didn't fit the model.";
		} else {
			$obj['q'] = $q;	
			// 
			// $obj['results'] = resultsToObject($results);
			// $obj['results'] = $results;

			$obj['results'] = resultsToObject($results);
		}
	}
	// $obj['post'] = $_POST;
?>

