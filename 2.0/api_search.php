<?php
	$q="call `v2.0_search`('".$_POST['token']."','".$_POST['search']."');";
	// $obj['q'] = $q;
	// 
	$results = q($q);
	// 
	// $obj['results'] = $results;

	// Build out the results

	$searchResults = Array();

	$tempGroup = Array();

	$groupId = 0;

	foreach($results as $row){
		// echo $row['name'];
		$searchResults[] = $row;

	}

	// $obj['results'] = $searchResults;


	$obj['results'] = searchProcess($searchResults);


	function searchProcess($r){
		$obj = Array();
		if(!isset($r[0]['id'])){
			return $r;
		} else {
			// Create the first section
			$section = Array('title'=>$r[0]['groupName'],'type'=> $r[0]['type'], 'results'=>Array());
			$groupId = $r[0]['group'];

			foreach($r as $record){
				if($record['group'] != $groupId){
					// Add the existing section to the last group.
					$obj[] = $section;
					// Start a new section with this title.
					$section = Array('title'=>$record['groupName'], 'type'=> $record['type'],'results'=>Array());
					$groupId = $record['group'];
				} 

				$section['results'][] = Array('nameId'=>$record['nameid'],'itemName'=>$record['name'], 'itemCount'=>$record['count']);	

				
			}

			// Add the final section
			$obj[] = $section;

			if(count($obj)> 0){
				return $obj;	
			}else{
				return 'no results';
			}
			
		}
	}
	
?>