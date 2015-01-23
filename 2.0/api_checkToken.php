<?php
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


$obj['error'] = false;

$q = "call `v2.0_tokenCheck`('".$_POST["token"]."')";
  // $obj['q'] = $q;
  

  $results = q($q);

  /* Disabled 1/22/2015 to increase speed. The client doesn't need to see the private FB Token.
  $obj['results'] = $results;
  */
  


if( isset($results[0]['error']) && $results[0]['error'] === "1"){
  $obj['error'] = true;
  $obj['errortxt'] = $results[0]['errortxt'];
}
else {

  $fbToken = $results[0]['fbToken'];


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

  $debug = false;

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

    $friends = (new FacebookRequest(
      $session, 'GET', '/me/Friends'
    ))->execute()->getResponse();
    if ($debug === true ) {
      $obj['friendsDebug'] = $friends;
    }

    // $obj['86'] = 86;

    // print_r($friends);
    /*(
      [data] => Array
          (
              [0] => stdClass Object
                  (
                      [name] => James Guthrie
                      [id] => 4700538
                  )

              [1] => stdClass Object
                  (
                      [name] => Greg Guthrie
                      [id] => 4700900
                  )

              [2] => stdClass Object
                  (
                      [name] => Jeff Bowen
                      [id] => 4702676 */

    // Time to process, login and return a plitto response.
    if(strlen($name) === 0){
      $obj['error'] = true;
      $obj['errortxt'] = 'No username';
    } else {
      $obj['success'] = true;

      // Prepare the friends
      $friendsArr = Array();
      $friendsCt = count($friends -> data);
      if($friendsCt > 0){
        for($i=0; $i < $friendsCt; $i++){
          $friendsArr[] = $friends -> data[$i] -> id;
        }
      }

      /* Disabled 1/22/2015 to increase speed. 
        $obj['ft'] = $friendsArr;
      */ 
      // Prepare the call 
      // 
      $q = "call `v2.0_fbLogin`('".$me -> id."', '".$me -> name."','" .$me -> email ."','".implode(',',$friendsArr)."','". $fbToken . "')"; 

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

        /* Disabled 1/22/2015 to increase speed. It's there for debugging
          // $obj['resultstemp'] = $results;
        */

        // Build the "me" part of the object from the API response.

        // Make sure that we have a valid response before proceeding.
        if( isset($results[0]["puid"] ) ) {
          $obj['me'] = $results[0]; 
          $obj['results'][0]['success'] = '1';
          

          $token = $results[0]['token'];

          // Get the other data is the token was valid.
          if(strlen($results[0]['token']) > 5){
            /* Disabled 1/22/2015 to increase speed. These will be loaded through different calls. 
              TODO2 - Reinstate as part of the request?  
            $q = "call `v2.0_friends`('".$token."')";
            $friendsResult = q($q);
            $obj['friends'] = $friendsResult;
            */
            /* Disabled 1/22/2015 to increase speed. 
            $qg = "call `v2.0_getSome`('','" . $token . "','','','ditto');";

            $obj['qg'] = $qg;
           
            $getSome = q($qg); 
            $obj['getSome'] =  resultsToObject($getSome);
            */
          }
        } else {
          $obj['error'] = true;
          $obj['errortxt'] = "Plitto Token Error";
        }

        
      }
    }
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

  /*
  end: 
    $obj['error'] = true;
    $obj['errorTxt'] = "invalid token";

  success:
    $obj['success'] = true;
  */
}


  // $obj['fbToken'] = $fbToken;

  // Use this token to check for new/updated friends for this user.
    


  // Refresh the Facebook information using the Facebook token for this user.
?>

