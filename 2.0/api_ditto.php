<?php

	$obj['temp'] = 'Made the Ditto call';
	// $q = 'call ditto("'.$_POST['action'].'","'.$_POST['userid'].'","'.$_POST['sourceUserID'].'","'
	// 		.$_POST['listnameid'].'","'.$_POST['thingid'].'","'.$_SESSION['testpuids'].'");';

	
	$q = "call `v2.0_ditto`('".$_POST['token']
		."','". $_POST['fromuserid']
		."','". $_POST['thingid']
		."','". $_POST['listid']
		."','". $_POST['action']
		."');";

	$obj['q'] = $q;
	
	$obj['results'] = q($q);
	/**/

?>