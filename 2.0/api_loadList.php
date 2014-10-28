<?php 
/* Load List - Builds all the stores for a list, or a subset. */

$token = $_POST['token'];
$listId = $_POST['id'];
$userIdFilter = $_POST['userIdFilter'];
$oldestKey = $_POST['oldestKey'];
$sharedFilter = $_POST['sharedFilter'];


if($_POST['type'] === "all"){
	$type = Array("ditto","shared","mine", "feed", "strangers");
	$obj['note'] = 'The type was all';
} else {
	$type = Array($_POST['type']);
}

foreach($type as $item){
	$q = 'call `v2.0_loadList`("'.$token.'","'. $item .'", "'. $listId.'","' . $userIdFilter . '","' . $oldestKey .'","' . $sharedFilter. '")';
	// 
	$results = q($q);
	$obj['q'] = $q;
	$obj['results'][$item] = resultsToObject($results);
	// $obj['results'][$item] = $results;
	// $obj['results'][$item] = Array();
}

$obj['debug'] = $_POST['type'];


/*
	$obj['q'] = " call `spShowSome`( 'general' , '".$_SESSION['puid']."' , '".$_SESSION['puids']."' , null );";

	

	// 

	$obj['results'] = resultsToObject(q($q));
	// $obj['results'] = q($q);
	$obj['puids'] = $_SESSION['puids'];

	

	// 
	$q = "call `v2.0_loadList`('".$_POST['type']."','".$_POST['token']."','".$_POST['userFilter']."','".$_POST['listFilter']."','".$_POST['sharedFilter']."');";
	

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
			$obj['errortxt'] = "Error. This didn't fit the model.";
			$obj['results'] = $results;
		} else {
			$obj['q'] = $q;	
			// 
			// $obj['results'] = resultsToObject($results);
			// $obj['results'] = $results;

			$obj['results'] = resultsToObject($results);
		}
	}
	// $obj['post'] = $_POST;
	*/
?>

