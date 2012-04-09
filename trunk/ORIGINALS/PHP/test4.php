<html>
<head>
	<title>Mike's workspace for Capstone</title>
</head>
<body>

<?php
// attempt a connection
$mydb = pg_connect("host=vergil.u.washington.edu port=10450 dbname=mike_v2 user=mike password=michael");
if (!$mydb) {
	die("Error in connection: " . pg_last_error());
	}
	
	$user = "{$_SERVER['REMOTE_USER']}";
	
	// set available
	if ($_POST['bookid']) {
		$sql4 = "UPDATE peoples_books
					SET availability='available'
					WHERE book_id=" . $_POST['bookid'] . ";";
	 //print_r($_POST);
	 
	 $result4 = pg_query($mydb, $sql4);
	 if (!$result4) {
		 die("Error in SQL query: " . pg_last_error());
	 }
	}
	 
	// set NOT available
	if ($_POST['bookid2']) {
	$sql5 = "UPDATE peoples_books
				SET availability='not available'
				WHERE book_id=" . $_POST['bookid2'] . ";";
	 
	 $result5 = pg_query($mydb, $sql5);
	 if (!$result5) {
		 die("Error in SQL query: " . pg_last_error());
	 }    
	}
	
	
		echo "<h1>Hello, <i>$user</i>!</h1>";

 // execute query
 $sql3 = "SELECT books.title, books.author, books.isbn13, peoples_books.availability, books.id FROM books
			JOIN peoples_books ON peoples_books.book_id = books.id
			JOIN people ON peoples_books.people_id = people.id
			WHERE people.username = '$user'";
			
 $result3 = pg_query($mydb, $sql3);
 if (!$result3) {
	 die("Error in SQL query: " . pg_last_error());
 }    	
 
 
 echo "<h2>List of YOUR books in the database:</h2>";	
 echo "<table>";
 echo "<ol>";
	while ($row = pg_fetch_array($result3)) {
	 $available = $row[3];
	 echo "<tr><td><li><u><i><font size='+1'>" . htmlspecialchars($row[0]) . "</font></i></u></li>" . " by " . htmlspecialchars($row[1]) . " (ISBN-13: " . htmlspecialchars($row[2]) . ")</td><td>Availability: " . availabilityColor($available) . "<br/>";
	 echo "<div style='float: left; width: 130px'><form method='post' action='test2.php'>
	 <input type='hidden' name='bookid' value='" . $row[4] . "'><input type='submit' name='setAvail' value='Set to \"Available.\"'/></form></div>	 
	 <div style='float: right; width: 225px'><form method='post' action='test2.php'>
	 <input type='hidden' name='bookid2' value='" . $row[4] . "'><input type='submit' name='setNotAvail' value='Set to \"Not Available.\"'/></form></div></tr>";
	 }
 echo "</ol></table>";
 
 // function for "availability" colors
 function availabilityColor($available) {
	if ($available == 'available') { 
		return "<font color='green'><i>Available.</i></font>";
		} else {
		return "<font color='red'><i>Not Available.</i></font>";
		}
 }
 
 
 // close connection
 pg_close($mydb);
 ?>       

   </body>
 </html>