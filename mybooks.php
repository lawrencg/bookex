<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: Display a users books for the BookEx web application.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	include 'includes/session_track.php';
	# Database connection parameters
	require 'includes/database_info.php';
	# Force non-registered users to register or leave
	# Pages would break if a UW NetID accessed a page directly
	# without being a user
	require 'includes/valid_user.php';
	
	# GLOBAL VARIABLES
	# Who knows what someone will try to POST
	$bookex_id = pg_escape_string($_POST['bookexid']);
	# Get the current UW NetID from the server via pubcookie
	$user = $_SERVER['REMOTE_USER'];
	$errormessage;

	# This POST came from the bookdetail.php page
	# This was the most logical place to drop users after they removed one of their books.
	if(isset($_POST['confirmdelete'])){
		$result = pg_query("SELECT removebook('{$bookex_id}'::integer, '{$user}'::varchar)") 
			or die('Query failed: ' . pg_last_error()); 
		# removebook() returns a boolean
		# Users can only remove a book that is not currently loaned out to someone else 
		# OR is not available for others to borrow. They must set it to available first then 
		# remove it. This is caused by a reuse of the funciton isstillavailable(bookid) in the 
		# removebook function. We don't want books to disappear that are accidentally removed when
		# someone is currently borrowing it.
		while($records = pg_fetch_array($result)) {
			if($records[0] == f){
				$erromessage = 'ERROR: Your book could not be removed at this time. Please ensure that 
				the book available for others to borrow and that it is not currently loaned to someone.';
			} else {
				$errormessage = 'Your book has been removed.';
			}
		}
	}

	function displaybooks(){
		global $user;
		$books = pg_query("SELECT * FROM getmybooks('{$user}') VALUES (bookid int, owner varchar, 
			date timestamp, title varchar, borrower varchar, available varchar, transstatus varchar)") 
			or die('Query failed: ' . pg_last_error()); 
		# Used a a flag to see if there are actually any books to display. Create a header if yes, and at the end
		# only close the table if we created one.
		$firsttime = true;
		# Reuslts are displayed in reverse chronological order. The most newly added books are displayed first. 
		# Hopefully this was a good choice over alphabetical. Good: if a user has 500 books, most likely they are going to
		# want to see the books they most recently added. Bad: The have 500 books and want to find a book with a title 
		# that starts with "B"
		while($records = pg_fetch_array($books)) {
			# Create a blank space for the borrower column if the book is not currently loaned
			if($records[4] == "")
				$borrow = '&nbsp;';
			else 
				$borrow = $records[4];
			if ($firsttime) {
				echo '						<table id="mybooklisttable">' . "\n";
				echo '							<thead>' . "\n";
				echo '								<tr>' . "\n";
				echo '									<td class="header">Title</td>' . "\n";
				echo '									<td class="header">Borrower</td>' . "\n";
				echo '									<td class="header">Due</td>' . "\n";
				echo '									<td class="header">Status</td>' . "\n";
				echo '								</tr>' . "\n";
				echo '							</thead>' . "\n";
				echo '							<tbody>' . "\n";
				$firsttime = false;
			}
			
			echo '								<tr>' . "\n";
			# Book title is a link to book details for that book
			echo '									<td class="booktitle"><a href="bookdetails.php?id='.$records[0].'">'.$records[3].'</a></td>' . "\n";
			echo '									<td class="booklender">'.$borrow.'</td>' . "\n";
			# Decides what status to display. For loaned books we need to display the transaction status,
			# for books that are not loaned out, we need to show the availability status.
			if($records[6] == ''){
				echo '									<td class="bookduedate">&nbsp;</td>' . "\n";
				echo '									<td class="bookstatus">'.$records[5].'</td>' . "\n";				
			} else {
				echo '									<td class="bookduedate">'.date("F j, Y").'</td>' . "\n";
				if($records[6] == 'Received'){
					echo '									<td class="bookstatus">Loaned Out</td>' . "\n";
				} else {
					echo '									<td class="bookstatus">'.$records[6].'</td>' . "\n";
				}
			}
			echo '								</tr>';
		}

		if($firsttime){
						echo '						<table id="mybooklisttable">' . "\n";
						echo '							<tbody>' . "\n";	
						echo '								<tr>' . "\n";	
						echo '									<td class="booktitle">You do not currently have any books added to your account.</td>' . "\n";
						echo '								</tr>' . "\n";
		}

		echo '							</tbody>' . "\n";
		echo '						</table>' . "\n";


	}
	function addbooksbutton(){		
		echo'				<form action="addbook.php" method="GET">' . "\n";
		echo'					<input type="submit" class="actionButton" value="Add New Book"/>' . "\n";
		echo'				</form>' . "\n";
		echo'			</div>' . "\n";
		echo'		</div>' . "\n";
		echo'	</div>' . "\n";
	}
	## MAIN SITE DISPLAY
	include 'includes/mybooks_0_header.php';
	include 'includes/siteheader.php';
	echo '		<div id="page">' . "\n";
	echo '			<div id="maincontent">' . "\n";
	echo '				<div class="pageTitle">My Books</div>' . "\n";
	
	if($errormessage != ''){
		echo '				<div id="notification" class="show">'.$errormessage.'</div>' . "\n";
	}
	
	echo '			<div id="mybooklistarea" class="contentarea">' . "\n";
	echo '					<div id="mybooklist">' . "\n";
	echo '						<div class="pageSubTitle">Books I Own</div>' . "\n";
	
	displaybooks();
	echo '				</div>' . "\n";
	addbooksbutton();
	include 'includes/sitefooter.php';
	# Close the database
	pg_close($dbconn);
?>