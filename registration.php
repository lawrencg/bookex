<?php 
	include 'includes/database_info.php';
	
	$dbconn = pg_connect($DB_CONNECT_STRING)
	    or die('Could not connect: ' . pg_last_error());
	# Get the current UW NetID from the server via pubcookie
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
		<!-- <link rel="stylesheet" href="main.css"/> -->
		<link rel="stylesheet" href="styles/main.css"/>
	
	</head>
	<body>
	<div id="pagecontainer">
		<!-- Top Div, this is included in every page.  Should be pulled out into a header file, use PHP include feature so we don't repeat code -->
		<div id="top">
			<div id="header">
				<div id="bookexlogo" class="frontpageonly">
					<!-- BookEX -->
					<img id="bookex-logo" src="images/bookex-logo.png" />
				</div>
				<!--<div class=""><h1>Agreement and Registration</h1></div>-->
			</div>
		</div>	
		<!-- End Top Div -->
		
		<div id="page">
			<div id="registrationtitle" class="pageTitle">Agreement and Registration</div>
			<div id="maincontent">
				<div class="contentarea centerDiv">
					<div id="agreementinformation">
						<p class="introstatement">To use BookEX, you have to agree to the follwing terms.</p>
						<p>The program is intended for use by UW students, factulties and staffs who have a valid UW NetID. You decide who to loan your textbooks to and we are not responsible for any damage or lost to your books by the borrower. You can resquest book and you can also accept or deny other's book requests.</p>
						<p class="agreementconditions">
						You agree that:<br/>
							<ul>
								<li>You will return the book you borrowed on time and in as good condition as when borrowed.</li>
								<li>You agree to pay for the damage or loss of the book that you borrow to the lender. Howerver we do not hadle the payment. You do that in person out of the system.</li>							
								<li>Conflict is dealt between lender and borrower and BookEX does not deal with conflict between lender and borrower. </li>
								<li>You will not held BookEX owners accoutable for any lost or damage to your textbooks or books or anyother lost</li>
								<li>You will not hack the system.</li>
								<li>Will not spam other members with your request.</li>
							</ul>
							You will have to use your UW NetID to log in.
							<p>Click "Agree" to agree to the agreement and use the BookEX or click "No thanks" if you don't agree to the terms and leave.</p>						
						</p>
					</div>
				</div>
				
				<div>
					<div id="uwnetiddisplayarea">
						<p>UW NetID:&nbsp;<b><?php echo $_SERVER['REMOTE_USER']; ?></b></p>
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
		
		<!-- Footer, this should be the same across every page.  Consider pulling out into an include -->
		<div id="footer">
			<ul id="footernavigation">
				<li id="privacy"><a href="/privacy">Privacy</a></li>
				<li id="terms"><a href="/terms">Terms</a></li>
				<li id="contact"><a href="/contact">Contact</a></li>
				<li id="help"><a href="/help">Help</a></li>
				<li id="ourstory"><a href="/ourstory">Our Story</a></li>
			</ul>
			<div id="copyrightstatement">
					&copy; 2011 All Rights Reserved by BookEX
			</div>
		</div>
	</div>	
		<!-- End Footer -->
	</body>
</html>