<?php 
	$_SESSION['puid'] = 1; $_SESSION['puids'] = '2,3,4,5,6,7,8,9,10,11,12,13,16,81,161';

	$q = " call `spShowSome`( 'general' , '".$_SESSION['puid']."' , '".$_SESSION['puids']."' , null );";

	// 	$obj['q'] = $q;

	// 

	// $results = resultsToObject(q($q));
	$results = q($q);

	$testFriends = Array(
		Array("uid"=>"2","username"=>"TEST USER #1","fbuid"=> "605592731","lists"=>Array(
				Array("lid"=>"6246","listname"=>"Test List #1",
					"items"=>Array(
						Array("added"=>"2012-04-10 04:01:25", "tid"=>"3416","thingname"=>"Thing I Do Not Have That has a Long Title that you can show in one way or another. Also, this user dittoed it from someone named","mykey"=> null,"dittokey"=>"721","dittouser"=>"2","dittofbuid"=>"1009170001", "dittousername"=>"Ronak Mundra"
						),Array("added"=>"2012-04-10 04:01:25", "tid"=>"3416","thingname"=>"Thing that I have, and this user typed it in.","mykey"=> "722","dittokey"=>"721","dittouser"=>null,"dittofbuid"=>null, "dittousername"=>null
						)
					)
				),
				Array("lid"=>"140","listname"=>"Products / Services I recommend",
					"items"=>Array(
						Array("added"=>"2012-04-10 04:01:25", "tid"=>"3416","thingname"=>"Plitto Connections","mykey"=> null,"dittokey"=>"721","dittouser"=>"2","dittofbuid"=>"1009170001", "dittousername"=>"Ronak Mundra"
						),Array("added"=>"2012-04-10 04:01:25", "tid"=>"3416","thingname"=>"Plitto for Conferences","mykey"=> "722","dittokey"=>"721","dittouser"=>null,"dittofbuid"=>null, "dittousername"=>null
						)
					)
				)
			)
		),
		Array("uid"=>"90121","username"=>"Anonymous Users","fbuid"=> "","lists"=>Array(
				Array("lid"=>"6246","listname"=>"Test List #2",
					"items"=>Array(
						Array("added"=>"2012-04-10 04:01:25", "tid"=>"3416","thingname"=>"Thing I Do Not Have That has a Long Title that you can show in one way or another. Also, this user dittoed it from someone named","mykey"=> null,"dittokey"=>null,"dittouser"=>null,"dittofbuid"=>null, "dittousername"=>null
						),Array("added"=>"2012-04-10 04:01:25", "tid"=>"3416","thingname"=>"Thing that I have, and this user typed it in.","mykey"=> "722","dittokey"=>null,"dittouser"=>null,"dittofbuid"=>null, "dittousername"=>null
						)
					)
				)
			)
		)
		// ,Array("uid"=>"13","username"=>"TEST USER #2","fbuid"=> "1009170001")
	); 


	$results = resultsToObject($results);

	array_unshift($results,$testFriends);



	// 
	$obj['results'] = $results;
	// $obj['puids'] = $_SESSION['puids'];
?>

