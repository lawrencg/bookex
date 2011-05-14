<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: Display a users books for the BookEx web application.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	include 'session_track.php';
	# Database connection parameters
	require 'database_info.php';
	require 'menu.php';
	include 'search.php';
	include 'greeting.php';
	
	# GLOBAL VARIABLES
	# Who knows what someone will try to POST
	$bookex_id = pg_escape_string($_POST['bookexid']);
	# Get the current UW NetID from the server via pubcookie
	$user = $_SERVER['REMOTE_USER'];

	# This POST came from the bookdetail.php page
	# This was the most logical place to drop users after they removed one of their books.
	if(isset($_POST['confirmdelete'])){
		# Connect to the database
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
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
				echo "<h2>ERROR: Your book could not be removed at this time. Please ensure that 
				the book available for others to borrow and that it is not currently loaned to someone.";
			} else {
				echo "<h2>Your book has been removed.</h2>";
			}
		}
		pg_close($dbconn);
	}

	# Connect to database
	$dbconn = pg_connect($DB_CONNECT_STRING)
	    or die('Could not connect: ' . pg_last_error());
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
			echo "<table border='2px'>";
			echo "<th>Title</th><th>Borrower</th><th>Due</th><th>Status</th>";
			# Book title is a link to book details for that book
			echo "<tr><td><a href='bookdetail.php?id={$records[0]}'>{$records[3]}</td>
			<td>{$borrow}</td>";
			# Decides what status to display. For loaned books we need to display the transaction status,
			# for books that are not loaned out, we need to show the availability status.
			if($records[6] == ''){
				echo "<td>&nbsp;</td><td>";
				echo $records[5];
			} else { 
				# The database does not store "Due" dates yet. This is just a place holder
				echo "<td>".date("F j, Y")."</td><td>";
				echo $records[6];
			}
			echo "</td></tr>";
			# Found one book, need to close the table tag at the end.
			$firsttime = false;
		} else {
			# Book title is a link to book details for that book
			echo "<tr><td><a href='bookdetail.php?id={$records[0]}'>{$records[3]}</td>
			<td>{$borrow}</td>";
			# Decides what status to display. For loaned books we need to display the transaction status,
			# for books that are not loaned out, we need to show the availability status.
			if($records[6] == ''){
				echo "<td>&nbsp;</td><td>";
				echo $records[5];
			} else { 
				# The database does not store "Due" dates yet. This is just a place holder
				echo "<td>".date("F j, Y")."</td><td>";
				echo $records[6];
			}
			echo "</td></tr>";
		}
	}
	# Close database connection
	pg_close($dbconn);	
	# Close the table if we added a book.	
	if(!$firsttime){
		echo "</table><br />";
	# User has no books associated with their BookEx account
	} else {
		echo "<p><i>&nbsp;&nbsp;&nbsp;You do not currently have any books added to your account.</i></p>"; 
	}
	# Here is an option for the user to do
	echo "<a href='addbook.php'>Add a Book</a>\n";
?>