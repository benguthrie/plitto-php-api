<?php
	/* This gets more content for lists or users when you open them. */

$obj['thetoken'] = $_POST['token'];

	$filters = array();

// echo 'Existing: ' .$_POST['existing'];
	if(isset($_POST['existing']) === false or $_POST['existing'] === null ){
		$existing = 0;
	} else {
		$existing = $_POST['existing'];
		// echo 'count Existing: '.count($existing);
		$maxCount = count($existing);
		if($maxCount > 200){
			$maxCount = 200;
		}
		for($i = 0; $i< $maxCount; $i++){
			// 
			$item = '(' . str_replace('|',',',$existing[$i]['ult']) .')';
			if(strpos($item,"undefined") === false){
				$filters[] = $item;
			}
			//
		//	$things[] = $existing[$i]['tid'];
			// 
			// $lists[] = $existing[$i]['lid'];
			//print_r();
		}
	}
	// The filter string is now built for user, list, thing
	$stringFilter = implode(",",$filters);

	if($_POST['type'] === 'user'){
		$userFilter = $_POST['id'];
	} else {
		// Default to my friends. The Token will handle this.
		$userFilter =  '';
	}

	 // $q = "call spGetMore('".$_POST["type"]."','".$_SESSION['puid']. "','".$_POST['id']."','".implode($lists,",")."','".implode($things,",")."');";
	$q = "call `v2.0_GetMore`('".$_POST['token']."','". $userFilter ."','". $_POST['type'] 
		."','". $_POST['id']."','". $stringFilter ."')";

	// $q = "call spGetMore(1,'2','user','2',');";
	// call spGetMore(719,'','list','2067','')}

 	$obj['q'] = $q;

	
	/* 	 
 	$q= sanitize($q);

	*/ 
 	 // 
 	 // 

 	$data = q($q);
 	// Debugging
 	// $obj['data'] = $data;

 	// 
 	$obj['results'] = resultsToObject($data);
 	// $obj['results'] = $data;
 	$obj['resultSize'] = count($data);

/*
 	$debug = 0;
 	if($debug === 0){
 	 	// $obj['type'] = $_POST['type'];
	 	
		// $obj['id'] = $_POST['id'];
		// Prod
	 	 // $res = q($q);
	 	 // NEED 
	 	 // $obj['res'] = $res;

	 	// echo 'result count: '. count($res);

		// Format the results.
		// 
	 	if(count($data) > 0){
	 		$dataP = resultsToObject($data);
	 		$obj['results'] =$dataP;
	 	} else {
	 		$obj['results'] = array();
	 	}
	 } else {
	 	$obj['q'] = $q;
	 }
*/  	 

// Inputs: {"call":"getMore","apipos":2,"type":"user","id":"37","existing":[{"lid":"7076","tid":"7478"},{"lid":"7076","tid":"7477"},{"lid":"2830","tid":"4493"},{"lid":"2830","tid":"4492"},{"lid":"2830","tid":"2834"},{"lid":"2830","tid":"3765"},{"lid":"231","tid":"7254"},{"lid":"2250","tid":"7285"},{"lid":"2250","tid":"6083"},{"lid":"2250","tid":"4998"}]}
	

?>