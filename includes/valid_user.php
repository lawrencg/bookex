<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 13, 2011
	# Title: Redirects you to the register page if the currently logged in UW NetID is not a BookEx user.
	
	$user = $_SERVER['REMOTE_USER'];
	$result = pg_query("SELECT isabookexuser('{$user}'::varchar)");// or die('Query failed: ' . pg_last_error()); 
	$userExists = pg_fetch_array($result);
	//
	if (isset($_POST['dontregister'])) {

	} else if ($userExists[0] == f && !isset($_POST['register']) && preg_match('/.w3.org$/',$_SERVER['REMOTE_HOST']) != 1) {
		header("Location: https://students.washington.edu/shanzha/registration.php");
		exit();
	}
?>