<?php 
class d{
	const size = 20;
	
	public function notInCommon($puid,$markNotInCommon)
	{
		$markArray = explode(",",$markNotInCommon);
		if(count($markArray)>0)
		{
			$insArr = array();
			for($a=0;$a<count($markArray);$a++)
			{
				$insArr[] = "('$puid','".$markArray[$a]."',CURRENT_TIMESTAMP)";
			}
			
			$sql = 'insert into listlinks_markread (UserID, ReadListLInkID, ReadTime) Values '.implode(",",$insArr);
			sql::mysqlQuery($sql,"37");
		}
	}
	
	public function addUser($uid , $name)
	{
		// Try to get the Plitto UserID from the database.
		$sql = "SELECT UserID FROM user_logins WHERE password='$uid' and LoginType='2' and Active = '1' limit 1";
			# echo $sql."<br/>";
		$result = sql::mysqlQuery($sql,"7");
	
		// If it doesn't exist, add a new user
		if(mysql_num_rows($result)==0)
		{
			// Get the last user ID and add to it.
			$sql = "select UserID from user_accounts 
				order by UserID desc limit 1";	
			$result = sql::mysqlQuery($sql,"8");
			// T
			$newID = mysql_result($result,0,"UserID")+ 1;
						
			// Add this new record.
			$sql = "insert into user_accounts (UserID,Verified,Active) values ('$newID','1','1')";
			sql::mysqlQuery($sql,"9");
			# echo $sql."<br/>";
			// Add the user info
			$sql = "insert into user_logins (UserID, password,AuthLevel,DateAdded,LoginType,Active, UserName)
				values ('$newID','$uid',2,CURRENT_TIMESTAMP,'2','1', ".json_encode($name).")";
			# echo $sql."<br/>";
			sql::mysqlQuery($sql,"26");
			
			$sql = "SELECT UserID FROM `user_logins` WHERE password='".$uid
				."' and LoginType='2' and Active = '1' limit 1";
			$result = sql::mysqlQuery($sql,"10");
			# echo $sql."<br/>";
			if(mysql_num_rows($result)==0)
			{
				echo "Well, we tried to create the account, but failed. Oops.";
				break;
			}
			
			$puid = mysql_result($result,0,"UserID");
			# echo "puid: ".$puid."<br/>";

			# 		echo $sql."<br/>";
			return $puid;
		}
	
		return mysql_result($result,0,"UserID");
	}
	
	public function search($pids,$searchTerm,$puid)
	{
		$_search = rawurldecode($searchTerm);
		$_search = addSlashes($_search); // Clean it up for edge cases.
				
	$sql = "
	
	 (
      select things.ThingName, listlinks.ParentListNameID as ThingID, 'List' as theType, count(*) as TheCount
      from listlinks 
      inner join things on listlinks.ParentListNameID = things.thingID
  
      where things.ThingName 
  
    like '%". $_search. "%'
    
    and listlinks.UserID in (".implode(",",$pids)." or $puid)
      group by listlinks.ParentListNameID
      order by TheCount desc
   limit 0,20
    )
  
     union
     (select things.ThingName, listlinks.child as ThingID, 'Thing' as theType, count(*) as TheCount
		from things 
		inner join listlinks on listlinks.child = things.thingID
		where things.ThingName 
		like '%". $_search. "%'
		
		and listlinks.UserID in (".implode(",",$pids)." or $puid) 
		
		group by listlinks.child
		order by TheCount desc
		limit 0,20
	   )
   
";
	// 	echo "$sql 	";
		$result = sql::mysqlQuery($sql,"11");
		//echo "Size: ".mysql_num_rows($result);
		$count = mysql_num_rows($result);
    $obj -> type = "search";
		if($count==0)
		{
			// return '{"name":"No Results","id":"0","type":"none","count":"0"}';
			$obj -> name = "No Results";
			$obj -> id = "0";
			$obj -> type = "none";
			$obj -> count = 0;
			return $obj;
		}
else {$items = array();}
		$tempType = "";
		for ($a=0;$a<$count;$a++)
		{
	
		  $data -> {$a} -> theType = mysql_result($result,$a,'theType');
		  $data -> {$a} -> name = json_encode(mysql_result($result,$a,'ThingName'));
		  $data -> {$a} -> id = mysql_result($result,$a,'ThingID');
		  $data -> {$a} -> count = mysql_result($result,$a,'theCount');
		}
		//return implode(",",$items);
		$obj -> results = $data;
		return $obj;
	}
	
	public function getUserName($userID)
	{
		if($userID>0)
		{
			$sql="select UserName from user_logins
				where UserID='$userID' and LoginType ='2' and Active=1
				limit 1 ";
			$result=sql::mysqlQuery($sql,"12");
			if(mysql_num_rows($result)==1)
			{
				return mysql_result($result,0,"UserName");
			}
			else
			{
				return "Somebody?";
			}
		}
		else
		{
			echo "Bad input for userID";
			return "";
		}
	}
	
	public function addToList($userID,$parentListNameID,$thingID)
	{
		# Check to see if everything is kosher
		if(is_numeric($userID)==1 and is_numeric($parentListNameID)==1 and is_numeric($thingID)==1) {} else { return "addtoList bad inputs. error d8"; }
		
		# Check to see if this thing is already there.
		$sql = "select Child from listlinks where UserID = '$userID' and ParentListNameID = '$parentListNameID' and Child='$thingID' and State = '1' limit 1";
		$result = sql::mysqlQuery($sql,"13");
		if(mysql_num_rows($result)>0) { return "You already have this: ";}
		
		#TODO get the ListID, if that ever will get used. 
		
		$sql = "insert into listlinks (Child,Modified,Added, State, ParentListNameID, UserID)
			values ('$thingID',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP, '1', '$parentListNameID','$userID')";
		
		sql::mysqlQuery($sql,"14");
		return "Added ";
	}
	
	public function getThingName($thingID)
	{
		$sql = "select ThingName from things where thingID = '$thingID' limit 1";
		$result = sql::mysqlQuery($sql,"15");
		if(mysql_num_rows($result)==0) { return "invalid thing ID. error d9."; }
		
		return stripslashes(mysql_result($result,0,"ThingName"));
	
	}
	
	public function getThingID($thingName)
	{
		if(strlen($thingName)==0) { echo "invalid thing ID"; return ""; }
		// echo "esc string: ".addslashes($thingName)."<br/>";
		$sql = "select ThingID from things where ThingName ='".addslashes($thingName)."' limit 1 ";
		$result = sql::mysqlQuery($sql,"16");
		// Try to make the thing ID what we got. No checking whether there was something yet.
		
		// Return it if it is valid
		if(mysql_num_rows($result)>0) { return mysql_result($result,0,"ThingID");}
		else {
		// The thing needs to be added
		$sql = "insert into things (thingName) values ('".mysql_real_escape_string($thingName)."');";
		$result = sql::mysqlQuery($sql,"17");
		}
		// Now the thing is added. Proceed.
		$sql = "select ThingID from things where ThingName ='".addslashes($thingName)."' limit 1 ";
		$result = sql::mysqlQuery($sql,"18");
		// Try to make the thing ID what we got. No checking whether there was something yet.
		$thingID = mysql_result($result,0,"ThingID");
		if (strlen($thingID)>0) { return $thingID; }
		echo "Something is FUBAR. d26."; return "";
	}
	
	public function ditto($puid,$personID,$parentListID,$thingID,$incommon)
	{
		# echo "Public: $puid <br/>";
		if($incommon==0)
		{
		$addit = 1;
		# Check to see if this thing is already there.
		$sql = "select Child from listlinks where UserID = '$puid' and ParentListNameID = '$parentListID' and Child='$thingID' and State='1' limit 1";
		# echo "SQL: $sql<br/>";
		$result = sql::mysqlQuery($sql,"19");
		if(mysql_num_rows($result)>0) { $addit = 0; }
		
		// The thing is not in common. It needs to be added.
		// But only if this user doesn't already have it.
		if($addit ==1 )
		{
			$listID = d::getListID($puid,$parentListID,$thingID);
			$sql = "insert into listlinks (Child,Modified, State, ListID, ParentListNameID, UserID)
				values ('$thingID',CURRENT_TIMESTAMP, '1', '$listID','$parentListID','$puid')
			";
			sql::mysqlQuery($sql,"20");
			# echo "Insert into listlinks:<br/>$sql<br/>";
		}
		
		# Handle the ditto loggin // Type 3
		$sql = "insert into alerts_peruser (FromUserID, ToUserID, isRead, isHidden, DateAdded, AlertType, ThingID, ListID )
			values('$puid',".d::getPlittoID($personID).",'0','0','0',CURRENT_TIMESTAMP, '3', '$thingID','$parentListID')";
		# 	echo "Insert into dittos:<br/>$sql<br/>";
		sql::mysqlQuery($sql,"21");
		return 1;
		}
		else
		{
		// The thing is already in common 
		# echo "This is already in common";
		return 0;
		}
	
	}
	
	public function getPlittoID($fbID)
	{
		$sql = "select UserID from user_logins where password = '$fbID' and LoginType = '2' limit 1";
		$result = sql::mysqlQuery($sql,"22");
		return mysql_result($result,0,"UserID");
	}
	
	private function getListID($puid,$parentListID,$thingNameID)
	{
		$sql = "select ListID from lists_unique where UserID = '$puid' and Parent = '$parentListID' limit 1";
		$result = sql::mysqlQuery($sql,"23");
		if (mysql_num_rows($result)==1)
		{
		return mysql_result($result,0,"ListID");
		}
		else 
		$sql = "insert into lists_unique (Parent,UserID, ModifiedDateTime, CreateDateTime) values ('$parentListID','$puid',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)";
		sql::mysqlQuery($sql,"24");

		$sql = "select ListID from lists_unique where UserID = '$puid' and Parent = '$parentListID' limit 1";
		# echo "Createt the ListID:<br/>$sql<br/>";
		$result = sql::mysqlQuery($sql,"25");
		
		mysql_result($result,0,"ListID");
	}
	
	// Function to generate nested SQL to get the Plitto IDs of the Facebook Users.
	public function fbuid($fbuidArray)
	{
		return "select UserID from user_logins	where LoginType = '2' and password in (".implode(",",$fbuidArray).")";
	}
	
// 6/24/2010
	public function thingToJSON($data,$sql)
	{
		$count = mysql_num_rows($data);
		if($count == 0)
		{
			echo '{"error":"no results","sql":"$sql"}';
			break;
		}
		else
		{
		// Create some temp IDs
		$tt=-1;
		$tl=-1;
		
		$thingInfoArray = array();
		
		// Start an array for a thing (this will be the only thing)
		for($a=0;$a<$count;$a++)
		{
			// User Info, if a new user
			if($tt != mysql_result($data,$a,"ThingID"))
			{
				$tt=-1;
				// Set this as the temp thingID
				$tt = mysql_result($data,$a,"ThingID");
				// It's a new user ID. Create array.
					// Plitto UID, User Name, fbuid
				$thingsInfo = array("things",$tt,mysql_result($data,$a,"ThingName"));
				
				array_push($thingInfoArray, $thingsInfo);
			}
			
			// Create a new list line, it it is a new list ID.
			if($tl != mysql_result($data,$a,"ListID"))
			{
				// Set Temp ListID
				$tl = mysql_result($data,$a,"ListID");
			
			// List array: ("lists",$listID, $listName)
			// It's a new List ID. Create array.
			$listsInfo=array("lists",mysql_result($data,$a,"ListID"),(mysql_result($data,$a,"ListName")));
				
			array_push($thingInfoArray, $listsInfo);
			}
			
			if(is_numeric(mysql_result($data,$a,"InCommonKey"))==1) { $inc = "icYes";} else {$inc = "icNo"; }
			
			#3 User Stuff
				// User array: ("users",$userID, $userName, $added, $inc)
			$userArray=array("users",mysql_result($data,$a,"UserID"),mysql_result($data,$a,"UserName"),$inc,mysql_result($data,$a,"added"));
			array_push($thingInfoArray, $userArray);
		}
		// print_r($feedArray);
		return  d::arrToJSONthing($modifiers,$thingInfoArray);
		}
	}
	
	public function sqlToJSON($modifiers,$data)
	{
		$count = mysql_num_rows($data);
		if($count == 0)
		{
			return "{\"error\": \"no records\" }";
			break;
		}
		else
		{
		// Create some temp IDs
		$tu=-1;
		$tl=-1;
		
		$feedArray = array();
		
		// Start an array for a person
		for($a=0;$a<$count;$a++)
		{
			// User Info, if a new user
			if($tu != mysql_result($data,$a,"UserID"))
			{
				$tl=-1;
				// Set this as the temp UserID
				$tu = mysql_result($data,$a,"UserID");
				// It's a new user ID. Create array.
					// Plitto UID, User Name, fbuid
				$uidInfo = array("users",$tu,(mysql_result($data,$a,"UserName")),mysql_result($data,$a,"fbuid"));
				
				array_push($feedArray, $uidInfo);
			}
			
			//List Info, if a new list
			if($tl != mysql_result($data,$a,"ParentListNameID"))
			{
				// Set Temp ListID
				$tl = mysql_result($data,$a,"ParentListNameID");
				// It's a new List ID. Create array.
				$listInfo=array("lists",$tl,(mysql_result($data,$a,"ListName")));
				
				array_push($feedArray, $listInfo);
			}
			
			//Thing Info - No need for the temps here. Every row needs a thing.
			if(mysql_result($data,$a,"InCommonKey")>0) { $inc = "icYes";} else {$inc = "icNo"; }
			$thingInfo=array("things",mysql_result($data,$a,"ThingID"),(mysql_result($data,$a,"ThingName")),$inc,mysql_result($data,$a,"Added"));
			array_push($feedArray, $thingInfo);
		}
		// 	print_r($feedArray);
		return  d::arrToJSON($modifiers,$feedArray);
		}
	}
	
private function arrToJSONthing($modifiers,$arr)
{
	// print_r($arr);
		// Start the JSON 
$start = "{
\"title\": \"Thing Feed\", \"description\": \"Info about a feed\",	\"modified\": \"".date('Y-M-D H:i:s')."\",
\"generator\": \"http://www.plitto.com/\", 
\"type\": \"thing\", 
\"things\":[";
	
// Set some counts to keep track of the number of users, lists and things.
$u=0;	$l=0;	$t=0;

$count = count($arr);

// Loop through the array, first looking for thing.
for($a=0;$a<$count;$a++)
{
	// Loop through looking for the thing call.
	if($arr[$a][0]=="things") 
	{
	// We know that it's just going to be the one thing, so we don't need to worry about more.
	$start .= "
	{  \"name\":".json_encode($arr[$a][2]).",\"id\":\"".$arr[$a][1]."\",";

		
	// We know the next array will be a list.
	$start.= "
	\"list\":[";
	// Set the temp thing count to 0
	$l=0;
	
	// The list section will have the title, etc, then the users nested below.
	while($a<$count and $arr[$a+1][0] == "lists")
	{
		if($l>0) {
			$start .= ",";
		}
		$l++;
		
		 // List array: ("lists",$listID, $listName)
		 $start .= "
		 {\"id\":\"".$arr[$a+1][1]."\",\"name\":".json_encode($arr[$a+1][2]).",";
		$t=1;
		// Advance it because we know there will be a user next.
		$a++;
		// End list printing until the next episode.
		
		// User data start.
			$start .= "
			\"user\":[";
			while($a<$count and $arr[$a+1][0] == "users")
			{
			// Add a comma, if it's not the first item.
				if($u!=0) { $start .= ","; }
				$u++;
				// User array: ("users",$userID, $userName, $added, $inc)
				$start .= "
				{\"id\":\"".$arr[$a+1][1]."\",\"name\":".json_encode($arr[$a+1][2]).",\"added\":".json_encode($arr[$a+1][4]).",\"incommon\":".json_encode($arr[$a+1][3])."}";
			$a++;
			// When they run out of users, the next line will either be a list, or the whole thing will be done.
			}
			$u=0;
			// End the user section.
			$start .= "
			]}";
	}
		
		// End the thing.
		$start .= "
	]} ";
	}
}
// End the master
$start .= "
]} ";

return $start;
}

private function arrToJSON($modifiers,$arr)
{
	// print_r($modifiers);
	# prepare for the modifiers by setting an empty string
	$jsonModifiers = "";
	
	// Start by handling the modifiers
	# If it's a child, show submenu and be done with it.
	if(is_numeric(array_search("child",$modifiers))==1)	{	$jsonModifiers .= '"view":"submenu", ';	}
	else
	{
		if(is_numeric(array_search("person",$modifiers))==1 )
		{
		$jsonModifiers .= '"title":"Profile View","view":"person","type":"person","menus":['
		# .'"title":"sort","buttons":[{"name":"oldest"},{"name":"newest"}]},'
			.'{"title":"filter","buttons":[{"name":"dittoable"},{"name":"incommon"},{"name":"all"}]}], ';
		}
		if(is_numeric(array_search("list",$modifiers))==1 )
		{
		$jsonModifiers .= '"title":"Profile View","view":"person","type":"list","menus":['
		# .'"title":"sort","buttons":[{"name":"oldest"},{"name":"newest"}]},'
			.'{"title":"filter","buttons":[{"name":"dittoable"},{"name":"incommon"},{"name":"all"},{"name":"me"},{"name":"friends"}]}], ';
		}
		else # It is the default feed
		{
		$jsonModifiers .= '"title":"Activities from Your Contacts","type":"feed","view":"main","menus":['
		# .'"title":"sort","buttons":[{"name":"oldest"},{"name":"newest"}]},'
		.'{"title":"filter","buttons":[{"name":"dittoable"},{"name":"incommon"},{"name":"all"},{"name":"me"},{"name":"friends"}]}], ';
		}
	}

// Start the JSON 
$start = '{
'.$jsonModifiers.'"modified":"'.date('Y-M-D H:i:s').'",
"generator": "http://www.plitto.com/", 
"person":[';
	
// Set some counts to keep track of the number of users, lists and things.
$u=0;	$l=0;	$t=0;

$count = count($arr);

// Loop through the array, first looking for users.
for($a=0;$a<$count;$a++)
{
	//Debug: Show the current array type	echo $arr[$a][0]."<br/>";
	//Debug: Show the ID 			if($arr[$a][0]=="user") {echo "<b>".$arr[$a][1]."</b><br/>";}
	
	// Loop through looking for users.
	if($arr[$a][0]=="users")
	{
		// This means we have a new user list.
			//set the temp user ID to the user id
			$tu = $arr[$a][1];
			
		// Reset list count
		$l = 0;
		
		// Lead with a comma if it isn't the first user.
		if($u!=0) { $start .= ","; }
		$start .= '
	{  "name":'.json_encode($arr[$a][2]).',"id":"'.$arr[$a][1].'","fbuid":"'. $arr[$a][3] .'",';
		//echo $arr[$a+1][2]."<br/>";
		
		// User data has been added. Move on.
		
		// List data start.
		$start .= '
		"list":[';
		while($a<$count and $arr[$a+1][0] == "lists")
		{
		// We know this is a list, so go ahead and incrent the count
		$a++;
		// Add a comma, if it's not the first item.
			if($l!=0) { $start .= ","; }

			$start .= '
			{"id":"'.$arr[$a][1].'","name":'.json_encode($arr[$a][2]).',
			';
			// We know the next array will be a thing.
			$start.= '
				"thing":[';
			// Set the temp thing count to 0
			$t=0;					
			
			while($a<$count and $arr[$a+1][0]=="things")
			{
				$a++;
				if($t != 0) { $start .= ","; }
				 //echo "Thing Name: ".$arr[$a+1][2]."<br/>";
				 $start .= '
					 {"id":"'.$arr[$a][1].'","name":'.json_encode($arr[$a][2]).',"incommon":"'.$arr[$a][3].'","added":"'
				 	.$arr[$a][4].'"}';
				$t=1;
			}
			
			// End the Thing, then the List segment
			$start .= "
				]
			}";
		 
			// Increment the temp list count
			$l=1;
		}
		// End the list section
		$start .= "
		]";
		
		// End the person.
		$start .= "
	} ";
		$u=1;
	}
}
// End the master
$start .= "
]} ";

return $start;
}
}
?>