<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 10, 2011
	# Title: All in one file for testing. Displays the users BookEx name and creates new users without asking.
	
	$dbconn = pg_connect($DB_CONNECT_STRING)
	    or die('Could not connect: ' . pg_last_error());
	$user = $_SERVER['REMOTE_USER'];
	//$user = 'shanzha2';
	/*$result = pg_query("SELECT isabookexuser('" . $user . "')") or die('Query failed: ' . pg_last_error()); 
	$userExists = pg_fetch_array($result);
	if ($userExists[0] == f) {
		$result = pg_query("SELECT addbookexuser('" . $user . "',null,null,null,null)")
			or die('Query failed: ' . pg_last_error());
		echo "<p>Thank you, {$user}. You have just been registered.</p>\n";
	}*/
	$result2 = pg_query("SELECT getbookexname('" . $user . "')") or die('Query failed: ' . pg_last_error()); 
	$bookexname = pg_fetch_array($result2);
	echo "<p>Hello {$bookexname[0]}.</p>\n";
	pg_close($dbconn);
?>
