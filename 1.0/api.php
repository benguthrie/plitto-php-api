<?php
session_start();
header('Content-type: application/json');

 	require_once('sql.inc');

error_reporting(-1);

// print_r($result);

function getToken(){

 	return md5(uniqid(mt_rand(), true));
}

$navString = $_SERVER['REQUEST_URI']; // Returns "/Mod_rewrite/edit/1/"
// $puid = $_SESSION['puid'];
// $puids = $_SESSION['puids'];



$parts = explode('/', $navString); // Break into an array

// The term after "api" is the API command.
$apiPos = array_search('api',$parts);
$apiCall = $parts[$apiPos + 1];

$obj = Array();

$obj['call'] = $apiCall;
$obj['apipos'] = $apiPos;
// This is for debugging
// $obj[] = Array("note" => "This is for debugging" , "type" => $apiCall);

// Build session Variables it htere is a token.
if(isset($_POST['token'])){
	#TODO This needs to come from the database.


	$_SESSION['puid'] = 1;
	$_SESSION['puids'] = '2,3,4,719,720,724,156';
	$_SESSION['username'] = 'Ben Guthrie';
}



// Error - Break out of things if there is an error.
require_once('fResultsToObjects.php');


switch(strtolower($apiCall)){

case 'fbfriendstest':

$obj = json_decode('{"call":"pFriends","apipos":1,"result":[{"id":"2","name":"Emily Muscarella Guthrie","fbuid":"605592731","things":"1259","shared":"633","dittoable":"626","lists":"143","sharedlists":"104"},{"id":"18","name":"Greg Guthrie","fbuid":"4700900","things":"1093","shared":"514","dittoable":"579","lists":"103","sharedlists":"76"},{"id":"25","name":"Jenny Muscarella Knowles","fbuid":"1489740361","things":"552","shared":"200","dittoable":"352","lists":"52","sharedlists":"34"},{"id":"156","name":"Matt Knowles","fbuid":"1016195417","things":"298","shared":"157","dittoable":"141","lists":"42","sharedlists":"32"},{"id":"14","name":"James Guthrie","fbuid":"4700538","things":"352","shared":"142","dittoable":"210","lists":"25","sharedlists":"21"},{"id":"64","name":"Jeff Bowen","fbuid":"4702676","things":"186","shared":"118","dittoable":"68","lists":"25","sharedlists":"19"},{"id":"132","name":"Julie Guthrie","fbuid":"1009170007","things":"271","shared":"104","dittoable":"167","lists":"29","sharedlists":"21"},{"id":"13","name":"Scott Guthrie","fbuid":"1009170001","things":"284","shared":"95","dittoable":"189","lists":"40","sharedlists":"21"},{"id":"69","name":"Desiree Lieber","fbuid":"1247873543","things":"144","shared":"40","dittoable":"104","lists":"6","sharedlists":"6"},{"id":"724","name":"Amy Kendrick Lee","fbuid":"1032705871","things":"41","shared":"23","dittoable":"18","lists":"8","sharedlists":"8"},{"id":"723","name":"Aimee Aslett Barborka","fbuid":"1463180510","things":"19","shared":"17","dittoable":"2","lists":"5","sharedlists":"5"},{"id":"733","name":"Carri Craver","fbuid":"500443894","things":"55","shared":"17","dittoable":"38","lists":"19","sharedlists":"8"},{"id":"161","name":"Randy Jensen","fbuid":"1661990056","things":"17","shared":"14","dittoable":"3","lists":"8","sharedlists":"7"},{"id":"168","name":"Ben Morrow","fbuid":"44401410","things":"108","shared":"7","dittoable":"101","lists":"2","sharedlists":"2"},{"id":"725","name":"Daniel Miller","fbuid":"10152556380552110","things":"2","shared":"2","dittoable":"0","lists":"2","sharedlists":"2"},{"id":"726","name":"Stephen Brewer","fbuid":"530649084","things":"2","shared":"2","dittoable":"0","lists":"2","sharedlists":"2"},{"id":"734","name":"Chirag Gupta","fbuid":"825550647","things":"5","shared":"1","dittoable":"4","lists":"4","sharedlists":"1"},{"id":"719","name":"BenTest GuthrieTest","fbuid":"1506496999593891","things":"12","shared":"0","dittoable":"12","lists":"3","sharedlists":"0"}],"puids":["2","18","25","156","14","64","132","13","69","724","723","733","161","168","725","726","734","719"]}');


break;

case 'getsome':
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	require_once('api_getSome.php');
break;

case 'getsometest':
	// require_once('api_getSomeTestRich.php');
	require_once('api_getSomeTest.php');
break;

case 'getsometestrich':
	
	require_once('api_getSomeTestRich.php');
break;

case 'thingdetail':
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};

	$q = "call spThingDetail(".$_SESSION['puid'].",'".$_SESSION['puids']."',".$_POST['thingid'].")";
	// $obj['q'] = $q;
	$results = q($q);
	$obj['results'] = resultsToObject($results); ;
break;

case 'search':
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};

	$q="call `spSearch`('".$_SESSION['puid']."', '".$_SESSION['puids']."','".$_POST['search']."');";
	$results = q($q);
	$obj['results'] = $results;
	
break;



case 'testcall':
	$startTime = microtime();


	$q = "call spgetActivity('1','2,3,4,5,6,13,18,15,17,168,131','2014-09-01')";
	// $obj['q'] = $q;
	$results = q($q);


	$obj['q'] = $q;
	$obj['phpTime'] = $endTime - $startTime;

	$obj['results'] = resultsToObject($results); 

	$endTime = microtime();

break;


/* Return a user's activity 
	9/12/2014 - Created 
*/
case 'getactivity':
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	$startTime = microtime();

	// TEST	$_POST = Array('lastActivityDate'=>'2014-09-16', 'user' => 'all');
	
	$obj['lastActivityDate'] = $_POST['lastActivityDate'];

	if($_POST['user'] === 'all' ){

		$user = $_SESSION['puids'];

		if(strlen($user) === 0) {
			$obj['null'] =true;
			// Don't do this without friends
			break ;
		}

	} else {
		$user = $_POST['user'];
	}

	$q = "call spgetActivity('".$_SESSION['puid']."','".$user ."','".$_POST['lastActivityDate']."')";
	// 
	$obj['q'] = $q;
	
	$results = q($q);

	// print_r($results);

	// 9/16/2014
	$obj['results'] =  resultsToObject($results);

	$endTime = microtime();

	$obj['phpTime'] = $endTime - $startTime;

break;




// 9/3/2014 - List of Lists, and context.
case 'listoflists':
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	// Filter by a user id if it's there.
//TODO0 
	// $_POST['users'] = 'all';

	if($_POST['users'] === 'all'){
		$users = $_SESSION['puids'];
	}else{
		// We expect the request to come as a string
		$users = $_POST['users'];
	}

	$q = "call spListOfLists('".
		$_SESSION["puid"]."', '"
		. $users ."','"
		."''"
	."');";
	// 
	$obj['q'] = $q;
	$result = q($q);
	// Break out the list of fbuids
	for($i=0;$i<count($result);$i++){
		// echo $result[$i]['fbuids'];
		$result[$i]['fbuids'] =  explode(',',$result[$i]['fbuids']);
	}

	$obj['result'] = $result;


break;	

// 8.29.2014 - Convert facebook friends into plitto friends
case 'pfriends':
	// returns a list of plitto friends, with things in common, etc.
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	
	// Use PHP to turn each friendID into part of the string
	$friends = Array();
	for($i=0; $i<count($_POST['fbFriends']); $i++){

		$friends[] = $_POST['fbFriends'][$i]['id'];
	}

	// Now, make that into a big string.
	$friendString = implode(',',$friends);

	// $q = "call spPlittoFriendsFromFb('".$friendString."');";
	$q = "call spFriendsFB('".$_SESSION['puid']."','".$friendString."')";

	$obj['q'] = $q;

	$friends = q($q);
	
	$result = $friends;

	$obj['result'] = $result;

	$puids = Array();
	foreach($result as &$value){
		$puids[] = $value['id'];
	}

	$obj['puids'] = $puids;

	// 
	$_SESSION['puids'] = implode(',',$puids);

break;



// 8/21/2014 - Lists - Even Better.
// 8/29/2014 - Updated to include their actual facebook friends.
case 'l':
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
$q = "call spListsB('".
	$_SESSION["puid"]."', '".$_SESSION["puids"]."','"
	."all"."','"
	."none"."',"
	//.$_POST["firstKey"]."','"
	// .$_POST["lastKey"]."','"
	."'0','0','both'"
	//.$_POST["direction"]."
	.");";
// 
$obj['q'] = $q;

$q2 = "call spFriends(" . $_SESSION['puid'] . ",'" . $_SESSION["puids"] . "');";
$q3 = "CALL `plitto2014`.`spListOfLists`(".$_SESSION['puid'].", '".$_SESSION['puids']."', null)";

$q4 = "call spDittosUser(".$_SESSION['puid'].",'" . $_SESSION['puids']."')";

	
	$debug = 0;
	if($debug === 0){
		$res = q($q);
		// Format the results.
		$dataP = resultsToObject($res);

		$res2 = q($q2);

		$obj['data'] = $dataP;
		
		$obj['friends'] = $res2;
		$obj['lists'] = q($q3);

		$dittosResults = qa($q4);
		$obj['dittosIn'] = $dittosResults[1];
		$obj['dittosOut'] = $dittosResults[0];

	} else {
		$obj['q'] = $q;
		$obj['q2'] = $q2;
		$obj['q3'] = $q3;

	}
break;

case 'fblogin':
	// this function expects the user id, email and name from Facebook.;
	// $obj['params'] = $_POST;

	$q = "call `spFbLogin` ('".$_POST['id']."', '".$_POST['name']."','" .$_POST['email']."')";
	// $obj['q'] = $q;
	// 


 	$obj['q'] = $q;
	$results = q($q); 	$obj['results'] = $results[0];

 	// Create the session variables based on this.
 	$_SESSION['fbuid'] = $results[0]['fbuid'];
 	$_SESSION['puid'] = $results[0]['puid'];
 	$_SESSION['username'] = $results[0]['username'];
 	$_SESSION['puids'] = ''; // If they don't have any friends, we need to have that accounted for here.

 	$obj['token'] = getToken();

 	$obj['session'] = $_SESSION;

break;


case 'testlogout':
	$_SESSION['puid'] = null;
	setcookie("puid",null );
	$obj['puid'] = null;
	$obj['success'] = true;
break;

// 8/12/2014 First Load - Pull everything locally in one initial call

case "load":
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	// Lists & stats - This loads three result sets into one response. 
		// These will need to build into the stores.

	$q = "call spLists('".$_SESSION['puid']."', '". $_SESSION['testpuids']."', 'all');";
	


	$obj['q'] =  $q;
	$res = qa($q);

	$obj['lists'] = $res[0];
	$obj['things'] = $res[1];
	$obj['users'] = $res[2];
	// My Friends & stats

	// ListLinks from Friends & Me & Dittoable & shared

	// All the Things from those listlinks.

break;

// 8/13/2014 - Lists - Better.
case 'lo':
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	// todo - Send last key
	/*forUserId: forUserId,
            forListIds: forListIds,
            filters: filters,
            startAfter: startAfter*/
    // Temp
    
   $_POST['forLists'] = 'all';
    $_POST['filters'] = 'incommon,shared,ditto';
    $_POST['firstKey'] = null;
    $_POST['lastKey'] = null;
    $_POST['direction'] = 'both';

    /*
    CREATE DEFINER=`root`@`localhost` PROCEDURE `spLists`(userId INT, forUserIDs TEXT, 
	forLists TEXT , filters VARCHAR(255) ,
	firstKey INT(11), lastKey INT(11), direction VARCHAR(255)
)
	*/

	$q = "call spLists('".
		$_SESSION['puid']."', '"
		.$_POST['forUserId']."','"
		.$_POST['forLists']."','"
		.$_POST['filters']."','"
		.$_POST['firstKey']."','"
		.$_POST['lastKey']."','"
		.$_POST['direction']."');";
	// 
	$res = qa($q);

	$q = "call spFriends(" . $_SESSION['puid'] . ",'" . $_SESSION['puids'] . "');";
	// 
	$res2 = qa($q);

	$obj['lists'] = $res[0];
	$obj['things'] = $res[1];
	// $obj['users'] = $res[2];
	$obj['friends'] = $res2[0];
	// $obj['q2'] = $q;
break;



case "thingid":

//TODO0 $_POST['thingName']='Joseph';
	$q = 'call spthingId("'.sanitize($_POST['thingName']).'");';
	$obj['q'] = $q;
	$obj['results'] = q($q);
break;


// 8/22/2014
case 'getmore':
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	/*
	*/
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
	$string = implode(",",$filters);

	if($_POST['type'] === 'user'){
		$userFilter = $_POST['id'];
	} else {
		$userFilter =  $_SESSION['puids'];
	}

	 // $q = "call spGetMore('".$_POST["type"]."','".$_SESSION['puid']. "','".$_POST['id']."','".implode($lists,",")."','".implode($things,",")."');";
	$q = "call spGetMore(". $_SESSION['puid'] .",'". $userFilter ."','". $_POST['type'] 
		."','". $_POST['id']."','". $string ."')";

	// $q = "call spGetMore(1,'2','user','2',');";
	// call spGetMore(719,'','list','2067','')}

 	$obj['q'] = $q;

	// 
 	 // $q= sanitize($q);
 	 // 
 	 // $data = q($q);
 	// Debugging

 	$debug = 0;
 	if($debug === 0){
	 	 	$obj['type'] = $_POST['type'];
	 	
		$obj['id'] = $_POST['id'];
		// Prod
	 	 $res = q($q);
	 	 // NEED 
	 	 // $obj['res'] = $res;

	 	// echo 'result count: '. count($res);

		// Format the results.
		// 
	 	if(count($res) > 0){
	 		$dataP = resultsToObject($res);
	 		$obj['results'] =$dataP;
	 	} else {
	 		$obj['results'] = array();
	 	}
	 } else {
	 	$obj['q'] = $q;
	 }
	// 
 	 // 
 	 

// Inputs: {"call":"getMore","apipos":2,"type":"user","id":"37","existing":[{"lid":"7076","tid":"7478"},{"lid":"7076","tid":"7477"},{"lid":"2830","tid":"4493"},{"lid":"2830","tid":"4492"},{"lid":"2830","tid":"2834"},{"lid":"2830","tid":"3765"},{"lid":"231","tid":"7254"},{"lid":"2250","tid":"7285"},{"lid":"2250","tid":"6083"},{"lid":"2250","tid":"4998"}]}
	



break;

case "listsearch":
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	// TODO take the User Context, and apply it here. It should be "public", "my contacts","us","specific user", etc.
	$q = 'call spListSearch('.$_SESSION['puid'].',"'.$_POST['searchTerm'].'","'.$_SESSION['puid'].','.$_SESSION['puids'].'");';
	$obj['q'] = $q;
	$obj['results'] = q($q);
break;


// Ditto something 8/19/2014
case "ditto":
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	$obj['temp'] = 'Made the Ditto call';
	// $q = 'call ditto("'.$_POST['action'].'","'.$_POST['userid'].'","'.$_POST['sourceUserID'].'","'
	// 		.$_POST['listnameid'].'","'.$_POST['thingid'].'","'.$_SESSION['testpuids'].'");';
	
	$q = "call spDitto('".$_SESSION['puid']
		."','". $_POST['fromuserid']
		."','". $_POST['thingid']
		."','". $_POST['listid']
		."','". $_POST['action']
		."','". $_SESSION['puids']
		."');";

	$obj['q'] = $q;
	
	$obj['results'] = q($q);
	/**/
break;




// Lists, with a context.
case "lists":
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	// DEBUG
	// print_r($_POST);

	// Require a logged in user.
	if( empty($_SESSION['puid'])  ){

	//	isset($_SESSION['puid']) = true and is_int($_SESSION['puid']) = 0 and isset($_SESSION['puids']) = true  ){
		$obj['error'] = true;
		$obj['errortxt'] = "You are not logged in.";
		$error = 1;
		break 1;
	} 


	// The required context here is an array of users, with their login types (plitto or fb), and the array of lists to use, list type is optional.



	// Let's switch the test to actual plitto UserIDs from the session variable.
	if(isset($_POST['userq']) == false or count($_POST['userq']) == 0){
		if(isset($_SESSION['puids']) == true){
			$_POST['userq'] = explode(",", $_SESSION['puids']);
		}else{
			$_POST['userq'] = array(9,168,13,16);	
		}
	} else {
		$_POST['userq'] = array(9,168,13,16);
	}



	// DEfault to a null list of lists
	// if(count($_POST['listids']) == 0 or isset($_POST['listids']) == false){
	if(isset($_POST['listids']) == false or count($_POST['listids']) == 0){
		$_POST['listids'] = array();
	}

	// Default to a null array of filters
	if(isset($_POST['filters']) == false or count($_POST['filters']) == 0){
		$_POST['filters'] = array();
	}

	// Default to a null array of order
	if(isset($_POST['order']) == false or count($_POST['order']) == 0){
		$_POST['order'] = array();
	}

	// Default to a null array of listtype
	if(isset($_POST['listtype']) == false or count($_POST['listtype']) == 0){
		$_POST['listtype'] = array();
	}

		// Default to a null array of listtype
	if(isset($_POST['listtype']) == false or count($_POST['listtype']) == 0){
		$_POST['usertype'] = "p";
	}
/*	
echo 'POST: ';
print_r($_POST);
*/
	// q('call log("Passed User count","'.count($_POST['userq']).'"');
	// q('call log("Passed User ","'. implode(',', $_POST['userq']) .'"');

	// Prepare the inputs
	$q = 'call lists("' 
		. $_SESSION['puid']  . 							// 1 Logged In User (integer)
		'","' . implode(',', $_POST['userq']) . '"'. 	// 2 Array of IDs to parse (optional input. Defaults to friends graph in the PHP #TODO)
		',"' . $_POST['usertype'] . '"'. 				// 3 type of user IDs (Facebook or Plitto, single character. Defaults to p)
		',"' . implode(",",$_POST['listids']) . '"'.	// 4 ParentList Name IDs (optional array)
		',"' . implode(",",$_POST['listtype']) . '"'.	// 5 list type (optional array)
		',"' . implode(",",$_POST['filters']) . '"'.	// 6 filters
		',"' . implode(",",$_POST['order']) . '"'.		// 7 order - The position of the records? 
		');';
	$temp = q($q);
	// print_r($_POST);

	// Time to build the list all pretty-like, so we're not repeating each listname and ID over and over and making the clients do all the work.
	$listNameID = "";
	$userOwnerId = "";

	$list = array();
	$lists = array();

	$tc =  count($temp)-1;
	for($i = 0; $i < $tc; $i++){
		// Change the lsit if something changed.
		if($listNameID != $temp[$i]['listnameid'] or $userOwnerId != $temp[$i]['userOwnerId']){
			// Push any completed list into the object, and move on tp the next list.
			if(count($list)> 0){
				$lists[] = $list;
			}

			// Update the constants
			$listNameID = $temp[$i]['listnameid'];
			$userOwnerId = $temp[$i]['userOwnerId'];

			$list = array(
					"listnameid"=>$temp[$i]['listnameid'],
					"listname"=>$temp[$i]['listname'],
					"userOwnerId"=>$temp[$i]['userOwnerId'], 
					"userOwnerName" => $temp[$i]['userOwnerName'], 
					"listOwnerFB"=>$temp[$i]['listOwnerFB']
				);
		}

		// Loop until the list changes. Same row as above, skipping above if it isn't a new list.

			do{
				$list["items"][] = array("thingid"=>$temp[$i]['thingid'],"thingname"=>
					$temp[$i]['thingname'], "modified"=>$temp[$i]['modified'],
					"myListItemStatus"=>$temp[$i]['myListItemStatus']);

				$i++;
			} while ($temp[$i]['listnameid'] == $listNameID 
				and $temp[$i]['userOwnerId'] == $userOwnerId and $i < $tc);
			

			
	}
	$obj['lists'] = $lists;
	
break;

// Add an item to a list
case 'addtolist':
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};

	// $thingname = cubrid_real_escape_string($_POST['thingName']);
	$thingName = sanitize($_POST['thingName']);
	// $thingName = $mysqli-> real_escape_string($_POST['thingName']);
	$q="call spAddToList('". $thingName ."','".
		$_POST['listnameid']."','".$_SESSION['puid']."');";
	$obj['q'] = $q;
	$obj['results'] = q($q);
	


break;

/* Removed. Not prod ready 
	// Take an input, and turn it into this user's friends.
	case "pgraph":
		// TEST:
		$_POST = array(
			"type"=>"facebook", // Facebook
			"sourceIds"=> array("4700538","4700900","4702676","44401410","605592731","1009170001","1009170007","1016195417","1247873543","1489740361","1661990056")
			);

		// print_r($_POST);

		$q = 'call pGraph("'.$_SESSION['puid'].'","' . $_POST['type'] . '","'. implode(",",$_POST['sourceIds']).'")';

		// echo $q;
		$obj = q($q);
		$puids = array();
		// Turn the results into a session variable.
		foreach($obj as &$value){
			$puids[] = $value['UserID'];

		}

		// $obj['puids'] = $puids; - $puids is now the array of Plitto User IDs for this user.
		$_SESSION['puids'] = $puids;

		// print_r($_SESSION['puids']);
		
	break;
*/

case "modallist":
	// todo - replace the testpuids
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};

	$q = 'call spList("'.$_SESSION['puid'].'","'.$_POST['listnameid'].'","'.$_SESSION['testpuids'].'","'.$_POST['listname'].'");';
	$obj['q'] = $q;
	$temp = q($q);
	// print_r($_POST);

	// Time to build the list all pretty-like, so we're not repeating each listname and ID over and over and making the clients do all the work.
	$listNameID = "";
	$userOwnerId = "";

	$list = array();
	$lists = array();

	$tc =  count($temp)-1;
	for($i = 0; $i < $tc; $i++){
		// Change the lsit if something changed.
		
		if($listNameID != $temp[$i]['listnameid'] or $userOwnerId != $temp[$i]['userOwnerId']){
			// Push any completed list into the object, and move on tp the next list.
			if(count($list)> 0){
				$lists[] = $list;
			}

			// Update the constants
			$listNameID = $temp[$i]['listnameid'];
			$userOwnerId = $temp[$i]['userOwnerId'];

			$list = array(
					"listnameid"=>$temp[$i]['listnameid'],"listname"=>$temp[$i]['listname'],
					"userOwnerId"=>$temp[$i]['userOwnerId'], "userOwnerName" => $temp[$i]['userOwnerName'], 
					"listOwnerFB"=>$temp[$i]['listOwnerFB'],"active"=> true
				);
		}

		// Loop until the list changes. Same row as above, skipping above if it isn't a new list.

			do{
				$list["items"][] = array("thingid"=>$temp[$i]['thingid'],
					"thingname"=>$temp[$i]['thingname'], "modified"=>$temp[$i]['modified'],
					"myListItemStatus"=>$temp[$i]['myListItemStatus'], "active"=> true);

				$i++;
			} while ($temp[$i]['listnameid'] == $listNameID and $temp[$i]['userOwnerId'] == $userOwnerId and $i < $tc);
			

			
	}
	$obj['results'] = $lists;




break;
/*
modalList: function(listnameid, listname){
        // This function should load everything about this list for this user's eyes.

        var modalListParams = $.param({listnameid: listnameid, listname: listname});

         $http({method:'POST',url:'api/modalList', 
*/


case "friends":
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	// Inputs will be an array of friend ids, the user ID will be part of the session.
	// Limits and order by will come later TODO
	// 
	$q = "call spFriends ('".$_SESSION['puid']."','".$_POST['type']."','".$_POST['ids']."')";
	// $obj['q'] = $q;
	// $obj['data'] = $_POST;
	$obj['results'] = q($q);
break;

case "logout":
	$obj['success'] = true;
	$obj['txt'] = 'Logged Out';
	// Delete the cookie
	if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
	    }
	}
	// Delete the session.
	session_destroy();
break;

// August 28th, 2014
case "dittos":
	if(!isset($_SESSION['puid'])){ $obj['error'] = true; $obj['errortxt']="You are not logged in."; break 1;};
	$_POST['friends'] = '2,3,4,5,6,7,8,9,10';
	$q = "call spDittosUser('".$_SESSION['puid']."','". $_POST['friends']."')";
	$result = qa($q);
	
	$obj['dittosIn'] = $result[0];
	$obj['dittosOut'] = $result[1];

break;

default:
	$obj['error'] = true;
	$obj['errortxt'] = "unknown request";
break;

}



// This is the output of the API.
// 
echo json_encode($obj);

$required = array("fbID","Othervar","shouldbemissing");

function requirePost($req,$post){
	// Pull out just the actual keys, removing the values. This is required for comparison.
	$act = array();
	foreach($post as $key => $val){
		$act[] = $key;
	}

	// echo "Required: "; print_r($req); echo "Actual: "; print_r($post);

	$diff = array_diff($req,$act);

	// echo "Difference: "; print_r($diff);

	// If the array is empty, then we're cool.
	// Pull out the missing key names;
	// Handle the error here, so it's just once.

	if(count($diff)>0){
		return "These inputs were missing: ". implode(",",$diff);
	} else {
		return null;

	}
}



?>