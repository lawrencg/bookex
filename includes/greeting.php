<?php
$dbconn = pg_connect("host=vergil.u.washington.edu port=10450 dbname=larry_test user=shanzha password=lawrence")
    or die('Could not connect: ' . pg_last_error());
$user = $_SERVER['REMOTE_USER'];
//$user = 'shanzha2';
$result = pg_query("SELECT isabookexuser('" . $user . "')") or die('Query failed: ' . pg_last_error()); 
$userExists = pg_fetch_array($result);
if ($userExists[0] == f) {
	$result = pg_query("SELECT addbookexuser('" . $user . "',null,null,null,null)")
		or die('Query failed: ' . pg_last_error());
	echo "<p>Thank you, {$user}. You have just been registered.</p>\n";
} else {
}
$result2 = pg_query("SELECT getbookexname('" . $user . "')") or die('Query failed: ' . pg_last_error()); 
$bookexname = pg_fetch_array($result2);
echo "<p>Hello {$bookexname[0]}.</p>\n";
pg_close($dbconn);
?>
