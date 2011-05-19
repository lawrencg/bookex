<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: Add a book to the BookEx web application.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	include 'includes/session_track.php';
	# Database connection parameters
	require 'includes/database_info.php';
	# Force non-registered users to register or leave
	# Pages would break if a UW NetID accessed a page directly
	# without being a user
	require 'includes/valid_user.php';
	include 'includes/request_process.php';
	
	# GLOBAL VARIABLES
	# Used to see if this book is owned by the current UW NetID that is logged in
	$owner_id = trim(pg_escape_string($_POST['ownerid']));
	# BookEx id
	$bookex_id = trim(pg_escape_string($_POST['book_id']));
	$title = trim(pg_escape_string($_POST['title'])); 
	# Author name is in two fields. Different from addbook.php where the authors are stored in an array. 
	$authorfirst = trim(pg_escape_string($_POST['authorfirst'])); 
	$authorlast = trim(pg_escape_string($_POST['authorlast'])); 
	$isbn10 = trim(remove_non_numeric($_POST['isbn10'])); 
	$isbn13 = trim(remove_non_numeric($_POST['isbn13']));
	# The class that the owner said the book was for.
    $course = trim(pg_escape_string($_POST['course']));
    # This condition must come from values in the BookEx database.
	$cond = pg_escape_string($_POST['condition']);
	# See addbook.php for justification for this field. The user can edit this value to 
	# contain something like "Has coffee on the bottom of all the pages."
	$note = trim(pg_escape_string($_POST['description'])); 
	# Available or unavailable.
	$status = pg_escape_string($_POST['available']);
	
	# Accepts a string and returns that string with only numbers that the original string contained.
	# Used to strip dashes and spaces from ISBN's entered by the user.
	function remove_non_numeric($string) {
		return preg_replace('/\D/', '', $string);
	}
	# Uses global variables to update the users book instance in the BookEx database.
	# Outputs database errors directly.
	function updatebook(){
		global $bookex_id, $course, $cond, $note, $status;
		if($status == 'on'){
			$status = 'Available';
		} else {
			$status = 'Unavailable';
		}
		$user = $_SERVER['REMOTE_USER'];
		$books = pg_query("SELECT editbook('{$bookex_id}'::int,'{$user}'::varchar,'{$course}'::varchar,'{$cond}'::varchar,'{$note}'::text,
		'{$status}'::varchar)");// or die('Query failed: ' . pg_last_error()); 
	}
	# Accepts any valid BookEx peoples_books.id and retrieves the book information from database then stores them
	# in global variables for use later.
	function getfromBookEx($book_id){
		global $owner_id, $bookex_id, $isbn10, $isbn13, $title, $course, $cond, $authorfirst, $authorlast, $note, $status;
		# Connect to database
		$books = pg_query("SELECT * FROM bookdetails WHERE bookid='{$book_id}'");
			//or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($books)) {
			$owner_id = $records[0];
			$bookex_id = $book_id; 
			$title = $records[2];
			$authorfirst = $records[3];
			$authorlast = $records[4];
			$isbn10 = $records[5];
			$isbn13 = $records[6];
			$course = $records[7];
			$cond = $records[8];
			$note = $records[9];
			# Convert the status to a input check box value
			if($records[10] == 'Available'){
				$status	= 'checked';
			} else {
				$status = 'unchecked';
			}
		}

	}
	# HTML to display the book information that has been retreived from the BookEx database and stored in global variables.
	# Using hidden input forms to transfer the data between POST's.
	# We initally do not want users to be able to edit the text fields so 
	# they are displayed as text with a paired hidden field. You cannot POST text that is not in an input with a form.
	function filledform(){
		# Global variables, elimnates the need to pass so many around or manage an array.
		# Good practice??
		global $owner_id, $bookex_id, $isbn10, $isbn13, $title, $course, $cond, $authorfirst, $authorlast, $note, $status;
		# Convert the POST value of a checkbox.
		# Unnecessary??
		if($status == 'on'){
			$status	= 'checked';
		}
		displaybookimage();
		echo "<div class='twoformbuttons'>";		
		//<div><label>Course:</label><div>{$course}</div></div>
		
		echo "
		<form action='' id='defaultform' name='book' method='POST'>
			<input type='hidden' value='{$bookex_id}' id='book_id' name='book_id' />
			<input type='hidden' value='{$owner_id}' id='ownerid' name='ownerid' />			
			<div><label>Title:</label><div>{$title}</div></div>
			<input type='hidden' value='{$title}' id='title' name='title' />
			<div><label>Author First Name:</label><div>{$authorfirst}</div></div>
			<input type='hidden' value='{$authorfirst}' id='authorfirst' name='authorfirst' />			
			<div><label>Author Last Name:</label><div>{$authorlast}</div></div>
			<input type='hidden' value='{$authorlast}' id='authorlast' name='authorlast' />			
			<div><label>ISBN-10:</label><div>{$isbn10}</div></div>
			<input type='hidden' value='{$isbn10}' id='isbn10' name='isbn10' />
			<div><label>ISBN-13:</label><div>{$isbn13}</div></div>
			<input type='hidden' value='{$isbn13}' id='isbn13' name='isbn13' />
			
			<div><label>Course:</label><div></div></div>
			<input type='hidden' value='{$course}' id='course' name='course' />
			
			<div><label>Condition:</label><div><select name='dropdown' disabled><option value='{$cond}' selected='selected'>{$cond}</option></select></div></div>
			<input type='hidden' value='{$cond}' id='condition' name='condition' />			
			<div><label>Description:</label><div class='bookDescription'><textarea cols='40' rows='5' id='frame' name='description' style='vertical-align:text-top;' virtual disabled />{$note}</textarea></div></div>
			<input type='hidden' value='{$note}' id='description' name='description' />
			<div><label>Available for loan?:</label><div><input type='checkbox' id='box' name='box' {$status} disabled /></div></div>
			<input type='hidden' id='available' name='available' value='{$status}' /><br /><br />" ;
		
		# Security feature. Check to see if the owner is the UW NetID that is logged in.
		$user = $_SERVER['REMOTE_USER'];
		if($owner_id == $user){
			echo "<input type='submit' name='edit' value='Edit' style='margin-left:10px' />
			<input type='submit' name='delete' value='Delete Book' style='margin-left:10px' />";
		}		
	}
	# HTML used to edit a books details. Should only be able to access this function if the current UW NetID is the owner of the Book.
	# Using hidden input forms to transfer the data between POST's.
	# We do not want users to be able to edit some fields so 
	# they are displayed as text with a paired hidden field. You cannot POST text that is not in an input with a form.
	function editform(){
		# Global variables, elimnates the need to pass so many around or manage an array.
		# Good practice??
		global $owner_id, $bookex_id, $isbn10, $isbn13, $title, $course, $cond, $authorfirst, $authorlast, $note, $status;
		# Convert the POST value of a checkbox.
		# Unnecessary??
		if($status == 'on'){
			$status	= 'checked';
		}
		displaybookimage();
		echo "<div class='twoformbuttons'>";		
		echo " 
		<form action='' id='defaultform' name='book' method='POST'>
			<input type='hidden' value='{$bookex_id}' id='book_id' name='book_id' />
			<input type='hidden' value='{$owner_id}' id='ownerid' name='ownerid' />			
			<div><label>Title:</label><div>{$title}</div></div>
			<input type='hidden' value='{$title}' id='title' name='title' />
			<div><label>Author First Name:</label><div>{$authorfirst}</div></div>
			<input type='hidden' value='{$authorfirst}' id='authorfirst' name='authorfirst' />			
			<div><label>Author Last Name:</label><div>{$authorlast}</div></div>
			<input type='hidden' value='{$authorlast}' id='authorlast' name='authorlast' />			
			<div><label>ISBN-10:</label><div>{$isbn10}</div></div>
			<input type='hidden' value='{$isbn10}' id='isbn10' name='isbn10' />
			<div><label>ISBN-13:</label><div>{$isbn13}</div></div>
			<input type='hidden' value='{$isbn13}' id='isbn13' name='isbn13' />
			
			<div><label>Course:</label><div><input type='text' value='{$course}' id='course' name='course' /></div></div>
						
			<div><label>Condition:</label><div><select name='condition'>";
					
		
		//START CONDTION OPTIONS DROP DOWN
		$conditions = pg_query("SELECT * FROM condition ORDER BY rank") ;
			//or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($conditions)) {
			if($records[0] == $cond){
				echo "<option value='{$records[0]}' selected='selected'>{$records[0]}</option>";
			} else {
				echo "<option value='{$records[0]}'>{$records[0]}</option>";
			}
		}
		//END DROPDOWN //end condition
		echo "</select></div></div> 
		
		<div><label>Description:</label><div class='bookDescription'><textarea cols='40' rows='5' id='frame' name='description' style='vertical-align:text-top;'/>{$note}</textarea>" ;
		
		
		# Security feature. Check to see if the owner is the UW NetID that is logged in.
		$user = $_SERVER['REMOTE_USER'];
		if($owner_id == $user){
			//end description
			echo "</textarea></div></div> 
			
			<div><label>Available for loan?:</label><div><input type='checkbox' id='available' name='available' {$status} /></div></div>
			
			<input type='submit' name='save' value='Save' style='margin-left:10px' />
			<input type='submit' name='cancel' value='Cancel' style='margin-left:10px' />";
		}
		
	}
	# HTML used when the owner of the book wants to delete a book that is associated with their account.
	function deletebook(){
		# Global variables, elimnates the need to pass so many around or manage an array.
		# Good practice??
		global $owner_id, $bookex_id, $isbn10, $isbn13, $title, $course, $cond, $authorfirst, $authorlast, $note, $status;
		# Convert the POST value of a checkbox.
		# Unnecessary??
		if($status == 'on'){
			$status	= 'checked';
		}
		echo "
		<div id='page'>
				<div class='pageTitle'>Book Details</div>		
				<div id='notification' class='show' >WARNING: Removing a book cannot be undone. Are you sure you want to delete this book?</div>
				
				<div id='maincontent'>
					<div id='' class='contentarea'>
						<div class='leftContent'>
							<div id='bookImageContent'>
								<div id='bookImagePhoto'><img src='images/default-book.png' /></div>
							</div>
						</div>						
						<div class='rightContent contentarea'>";
						
		echo "<div class='twoformbuttons'>";				
		echo "				
		<form action='mybooks.php' id='defaultform' name='book' method='POST'>
			<input type='hidden' value='{$bookex_id}' id='book_id' name='book_id' />
			<input type='hidden' value='{$owner_id}' id='ownerid' name='ownerid' />			
			
			<div><label>Title:</label><div>{$title}</div></div>
			<input type='hidden' value='{$title}' id='title' name='title' />
			<div><label>Author First Name:</label><div>{$authorfirst}</div></div>
			<input type='hidden' value='{$authorfirst}' id='authorfirst' name='authorfirst' />			
			<div><label>Author Last Name:</label><div>{$authorlast}</div></div>
			<input type='hidden' value='{$authorlast}' id='authorlast' name='authorlast' />	
			<div><label>ISBN-10:</label><div>{$isbn10}</div></div>
			<input type='hidden' value='{$isbn10}' id='isbn10' name='isbn10' />
			<div><label>ISBN-13:</label><div>{$isbn13}</div></div>
			<input type='hidden' value='{$isbn13}' id='isbn13' name='isbn13' />
			<div><label>Course:</label><div>{$course}</div></div>
			<input type='hidden' value='{$course}' id='course' name='course' />
			<div><label>Condition:</label><div><select name='condition' disabled><option value='{$cond}' selected='selected'>{$cond}</option></select></div></div>
			<input type='hidden' value='{$cond}' id='condition' name='condition' />			
			<div><label>Description:</label><div class='bookDescription'><textarea cols='40' rows='5' id='frame' name='description' style='vertical-align:text-top;' virtual disabled />{$note}</textarea></div></div>
			<input type='hidden' value='{$note}' id='description' name='description' />
			<div><label>Available for loan?:</label><div><input type='checkbox' id='box' name='box' {$status} disabled /></div></div>
			<input type='hidden' id='available' name='available' value='{$status}' /><br /><br />" ;
		
		# Security feature. Check to see if the owner is the UW NetID that is logged in.
		$user = $_SERVER['REMOTE_USER'];
		if($owner_id == $user){
			echo "<div id='firstbutton'><input type='submit' name='confirmdelete' value='Delete' style='margin-left:10px' /></div>";
			echo "</form><form action='bookdetails.php' method='get'><div><input type='hidden' value='{$bookex_id}' id='id' name='id' /><input type='submit' name='cancel' value='Cancel' style='margin-left:10px' /></div>";
		}
	}
	function displaybookimage(){
		global $bookex_id, $owner_id, $user;
		echo '
				<div id="page">
				<div class="pageTitle">Book Details</div>
				<div id="maincontent">
					<div id="" class="contentarea">
						<div class="leftContent">
							<div id="bookImageContent">
								<div id="bookImagePhoto"><img src=\'images/default-book.png\' /></div>

							</div>';
		if($owner_id != $user){
				echo "<div class=\"detailsrequestbutton\">";
				request_button($bookex_id);
				echo "</div>";
		}

		echo '				</div>						
						<div class="rightContent contentarea">';	

	}
	# Default HTML
	
	include 'includes/bookdetails_0_header.php';
	include 'includes/siteheader.php';
	
	# Request method of GET means that the user followed a link to get to this page.
	if($_SERVER['REQUEST_METHOD'] == 'GET'){ 
		# Check to see if the id is set first
		if(isset($_GET['id'])){
			# Get the book from the BookEx database and set the global variables
			getfromBookEx($_GET['id']);
			# Display the filled form
			filledform();
		} else {
			filledform();
		}
	# The user was already on this page and is modifying a book
	} elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
		# The edit button was clicked. Allow the user to edit their book.
		# The edit button will not be displayed if the current UW NetID logged in does not match the 
		# owner of the book
		if(isset($_POST['edit'])){
			editform();
		# Some request was canceled, either an edit or a delete
		} elseif(isset($_POST['cancel'])){
			//getfromBookEx($_GET['id']);
			# Display the book information again
			getfromBookEx($_POST['book_id']);
			filledform();
		# The user wants to update the book information
		} elseif(isset($_POST['save'])){
			# Update the database
			updatebook();
			# Get the updated information from the database
			getfromBookEx($_POST['book_id']);
			# Display the books information
			filledform();
		# The user wants to delete the book
		} elseif(isset($_POST['delete'])){
			# Display the form to delete the book
			# Subsequent form will be processed by mybooks.php
			deletebook();
		} elseif(isset($_POST['request'])){
			//getfromBookEx($_GET['id']);
			# Display the book information again
			getfromBookEx($_POST['book_id']);
			filledform();
		# The user wants to update the book information
		}
	}
	# Close any form that was made. Need to make sure one was opened first.
	echo "</form></div></p>";
	include 'includes/bookdetails_2_contentarea.php';
	include 'includes/sitefooter.php';
	pg_close($dbconn);
?>