<?php 

/*
	$obj['q'] = " call `spShowSome`( 'general' , '".$_SESSION['puid']."' , '".$_SESSION['puids']."' , null );";

	

	// 

	$obj['results'] = resultsToObject(q($q));
	// $obj['results'] = q($q);
	$obj['puids'] = $_SESSION['puids'];

	*/

	// 
	$q = 'call `v2.0_addComment`("'.$_POST['token'].'","'.$_POST['uid'].'","'.$_POST['lid'].'","'.$_POST['tid'].'","'.$_POST['comment'].'","'. sanitize( $_POST['status'] ) .'" );';
	$obj['q'] = $q;
	$obj['results'] = q($q);

	// $obj['post'] = $_POST;
?>

