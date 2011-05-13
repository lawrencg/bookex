<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 13, 2011
	# Title: Redirects you to the register page if the currently logged in UW NetID is not a BookEx user.
	
	require 'includes/database_info.php';
	
	$user = $_SERVER['REMOTE_USER'];
	$dbconn = pg_connect($DB_CONNECT_STRING)
	$result = pg_query("SELECT isabookexuser('{$user}'::varchar)") or die('Query failed: ' . pg_last_error()); 
	$userExists = pg_fetch_array($result);
	pg_close($dbconn);
	if ($userExists[0] == f) {
		echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		echo'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
		echo'	<head>';
		echo'		<title>BookEx Unathorized Access</title>';
		echo'		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />';
		echo'        <meta name="author" content="BookEx" />';
		echo'        <meta http-equiv="REFRESH" content="0;url=https://bookex.info/register.php" />';
		echo'	</head>';
		echo'	<body>';
		echo'		<p><a href="https://bookex.info/register.php">You must be a registered BookEx user to view this page.</a></p>';
		echo'	</body>';
		echo'</html>';
	}
?>
