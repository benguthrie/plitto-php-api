<?php 


	// 
	$q = 'call `v2.0_addComment`("'.$_POST['token'].'","'.$_POST['uid'].'","'.$_POST['lid'].'","'.$_POST['tid'].'","'. sanitize( $_POST['comment'] ) .'","'. sanitize( $_POST['status'] ) .'" );';
	$obj['q'] = $q;
	$obj['results'] = q($q);

	// $obj['post'] = $_POST;
?>

