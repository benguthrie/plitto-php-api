<?php
class admin {
	
	public function process($command,$accessToken,$puid)
	{
		//("updatefb", $_SESSION['accessToken'],$_SESSION['puid'])
		$command = strtolower($command);
		if(strlen($command)==0)
		{
			return '{"action":"no command"}';
		}
		if ($command == "getnames")
		{
			$daysBack = 30;
			// Get the Facebook IDs that haven't been updated lately.
			$sql = "select UserID, password, DATEDIFF(CURRENT_TIMESTAMP,DateModified) as TheDateDiff from user_logins 
				where LoginType='2'
				and Active= '1'
				and UserID = '$puid'
				and DATEDIFF(CURRENT_TIMESTAMP,DateModified) > $daysBack 
				limit 1";
			$result = sql::mysqlQuery($sql,"2");
			if (mysql_num_rows($result)==0)
			{
				return '{"return":"everyone has names"}';
			}
			$uids = array();
		for($a=0;$a<mysql_num_rows($result);$a++)
			{
				$uids[] = array(mysql_result($result,$a,"UserID"),mysql_result($result,$a,"password"));
				
				// $me = json_decode(file_get_contents('https://graph.facebook.com/me/?access_token=' .$_SESSION['accessToken']));
				$data = json_decode(file_get_contents('https://graph.facebook.com/'.$uids[$a][1].'/?access_token=' .$_SESSION['accessToken']));
				
				// Update their name
				$sql = 'update user_logins set UserName="'.$data -> name.'" DateModified = CURRENT_TIMESTAMP where password = "'. $uids[$a][1] .'" and LoginType = "2" ';
				sql::mysqlQuery($sql,"3");
				
			
					
			}
		}
		else if($command == "updatefb")
		{
			// Get the user information that hasn't been updated in X number of days
			$daysBack = -10;
			// Get the Facebook IDs that haven't been updated lately.
			$sql = "select UserID, password, DATEDIFF(CURRENT_TIMESTAMP,DateModified) as TheDateDiff from user_logins 
				where DATEDIFF(CURRENT_TIMESTAMP,DateModified) > $daysBack
				and LoginType='2' and UserID = '$puid'
				limit 3";
			$result = sql::mysqlQuery($sql,"4");
			// echo "$sql";
			if (mysql_num_rows($result)==0) {
				return "Everything is up to date";
			}
			$uids = array();
			for($a=0;$a<mysql_num_rows($result);$a++)
			{
				$uids[] = array(mysql_result($result,$a,"UserID"),mysql_result($result,$a,"password"),mysql_result($result,$a,"TheDateDiff"));
				
				
				// $me = json_decode(file_get_contents('https://graph.facebook.com/me/?access_token=' .$_SESSION['accessToken']));
				$data = json_decode(file_get_contents('https://graph.facebook.com/'.$uids[$a][1].'/?access_token=' .$_SESSION['accessToken']));
				
				// Update their name
				$sql = 'update user_logins set UserName="'.$data -> name.'" where password = "'. $uids[$a][1] .'" and LoginType = "2" ';
				sql::mysqlQuery($sql,"6");
				
				// Handle a list called "about me" : List ID: 2250
				$aboutMe= array();
				
				//
				if($data -> hometown->name)
				{
					$aboutMe[] = "Hometown: " . $data -> hometown->name;	
				}

				if($data -> birthday)
				{
					$aboutMe[] = "Birthday: " . $data -> birthday ;	
				}
				
				if($data->relationship_status)
				{
					$hookedUp = $data->relationship_status;
					if($data->significant_other)
					{
						$hookedUp .= " with ". $data->significant_other->name ;
						
					}
					$aboutMe[] = $hookedUp;
				}
				
				if($data -> location)
				{
					$aboutMe[] = "Location: " . $data -> location -> name ;	
				}
				if($data -> education)
				{
					for($b=0;$b<count($data -> education);$b++)
					{
						// 	echo "xxxxx".$data->education[$b]->school->name;
						
						// print_r($data->education[$b]);
						
						$tmpSch = "";
						if($data->education[$b]->school->name)
						{
							$tmpSch = "School: " . $data->education[$b]->school->name;
							
							if($data->education[$b]->year->name)
							{
								$tmpSch .= ' ('. $data->education[$b]->year->name.')';
							}
							
							$aboutMe[] = $tmpSch;
						}
						if($data->education[$b]->concentration-> name)
						{
							$aboutMe[] = "Schooled in: " . $data->education->concentration->name;
						}
					
					}
				
				}
				if($data -> gender)
				{
					$aboutMe[] = "Gender: " . $data -> gender ;	
				}
				if($data -> political)
				{
					$aboutMe[] = "Political Views: " . $data -> political ;	
				}
				if($data -> website)
				{
					$aboutMe[] = "My Website: " . $data -> website;	
				}
				
				
				
				//print_r($aboutMe);
				//print_r($data);
				
				// Get the facebook information for this user
				//echo 'https://graph.facebook.com/'.$uids[$a][1].'/?access_token=' .$accessToken.'
				$tUID = d::getPlittoID($data->id);
				// Add to this list "About Me. : List ID: 2250
				
				$jsonStr = array();
				
				for($c=0;$c<count($aboutMe);$c++)
				{
					
					$jsonStr[] = " $tUID added ". d::getThingID($aboutMe[$c]). " to 'About Me'	";
					d::addToList($tUID,'2250',d::getThingID($aboutMe[$c]));
				}
				
				// Now, mark this person as modified, so we don't do this again for X days
				$sqlU = "update user_logins set DateModified = CURRENT_TIMESTAMP where LoginType='2' and UserID = '".mysql_result($result,$a,"UserID")."'";
				sql::mysqlQuery($sqlU,"5");
					
			}

		
			// print_r($uids);
			
			
			return '{"'.implode("},{",$jsonStr).'}';
		}
		else
		{
			return '{"action":"no valid"}';
		}
		
	}
	
}
?>