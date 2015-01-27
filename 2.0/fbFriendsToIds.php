<?php




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
      $obj['friendIds'] = implode(',',$friendIds);

  ?>