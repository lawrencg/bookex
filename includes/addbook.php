<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: Add a book to the BookEx web application.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	include 'session_track.php'
	# Database connection parameters
	include 'database_info.php';
	include 'menu.php';
	include 'search.php';
	include 'greeting.php';
	
	# GLOBAL VARIABLES
	# BookEx book id
	$bookex_id = pg_escape_string($_POST['bookexid']);
	$isbn10 = remove_non_numeric($_POST['isbn10']); 
	$isbn13 = remove_non_numeric($_POST['isbn13']);
	# Title of the book
	$title = pg_escape_string($_POST['title']); 
	# An array of author names.
	# Should be stored in reverse order.
	# ex. Authors Tom L. Welling and Mark Weiss
	# ("Welling","Tom L.","Weiss","Mark") 
	$authors = pg_escape_string($_POST['authors']); 
	# The course assoicated with this instance of the book.
	# Currently only one course can be assoicated to a book.
    $course = pg_escape_string($_POST['class']);
    # The description of this instance of the book.
    # Data from ISBNDB has a Summary field and Notes Field.
    # On initial tests, notes looked more promissing but Summary might actually
    # be a better choice. 
	$note = pg_escape_string($_POST['description']); 
	# The condition of this instance of the book. Must be choosen from 
	# values in the conditions table of the BookEx database.
	$condition = pg_escape_string($_POST['condition']);
	# The borrowing status of this instance, Available or Unavailable
	$status = pg_escape_string($_POST['available']);
	# Possible message to display.
	$errormessage;

	# Accepts a string and retuns the string modified to include ONLY numbers.
	# Used with the inital ISBN search if the user inputs spaces or dashes.
	function remove_non_numeric($string) {
		return preg_replace('/\D/', '', $string);
	}
	
	# Accepts a string of author names and retuns an array of author names in reverse pair order.
	# Used to normalize the author node from the ISBNDB XML response.
	# ex input: "Tom L. Welling, Mark Weiss, "
	# ex output: ("Welling Tom L.","Weiss Mark")
	function splitauthors($param1){
		global $authors;
		# Remove space after commas and before the next authors name.
		$authors = preg_replace('/,\ /',',',$param1);
		# Remove spaces
		$authors = trim($authors);
		# Create an array based on commas
		$authors_array = explode(',',$authors);	
		# Reassigning it to $authors_array didn't work
		$temp_array;
		foreach($authors_array AS $author){
			# Empty authors from isbndb.com
			if( $author == '') break;
			# Reverse is used to get the last space, should be the space before the last name
			# ex input: "Tom L. Welling"
			# ex output: "gnilleW .L moT"
			$temp1 = strrev($author);
			# Separate the last name from the rest
			# Hmm... made an array here.
			# ex: ("gnilleW",".L moT")
			$author = explode(' ',$temp1,2);
			$temp = "";
			foreach($author AS $name){
				# Re-reverse
				# ex: "Welling Tom L."
				$temp .= strrev($name) . " ";		
			}
			# Remove extra spaces from the orginal string
			$temp_array[] = trim($temp);	
		}
		# Magic
		# ex: ("Weiss Mark","Welling Tom L.")
		return $temp_array;
	}
	# Accepts a string and returns everything after the first space.
	# ex input: "Welling Tom L."
	function authorfirstname($name){
		$array = explode(' ',$name,2);
		# ex output: "Tom L."
		return $array[1];
	}
	# Accepts a string and returns everything after the first space.
	# ex input: "Welling Tom L."
	function authorlastname($name){
		$array = explode(' ',$name,2);
		# ex output: "Welling"
		return $array[0];
	}
	# HTML used for the initial ISBN search. 
	function initialsearch(){
		echo "<p>Please enter the ISBN-10 or ISBN-13 for your book.</p><input type='text' value='' id='isbn' name='isbn' size='30' />&nbsp;";
		echo "<input type='submit' name='addbooksearch' value='Search' />&nbsp;";
		echo "<input type='submit' name='manual' value='I don&#39;t have an ISBN' />";
	}
	# Accepts a numeric value and attempts to find a book in the BookEx database by ISBN-10 or ISBN-13
	# Sets global variables if a book is found.
	function getfromBookEx($post_isbn){
		# Global variables
		global $bookex_id, $isbn10, $isbn13, $title, $authors, $DB_CONNECT_STRING;
		# Connect to the database
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$books = pg_query("SELECT * FROM findbook('{$post_isbn}'::numeric) as records(book_id int, isbn10 numeric, isbn13 numeric, title varchar, author text)") 
			or die('Query failed: ' . pg_last_error()); 
		# Hopefully we found something. If more than one record is returned, only the 
		# last books infomation is saved.
		while($records = pg_fetch_array($books)) {
			$bookex_id = $records[0]; 
			$isbn10 = $records[1];
			$isbn13 = $records[2];
			$title = $records[3];
			# Creates an array from a string of names.
			$authors = splitauthors($records[4]);
		}
		# Close the database
		pg_close($dbconn);
	}
	# Accepts a numeric value and attempts to find a book from ISBNDB.com
	# Process the XML response from ISBNDB.com
	# Sets global variables if a book is found.
	function getfromISBNDB($post_isbn){
		# Global variables
		global $isbn10, $isbn13, $title, $authors, $note, $errormessage;
		# The Developer key used for Remote API access to ISBNDB.com
		# I have two keys for access, each has 500 queries per day.
		# https://isbndb.com/account
		# username: shanzha@uw.edu
		# password: poop13
		$isbndb_key = 'HC8XH63Q';
		# The url for accessing ISBNDB
		$url = "http://isbndb.com/api/books.xml?access_key={$isbndb_key}&results=texts&index1=isbn&value1={$post_isbn}";
		$doc = new DOMDocument();
		$doc->load($url);
		$books = $doc->getElementsByTagName('BookData');
		if($books->length > 0){
			# Some values are in attributes and some are in text nodes. Lame.
			$isbn10 = $books->item(0)->getAttribute('isbn');
			$isbn13 = $books->item(0)->getAttribute('isbn13');
			$titles = $books->item(0)->getElementsByTagName('Title');
			$title = $titles->item(0)->nodeValue;
			$authors = $books->item(0)->getElementsByTagName('AuthorsText');
			$authors_all = $authors->item(0)->nodeValue;
			$authors = splitauthors($authors_all);
			$notes = $books->item(0)->getElementsByTagName('Notes');
			# Again, notes were initially choosen over the Summary for a BookEx book description.
			$note = $notes->item(0)->nodeValue;
		# Usually a call to BookEx is first then if nothing is found, a call to ISBNDB. If nothing is returned from ISBNDB, we let the user know
		# that they will need to enter the book information manually.
		} else {
			$errormessage =  "<p style='color:red;font-style:italic;font-size:12px'>Sorry, we could not locate the ISBN '" . $_POST['isbn'] . 
				"' in our database or on the interent.<br /> Please enter the book information manually or <a href='addbook.php' style='color:blue'>Search Again</a></p>";
		}
	}
	# HTML used to display a populated form with book information.
	# Using hidden input forms to transfer the data between POST's.
	# We initally do not want users to be able to edit the text fields so 
	# they are displayed as text with a paired hidden field. You cannot POST text that is not in an input with a form.
	function filledform(){
		# Global variables
		global $bookex_id, $title, $authors, $isbn10, $isbn13, $course, $note, $DB_CONNECT_STRING;
		echo"
		<input type='hidden' value='{$bookex_id}' id='bookexid' name='bookexid' />
		<b>Title:</b>&nbsp;" . $title . "<br /><br />
		<input type='hidden' value='{$title}' id='title' name='title' />
		<b>Author(s)</b><br / ><br />";
		# Displays multiple authors if there are more than one. Only the first author will be passed in a post.
			foreach($authors AS $author){
				echo "<i>First name:</i>&nbsp;" . authorfirstname($author) . "<br />
				<i>Last name:</i>&nbsp;" . authorlastname($author) . "<br /><br />";
			}
		echo "<input type='hidden' value='{$authors[0]}' id='authors' name='authors' />
		<b>ISBN-10:</b>&nbsp;" .$isbn10 . "<br /><br />
		<input type='hidden' value='{$isbn10}' id='isbn10' name='isbn10' />
		<b>ISBN-13:</b>&nbsp;" .$isbn13 . "<br /><br />
		<input type='hidden' value='{$isbn13}' id='isbn13' name='isbn13' />
		<b>Class:</b>&nbsp;<input type='text' value='' id='course' name='class' size='8' /><br /><br />
		<b>Condition:</b>&nbsp;<select name='condition'>";
		//START CONDTION OPTIONS DROP DOWN
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$conditions = pg_query("SELECT * FROM condition ORDER BY rank") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($conditions)) {
			# The default value for books being added to BookEx are assumed to be "Used"
			if($records[0] == 'Used'){
				echo "<option value='$records[0]' selected='selected'>" . $records[0] . "</option>";
			} else {
				echo "<option value='$records[0]'>" . $records[0] . "</option>";
			}
		}
		pg_close($dbconn);
		//END DROPDOWN
		echo "</select><br /><br />
		<b>Description:</b>&nbsp;<textarea cols='40' rows='5' id='description' name='description' style='vertical-align:text-top;' virtual />" . $note . 
		"</textarea><br /><br />
		<b>Make book available for others to borrow.</b>&nbsp;<input type='checkbox' id='available' name='available' checked/><br /><br />";
		echo "<input type='submit' name='standardadd' value='Add to My Books' style='margin-left:200px' />";
		echo "<input type='submit' name='edit' value='Edit Information' style='margin-left:10px' />";
	}
	# HTML used to edit a form populated with book information.
	function editform(){
		# Global variables
		global $bookex_id, $title, $authors, $isbn10, $isbn13, $course, $note, $DB_CONNECT_STRING;
		echo"
		<input type='hidden' value='{$bookex_id}' id='bookexid' name='bookexid' />
		<b>Title (required):</b>&nbsp;<input type='text' value='" . $title . "' id='title' name='title' size='40' /><br /><br />
		<b>Author</b><br / ><br /><i>First name:</i>&nbsp;<input type='text' value='" . authorfirstname($authors) . 
		"' id='author_fname' name='author_fname' size='30' /><br />
		<i>Last name:</i>&nbsp;<input type='text' value='" . authorlastname($authors) . "' id='author_lname' name='author_lname' size='30' /><br /><br /><br />
		<b>ISBN-10:</b>&nbsp;<input type='text' value='" . $isbn10 . "' id='isbn10' name='isbn10' size='13' /><br /><br />
		<b>ISBN-13:</b>&nbsp;<input type='text' value='" . $isbn13 . "' id='isbn13' name='isbn13' size='13' /><br /><br />
		<b>Class:</b>&nbsp;<input type='text' value='" . $course . "' id='class' name='class' size='8' /><br /><br />
		<b>Condition:</b>&nbsp;<select name='condition'>";
		//START CONDTION OPTIONS DROP DOWN
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$conditions = pg_query("SELECT * FROM condition ORDER BY rank") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($conditions)) {
			if($records[0] == 'Used'){
				echo "<option value='$records[0]' selected='selected'>" . $records[0] . "</option>";
			} else {
				echo "<option value='$records[0]'>" . $records[0] . "</option>";
			}
		}
		pg_close($dbconn);
		//END DROPDOWN
		echo "</select><br /><br />
		<b>Description:</b>&nbsp;<textarea cols='40' rows='5' id='description' name='description' style='vertical-align:text-top;' virtual />" . $note . 
		"</textarea><br /><br />
		<b>Make book available for others to borrow.</b>&nbsp;<input type='checkbox' id='available' name='available' checked/><br /><br />
		<input type='submit' name='forceadd' value='Add to My Books' style='margin-left:200px' />";
	}
	# HTML used to manually add a book to BookEx
	function blankform(){
		global $DB_CONNECT_STRING;
		echo"
		<b>Title (required):</b>&nbsp;<input type='text' value='' id='title' name='title' size='40' /><br /><br />
		<b>Author</b><br / ><br /><i>First name:</i>&nbsp;<input type='text' value='' id='author_fname' name='author_fname' size='30' /><br />
		<i>Last name:</i>&nbsp;<input type='text' value='' id='author_lname' name='author_lname' size='30' /><br /><br /><br />
		<b>ISBN-10:</b>&nbsp;<input type='text' value='' id='isbn10' name='isbn10' size='13' /><br /><br />
		<b>ISBN-13:</b>&nbsp;<input type='text' value='' id='isbn13' name='isbn13' size='13' /><br /><br />
		<b>Class:</b>&nbsp;<input type='text' value='' id='course' name='class' size='8' /><br /><br />
		<b>Condition:</b>&nbsp;<select name='condition'>";
		//START CONDTION OPTIONS DROP DOWN
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$conditions = pg_query("SELECT * FROM condition ORDER BY rank") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($conditions)) {
			if($records[0] == 'Used'){
				echo "<option value='$records[0]' selected='selected'>" . $records[0] . "</option>";
			} else {
				echo "<option value='$records[0]'>" . $records[0] . "</option>";
			}
		}
		pg_close($dbconn);
		//END DROPDOWN
		echo "</select><br /><br />
		<b>Description:</b>&nbsp;<textarea cols='40' rows='5' id='description' name='description' style='vertical-align:text-top;' virtual /></textarea><br /><br />
		<b>Make book available for others to borrow.</b>&nbsp;<input type='checkbox' id='available' name='available' checked/><br /><br />
		<input type='submit' id='forceadd' name='forceadd' value='Add to My Books' style='margin-left:200px' />";
	}
	# Adds an instance of a book to the BookEx users account.
	# Accepts an integer value as the mode. Mode indicates if the book was already in the BookEx database 
	# or if this book is a new addition, either from ISBNDB or a manual entry.
	# Mode 0 = A new instance of an existing BookEx book.
	# Mode 1 = A completely new BookEx book.
	function addbook($mode){
		global $bookex_id, $isbn10, $isbn13, $title, $authors, $note, $DB_CONNECT_STRING, $status, $condition, $course;
		# Convert the input checkbox to a value in the database.
		if($status == 'on')
			$status = 'Available';
		else
			$status = 'Unavailable';
		# Prevent two single quotes from being entered into the database.
		if($note == '')
			$note = ' ';
		if($course == '')
			$course = ' ';
		if($isbn10 == '')
			$isbn10 = 1;
		if($isbn13 == '')
			$isbn13 = 1;
		# Connect to the database
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$user = $_SERVER['REMOTE_USER'];
		# Minimal information is needed if the book already exists in BookEx
		if($mode == 0){
			pg_query("SELECT addbook('{$user}'::varchar,'{$bookex_id}'::int, '{$status}'::varchar,'{$course}'::varchar,'{$condition}'::varchar,'{$note}'::text)") 
				or die('Query failed: ' . pg_last_error()); 
		# Need all of the information associated with a book to create it in BookEx
		} elseif($mode == 1) {
			pg_query("SELECT addbook('{$user}'::varchar,'{$title}'::varchar,'" . authorfirstname($authors) . "'::varchar,'" . authorlastname($authors) . "'::varchar,'{$course}'::varchar,'{$condition}'::varchar,'{$note}'::text,{$isbn10}::numeric,{$isbn13}::numeric,'{$status}'::varchar)")
				or die('Query failed: ' . pg_last_error()); 
		}
		# Close databse connection.
		pg_close($dbconn);
	}
	# The actual page.
	# Start the form, empty action POST's or GET's to itself.
	# http://www.whatwg.org/specs/web-apps/current-work/multipage/association-of-controls-and-forms.html#form-submission-algorithm
	echo "<p><form action='' id='book' name='book' method='POST'>";
	# This is a POST
	if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
		if(isset($_POST['addbooksearch'])){
			# Try to find the book in BookEx
			if($_POST['isbn'] != ''){
				getfromBookEx(remove_non_numeric($_POST['isbn']));
			# Empty search
			} else {
				$errormessage =  "<p style='color:red;font-style:italic;font-size:12px'>Sorry, you did not enter an ISBN.<br /> 
				Please enter the book information manually or <a href='addbook.php' style='color:blue'>Search Again</a></p>";
			}
			# Check the title, empty means that BookEx did not return any results.
			if($title == '' && $_POST['isbn'] != '')
				getfromISBNDB(remove_non_numeric($_POST['isbn']));
			# The search from ISBNDB truned up nothing as well. Manual entry is necessary.
			if($title == ''){
				echo $errormessage;
				blankform();
			# The title is not blank so we can assume that something was found and we can fill the form.
			} else {	
				filledform();
			}
		# A manual entry method choosen by the user from the intial search box.
		} elseif (isset($_POST['manual'])){
			blankform();
		# The Edit button is only visible when a filled form is created, aka book information was found in BookEx or ISBNDB
		# The user wants to edit some of it for their copy. This will eventually create a new book in the database.
		} elseif (isset($_POST['edit'])){
			editform();
		# User accepted the book that was found in the BookEx database. This is the ideal addbook
		} elseif (isset($_POST['standardadd']) && $bookex_id != ''){
			# New instance
			addbook(0);	
			# Message to the user, confirmation. PHP will error horribly if something fails. 
			# Might want to catch exceptions in future versions.
			echo "<p>Your book has been added sucessfully.</p>";
			# Add another book
			initialsearch();
		# The Forceadd button is visible when the user wants to edit an existing book or when the book was 
		# not in the BookEx database. The standardadd will be set when a book was found in BookEx or ISBNDB but 
		# the bookex_id will be empty when the book came from ISBNDB. Same as a forceadd, the book is new
		# to the BookEx database.
		} elseif (isset($_POST['forceadd']) || (isset($_POST['standardadd']) && $bookex_id == '')){
			# This is a manual add and the addbook function requrires an array of author names.
			# Create one here.
			if(isset($_POST['forceadd'])){
				$temp = $_POST['author_lname'] . ' ' . $_POST['author_fname'];
				$authors = $temp;
			}
			# Completely new book
			addbook(1);
			# Message to the user, confirmation. PHP will error horribly if something fails. 
			# Might want to catch exceptions in future versions.
			echo "<p>Your book has been added sucessfully.</p>";
			# Add another book
			initialsearch();
		}
	# This page was not accessed via a POST. Could mean a GET but we are not checking for that. Means that this is a new book add.
	} else {
		initialsearch();
	}
	#Close the form. Might need to move this later. Maybe not.
	echo "</form></p>";
?>