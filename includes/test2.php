<html>
<head>
	<title>Mike's workspace for Capstone</title>
</head>
<body>

<?php
// attempt a connection
$mydb = pg_connect("host=vergil.u.washington.edu port=10450 dbname=mike_v1 user=mike password=michael");
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
	
	// add a book
	if ($_POST['addBook']) {
	$sql6 = "INSERT INTO books (isbn13, title, author, edition, condition) 
				VALUES (" . $_POST['addISBN13'] . ',' . $_POST['addTitle'] . ',' . $_POST['addAuthor'] . ',' . $_POST['addEdition'] . ',' . $_POST['addCondition'] . ");";
	 
	 $result6 = pg_query($mydb, $sql6);
	 if (!$result6) {
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
	 echo $row[4];
 echo "</ol></table>";
 
 // function for "availability" colors
 function availabilityColor($available) {
	if ($available == 'available') { 
		return "<font color='green'><i>Available.</i></font>";
		} else {
		return "<font color='red'><i>Not Available.</i></font>";
		}
 }
 
 
  // execute query
 $sql2 = 'SELECT * FROM people
			JOIN peoples_books ON peoples_books.people_id = people.id
			JOIN books ON peoples_books.book_id = books.id';
 $result2 = pg_query($mydb, $sql2);
 if (!$result2) {
	 die("Error in SQL query: " . pg_last_error());
 }    
 
 echo "<h2>Complete list of everyone's books in the database:</h2>";
 echo "<table border='1'><tr><td><b>Book Title: </b></td><td><b>Owner of this book: </b></td><td><b>Owner's UW NetID</b></td></tr>";
 
 while ($row = pg_fetch_array($result2)) {
	echo "<tr><td>" . $row[14] . "</td><td>" . $row[1] . " " . $row[2] . "</td><td>" . $row[5] . "</td></tr/>";
 } 
 
 echo "</table>";
	
// execute query
 $sql = 'SELECT * FROM "people"';
 $result = pg_query($mydb, $sql);
 if (!$result) {
     die("Error in SQL query: " . pg_last_error());
 }       

 
 echo "<h2>Add a Book: (the above list should update)</h2>";
 echo "<form action='test2.php' method='post'>
 Title: <input type='text' value='' name='addTitle' id='addTitle' size='50'/>
 Author: <input type='text' value='' name='addAuthor' id='addAuthor' size='32'/><br/>
 Edition: <input type='text' value='' name='addEdition' id='addEdition'/>
 Condition: <input type='text' value='' name='addCondition' id='addCondition'/> 
 ISBN-13: <input type='text' value='' name='addISBN13' id='addISBN13'/><br/>
 <input type='hidden' name='addBook' value=''><input type='submit' name='submitBook' value='Add This Book' style='height: 5em; width: 15em'/></form>";
 
 
 
 echo "<h2>Users in the Database:</h2>";
 echo "<table border='1'><tr><td><b>Name: </b></td><td><b>Secondary Email: </b></td><td><b>Phone: </b></td></tr>";
 
 // iterate over result set
 // print each row
 while ($row = pg_fetch_array($result)) {
     echo "<td>" . $row[1] . " " . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td></tr>";
 }       

 echo "</table>";
 

 echo "<h2>View:</h2>";
 echo "<table border='1'><tr><td><b>ID: </b></td><td><b>Title: </b></td><td><b>ISBN13: </b></td></tr>";
 $query = "SELECT * FROM my_rows5";
 	 $resultq = pg_query($mydb, $query);
	 if (!$resultq) {
		 die("Error in SQL query: " . pg_last_error());
	 }

 while ($row = pg_fetch_array($resultq)) {
     echo "<td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
 }
 
 echo "</table>";
 
 // free memory
 pg_free_result($result);       

 // close connection
 pg_close($mydb);
 ?>       

   </body>
 </html>