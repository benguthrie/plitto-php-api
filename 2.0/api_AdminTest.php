
<?php
  $appId = '207184820755';
  $secret = 'a3b57227479539ea7812d58d3ebc00fc';

  // Hard Coded

  $fbToken = 'CAAAAMD0tehMBAOWJPJ91MyhPAwibV7wWOH0Y7ZBAcHFYrGkdJ4xDOqn28vGQkZAMBZA7bPCiGIKIzqxI3cZBOF5AZBegInhM4yAwX5lqQAkpZBcPZC6T614RNsgfBlNqmqpcMkHmmUyBeIDnTmu7FK4GhCp765dPjuJFh5wZBqKYuZCyT1jPZCTheW';

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


$obj['error'] = false;

  FacebookSession::setDefaultApplication($appId,$secret);

  $session = new FacebookSession($fbToken);

  // print_r($session);
  /*
  Facebook\FacebookSession Object
  (
      [accessToken:Facebook\FacebookSession:private] => Facebook\Entities\AccessToken Object
          (
              [accessToken:protected] => CAAAAMD0tehMBABDb8YxoOd8JHPhUsJuvP0OUQLnxIX6C2giocrvQcrcqETJOVzKtqCWkY8p17SLCD0g1sWZAZB3DG8ECXMFTwVshyN5mAghaF3clBMNNMaMGp2bnbr7RhWjOBqRiDUNFpiAWS9AQ93i0DDZAAZCitwqiQ5UDI2QGbr6DR4PtotZBqQ4OcfzKHBjZAX7p45vrpkDxCqTwXx
              [machineId:protected] => 
              [expiresAt:protected] => 
          )

      [signedRequest:Facebook\FacebookSession:private] => 
  )
  */

  // Get the GraphUser object for the current user:

  $debug = true;
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

  
  // Apply the name of the user.
  $name = $me->name;
    // Time to process, login and return a plitto response.
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
  $obj['fbIdsString'] = $fbIdsString;
}  
/* END Fb FRIENDS BY IDs. */
  

    // echo json_encode($obj);

  } catch (FacebookRequestException $e) {
    // The Graph API returned an error
    $obj['error'] = true;
    
    $obj['errorTxt'] = 'Facebook Returned Error.';
    $obj['e'] = $e;
    $obj['fbToken'] = $fbToken;
    if($debug === true){
      print_r($e);  
    }
    
  } catch (\Exception $e) { 
    // Some other error occurred
  }



  // $obj['fbToken'] = $fbToken;

  // Use this token to check for new/updated friends for this user.
    


  // Refresh the Facebook information using the Facebook token for this user.
?>

