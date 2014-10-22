<?php 
	$_SESSION['puid'] = 1; $_SESSION['puids'] = '2,3,4,5,6,7,8,9,10,11,12,13,16,81,161';

	$q = " call `spShowSome`( 'general' , '".$_SESSION['puid']."' , '".$_SESSION['puids']."' , null );";

	// 	$obj['q'] = $q;

	// 

	// $results = resultsToObject(q($q));
	$results = q($q);


// 	array_unshift($results,$testFriends);


	// 
	$obj['results'] = resultsToObject($results);;
	// $obj['puids'] = $_SESSION['puids'];
?>

