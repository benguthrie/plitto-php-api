<?php
session_start();
header('Content-type: text/javascript');


error_reporting(-1);
$obj = Array();
if($_GET['type'] === 'login'){
  $_SESSION['puid'] = 9;
  // 
  	$obj['success'] = true;
} else if($_GET['type'] === 'logout'){
	// $obj['result'] = 'logged out';
	$_SESSION['puid'] = null;
	$obj['success'] = true;
}
$obj['puid'] = $_SESSION['puid'];
// $obj['result'] = 'success';
// $obj['session'] = $_SESSION['puid'];
echo '['.json_encode($obj).']';

?>