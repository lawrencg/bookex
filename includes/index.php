<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: Test landing page.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	include 'session_track.php'
	# Database connection parameters
	include 'database_info.php';
	
	echo "<h1>Welcome to BookEx!</h1><br /><a href='dashboard.php'>Go to My Dashboard</a>";
	
		$user = $_SERVER['REMOTE_USER'];
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$conditions = pg_query("SELECT id FROM users") 
			or die('Query failed: ' . pg_last_error()); 
		echo "<p>";
		while($records = pg_fetch_array($conditions)) {
			if($records[0] == $user){
			} else {
				echo "<a href='profile.php?id={$records[0]}' style='text-decoration:none;color:#0000FF'>{$records[0]}'s books</a><br />";
			}
		}
		echo "</p>";
?>
