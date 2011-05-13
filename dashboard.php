<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: The main user interface for the BookEx web application. This page displays all of the books that a user is invloced with, both lending and borrowing.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	include 'includes/session_track.php';
	# Database connection parameters
	require 'includes/database_info.php';

	
	$user = $_SERVER['REMOTE_USER'];
	$errormessage;
	
	function comments(){
	#	.button-container form,
	#	.button-container form div {
	#	    display: inline;
	#	}
	#	.button-container button {
	#	    display: inline;
	#	    vertical-align: middle;
	#	}
	#	<div class="button-container">
	#	    <form action="confirm.php" method="post">
	#		<div>
	#			<button type="submit">Confirm</button>
	#		</div>
	#	    </form>
	#	    <form action="cancel.php" method="post">
	#		<div>
	#			<button type="submit">Cancel</button>
	#		</div>
	#	    </form>
	#	</div>
	}
	# Accepts a BookEx book id, input button name, and button label to create different types of buttons.
	# All buttons submit back to this page.
	function register_user(){
		global $user, $errormessage;
		
		$firstname = trim(pg_escape_string($_POST['firstname']));
		$lastname = trim(pg_escape_string($_POST['lastname']));
		$email = trim(pg_escape_string($_POST['email']));
		$major = trim(pg_escape_string($_POST['major']));
		
		//$dbconn = pg_connect($DB_CONNECT_STRING)
	    //	or die('Could not connect: ' . pg_last_error());
		pg_query("SELECT addbookexuser('{$user}'::varchar,'{$firstname}'::varchar,
			'{$lastname}'::varchar,'{$email}'::varchar,'{$major}'::varchar)") or die('Query failed: ' . pg_last_error());
		$result = pg_query("SELECT getbookexname('{$user}')") or die('Query failed: ' . pg_last_error()); 
		$bookexname = pg_fetch_array($result);
			$errormessage = "Thank you, {$bookexname[0]}. You have just been registered.";
	}
	function leave_bookex(){
		include 'includes/denyregistration.php';
	}
	function createbutton($name, $label, $bookid){
		echo "<form action='' id='form_98' name='form_98' method='POST'>";
		echo "<input type='hidden' value='{$bookid}' id='transid' name='transid' />";
		echo "<input type='submit' id='{$name}' name='{$name}' value='{$label}' />";
		echo "</form>";	
	}
	# Books I have requested
	function myrequests(){
		# Global variables
		# Only need to get the username from the server once.
		global $user;
		# Might need to create the table and print the table headers.
		$firsttime = true;
		$yourequested = pg_query("SELECT * FROM detailedtransactions WHERE recipientid = '" . $user . "' AND transstatus = 'Requested'") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($yourequested)) {
			if ($firsttime){
				echo "<h3>Your Requests</h3>\n";
				echo "<p>You have requested to borrow \"{$records[3]}\" from {$records[6]}. ";
				createbutton('cancelrequest','Cancel',$records[0]);
				echo "</p>\n";
				$firsttime = false;
			} else {
				echo "<p>You have requested to borrow \"{$records[3]}\" from {$records[6]}. ";
				createbutton('cancelrequest','Cancel',$records[0]);
				echo "</p>\n";
			}
		}
	}
	# Books others have requested
	function othersrequests(){
		# Global variables
		# Only need to get the username from the server once.
		global $user;
		# Might need to create the table and print the table headers.
		$firsttime = true;
		$theyrequested = pg_query("SELECT * FROM detailedtransactions WHERE myid = '" . $user . "' AND transstatus = 'Requested'") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($theyrequested)) {
			if ($firsttime){
				echo "<h3>Others have Requested</h3>\n";
				echo "<p>{$records[5]} has requested to borrow \"{$records[3]}\" ";
				createbutton('accept','Accept',$records[0]);
				echo "&nbsp;";
				createbutton('deny','Deny',$records[0]);
				echo "</p>\n";
				$firsttime = false;
			} else {	
				echo "<p>{$records[5]} has requested to borrow \"{$records[3]}\" ";
				createbutton('acceptrequest','Accept',$records[0]);
				echo "&nbsp;";
				createbutton('deny','Deny',$records[0]);
				echo "</p>\n";
			}
		}
	}
	# Books I need to deliver
	function deliveryconfirmations(){
		# Global variables
		# Only need to get the username from the server once.
		global $user;
		# Might need to create the table and print the table headers.
		$firsttime = true;
		$awaiting = pg_query("SELECT * FROM detailedtransactions WHERE myid = '" . $user . "' AND transstatus = 'Awaiting Delivery'") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($awaiting)) {
			if ($firsttime) {
				echo "<h3>Delivery Confirmations</h3>\n";
				echo "<p>Have you delivered \"{$records[3]}\" to {$records[5]}? "; 
				createbutton('delivered','Delivered',$records[0]);
				echo "&nbsp;";
				createbutton('deny','Deny',$records[0]);
				echo "</p>\n";
				$firsttime = false;
			} else {
				echo "<p>Have you delivered \"{$records[3]}\" to {$records[5]}? "; 
				createbutton('delivered','Delivered',$records[0]);
				echo "&nbsp;";
				createbutton('deny','Deny',$records[0]);
				echo "</p>\n";
			}
		}
	}
	# Books I have received
	function receiptconfirmations(){
		# Global variables
		# Only need to get the username from the server once.
		global $user;
		# Might need to create the table and print the table headers.
		$firsttime = true;
		$delivered = pg_query("SELECT * FROM detailedtransactions WHERE recipientid = '" . $user . "' AND transstatus = 'Delivered'") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($delivered)) {
			if ($firsttime) {
				echo "<h3>Receipt Confimation</h3>\n";
				echo "<p>Have you received \"{$records[3]}\" from {$records[6]}?";
				createbutton('confirmdelivery','Received',$records[0]);
				echo "</p>\n";
				$firsttime = false;
			} else {
				echo "<p>Have you received \"{$records[3]}\" from {$records[6]}?";
				createbutton('confirmdelivery','Received',$records[0]);
				echo "</p>\n";
			}
		}
	}
	# Books someone has returned to me
	function returnconfirmations(){
		# Global variables
		# Only need to get the username from the server once.
		global $user;
		# Might need to create the table and print the table headers.
		$firsttime = true;
		$returned = pg_query("SELECT * FROM detailedtransactions WHERE myid = '" . $user . "' AND transstatus = 'Returned'") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($returned)) {
			if ($firsttime) {
				echo "<h3>Return Confirmations</h3>\n";
				echo "<p>Has {$records[5]} returned your \"{$records[3]}\" book?";
				createbutton('confirmreturnedbook','Returned',$records[0]);
				echo "</p>\n";
				$firsttime = false;
			} else {
				echo "<p>Has {$records[5]} returned your \"{$records[3]}\" book?";
				createbutton('confirmreturnedbook','Returned',$records[0]);
				echo "</p>\n";
			}
		}
	}
	# HTML to display system notifications
	function notifications(){
		echo "<h3>Notifications</h3>\n";
		echo "<p style='color:red'><i>&nbsp;&nbsp;&nbsp;BookEx is currently under maintenance. Some features may be temporarily unavailable."; 
		echo "</i></p>\n";
	}
	# HTML to display the books that the user needs to return after they are done.
	function imborrowing(){
		# Global variables
		# Only need to get the username from the server once.
		global $user;
		# Might need to create the table and print the table headers.
		$firsttime = true;	
		echo "<h3>Books I'm borrowing</h3>\n";
		$returned = pg_query("SELECT * FROM detailedtransactions WHERE recipientid = '" . $user . "' AND transstatus = 'Received'") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($returned)) {
			if ($firsttime) {
				echo "<table border='2px'>";
				echo "<th>Title</th><th>Lender</th><th>Due Date</th><th></th>";
				# Book title
				echo "<tr><td>{$records[3]}</td>";
				# Book owner
				echo "<td>{$records[6]}</td>";
				# BookEx does not currently store a 'Due' date
				echo "<td>".date("F j, Y")."</td><td>";
				createbutton('return','Return',$records[0]);
				echo "</td></tr>";
				$firsttime = false;
			} else {
				# Book title
				echo "<tr><td>{$records[3]}</td>";
				# Book owner
				echo "<td>{$records[6]}</td>";
				# BookEx does not currently store a 'Due' date
				echo "<td>".date("F j, Y")."</td><td>";
				createbutton('return','Return',$records[0]);
				echo "</td></tr>";
			}
		}
		if($firsttime){
			echo "<p><i>&nbsp;&nbsp;&nbsp;You are currently not borrwing and books.</i></p>"; 
		} else {
			echo "</table>";
		}
	}
	# Connect to database
	$dbconn = pg_connect($DB_CONNECT_STRING)
    		or die('Could not connect: ' . pg_last_error());
    # Process requests that come from this page.
    # This is majority of the borrow and loaning process. Initial requests are the only thing missing.
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
		# The user accepeted a book request.
		if(isset($_POST['accept'])){
			pg_query("SELECT acceptbookrequest('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
				or die('Query failed: ' . pg_last_error()); 
		# The owner has delivered the book to the requestor			
		} else if (isset($_POST['delivered'])){
			pg_query("SELECT deliverbook('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
				or die('Query failed: ' . pg_last_error()); 
		# The requestor now has the book			
		} else if (isset($_POST['confirmdelivery'])){
			pg_query("SELECT confirmdelivery('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
				or die('Query failed: ' . pg_last_error()); 
		# The user returned the book to the owner
		} else if (isset($_POST['return'])){
			pg_query("SELECT returnbooktoowner('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
				or die('Query failed: ' . pg_last_error()); 
		# The user confirmed that the book was returned to them.				
		} else if (isset($_POST['confirmreturnedbook'])){
			pg_query("SELECT confirmreturnedbook('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
				or die('Query failed: ' . pg_last_error()); 
		# The user canceled a book request.			
		} else if (isset($_POST['cancelrequest'])){
			pg_query("SELECT cancelrequest('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
				or die('Query failed: ' . pg_last_error()); 
		# The user denied a book request.			
		} else if (isset($_POST['deny'])){
			pg_query("SELECT denybookrequest('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
				or die('Query failed: ' . pg_last_error()); 
		} else if (isset($_POST['register'])){
			register_user();
		} else if (isset($_POST['dontregister'])){
			leave_bookex();
		}
	}
	include 'includes/siteheader.php';
	# Display the things we want in the order we want them.
	# All of these functions need a database connection.
	if(!isset($_POST['dontregister'])){
		myrequests();
		othersrequests();
		deliveryconfirmations();
		receiptconfirmations();
		returnconfirmations();
		# System notifications are always displayed. Might be the first if there is no activity for the current user.
		notifications();
		# From user testing, this is where we should have the book that people are going to return when they are done.
		# mybooks was not intuitive
		imborrowing();
		# Close the database
	}
	pg_close($dbconn);
?>
