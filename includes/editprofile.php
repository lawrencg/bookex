<?php
$dbconn = pg_connect("host=vergil.u.washington.edu port=10450 dbname=larry_test user=shanzha password=lawrence");
if (!$dbconn) {
	die("Error in connection: " . pg_last_error());
}
$user = $_SERVER['REMOTE_USER'];

$fname = pg_escape_string($_POST['fname']);
$lname = pg_escape_string($_POST['lname']);
$email = pg_escape_string($_POST['email']);

echo "<p>\n";
echo "\t<a href='dashboard.php'>Dashboard</a>\n";
echo "\t&nbsp;|&nbsp;<a href='mybooks.php'>My Books</a>\n";
echo "\t&nbsp;|&nbsp;My Profile\n";
echo "\t&nbsp;|&nbsp;<a href='https://weblogin.washington.edu/logout/'>Logout</a>\n";
echo "</p>\n";
include "search.php";
echo "<h1>Edit My Profile</h1>";

$myinfo = "SELECT * FROM getmyinfo('" . $user . "') AS results(id varchar, first_name varchar, last_name varchar, email varchar, major integer, pic varchar)";
$myinfoResult = pg_query($dbconn, $myinfo); 			
if (!$myinfoResult) {
	die("Error in SQL query: " . pg_last_error());
}
 
while ($row = pg_fetch_array($myinfoResult)) {
	$myinfoNetID = $row[0];
	$myinfoFirstName = $row[1];
	$myinfoLastName = $row[2];
	$myEmail = $row[3];
	echo "<p><form action='' id='profile' name='profile' method='POST'>";
	echo "First Name: <span style='font-weight:normal;'>&nbsp;<input type='text' value='" . $myinfoFirstName . "' id='fname' name='fname' size='15' /></span><br/>";
	echo "Last Name: <span style='font-weqight:normal;'>&nbsp;<input type='text' value='" . $myinfoLastName . "' id='lname' name='lname' size='15' /></span><br/>";
	echo "UW NetID: <span style='font-weight:normal;'>" . $myinfoNetID . "</span><br/>";
	echo "E-mail: <span style='font-weight:normal;'>&nbsp;<input type='text' value='" . $myEmail . "' id='email' name='email' size='20' /></span><br/>";
	echo "Major: <br/>";	
}

	//echo "<input type='hidden' name='saveID' value='" . $row[0] . "'><input type='submit' name='save' value='Save' style='margin-left:200px' />";
	echo "<input type='submit' name='saveID' value='Save' style='margin-left:100px' />";	
	echo "<input type='submit' name='cancelID' value='Cancel'/>";	

	if (pg_escape_string($_POST['saveID'])){
		savemyinfo();
		echo '<META HTTP-EQUIV="refresh" content="0;URL=https://students.washington.edu/shanzha/myprofile.php">';
		exit;
	}
	
	if (pg_escape_string($_POST['cancelID'])){
		echo '<META HTTP-EQUIV="refresh" content="0;URL=https://students.washington.edu/shanzha/myprofile.php">';
		exit;
	}

	function savemyinfo(){
		global $fname, $lname, $email;
		//global $dbconn;
		//$dbconn2 = pg_connect($dbconn)
		//    or die('Could not connect: ' . pg_last_error());
		$user2 = $_SERVER['REMOTE_USER'];
		pg_query("SELECT savemyinfo('{$fname}'::varchar,'{$lname}'::varchar, '{$email}'::varchar, '{$user2}'::varchar)") 
			or die('Query failed: ' . pg_last_error()); 
	}
	
	echo "</form>";
	
?>