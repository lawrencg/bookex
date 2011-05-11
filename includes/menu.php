<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 10, 2011
	# Title: Testing menu. Also tracks one previous URL of the session for the submitbug.php page.
	
	session_start();
	$current= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	# Might not work properly if they visit the submitbug.php page directly. 
	$_SESSION['previouspage'][1]=$_SESSION['previouspage'][0];
	$_SESSION['previouspage'][0]=$current;
	
	# Testing menu
	echo "<p>\n";
	echo "\t<a href='dashboard.php'>Dashboard</a>\n";
	echo "\t&nbsp;|&nbsp;<a href='mybooks.php'>My Books</a>\n";
	echo "\t&nbsp;|&nbsp;<a href='myprofile.php'>My Profile</a>\n";
	echo "\t&nbsp;|&nbsp;<a href='https://weblogin.washington.edu/logout/'>Logout</a>\n";
	echo "\t&nbsp;|&nbsp;<a href='submitbug.php'>Submit a Bug</a>\n";
	echo "\t&nbsp;|&nbsp;<a href='index.php'>MAIN PAGE</a>\n";
	echo "</p>\n";
?>
