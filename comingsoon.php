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
		echo "<div></div>";
	}
	
	include 'includes/commingsoon_0_header.php';
	include 'includes/siteheader.php';
	
	echo '		<div id="page">' . "\n";
	echo '			<div id="maincontent">' . "\n";
	echo '				<div class="pageTitle">BookEx Future Implementations</div>' . "\n";	
	echo '			<div id="featuresarea" class="contentarea">' . "\n";
	echo '					<div id="featuretext">' . "\n";
	features();
	echo '			</div>' . "\n";
	echo '		</div>' . "\n";
	echo '	</div>' . "\n";
	echo '	</div>' . "\n";
		
	include 'includes/sitefooter.php';
?>