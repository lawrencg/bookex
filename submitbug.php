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

	function bugform(){
		echo "<div class='button-container'><form action='dashboard.php' id='book' name='book' method='POST'>
		<p>Description of bug:</p><textarea cols='80' rows='5' id='description' 
			name='description' style='vertical-align:text-top;resize:none;' virtual /></textarea><br /><br />
		<p>The following information will also be sent:</p>
		<input type='hidden' id='data' name='data' />
		<textarea cols='80' rows='12' id='display' 
			name='display' style='vertical-align:text-top;resize:none;' virtual disabled /></textarea><br /><br />";
		$user = $_SERVER['REMOTE_USER'];
		echo "<script type=\"text/javascript\">
		txt = \"Browser CodeName: \" + navigator.appCodeName + \"\\n\";
		txt+= \"Browser Name: \" + navigator.appName + \"\\n\";
		txt+= \"Browser Version: \" + navigator.appVersion + \"\\n\";
		txt+= \"Cookies Enabled: \" + navigator.cookieEnabled + \"\\n\";
		txt+= \"Platform: \" + navigator.platform + \"\\n\";
		txt+= \"User-agent header: \" + navigator.userAgent + \"\\n\";
		txt+= \"UW NetID: {$user}\" + \"\\n\";
		txt+= \"Previous Page: {$_SESSION['previouspage'][1]}\" + \"\\n\";
		txt+= \"Date: ".date("F j, Y")."\" + \"\\n\";
		txt+= \"Time: ".date("G:i:s \(T\)")."\" + \"\\n\";
		txt+= \"User Domain: {$_SERVER['REMOTE_HOST']}\" + \"\\n\";
		document.getElementById(\"display\").innerHTML=txt;
		document.getElementById(\"data\").value=txt;
		</script>";
		echo "
		<div><input type='submit' name='sendbug' value='Submit' style='margin-left:10px' /></div>";
		echo "</form>";
		echo "<form action='dashboard.php' id='nothing' name='nothing' method='POST'><div>
		<input type='submit' name='cancelbug' value='Cancel' style='margin-left:10px' /></div></form></div>";
	}
	
	include 'includes/submitbug_0_header.php';
	include 'includes/siteheader.php';
	
	echo '		<div id="page">' . "\n";
	echo '			<div id="maincontent">' . "\n";
	echo '				<div class="pageTitle">Submit a Bug</div>' . "\n";	
	echo '			<div id="submitbugarea" class="contentarea">' . "\n";
	echo '					<div id="submitbug">' . "\n";
	
	if(!isset($_POST['sendbug'])){
		bugform();
	} 
	echo '			</div>';
	echo '		</div>';
	echo '	</div>';
	echo '	</div>';
		
	include 'includes/sitefooter.php';
?>
