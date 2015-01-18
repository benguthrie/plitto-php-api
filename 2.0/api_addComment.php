<?php 
	/* This function allows a user to add a comment onto a specific user --> list-->thing */

	// 
	$q = 'call `v2.0_addComment`("'.$_POST['token'].'","'.$_POST['uid'].'","'.$_POST['lid'].'","'.$_POST['tid'].'","'.$_POST['itemKey'].'","'. sanitize( $_POST['comment'] ) .'","'. sanitize( $_POST['status'] ) .'" );';
	$obj['q'] = $q;
	$results = q($q);
	
	$sqlErrorCheck = tokenCheck($results);

	if($sqlErrorCheck['error'] === true){
		$obj =  $sqlErrorCheck;
	} else {
		$obj['results'] = $results;
	}
?>

