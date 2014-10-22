<?php
# This will be the configuration class
class c
{
	const limit = 200;

	public function constants($type)
	{
		# This is the hard-coded section where the switch from dev to prod happens.
		#$active = "dev";
		#
		$active = "prod";
		# $active = "localhost";
		if($type=="limit")
		{	return 15;	}
		
		if($type=="json")
		{
			if($active=="dev")
			{	return "http://dev.plitto.com/json/";	}
			else
			{	return "http://www.plitto.com/json/";	}
		}
		
		if($type=="db")
		{
			// $DBusername="publicly";$DBpassword="PUbi$l&";
			$DBusername="theuser";$DBpassword="applychanges";
			if($active=="dev")
			{	$DBserver = "mysql.plitto.com"; $DBdatabase = "devplitto";	}
			else if($active=="prod")
			{	$DBserver = "mysql.plitto.com";	$DBdatabase = "plitto"; }
		$dbarray = array($DBserver,$DBdatabase,$DBusername,$DBpassword);
		return $dbarray;
		}
		
		if($type=="path")
		{
			if($active=="dev")
			{	$path = "/home/plitto/dev.plitto.com/";	}
			else if($active=="prod")
			{	$path = "/home/plitto/plitto.com/";	}
		return $path;
		}
		
		if($type=="url")
		{
			if($active=="dev")
			{	$url = "http://dev.plitto.com/type_facebook/";	}
			else if($active=="prod")
			{	$url = "http://www.plitto.com/type_facebook/";	}
		return $url;
		}
		
		if($type=="api")
		{
			if($active=="dev")
			{	$api_key = 'a7a0ea2170d7ad556dba13f7a8d4bd19';	$secret = '35be12900fe12eb148127f79c425c8fb'; $appID = '21726148393';	}
			else if($active=="prod")
			{	$api_key = '7fd42a83ef8ebbffaee4c4f9ad488fad'; 	$secret  = '44d369ae68ee2294762fc0208cfa3f4a'; $appID = '23832129102';	}
			return array($api_key,$secret,$appID);
		}
	}
}
?>