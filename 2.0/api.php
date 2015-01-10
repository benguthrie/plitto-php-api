<?php
session_start();
header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");

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
$apiCall = $parts[$apiPos + 2];

$obj = Array();

$obj['call'] = $apiCall;
$obj['apipos'] = $apiPos;
// This is for debuggingF
// $obj[] = Array("note" => "This is for debugging" , "type" => $apiCall);

// Build session Variables it htere is a token.
/*
	if(isset($_POST['token'])){
		#TODO This needs to come from the database.
		$_SESSION['puid'] = 1;
		$_SESSION['puids'] = '2,3,4,719,720,724,156';
		$_SESSION['username'] = 'Ben Guthrie';
	}
*/

// Error - Break out of things if there is an error.

// These are the data models.
	// User / List / Thing cascade
	require_once('fResultsToObjects.php');

function tokenError(){
	// $error = new stdClass();
	$error = Array();
	$error['error'] = true;
	$error['errortxt'] = "missing token";
	return $error;
}	


switch(strtolower($apiCall)){
/* Launch every time a user logs in.

Updated 12.20. */



case "checktoken":
	require_once("api_checkToken.php");

break;

/* 10/31/2014 */
case 'fbtoken':
	require_once('api_fbToken.php');
break;

case "addcomment":
	require_once("api_addComment.php");
break;

/* 10/19/2014 - Updated to include everything needed for the initial login. 
case 'fblogin':
	// No token required. This is the login bit.
	require_once('api_fblogin.php');
break;
*/

/* 10/20/2014 */

// Populate the thing modal.
case 'test':
	$_POST['token'] = '2adba3539cb1aaa9d48ed26d36b27906';
	$_POST['type'] = "";
	$_POST['userFilter'] = "";
	$_POST['listFilter'] = "";
	require_once('api_getSome.php');
break;


/* 12.18.2014 */

case 'updatecounts':
	if(isset($_POST['token'])){
		require_once('api_updateCounts.php');
	} else {
		$obj = tokenError();
	}
break;

/* 12.17.2014 */

case 'makenotificationread':
	if(isset($_POST['token'])){
		require_once('api_makeNotificationRead.php');
	} else {
		$obj = tokenError();
	}

break;

/* 12.16.2014 */
case 'loadnotifications':
	if(isset($_POST['token'])){
		require_once('api_loadNotifications.php');
	} else {
		$obj = tokenError();
	}

break;

case 'loadlist':
	if(isset($_POST['token'])){
		require_once('api_loadList.php');
	} else {
		$obj = tokenError();
	}

break;

case 'showfeed':
	
	if(isset($_POST['token'])){
		require_once('api_showFeed.php');
	} else {
		$obj = tokenError();
	}

break;

case "listsearch":
	if(isset($_POST['token'])){
		require_once('api_listSearch.php');
	} else {
		$obj = tokenError();
	}
break;




// Ditto something 8/19/2014
case 'ditto':
	if(isset($_POST['token'])){
		require_once('api_ditto.php');
	} else {
		$obj = tokenError();
	}	
break;


// 9/3/2014 - List of Lists, and context.
case 'listoflists':

	if(isset($_POST['token'])){
		require_once('api_listoflists.php');
	} else {
		$obj = tokenError();
	}

break;	



/* 10/20/2014 */

// Populate the thing modal.
case 'thingdetail':
	
	if(isset($_POST['token'])){
		require_once('api_thingDetail.php');
	} else {
		$obj = tokenError();
	}

break;


// Add an item to a list
case 'addtolist':

	$obj['token'] = $_POST['token'];

	if(isset($_POST['token'])){
		require_once('api_addtolist.php');
	} else {
		$obj = tokenError();
	}

break;

/* 10/19.2014 */

// 8/22/2014
case 'getmore':
	if(isset($_POST['token'])){
		require_once('api_getMore.php');
	} else {
		$obj = tokenError();
	}
break;

/* 10/19/2014 - Updated to be token-ed */
case 'search':
	if(isset($_POST['token'])){
		require_once('api_search.php');
	} else {
		$obj = tokenError();
	}
break;


case 'getsome':
	
	if( isset($_POST['token'])  ){
		require_once('api_getSome.php');
	} else {
		$obj = tokenError();
	}
break;

case "thingid":

//TODO0 $_POST['thingName']='Joseph';
	$q = 'call spthingId("'.sanitize($_POST['thingName']).'");';
	$obj['q'] = $q;
	$obj['results'] = q($q);
break;

default:
	$obj['error'] = true;
	$obj['errortxt'] = "unknown request";
break;

}

// This is the output of the API.
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