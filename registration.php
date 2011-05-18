<?php 
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 17, 2011
	# Title: Registers a new user to the BookEx system.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	require 'includes/session_track.php';
	# Database connection parameters
	require 'includes/database_info.php';	
	
	$user = $_SERVER['REMOTE_USER'];
	if(isset($_GET['maint']))
		$user = null;
	$result = pg_query("SELECT isabookexuser('{$user}'::varchar)") or die('Query failed: ' . pg_last_error()); 
	$userExists = pg_fetch_array($result);
	pg_close($dbconn);
	if ($userExists[0] == t) {
		header("Location: dashboard.php");
		exit();
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd" author and creator: Jessica Pardee>
<html>
	<head>
		<title>BookEx User Agreement and Registration</title>
		<link rel="stylesheet" href="styles/main.css"/>
	</head>
	<body>
	<div id="pagecontainer">
		<div id="top">
			<div id="header">
				<div id="bookexlogo" class="frontpageonly">
					<img id="bookex-logo" src="images/bookex-logo.png" />
				</div>
			</div>
		</div>	
		<div id="page">
			<div id="registrationtitle" class="pageTitle">Agreement and Registration</div>
			<div id="maincontent">
				<div class="contentarea centerDiv">
					<div id="agreementinformation">
						<p class="introstatement">BookEx is a book exchange application that is intended for use by anyone who has a valid UW NetID. 
						The decision to loan your books is completely up to you. We are not responsible for lost or damaged books. You can request books from all BookEx 
						user but they have the right to deny the request for any reason. Exchanges are intended to be made in good faith. Please do not be lame.</p>
						<p></p>
						<p class="agreementconditions">
						By clicking the "I agree" button below, you will be agreeing to the following terms:<br/>
						</p>
						<ul>
							<li>You will return the book you borrowed on time and in as good condition as when borrowed.</li>
							<li>You agree to pay for damaged or lost books. However, BookEx does not handle payment processing.</li>							
							<li>Any conflicts are dealt with between the lender and borrower. BookEx does not have a conflict resolution mechanism.</li>
							<li>Will not spam other users with excessive book requests.</li>
							<li>You will not hack the system.</li>
						</ul>
						<p>You will be identified by your UW NetID and will need it to login.</p>
						<p>Click "I agree" to accept the terms displayed above and create your BookEx account. "No thanks" will return you to the main page and 
						not add your UW NetID to the BookEx system.</p>						
					</div>
				</div>			
				<div>
					<div id="uwnetiddisplayarea">
						<p><b>UW NetID:&nbsp;</b><?php echo $_SERVER['REMOTE_USER']; ?></p>
					</div>
					<div id="pleasenotemessage">				
						<b>Please note:</b> All information in the box below is optional, 
						but by entering more information, you will make it easier for 
						others to find you, as well as allowing for people to see more 
						information about you, increasing your chances of being able to 
						borrow books.
						<br/>
					</div>
					<form action='dashboard.php' id='register' name='register' method='POST'>
						<div id="additionalinfotextareas" class="contentarea centerDiv">
							<div id="centeringtextarea">
									<div class="firstnametextarea">
										<label>First Name:</label>
										<input type="text" id="firstname" name="firstname" />
									</div>
									<div class="lastnametextarea">
										<label>Last Name:</label>
										<input type="text" id="lastname" name="lastname"/>
									</div>
									<div class="majortextarea">
										<label>Major:</label>
										<input type="text" id="major" name="major"/>
									</div>
									<div class="additionalemailtextarea">
										<label>Additional E-mail:</label>
										<input type="text" id="email" name="email"/>
									</div>	
							</div>
						</div>
						<div id="agreementbuttons" class="centerDiv">
							<input type="submit" id="register" name="register" value="I agree" />
							<input type="submit" id="dontregister" name="dontregister" value="No thanks" /> 
						</div>
					</form>
				</div>
			</div>
		</div>
<?php 
	include 'includes/sitefooter.php';
?>