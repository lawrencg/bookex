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
		echo "<div><p>First of all, we would like to thank everyone who has given us input on our project so far. To give you a view of things to come,
				we have the following list of features we are planning to add. Many of the great ideas have come our User Testing with UW students. Keep 
				logging into BookEx to see how we are coming along.<br /><br />Thanks,<br /><i>The BookEx Team</i></p><ul>
				<li>User ratings</li>
				<li>User messaging from within BookEx for delivery coordination</li>
				<li>Add books by title</li>
				<li>Add books with a smartphone barcode scanner</li>
				<li>Transaction history for your books</li>
				<li>Transaction history individual users</li>
				<li>Search for books by course</li>
				<li>&quot;You have saved $&quot; messages</li>
				<li>Remove your BookEx account instantly</li>
				<li>Remove your profile picture without uploading a new one</li>
				<li>Dashboard customization</li>
				</ul></div><br />";
	}
	
	include 'includes/comingsoon_0_header.php';
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