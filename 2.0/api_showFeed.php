<?php
	/* This gets more content for lists or users when you open them. */
$debug = false;

$obj['thetoken'] = $_POST['token'];

// Prep the variables
if(!isset($_POST['newerOrOlder'])){
	$newerOrOlder = '';
}
else {
	$newerOrOlder = $_POST['newerOrOlder'];
}

$q = "call `v2.0_feed`('"
	.$_POST['token'] . "'"
	.",'".$_POST['theType'] ."'"
	.",'".$_POST['userFilter'] ."'"
	.",'".$_POST['listFilter'] ."'"
	.",'".$_POST['myState'] ."'"
	.",'".$_POST['continueKey']."'"
	.",'".$newerOrOlder."'"
	.");";

$obj['q'] = $q;

// 
$results = q($q);
// $obj['results'] = $results;

// $obj['debug'] = $results;
	
   if($debug == true){
		$obj['q'] = $q;	
		// $obj['results'] = $results;
		print_r($results);

		foreach($results as $row){
			echo "row" . $row['id']."
			";
		}

	} else {
        // TODO1 - Copy this block to all places where resultsToObject come from.
        if(isset($results[0]['error'])){
            $obj['error'] = true;
            $obj['errorTxt'] = $results[0]['errortxt'];

            if($obj['errorTxt'] === "Invalid token"){
                $obj['logout'] = true;
            }

        } else {

            $obj['results'] = resultsToObject($results);
        }

		// $obj['results'] = q($q);

	}
   
     /*
Array
(
    [0] => Array
        (
            [id] => 166310
            [uid] => 2
            [username] => Emily Muscarella Guthrie
            [fbuid] => 605592731
            [lid] => 66
            [listname] => Bands I have seen live
            [tid] => 646
            [thingname] => The Black Crowes
            [added] => 2014-10-24 09:11:20
            [state] => 1
            [dittokey] => 1832
            [dittouser] => 
            [dittousername] => 
            [dittofbuid] => 
            [mykey] => 
        )

    [1] => Array
        (
            [id] => 166309
            [uid] => 2
            [username] => Emily Muscarella Guthrie
            [fbuid] => 605592731
            [lid] => 8986
            [listname] => aweeee
            [tid] => 8988
            [thingname] => dung
            [added] => 2014-10-23 22:15:00
            [state] => 1
            [dittokey] => 0
            [dittouser] => 
            [dittousername] => 
            [dittofbuid] => 
            [mykey] => 
        )
)
			
{"call":"showFeed","apipos":1,"thetoken":"75df7700d30cd7dfe84fa961d9c81e11","q":"call `v2.0_feed`('75df7700d30cd7dfe84fa961d9c81e11',  'friends', '', '', '','');"}*/
?>