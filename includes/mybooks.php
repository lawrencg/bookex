<?php
	include 'menu.php';
	include "search.php";
	include 'greeting.php';
	$DATABASE = "larry_test";
	$DB_USER = "shanzha";
	$DB_PASSWORD = "lawrence";
	$DB_CONNECT_STRING = "host=vergil.u.washington.edu port=10450 dbname=" . $DATABASE . " user=" . $DB_USER . " password=" . $DB_PASSWORD;
	
	$bookex_id = pg_escape_string($_POST['bookexid']);
	$user = $_SERVER['REMOTE_USER'];

	if(isset($_POST['confirmdelete'])){
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$result = pg_query("SELECT removebook('{$bookex_id}'::integer, '{$user}'::varchar)") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($result)) {
			if($records[0] == f){
				echo "<h2>ERROR: Your book could not be removed at this time. Please ensure that 
				the book available for others to borrow and that it is not currently loaned to someone.";
			} else {
				echo "<h2>Your book has been removed.</h2>";
			}
		}
	}

	$user = $_SERVER['REMOTE_USER'];
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$books = pg_query("SELECT * FROM getmybooks('{$user}') VALUES (bookid int, owner varchar, 
			date timestamp, title varchar, borrower varchar, available varchar, transstatus varchar)") 
			or die('Query failed: ' . pg_last_error()); 
		$firsttime = true;
		while($records = pg_fetch_array($books)) {
			if($records[4] == "")
				$borrow = '&nbsp;';
			else 
				$borrow = $records[4];
			if ($firsttime) {
				echo "<table border='2px'>";
				echo "<th>Title</th><th>Borrower</th><th>Due</th><th>Status</th>";
				echo "<tr><td><a href='bookdetail.php?id={$records[0]}'>{$records[3]}</td>
				<td>{$borrow}</td>";
				if($records[6] == ''){
					echo "<td>&nbsp;</td><td>";
					echo $records[5];
				} else { 
					echo "<td>".date("F j, Y")."</td><td>";
					echo $records[6];
				}
				echo "</td></tr>";
				$firsttime = false;
			} else {
				echo "<tr><td><a href='bookdetail.php?id={$records[0]}'>{$records[3]}</td>
				<td>{$borrow}</td>";
				if($records[6] == ''){
					echo "<td>&nbsp;</td><td>";
					echo $records[5];
				} else { 
					echo "<td>".date("F j, Y")."</td><td>";
					echo $records[6];
				}
				echo "</td></tr>";
			}
		}		
		if(!$firsttime){
			echo "</table><br />";
		} else {
			echo "<p><i>&nbsp;&nbsp;&nbsp;You do not currently have any books added to your account.</i></p>"; 
		}
		echo "<a href='addbook.php'>Add a Book</a>\n";
?>
