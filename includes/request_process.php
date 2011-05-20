<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: Used to request a book from multiple locations of the BookEx web application.
	# MUST BE INCLUDED IN THE CORRECT SPOT. MUST BE AFTER DATABASE_INFO.PHP SO THAT THE 
	# GLOBAL VARABLES EXIST.
	
	# Accepts an integer that represents a BookEx book id
	# Displays a button in a form that POST's back to this files initiate_request() function.
	function request_button($bookid){
		# Get the current UW NetID from the server via pubcookie
		$user = $_SERVER['REMOTE_USER'];
		# Two possible conditions for the request button to be disabled.
		# Condition #1: It is not available, which means someone else has borrowed it OR the user has it marked 'Unavailable'
		#               Not sure how they would get to this request but never hurts to be sure.
		# Condition #2: The user has already requested the book. Prevents multiple requests to the owner.
		#               This should always happend right after the request is made and the page refreshes.
		# Condition 1
		$result = pg_query("SELECT verifystillavailable('{$bookid}'::integer)"); //or bookex_error_handler(pg_last_error()); //or die('Query failed: ' . pg_last_error()); 
		$stillavailable = pg_fetch_array($result);
		# Condition 2
		$result2 = pg_query("SELECT alreadyrequested('{$user}'::varchar,'{$bookid}'::integer)"); //or bookex_error_handler(pg_last_error()); // or die('Query failed: ' . pg_last_error()); 
		$alreadyrequested = pg_fetch_array($result2);
		# Closing the database was causing problems. Need to figure this out.
		//pg_close($dbconn);
		# The form. Might need some CSS to have two forms display buttons next to eachother.
		echo "<form action='' id='form_99' name='form_99' method='POST'>";
		# Condition 2 exists. This should be the true directly after a request is made and the page is POSTed to.
		if ($alreadyrequested[0] == t){
			echo "<input type='submit' id='notrequest' name='notrequest' value='Pending Request' class='requestbutton' disabled />\n";
		# Condition 1, then book is no longer available. Most places this should not be displayed. 
		# Concurrency issue only.
		} elseif ($stillavailable[0] == f){
			echo "<input type='submit' id='notrequest' name='notrequest' value='Not Available' class='requestbutton' disabled='disabled' />\n";
		# Good to go, request if you want.
		}else {
			echo "<input type='hidden' value='{$bookid}' id='book_id' name='book_id' class='requestbutton' />\n";
			echo "<input type='submit' id='request' name='request' value='Request' class='requestbutton' />\n";
		}
		# Close the form tag.	 
		echo "</form>";	
	}
	# Processes a book request. Accepts a valid BookEx book id and creates a new 
	# transaction for the current UW NetID
	function initiate_request($bookid){
		# Get the current UW NetID from the server via pubcookie
		$user = $_SERVER['REMOTE_USER'];
		# Creates an entry in the transactions table
		$results = pg_query("SELECT requestbook('{$bookid}'::integer,'{$user}'::varchar)"); 
			//or die('Query failed: ' . pg_last_error()); 
	}
	# Time to work, process this request
	if(isset($_POST['request']) && $_POST['book_id'] != null)
		initiate_request($_POST['book_id']);
?>
