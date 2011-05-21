<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: The main user interface for the BookEx web application. This page displays all of the books that a user is invloced with, both lending and borrowing.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	require 'includes/session_track.php';
	# Database connection parameters
	require 'includes/database_info.php';
	# Force non-registered users to register or leave
	# Pages would break if a UW NetID accessed a page directly
	# without being a user
	require 'includes/valid_user.php';
	
	$user = $_SERVER['REMOTE_USER'];
	$errormessage;
	$noconfirmations = true;
	
	# Accepts a BookEx book id, input button name, and button label to create different types of buttons.
	# All buttons submit back to this page.
	function register_user(){
		global $user, $errormessage;

		# Get the current UW NetID from the server via pubcookie
		$user = $_SERVER['REMOTE_USER'];
		$result = pg_query("SELECT isabookexuser('{$user}'::varchar)") ;//or die('Query failed: ' . pg_last_error()); 
		$userExists = pg_fetch_array($result);
		if ($userExists[0] == f) {
			$firstname = trim(pg_escape_string($_POST['firstname']));
			$lastname = trim(pg_escape_string($_POST['lastname']));
			$email = trim(pg_escape_string($_POST['email']));
			$major = trim(pg_escape_string($_POST['major']));
			
			if($firstname == '')
				$firstname = null;
			if($lastname == '')
				$lastname = null;
			if($email == '')
				$email = null;
			if($major == '')
				$major = null;
			
			pg_query("SELECT addbookexuser('{$user}'::varchar,'{$firstname}'::varchar,
				'{$lastname}'::varchar,'{$email}'::varchar,'{$major}'::varchar)");// or die('Query failed: ' . pg_last_error());
			$result = pg_query("SELECT getbookexname('{$user}')");// or die('Query failed: ' . pg_last_error()); 
			$bookexname = pg_fetch_array($result);
				$errormessage = "Thank you, {$bookexname[0]}. You have just been registered.";
		}
		
	}
	function submitbug(){
		global $errormessage;
		$note = htmlspecialchars($_POST['description']); 
		$info = htmlspecialchars($_POST['data']); 
		$system_message = "This is a bug submission from BookEx.";
		$from = 'bookex@u.washington.edu';
		$message = $system_message . "\n\n--USER INPUT--\n" . $note . "\n\n--BROWSER INFO--\n" . $info;
		$subject = 'BookEx Bug Report';
		$to = 'bookex@u.washington.edu';
		$headers = 'From: BookEx<' . $from . '>' . "\r\n" .
		'Reply-To: BookEx<bookex@u.washington.edu>' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();		
		mail($to,$subject,$message,$headers);
		$errormessage = 'Thank you for your input. Your report has been submitted.';
	}
	function leave_bookex(){
		header("Location: https://students.washington.edu/shanzha");
		exit();
	}
	function ourstory(){
		header("Location: http://www.bookex.info/ourstory.html");
		exit();
	}
	function createbutton($name, $label, $bookid){
		echo '											<form action=\'\' id=\'form_' . $name . '\' name=\'form_' . $name . '\' method=\'post\'>' . "\n";
		echo '												<div class="dashboardbutton"><input type=\'hidden\' value=\'' . $bookid . '\' id=\'transid\' name=\'transid\' />' . "\n";
		echo '												<input type=\'submit\' id=\'' . $name . '\' name=\'' . $name . '\' value=\'' . $label . '\' /></div>' . "\n";
		echo '											</form>' . "\n";	
	}
	# Books I have requested
	function myrequests(){
		# Global variables
		# Only need to get the username from the server once.
		global $user, $noconfirmations;
		# Might need to create the table and print the table headers.
		$firsttime = true;
		$yourequested = pg_query("SELECT * FROM detailedtransactions WHERE recipientid = '{$user}' AND transstatus = 'Requested'") ;
			//or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($yourequested)) {
			if ($firsttime){
				if($noconfirmations){
					echo '				<div id="confirmationmessagearea" class="contentarea">' . "\n";
					$noconfirmations = false;
				}
				echo '						<div id="yourrequests">' . "\n";
				echo '							<p class="header">I have requested ...</p>' . "\n";
				echo '								<table id="yourrequeststable">' . "\n";
				$firsttime = false;
			} 
			echo '									<tr>' . "\n";
			echo '										<td class="yourrequestsmessage">' . "\"{$records[3]}\" from {$records[6]}.</td>\n";
			echo '										<td class="yourrequestsbutton">' . "\n";
			createbutton('cancelrequest','Cancel',$records[0]);
			echo '										</td>' . "\n";
			echo '									</tr>' . "\n";
		}
		if(!$firsttime){
			echo '								</table>' . "\n";
			echo '						</div>' . "\n";
		}
		
	}
	# Books others have requested
	function othersrequests(){
		# Global variables
		# Only need to get the username from the server once.
		global $user, $noconfirmations;
		# Might need to create the table and print the table headers.
		$firsttime = true;
		$theyrequested = pg_query("SELECT * FROM detailedtransactions WHERE myid = '{$user}' AND transstatus = 'Requested'") ;
			//or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($theyrequested)) {
			if ($firsttime){
				if($noconfirmations){
					echo '				<div id="confirmationmessagearea" class="contentarea">' . "\n";
					$noconfirmations = false;
				}
				echo '						<div id="othersrequests">' . "\n";
				echo '							<p class="header">Approvals</p>' . "\n";
				echo '								<table id="othersrequeststable">' . "\n";
				$firsttime = false;
			}
			echo '									<tr>' . "\n";
			echo '										<td class="yourrequestsmessage">'.$records[5].' has requested to borrow "'.$records[3]."</td>\n";
			echo '										<td class="othersrequestsacceptbutton">' . "\n";
			createbutton('acceptbookrequest','Accept',$records[0]);
			echo '										</td>' . "\n";
			echo '										<td class="othersrequestsacceptbutton">' . "\n";
			createbutton('deny','Deny',$records[0]);
			echo '										</td>' . "\n";
			echo '									</tr>' . "\n";
		}
		if(!$firsttime){
			echo '								</table>' . "\n";
			echo '						</div>' . "\n";
		}
	}
	# Books I need to deliver
	function deliveryconfirmations(){
		# Global variables
		# Only need to get the username from the server once.
		global $user, $noconfirmations;
		# Might need to create the table and print the table headers.
		$firsttime = true;
		$awaiting = pg_query("SELECT * FROM detailedtransactions WHERE myid = '{$user}' AND transstatus = 'Awaiting Delivery'") ;
			//or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($awaiting)) {
			if ($firsttime) {
				if($noconfirmations){
					echo '				<div id="confirmationmessagearea" class="contentarea">' . "\n";
					$noconfirmations = false;
				}
				echo '						<div id="deliveryconfirmations">' . "\n";
				echo '							<p class="header">Have you delivered ...</p>' . "\n";
				echo '								<table id="deliveryconfirmationstable">' . "\n";
				$firsttime = false;
			} 
			echo '									<tr>' . "\n";
			echo '										<td class="deliveryconfirmationsmessage">' . "\"{$records[3]}\" to {$records[5]}?</td>\n";
			echo '										<td class="deliveryconfirmationsbutton">' . "\n";
			createbutton('delivered','Delivered',$records[0]);
			echo '										</td>' . "\n";
			echo '										<td class="deliveryconfirmationsbutton">' . "\n";
			createbutton('deny','Deny',$records[0]);
			echo '										</td>' . "\n";
			echo '									</tr>' . "\n";
		}
		if(!$firsttime){
			echo '								</table>' . "\n";
			echo '						</div>' . "\n";
		}
	}
	# Books I have received
	function receiptconfirmations(){
		# Global variables
		# Only need to get the username from the server once.
		global $user, $noconfirmations;
		# Might need to create the table and print the table headers.
		$firsttime = true;
		$delivered = pg_query("SELECT * FROM detailedtransactions WHERE recipientid = '{$user}' AND transstatus = 'Delivered'") ;
			//or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($delivered)) {
			if ($firsttime) {
				if($noconfirmations){
					echo '				<div id="confirmationmessagearea" class="contentarea">' . "\n";
					$noconfirmations = false;
				}
				echo '						<div id="receiveconfirmations">' . "\n";
				echo '							<p class="header">Have you received ...</p>' . "\n";
				echo '								<table id="receiveconfirmationstable">' . "\n";
				$firsttime = false;
			}
			echo '									<tr>' . "\n";
			echo '										<td class="receiveconfirmationsmessage">' . "\"{$records[3]}\" from {$records[6]}?</td>\n";
			echo '										<td class="receiveconfirmationsbutton">' . "\n";
			createbutton('confirmdelivery','Received',$records[0]);
			echo '										</td>' . "\n";
			echo '									</tr>' . "\n";
		}
		if(!$firsttime){
			echo '								</table>' . "\n";
			echo '						</div>' . "\n";
		}
	}
	# Books someone has returned to me
	function returnconfirmations(){
		# Global variables
		# Only need to get the username from the server once.
		global $user, $noconfirmations;
		# Might need to create the table and print the table headers.
		$firsttime = true;
		$returned = pg_query("SELECT * FROM detailedtransactions WHERE myid = '{$user}' AND transstatus = 'Returned'") ;
			//or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($returned)) {
			if ($firsttime) {
				if($noconfirmations){
					echo '				<div id="confirmationmessagearea" class="contentarea">' . "\n";
					$noconfirmations = false;
				}
				echo '						<div id="returnconfirmations">' . "\n";
				echo '							<p class="header">Returns</p>' . "\n";
				echo '								<table id="returnconfirmationstable">' . "\n";
				$firsttime = false;
			} 
			echo '									<tr>' . "\n";
			echo '										<td class="returnconfirmationsmessage">Has ' . $records[5] . " returned \"{$records[3]}\"?</td>\n";
			echo '										<td class="returnconfirmationsbutton">' . "\n";
			createbutton('confirmreturnedbook','Returned',$records[0]);
			echo '										</td>' . "\n";
			echo '									</tr>' . "\n";
		}
		if(!$firsttime){
			echo '								</table>' . "\n";
			echo '						</div>' . "\n";
		}
	}
	# HTML to display system notifications
	function notifications(){
		$nonotifications = true;
		
		echo '				<div id="notificationmessagearea" class="contentarea">' . "\n";
		echo '					<div id="notifications">' . "\n";
		echo '							<p class="header">Notifications</p>' . "\n";
		echo '								<table id="notificationstable">' . "\n";
		echo '									<tr>' . "\n";
		echo '										<td class="notificationsmessage">BookEx is currently under maintenance. Some features may be temporarily unavailable.</td>' . "\n";
		echo '									</tr>' . "\n";
		echo '								</table>' . "\n";
		echo '					</div>' . "\n";
		echo '				</div>' . "\n";
	}
	# HTML to display the books that the user needs to return after they are done.
	function imborrowing(){
		# Global variables
		# Only need to get the username from the server once.
		global $user;
		# Might need to create the table and print the table headers.
		$firsttime = true;	
		$noborrowing = true;
		echo '				<div id="booksborrowedlist" class="contentarea">' . "\n";
		echo '					<p class="header">Books I\'m Borrowing</p>' . "\n";
		$returned = pg_query("SELECT * FROM detailedtransactions WHERE recipientid = '" . $user . "' AND transstatus = 'Received'") ;
			//or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($returned)) {
			if ($firsttime) {
				echo '						<table id="booksborrowingtable">' . "\n";
				echo '							<thead>' . "\n";
				echo '								<tr>' . "\n";
				echo '									<td class="booktitle header">Title</td>' . "\n";
				echo '									<td class="booklender header">Lender</td>' . "\n";
				echo '									<td class="bookduedate header">Due</td>' . "\n";
				echo '									<td class="bookreturnbutton"></td>' . "\n";
				echo '								</tr>' . "\n";
				echo '							</thead>' . "\n";
				$firsttime = false;
			} 
			echo '							<tr>' . "\n";
			# Book title
			echo '								<td class="booktitle"><a href="bookdetails.php?id='.$records[1].'">' . $records[3] . '</a></td>' . "\n";
			# Book owner
			echo '								<td class="booklender"><a href="profile.php?id='.$records[7].'">'.$records[6].'</a></td>' . "\n";
			# BookEx does not currently store a 'Due' date
			echo '								<td class="bookduedate">' . date("F j, Y"). '</td>' . "\n";
			echo '								<td class="bookreturnbutton">' . "\n";
			createbutton('return','Return',$records[0]);
			echo '								</td>' . "\n";
			echo '							</tr>' . "\n";
		}
		
		if($firsttime){
			echo '						<table id="booksborrowingtable">' . "\n";
			echo '							<tr>' . "\n";
			echo '								<td class="booktitle">You are currently not borrwing any books.</td>' . "\n";
			echo '							</tr>' . "\n";
		} 
		echo '						</table>' . "\n";
		echo '				</div>' . "\n";
		echo '			</div>' . "\n";
		echo '			<br />' . "\n";
		echo '		</div>' . "\n";
	}

    # Process requests that come from this page.
    # This is majority of the borrow and loaning process. Initial requests are the only thing missing.
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		# The user accepeted a book request.
		if(isset($_POST['acceptbookrequest'])){
			pg_query("SELECT acceptbookrequest('{$_POST['transid']}'::integer,'{$user}'::varchar)") ;
				//or die('Query failed: ' . pg_last_error()); 
		# The owner has delivered the book to the requestor			
		} else if (isset($_POST['ourstory'])){
			ourstory();
		} else if (isset($_POST['delivered'])){
			pg_query("SELECT deliverbook('{$_POST['transid']}'::integer,'{$user}'::varchar)") ;
				//or die('Query failed: ' . pg_last_error()); 
		# The requestor now has the book			
		} else if (isset($_POST['confirmdelivery'])){
			pg_query("SELECT confirmdelivery('{$_POST['transid']}'::integer,'{$user}'::varchar)") ;
				//or die('Query failed: ' . pg_last_error()); 
		# The user returned the book to the owner
		} else if (isset($_POST['return'])){
			pg_query("SELECT returnbooktoowner('{$_POST['transid']}'::integer,'{$user}'::varchar)") ;
				//or die('Query failed: ' . pg_last_error()); 
		# The user confirmed that the book was returned to them.				
		} else if (isset($_POST['confirmreturnedbook'])){
			pg_query("SELECT confirmreturnedbook('{$_POST['transid']}'::integer,'{$user}'::varchar)") ;
				//or die('Query failed: ' . pg_last_error()); 
		# The user canceled a book request.			
		} else if (isset($_POST['cancelrequest'])){
			pg_query("SELECT cancelrequest('{$_POST['transid']}'::integer,'{$user}'::varchar)") ;
				//or die('Query failed: ' . pg_last_error()); 
		# The user denied a book request.			
		} else if (isset($_POST['deny'])){
			pg_query("SELECT denybookrequest('{$_POST['transid']}'::integer,'{$user}'::varchar)") ;
				//or die('Query failed: ' . pg_last_error()); 
		} else if (isset($_POST['register'])){
			register_user();
		} else if (isset($_POST['dontregister'])){
			leave_bookex();
		} else if (isset($_POST['cancelbug'])){
			$errormessage = "Bug report canceled.";
		} else if (isset($_POST['sendbug'])){
			submitbug();
		}
	}
	include 'includes/dashboard_0_header.php';
	include 'includes/siteheader.php';
	echo '<body id="dashboard">';
	include 'includes/siteheader2.php';
	
	echo '		<div id="page">' . "\n";
	echo '			<div id="maincontent">' . "\n";
	echo '				<div class="pageTitle">Dashboard</div>' . "\n";
	if($errormessage != '')
		echo '				<div id="notification" class="show">' . $errormessage . '</div>' . "\n";
	
	# Display the things we want in the order we want them.
	myrequests();
	othersrequests();
	deliveryconfirmations();
	receiptconfirmations();
	returnconfirmations();
	if(!$noconfirmations){
		echo '				</div>' . "\n";
	}
	#echo '<div class="contentarea" style="text-align:center;"><img src="images/new-dashboard.png" alt="Graphical Dashboard" /></div>';
	# System notifications are always displayed. Might be the first if there is no activity for the current user.
	notifications();
	# From user testing, this is where we should have the book that people are going to return when they are done.
	# mybooks was not intuitive
	imborrowing();
	include 'includes/sitefooter.php';
	# Close the databasee
	pg_close($dbconn);
?>