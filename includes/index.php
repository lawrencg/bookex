<?php
//	include 'menu.php';
//	include 'greeting.php';

	$DATABASE = "larry_test";
	$DB_USER = "shanzha";
	$DB_PASSWORD = "lawrence";
	$DB_CONNECT_STRING = "host=vergil.u.washington.edu port=10450 dbname=" . $DATABASE . " user=" . $DB_USER . " password=" . $DB_PASSWORD;
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
				echo "<a href='userprofile.php?id={$records[0]}' style='text-decoration:none;color:#0000FF'>{$records[0]}'s books</a><br />";
			}
		}
		echo "</p>";
?>
