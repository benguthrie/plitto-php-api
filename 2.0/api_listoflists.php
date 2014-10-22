<?php 
	// This is to show a list of lists for a user, or all of a user's friends including that user.

	$q = "call `v2.0_listoflists`('".
		$_POST["token"]."', '"
		. $_POST['userfilter'] ."','');";
	// 
	$obj['q'] = $q;
	
	// Break out the list of fbuids
	// for($i=0;$i<count($result);$i++){
	// 	// echo $result[$i]['fbuids'];
	// 	$result[$i]['fbuids'] =  explode(',',$result[$i]['fbuids']);
	// }

	// 
	$result = q($q); $obj['result'] = $result;

?>