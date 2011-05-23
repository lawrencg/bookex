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

	function features(){
		echo "<div><p>The BookEx Team</p><p>You can reach the entire team by sending emails to bookex@u.washington.edu or by submitting a bug from the main 
				menu. You can also choose to email anyone directly.</p><p><i>Thanks.</i></p>
				<p><a href='profile.php?id=cheungm'>Michael Cheung</a><br />cheungm@uw.edu</p>
				<p><a href='profile.php?id=shanzha'>Lawrence Gabriel</a><br />shanzha@uw.edu</p>
				<p><a href='profile.php?id=skun'>Sopheap Kun</a><br />skun@uw.edu</p>
				<p><a href='profile.php?id=jpp6'>Jessica Pardee</a><br />jpp6@uw.edu</p></div>";
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