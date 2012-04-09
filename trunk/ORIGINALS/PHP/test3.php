<?php
// attempt a connection
$mydb = pg_connect("host=vergil.u.washington.edu port=10450 dbname=mike_v2 user=mike password=michael");
if (!$mydb) {
	die("Error in connection: " . pg_last_error());
	}
	
	$user = "{$_SERVER['REMOTE_USER']}";
	$check = "SELECT id FROM users WHERE id= '" . $user . "'";
	$checkTest = @pg_query($mydb, $check);
	if (!pg_num_rows($checkTest)) {
		echo "To use BookEX, you have to agree to the following terms.<br/><br/>";
		echo "<b>Please note</b>: All information is optional but entering more information will <br/>make it easier for other to know who you are, which will increase your chance <br/>of being able to borrow book from other.<br/><br/><br/><br/>";
		echo "UW NetID: " . "<b>" . $user . "</b><br/>";
		echo "<form action='test3.php' method='post'>";
		echo "E-mail: " . "<input type='text' value='' name='newEmail' id='newEmail' size='30'/>";
		echo "First Name: " . "<input type='text' value='' name='newFirstName' id='newFirstName' size='30'/><br/>";
		echo "Last Name: " . "<input type='text' value='' name='newLastName' id='newLastName' size='30'/><br/>";	
		echo "<input type='submit' name='submit' value='Agree and let me in' action='test3.php' />";
	} else {
		echo "Hello, " . $user . "!";
		}
	
	if (pg_escape_string($_POST['submit'])) {
		$sql = "INSERT INTO users (id, secondary_email, first_name, last_name)
			VALUES ('" . $user . "', '" . pg_escape_string($_POST['newEmail']) . "', '" . pg_escape_string($_POST['newFirstName']) . "', '" . pg_escape_string($_POST['newLastName']) . "')";
		$result = pg_query($mydb, $sql);
	}	
	
 // close connection
 pg_close($mydb);
?>