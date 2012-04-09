<html>
<head></head>
<body>

<?php
// attempt a connection
$mydb = pg_connect("host=vergil.u.washington.edu port=10450 dbname=test user=mike password=michael");
if (!$mydb) {
	die("Error in connection: " . pg_last_error());
	}
	
// execute query
 $sql = 'SELECT * FROM "Countries"';
 $result = pg_query($mydb, $sql);
 if (!$result) {
     die("Error in SQL query: " . pg_last_error());
 }       

 // iterate over result set
 // print each row
 while ($row = pg_fetch_array($result)) {
     echo "Country code: " . $row[0] . "<br />";
     echo "Country name: " . $row[1] . "<p />";
 }       

 // free memory
 pg_free_result($result);       

 // close connection
 pg_close($mydb);
 ?>       

   </body>
 </html>