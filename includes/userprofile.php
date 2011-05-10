<?php
	include 'menu.php';
	include 'greeting.php';

	$DATABASE = "larry_test";
	$DB_USER = "shanzha";
	$DB_PASSWORD = "lawrence";
	$DB_CONNECT_STRING = "host=vergil.u.washington.edu port=10450 dbname=" . $DATABASE . " user=" . $DB_USER . " password=" . $DB_PASSWORD;
	
	include 'request_process.php';

	if(!isset($_GET['id'])){
		echo "No user choosen.";
	} else {
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$available = pg_query("SELECT * FROM availablebooksfromuser('{$_GET['id']}'::varchar) 
			VALUES( book_id int, title varchar, isbn10 numeric, isnb13 numeric, author text)") 
			or die('Query failed: ' . pg_last_error()); 
		$firsttime = true;
		while($records = pg_fetch_array($available)) {
			if ($firsttime) {
				echo "<h3>Book List for {$_GET['id']}</h3>\n";
				echo "<table border='2px'><th width='250px'>Title</th><th width='200px'>Author</th>
				<th width='100px'>ISBN-10</th><th width='100px'>ISBN-13</th><th width='300px'></th>";
				echo "<tr>";
				echo "<td><a href='bookdetail.php?id={$records[0]}'>{$records[1]}</a></td>
				<td>{$records[4]}</td><td>{$records[2]}</td><td>{$records[3]}</td><td>";
				request_button($records[0]); 
				echo "</td>\n";
				echo "</tr>";
				$firsttime = false;
			} else {
				echo "<tr>";
				echo "<td><a href='bookdetail.php?id={$records[0]}'>{$records[1]}</a></td>
				<td>{$records[4]}</td><td>{$records[2]}</td><td>{$records[3]}</td><td>"; 
				request_button($records[0]);
				echo "</td>\n";
				echo "</tr>";
			}
		}
		pg_close($dbconn);
			echo "</table";	
	}
?>
