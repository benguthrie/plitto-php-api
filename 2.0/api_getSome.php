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
	$obj['results'] = resultsToObject(q($q));
	// $obj['post'] = $_POST;
?>

