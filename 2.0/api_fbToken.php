<?php
if(isset($_GET['fbToken'])){
	$fbToken = $_GET['fbToken'];	
} else {
	$fbToken = $_POST['fbToken'];	
}

$obj['fbtoken'] = $fbToken;


$appId = '207184820755';
$secret = 'a3b57227479539ea7812d58d3ebc00fc';

	// The token 
// require 'facebook-php-sdk-v4-4.0-dev/src/facebook/HttpClients/FacebookCurlHttpClient.php';
// require 'facebook-php-sdk-v4-4.0-dev/src/facebook/HttpClients/FacebookHttpable.php';
// require 'facebook-php-sdk-master/src/facebook.php';
// 
require 'Facebook/FacebookSession.php';
require 'Facebook/Entities/AccessToken.php';
require 'Facebook/HttpClients/FacebookHttpable.php';
require 'Facebook/HttpClients/FacebookCurlHttpClient.php';
require 'Facebook/HttpClients/FacebookCurl.php';
require 'Facebook/FacebookSDKException.php';
require 'Facebook/FacebookRequestException.php';
require 'Facebook/FacebookAuthorizationException.php';



require 'Facebook/FacebookRequest.php';
require 'Facebook/FacebookResponse.php';

require 'Facebook/GraphObject.php';
require 'Facebook/GraphUser.php';


use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookSDKException;

FacebookSession::setDefaultApplication($appId,$secret);

$session = new FacebookSession($fbToken);

// Get the GraphUser object for the current user:

try {
  $me = (new FacebookRequest(
    $session, 'GET', '/me'
  ))->execute()-> getResponse();
  // 
  // print_r($me);
  $name = $me->name;

  $friends = (new FacebookRequest(
    $session, 'GET', '/me/Friends'
  ))->execute()->getResponse();
  // print_r($friends);

  // Time to process, login and return a plitto response.
  if(strlen($name) === 0){
  	$obj['error'] = true;
  	$obj['errortxt'] = 'No username';
  } else {
  	$obj['success'] = true;

  	// Prepare the friends
  	$friendsArr = Array();

  	
		for($i=0; $i<count($friends -> data); $i++){

			$friendsArr[] = $friends -> data[$i] -> id;
		}

		$obj['ft'] = $friendsArr;
  	// Prepare the call 
  	// 
  	$q = "call `v2.0_fbLogin`('".$me -> id."', '".$me -> name."','" .$me -> email ."','".implode(',',$friendsArr)."')";	

		$debug = true;

		if($debug===false){
			$obj['q'] =$q;
			// Debug
	  	// $obj['friendsTemp'] = $friends;

	  	// print_r($friends['data']);
	  	// echo 'start';
	  	// $friends = (array) $friends;
	  	// $friends = (object) $friends;
	  	// $friends = (array) $friends;
	  	// print_r($friends -> data);
	  	// print_r($friends ->'data');
	  	// echo 'end';

		} else {
			
			$results = q($q);
			$obj['resultstemp'] = $results;

			// PRINT_R($results[0]);

			$obj['me'] = $results[0];

			$token = $results[0]['token'];

			$q = "call `v2.0_friends`('".$token."')";
			$friendsResult = q($q);
			$obj['friends'] = $friendsResult;

			$qg = "call `v2.0_getSome`('','".$token."','','','ditto');";

			$obj['qg'] = $qg;
		 
			$getSome = q($qg); 
			$obj['getSome'] =  resultsToObject($getSome);
		}
  }
  // echo json_encode($obj);

} catch (FacebookRequestException $e) {
  // The Graph API returned an error
} catch (\Exception $e) { 
  // Some other error occurred
}

?>