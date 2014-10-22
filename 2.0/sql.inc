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


 function d($q){

	$conn = connDb();
	$mysqli = new mysqli($conn['dba'],$conn['user'],$conn['pwd'],$conn['db']);
	 	/* check connection */
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
}

// For a single result set.
 function q($q){
 	// SQL info
 // If it's at plitto.com


	$conn = connDb();
	$mysqli = new mysqli($conn['dba'],$conn['user'],$conn['pwd'],$conn['db']);
	 	/* check connection */
		if (mysqli_connect_errno()) {
		    echo "Connect failed: %s\n" & mysqli_connect_error();
		    exit();
		} else {
			// $q = 'select count(*) from tlist';			$q = "call `spFbLogin` ('532345366', 'Ben Guthrie','ben@bemily.com')";

			$result = mysqli_query($mysqli,$q);
	
			// echo $q;
			$obj = Array();

			while ($row = $result->fetch_assoc()) {
			    // do what you need.
			    // $obj[$a] -> $row;
				$obj[] = $row;    
			}	

			mysqli_free_result($result);
			return $obj;

			mysqli_close($mysqli);
		}

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