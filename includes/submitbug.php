<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: Allows users to send feedback to the slcap@u.washington.edu email list about the BookEx web application.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	include 'session_track.php';
	# Database connection parameters
	require 'database_info.php';
	require 'menu.php';
	include 'greeting.php';

	function bugform(){
		echo "<form action='' id='book' name='book' method='POST'>
		<p><b>Description of bug:</b></p><textarea cols='100' rows='5' id='description' 
			name='description' style='vertical-align:text-top;' virtual /></textarea><br /><br />
		<p><b>The following information will also be sent:</b></p>
		<input type='hidden' id='data' name='data' />
		<textarea cols='100' rows='10' id='display' 
			name='display' style='vertical-align:text-top;' virtual disabled /></textarea><br /><br />";
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
		<input type='submit' name='sendbug' value='Submit' style='margin-left:10px' />";
		echo "</form></p>";
		echo "<form action='dashboard.php' id='nothing' name='nothing' method='POST'>
		<input type='submit' name='cancel' value='Cancel' style='margin-left:10px' /></form>";
	}
	function submitbug(){
		$note = pg_escape_string($_POST['description']); 
		$info = pg_escape_string($_POST['data']); 
		$header = "This is a bug submission from BookEx.";
		$message = $header . "\n\n--USER INPUT--\n" . $note . "\n\n--BROWSER INFO--\n" . $info;
		exec("echo -e \"{$message}\" | mail -s \"BookEx Bug\" slcap@uw.edu");
		#exec(echo {$message} | mail -s \"BookEx Bug\");
		echo "<p>Thank you for your input. Your report has been submitted.</p>";
		echo "<a href='dashboard.php'>Return to Dashboard</a>";
	}
	echo "<h1>Bug Submission</h1>";
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['sendbug'])){
			submitbug();
		}
	} else {
		bugform();
	}
?>
