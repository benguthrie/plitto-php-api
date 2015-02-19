<?php
/* This function takes a short term Facebook Session (web) and logs into Plitto using it. */

if(isset($_GET['fbToken'] ) ){
  $fbToken = $_GET['fbToken'];	
} else if ( isset( $_POST['fbToken'] ) ){
  $fbToken = $_POST['fbToken'];	
} else {
  $fbToken = '';
}
// TODO1 - Track this token in the Plitto database.
$obj['fbtoken'] = $fbToken;

$appId = '207184820755';
$secret = 'a3b57227479539ea7812d58d3ebc00fc';

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

// print_r($session);

// Get the GraphUser object for the current user:

$debug = false;
$error = false;

// TODO1 - This is not running locally.
try {
  $me = (new FacebookRequest(
    $session, 'GET', '/me'
  ))->execute()->getResponse();

  if ($debug === true ) {
    $obj['debug'] = true;
    $obj['meDebug'] = $me;
  }

	
  $name = $me->name;
if(strlen($name) === 0){
    $error = true;    
    $obj['error'] = true;
    $obj['errortxt'] = 'No username';
  } else {
    $obj['success'] = true;
  }

  /* Begin getting Facebook Friend Ids  */
  if($error === false){
   // Create a loop for getting ALL this user's friends who are on Plitto.
    // Declare the number of friends to pull per request.
    $pagingLimit = 50;

    // Create an array to hold their friends.
    $friendsArr = Array();

    // Get their first $pagingLimit friends.
    $friends = (new FacebookRequest(
      $session, 'GET', '/'. $me-> id . '/friends?limit='.$pagingLimit
    ))->execute()->getResponse();

    // Get a count of the number of friends returned.
    $friendsCt = count($friends -> data);

    $friendsArr = $friends -> data;

    // For research, how many friends does this person have?
    $obj['totalFriends'] = $friends -> summary -> total_count;

    // Loop Count set
    $loopCount = 1;

    while( ($pagingLimit === $friendsCt) ){
      // Overwrite the existing response.
      $friends = (new FacebookRequest(
        $session, 'GET', '/me/friends?limit='.$pagingLimit .'&offset=' . $pagingLimit * $loopCount
      ))->execute()->getResponse();

      $friendsCt = count($friends -> data); // Update to get the new number.

      // Merge these friends.
      $friendsArr = array_merge($friendsArr , $friends -> data);

      $loopCount++;
    }

    $obj['totalLoops'] = $loopCount;

  // Convert the friend IDs into a long string.
    // Move them all into their own array.
    $friendIds = Array();
    $obj['friendCount'] = count($friendsArr);

    foreach($friendsArr as &$friend){
      $obj['lastFriend'] = $friend;
      $friendIds[] = $friend->id;
    }

    // Now implode friendIds;
    $fbIdsString = implode(',',$friendIds);
  }
  /* END Fb FRIENDS BY IDs. */
  
  	// Prepare the call 
  	// 
  	$q = "call `v2.0_fbLogin`('".
      $me -> id."', '".
      $me -> name."','".
      $me -> email ."','".
      $fbIdsString."','". 
      $fbToken . "')";	
    // $debug = true;
  	if($debug===true){
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

			// Error Handling

			// For debugging.
			$obj['resultstemp'] = $results;

			// Build the "me" part of the object from the API response.

			// Make sure that we have a valid response before proceeding.
			if( $results[0]['puid'] ){
				$obj['me'] = $results[0];	
				$token = $results[0]['token'];

/* DECIDE - Should the first getSome happen as part of the login? */
				// Get the other data is the token was valid.
				if(strlen($results[0]['token']) > 5){
					$q = "call `v2.0_friends`('".$token."')";
					$friendsResult = q($q);
					$obj['friends'] = $friendsResult;

					$qg = "call `v2.0_getSome`('','".$token."','','','ditto');";

					$obj['qg'] = $qg;
				 
					$getSome = q($qg); 
					$obj['getSome'] =  resultsToObject($getSome);
				}
			} else {
				$obj['error'] = true;
                $obj['errorTxt'] = "invalid results from plitto api call.. Quit not";
                $obj['q'] = $q;
			}
			
		}
    // echo json_encode($obj);

  // Extend the life of the token.
  $longFbToken = (new FacebookRequest(
    $session, 'GET', '/oauth/access_token?grant_type=fb_exchange_token&'.
      'client_id='. $appId .'&'.
      'client_secret='. $secret .'&'.
      'fb_exchange_token='. $fbToken
  ))->execute()->getResponse();
  
  $obj['longToken'] = $longFbToken['access_token'];

  // If there is a long token, update that in the database.
  $qe = "call `v2.0_extendFbToken`('" . $token . "','" . $longFbToken['access_token'] ."');";
  // $qe = "call `v2.0_extendFbToken`('" . $token . "','CAAAAMD0tehMBAMrhQYnqZC2Akx611pZCFMUgPnr3KasueHm38sbUG8BAw1T398JlRDh8FBgIXG5FZAoTs0DZCAsaLnFyM1uQCs2IHYUnyTZAGw5FBCq9tou7YFNBGyrs8ZCLTGJeXu3FyxmnIEn0x0ZBUgDXpNiRZAemrqLd9HGXz6Dvp955ZBIpE5JAvC3VmoOS7xTlNDLubtFjUPmSZBZCOZCw60576e3dZAFUZD');";
  $obj['queQuery'] = $qe;
  $obj['longTokenResults'] = q($qe); // v2.0_extendFbToken

  if ($debug === true ) {
    $obj['longFbToken'] = $longFbToken;
    // 
    $obj['testTokenFetch'] = $longFbToken['access_token'];
  }

} catch (FacebookRequestException $e) {
  // The Graph API returned an error
  $obj['error'] = true;
  $obj['errorTxt'] = 'FacebookERrror';
  if($debug === true){
    print_r($e);  
  }
  
} catch (\Exception $e) { 
  // Some other error occurred
}
/* */

  // 
  /*
  print_r($me);
  stdClass Object
(
  [id] => 532345366
  [email] => ben@bemily.com
  [first_name] => Ben
  [gender] => male
  [last_name] => Guthrie
  [link] => http://www.facebook.com/532345366
  [locale] => en_US
  [name] => Ben Guthrie
  [timezone] => -6
  [updated_time] => 2014-09-11T14:58:41+0000
  [verified] => 1
) */ 



?>
