<?php 
function connDb(){

	if($_SERVER['SERVER_NAME'] === 'localhost'){
		$user = 'root';
		$password = '';
		$dba = "localhost";
		$db = "plitto2014";

	}else {
		$user = 'procuser';
		$password = '508deda0-9757-4c53-bcea';
		$dba = "mysql.plitto.com";
		$db = "plitto2014";	
	}
	/*
	*/
	return Array('user'=>$user,'pwd'=>$password,'dba'=>$dba,'db'=>$db);

}

/*
 function d($q){

	$conn = connDb();
	$mysqli = new mysqli($conn['dba'],$conn['user'],$conn['pwd'],$conn['db']);
	 	// check connection 
	 	if (mysqli_connect_errno()) {
		    echo "Connect failed: %s\n" & mysqli_connect_error();
		    exit();
		}

		// echo $q;
		try {
			$result = mysqli_query($mysqli,$q);
			print_r($result);
		} catch (Exception $e){
			print_r($e);
		}
		mysqli_free_result($result);


		print_r(mysqli_error($mysqli));
		
		mysqli_close($mysqli);



		// return $obj;
} */

// For a single result set.
 function q($q){
 	// SQL info
 // If it's at plitto.com


	$conn = connDb();
	$mysqli = new mysqli($conn['dba'],$conn['user'],$conn['pwd'],$conn['db']);
	 	/* check connection */
		if (mysqli_connect_errno()) {
		    // die(sprintf("[%d] %s\n", mysqli_connect_errno(), mysqli_connect_error()));
		    $obj = Array();
		 	$obj[] = Array('error'=> true, 'errortxt'=> $mysqli->mysqli_connect_error(), 'mysqlerrornum'=>$mysqli->mysqli_connect_errno());
		    exit();
		} 

		if (!($res = $mysqli->query($q))) {
		 // printf("[%d] %s\n", $mysqli->errno, $mysqli->error);
		 	$obj = Array();
		 	$obj[] = Array('error'=> true, 'errortxt'=> $mysqli->error, 'mysqlerrornum'=>$mysqli->errno);
		 	return $obj;
		} else {
		 	$obj = Array();
 
		 	// 

		 	// echo $q;

		 	// 
			$debug = false;

			if($debug === true){

		 	$result = mysqli_query($mysqli,$q);
		 	//echo('<h1>result</h1>');
			//print_r($result);
			//echo('<h1>res</h1>');
			// print_r($res);

		 	}

		 	if(is_object($res)){
				while ( $row = $res->fetch_assoc() ) {
				    // do what you need. 
				    // $obj[$a] -> $row;
				    // echo("Row:");
				    // print_r($row);
					// $obj.push( $row );     
					// array_push($obj,$row);
					$obj[] = $row;
				}	
			}else{
				$obj[] = Array( 'error'=>true, 'errortxt'=>'No rows returned', 'res'=> $res );
			} 

			// $obj[] = Array('error'=>true, 'errortxt'=>'No rows returned', 'res'=> $res);
			
			
			// $obj['q'] = $q;


			return $obj;
		}

		

		$mysqli -> close(); 
 
		// echo $q;		
}

function sanitize($q){

	$conn = connDb();
	$mysqli = new mysqli($conn['dba'],$conn['user'],$conn['pwd'],$conn['db']);

	if (mysqli_connect_errno()) {
    	printf("Connect failed: %s\n", mysqli_connect_error());
    	exit();
	}

	$sanitized = $mysqli -> real_escape_string($q);

	$mysqli -> close();
	
	return $sanitized;
}

// For Multiple Results Sets.

function qa($q){

	$conn = connDb();
	$mysqli = new mysqli($conn['dba'],$conn['user'],$conn['pwd'],$conn['db']);

if ($mysqli->multi_query($q)) {
    do {
        /* store first result set */
        if ($result = $mysqli->store_result()) {
            while ($row = $result->fetch_assoc()) {
                // printf("%s\n", $row[0]);
                $b[] = $row;
            }
            $result->free();
        }
        /* print divider */
        if ($mysqli->more_results()) {
        	$obj[] = $b;
        	$b = Array();
            // printf("-----------------\n");
        }
    } while ($mysqli->more_results() && $mysqli -> next_result());
}

/* close connection */
$mysqli->close();

	return $obj;
}


?>