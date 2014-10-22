<?php 
	$obj['q'] = " call `spShowSome`( 'general' , '".$_SESSION['puid']."' , '".$_SESSION['puids']."' , null );";

	$q = $obj['q'];

	// 

	$obj['results'] = resultsToObject(q($q));
	// $obj['results'] = q($q);
	$obj['puids'] = $_SESSION['puids'];
?>

