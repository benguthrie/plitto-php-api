<?php
class sql{
	public function mysqlQuery($sql,$id)
	{
		# echo "mysqlQuery: $sql<br>";
		$dbinfo = c::constants("db");
		$DBserver = $dbinfo[0];
		$DBdatabase = $dbinfo[1];
		$DBusername=$dbinfo[2];
		$DBpassword=$dbinfo[3];

		$conn = mysql_connect ($DBserver, $DBusername, $DBpassword) or die ('DB Con Failed: '.mysql_error());
			mysql_select_db ($DBdatabase);

		# 		if ($_SESSION['debug']==1 or 0==0) { echo "<h3>Description: $description<br>Sql: <br>$sql</h3>"; return ""; }

		$startTime = sql::getmicrotime();

		$result = mysql_query($sql);
		$sqlerror = mysql_error();
		$endTime = sql::getmicrotime();
		$queryTime=$endTime-$startTime;

//		// logError($sql,$sqlerror,$errorText,$page)
//		if ( strlen($sqlerror)>0 )
//		{
//			sql::logSqlError(addslashes($sql) , $sqlerror , $errorText );
//		}

//		// Allow for 3rd party apps to do things.
//		if (strlen($_SESSION['sessionID'])>0 and strlen($_SESSION['countSession'])>0 and strlen($_SERVER['PHP_SELF'])>0)
//		{}
			# s::logSqlQuery($_SESSION['sessionID'], $_SESSION['countSession'], $sql, $queryTime, $_SERVER['PHP_SELF'],$debug);
		$sql_log = "insert into log_queries (QueryID, queryText, queryTime) values ('".$id."','".addslashes($sql)."','$queryTime')";
		mysql_query($sql_log);
		// echo $sql_log;



		return $result;
	}
	
	private function logSqlError($sql,$sqlerror,$errorText)
	{
		$sql = "insert into log_errors (failedSQL,information) values ('".$sql."','".$sql." info: ".$sqlerror ." &&& " . $errorText."')";
		sql::mysqlQuery($sql,"27");
	}
	
	private function getmicrotime()
	{
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}
}
?>