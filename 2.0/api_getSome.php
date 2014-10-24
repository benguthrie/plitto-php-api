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
	$obj['q'] = $q;

	$results = q($q);

	if(isset($results[0]['error'])){
		$obj =$results;
		$obj['q'] = $q;
		$obj['errortxt'] = "This didn't fit the model.";
	} else {

		// 
		$obj['results'] = resultsToObject($results);
		// $obj['results'] = $results;

	}
	// $obj['post'] = $_POST;
?>

