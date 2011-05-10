<?php
	include 'menu.php';
	include "search.php";
	include 'greeting.php';

	$DATABASE = "larry_test";
	$DB_USER = "shanzha";
	$DB_PASSWORD = "lawrence";
	$DB_CONNECT_STRING = "host=vergil.u.washington.edu port=10450 dbname=" . $DATABASE . " user=" . $DB_USER . " password=" . $DB_PASSWORD;
	
	$bookex_id = pg_escape_string($_POST['bookexid']);
	$isbn10 = remove_non_numeric($_POST['isbn10']); 
	$isbn13 = remove_non_numeric($_POST['isbn13']); 
	$title = pg_escape_string($_POST['title']); 
	$authors = pg_escape_string($_POST['authors']); 
        $course = pg_escape_string($_POST['class']);	
	$note = pg_escape_string($_POST['description']); 
	$condition = pg_escape_string($_POST['condition']);
	$status = pg_escape_string($_POST['available']);
	$errormessage;

	function remove_non_numeric($string) {
		return preg_replace('/\D/', '', $string);
	}
	function initialsearch(){
		echo "<p>Please enter the ISBN-10 or ISBN-13 for your book.</p><input type='text' value='' id='isbn' name='isbn' size='30' />&nbsp;";
		echo "<input type='submit' name='addbooksearch' value='Search' />&nbsp;";
		echo "<input type='submit' name='manual' value='I don&#39;t have an ISBN' />";
	}
	function splitauthors($param1){
		global $authors;
		#remove leading spaces
		$authors = preg_replace('/,\ /',',',$param1);
		$authors = trim($authors);
		#separate based on commas
		$authors_array = explode(',',$authors);	
		#reassigning it to $authors_array didn't work
		$temp_array;
		foreach($authors_array AS $author){
			#empty authors from isbndb.com
			if( $author == '') break;
			#reverse is used to get the last space, should be the space before the last name
			$temp1 = strrev($author);
			# separate the last name from the rest
			$author = explode(' ',$temp1,2);
			$temp = "";
			foreach($author AS $name){
				#re-reverse
				$temp .= strrev($name) . " ";		
			}
			#extra spaces from the orginal string
			$temp_array[] = trim($temp);	
		}
		return $temp_array;
	}
	function authorfirstname($name){
		$array = explode(' ',$name,2);
		return $array[1];
	}
	function authorlastname($name){
		$array = explode(' ',$name,2);
		return $array[0];
	}
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
	function editform(){
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

	function filledform(){
		global $bookex_id, $title, $authors, $isbn10, $isbn13, $course, $note, $DB_CONNECT_STRING;
		echo"
		<input type='hidden' value='{$bookex_id}' id='bookexid' name='bookexid' />
		<b>Title:</b>&nbsp;" . $title . "<br /><br />
		<input type='hidden' value='{$title}' id='title' name='title' />
		<b>Author(s)</b><br / ><br /><input type='hidden' value='{$authors[0]}' id='authors' name='authors' />";
			foreach($authors AS $author){
				echo "<i>First name:</i>&nbsp;" . authorfirstname($author) . "<br />
				<i>Last name:</i>&nbsp;" . authorlastname($author) . "<br /><br />";
			}
		echo "<b>ISBN-10:</b>&nbsp;" .$isbn10 . "<br /><br />
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
	function getfromBookEx($post_isbn){
		global $bookex_id, $isbn10, $isbn13, $title, $authors, $note, $errormessage, $DB_CONNECT_STRING;
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$books = pg_query("SELECT * FROM findbook('{$post_isbn}'::numeric) as records(book_id int, isbn10 numeric, isbn13 numeric, title varchar, author text)") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($books)) {
			$bookex_id = $records[0]; 
			$isbn10 = $records[1];
			$isbn13 = $records[2];
			$title = $records[3];
			$authors = splitauthors($records[4]);
		}
		pg_close($dbconn);
	}
	function getfromISBNDB($post_isbn){
		global $isbn10, $isbn13, $title, $authors, $note, $errormessage;
		$url = "http://isbndb.com/api/books.xml?access_key=6YP3EDSJ&results=texts&index1=isbn&value1=" . $post_isbn;
		$doc = new DOMDocument();
		$doc->load($url);
		$books = $doc->getElementsByTagName( "BookData" );
		if($books->length > 0){
			$isbn10 = $books->item(0)->getAttribute('isbn');
			$isbn13 = $books->item(0)->getAttribute('isbn13');
			$titles = $books->item(0)->getElementsByTagName( "Title" );
			$title = $titles->item(0)->nodeValue;
			$authors = $books->item(0)->getElementsByTagName( "AuthorsText" );
			$authors_all = $authors->item(0)->nodeValue;
			$authors = splitauthors($authors_all);
			$notes = $books->item(0)->getElementsByTagName( "Notes" );
			$note = $notes->item(0)->nodeValue;
		} else {
			$errormessage =  "<p style='color:red;font-style:italic;font-size:12px'>Sorry, we could not locate the ISBN '" . $_POST['isbn'] . 
				"' in our database or on the interent.<br /> Please enter the book information manually or <a href='addbook.php' style='color:blue'>Search Again</a></p>";
		}
	}	
	function addbook($mode){
		global $bookex_id, $isbn10, $isbn13, $title, $authors, $note, $DB_CONNECT_STRING, $status, $condition, $course;
		if($status == 'on')
			$status = 'Available';
		else
			$status = 'Unavailable';
		if($note == '')
			$note = ' ';
		if($course == '')
			$course = ' ';
		if($isbn10 == '')
			$isbn10 = 1;
		if($isbn13 == '')
			$isbn13 = 1;
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$user = $_SERVER['REMOTE_USER'];
		if($mode == 0){
			pg_query("SELECT addbook('{$user}'::varchar,'{$bookex_id}'::int, '{$status}'::varchar,'{$course}'::varchar,'{$condition}'::varchar,'{$note}'::text)") 
				or die('Query failed: ' . pg_last_error()); 
		} elseif($mode == 1) {
			pg_query("SELECT addbook('{$user}'::varchar,'{$title}'::varchar,'" . authorfirstname($authors) . "'::varchar,'" . authorlastname($authors) . "'::varchar,'{$course}'::varchar,'{$condition}'::varchar,'{$note}'::text,{$isbn10}::numeric,{$isbn13}::numeric,'{$status}'::varchar)")
				or die('Query failed: ' . pg_last_error()); 
		}
		pg_close($dbconn);
	}
	echo "<p><form action='addbook.php' id='book' name='book' method='POST'>";
	if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
		if(isset($_POST['addbooksearch'])){
			if($_POST['isbn'] != ''){
				getfromBookEx(remove_non_numeric($_POST['isbn']));
			} else {
				$errormessage =  "<p style='color:red;font-style:italic;font-size:12px'>Sorry, you did not enter an ISBN.<br /> 
				Please enter the book information manually or <a href='addbook.php' style='color:blue'>Search Again</a></p>";
			}
			if($title == '' && $_POST['isbn'] != '')
				getfromISBNDB(remove_non_numeric($_POST['isbn']));
			if($title == ''){
				echo $errormessage;
				blankform();
			} else {	
				filledform();
			}
		} elseif (isset($_POST['manual'])){
			blankform();
		} elseif (isset($_POST['edit'])){
			editform();
		} elseif (isset($_POST['standardadd']) && $bookex_id != ''){
			addbook(0);	
			echo "<p>Your book has been added sucessfully.</p>";
			initialsearch();
		} elseif (isset($_POST['forceadd']) || (isset($_POST['standardadd']) && $bookex_id == '')){
			if(isset($_POST['forceadd'])){
				$temp = $_POST['author_lname'] . ' ' . $_POST['author_fname'];
				$authors = $temp;
			}
			addbook(1);
			echo "<p>Your book has been added sucessfully.</p>";
			initialsearch();
		}
	} else {
		initialsearch();
	}
	echo "</form></p>";
?>
