<?php
session_start();
$current= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$_SESSION['previouspage'][1]=$_SESSION['previouspage'][0];
$_SESSION['previouspage'][0]=$current;


echo "<p>\n";
echo "\t<a href='dashboard.php'>Dashboard</a>\n";
echo "\t&nbsp;|&nbsp;<a href='mybooks.php'>My Books</a>\n";
echo "\t&nbsp;|&nbsp;<a href='myprofile.php'>My Profile</a>\n";
echo "\t&nbsp;|&nbsp;<a href='https://weblogin.washington.edu/logout/'>Logout</a>\n";
echo "\t&nbsp;|&nbsp;<a href='submitbug.php'>Submit a Bug</a>\n";
echo "\t&nbsp;|&nbsp;<a href='index.php'>MAIN PAGE</a>\n";
echo "</p>\n";
?>
