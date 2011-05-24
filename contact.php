<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: Display a users information. Will be displayed differently if the user is looking at their own profile.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	require 'includes/session_track.php';
	# Database connection parameters
	require 'includes/database_info.php';
	require 'includes/valid_user.php';
	
	$michael = 'cheungm';
	$larry = 'shanzha';
	$sopheap = 'skun';
	$jessica = 'jpp6';
	
	$person = $_GET['id'];
	$user = $_SERVER['REMOTE_USER'];
	if($person == null){
		$person = $user;
	}
	
	$pic1 = pictureurl($michael);
	$pic2 = pictureurl($larry);
	$pic3 = pictureurl($sopheap);
	$pic4 = pictureurl($jessica);
	
	function pictureurl($name){
		$imageURL = "SELECT users.profile_pic FROM users WHERE id = '{$name}'";
		$imageURLResult = pg_query($imageURL);
		if (!$imageURLResult) {
			die("Error in SQL query: " . pg_last_error());
		}

		while ($row = pg_fetch_array($imageURLResult)) {
			$currentPictureURL = $row[0];
		}
		return $currentPictureURL;
	}	
	
	function features(){
	global $pic1, $pic2, $pic3, $pic4;
	echo "<div><p>The BookEx Team</p><p>You can reach the entire team by sending emails to bookex@u.washington.edu or by submitting a bug from the main 
		menu. You can also choose to email anyone directly.</p><p><i>Thanks.</i></p>
		<table><tr><th align='right'>Application Developers<th><th align='right'>Interface Designers<th></tr>
		<tr><td align=center><img src='images/profiles/" . $pic1 . "' alt='User Picture' height='100' width='100'></img></td><td><a href='profile.php?id=cheungm'>Michael Cheung</a><br />cheungm@uw.edu</td>
		<td align=center><img src='images/profiles/" . $pic3 . "' alt='User Picture' height='100' width='100'></img></td><td><a href='profile.php?id=skun'>Sopheap Kun</a><br />skun@uw.edu</td></tr>
		<tr><td align=center><img src='images/profiles/" . $pic2 . "' alt='User Picture' height='100' width='100'></img></td><td><a href='profile.php?id=shanzha'>Lawrence Gabriel</a><br />shanzha@uw.edu</td>
		<td align=center><img src='images/profiles/" . $pic4 . "' alt='User Picture' height='100' width='100'></img></td><td><a href='profile.php?id=jpp6'>Jessica Pardee</a><br />jpp6@uw.edu</td></tr></table></div>";
	}
	
	include 'includes/contact_0_header.php';
	include 'includes/siteheader.php';

	echo '		<div id="page">' . "\n";
	echo '			<div id="maincontent">' . "\n";
	echo '				<div class="pageTitle">Contact Information</div>' . "\n";	
	echo '			<div id="contact" class="contentarea">' . "\n";
	echo '					<div id="emails">' . "\n";
	features();
	echo '			</div>' . "\n";
	echo '		</div>' . "\n";
	echo '	</div>' . "\n";
	echo '	</div>' . "\n";
		
	include 'includes/sitefooter.php';
?>