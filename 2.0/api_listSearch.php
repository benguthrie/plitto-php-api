<?php 
	$q = 'call `v2.0_listSearch`("'.$_POST['token'].'","'.$_POST['searchTerm'].'");';
	// $obj['q'] = $q;
	// 
	// $obj['results'] = q($q);


	$results = q($q);

	$sqlErrorCheck = tokenCheck($results);

	if($sqlErrorCheck['error'] === true){
		$obj =  $sqlErrorCheck;
	} else {
		// Debugging
	 	
	 	$obj['results'] = $results;
	 	// $obj['results'] = $data;
	 	
	}

?>