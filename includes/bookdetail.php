<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 10, 2011
	# Title: Add a book to the BookEx web application.
	
	# menu.php include must be before any HTML. The PHP session can only be started before any HTML is output.
	# Might need to change this configuration later becasue menu.php will not be the first include. 
	# menu.php was the easiest palce to start the session globally so that bug submission could have a previous page URL.
	include 'menu.php';
	# Database connection parameters
	include 'database_info.php';
	include 'greeting.php';
	
	$owner_id = pg_escape_string($_POST['ownerid']);
	$bookex_id = pg_escape_string($_POST['bookexid']);
	$title = pg_escape_string($_POST['title']); 
	$authorfirst = pg_escape_string($_POST['authorfirst']); 
	$authorlast = pg_escape_string($_POST['authorlast']); 
	$isbn10 = remove_non_numeric($_POST['isbn10']); 
	$isbn13 = remove_non_numeric($_POST['isbn13']); 
    $course = pg_escape_string($_POST['course']);	
	$cond = pg_escape_string($_POST['condition']);
	$note = pg_escape_string($_POST['description']); 
	$status = pg_escape_string($_POST['available']);

	function remove_non_numeric($string) {
		return preg_replace('/\D/', '', $string);
	}
	function deletebook(){
		global $owner_id, $bookex_id, $isbn10, $isbn13, $title, $course, $cond, $authorfirst, $authorlast, $note, $status, $DB_CONNECT_STRING;
		if($status == 'on')
			$status	= 'checked';
		echo "<p><form action='mybooks.php' id='book' name='book' method='POST'>
		<h2>Are you sure you want to remove this book from your BookEx account?<br />This cannot be undone.</h2>
		<input type='hidden' value='{$bookex_id}' id='bookexid' name='bookexid' />
		<b>Title:</b>&nbsp;{$title}<br />
		<b>Author Firstname</b>:&nbsp;{$authorfirst}<br /> 
		<b>Author Lastname</b>:&nbsp;{$authorlast}<br /> ";
		echo "<b>ISBN-10:</b>&nbsp;{$isbn10}<br />
		<b>ISBN-13:</b>&nbsp;{$isbn13}<br />
		<b>Course:</b>&nbsp;{$course}<br />
		<b>Condition:</b>&nbsp;<select name='dropdown' disabled>
		<option value='{$cond}' selected='selected'>{$cond}</option></select><br /><br />
		<b>Description:</b>&nbsp;
		<textarea cols='40' rows='5' id='frame' name='frame' style='vertical-align:text-top;' virtual disabled />{$note}</textarea><br /><br />
		<b>Available for loan?</b>&nbsp;<input type='checkbox' id='box' name='box' {$status} disabled />";
		$user = $_SERVER['REMOTE_USER'];
		if($owner_id == $user){
			echo "<input type='submit' name='confirmdelete' value='Delete' style='margin-left:10px' />";
			echo "<input type='submit' name='cancel' value='Cancel' style='margin-left:10px' />";
		}
	}
	function editform(){
		global $owner_id, $bookex_id, $isbn10, $isbn13, $title, $course, $cond, $authorfirst, $authorlast, $note, $status, $DB_CONNECT_STRING;
		if($status == 'on')
			$status	= 'checked';
		echo "<p><form action='' id='book' name='book' method='POST'>
		<input type='hidden' value='{$bookex_id}' id='bookexid' name='bookexid' />
		<input type='hidden' value='{$owner_id}' id='ownerid' name='ownerid' />
		<b>Title:</b>&nbsp;" . $title . "<br />
		<input type='hidden' value='{$title}' id='title' name='title' />
		<b>Author Firstname</b>:&nbsp;{$authorfirst . "<br /> 
		<input type='hidden' value='{$authorfirst}' id='authorfirst' name='authorfirst' />
		<b>Author Lastname</b>:&nbsp;{$authorlast . "<br /> 
		<input type='hidden' value='{$authorlast}' id='authorlast' name='authorlast' /><b>ISBN-10:</b>&nbsp;" .$isbn10 . "<br />
		<input type='hidden' value='{$isbn10}' id='isbn10' name='isbn10' />
		<b>ISBN-13:</b>&nbsp;" .$isbn13 . "<br />
		<input type='hidden' value='{$isbn13}' id='isbn13' name='isbn13' />
		<b>Course:</b>&nbsp;<input type='text' value='{$course}' id='course' name='course' size='8' /><br /><br />
		<b>Condition:</b>&nbsp;<select name='condition'>";
		//START CONDTION OPTIONS DROP DOWN
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$conditions = pg_query("SELECT * FROM condition ORDER BY rank") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($conditions)) {
			if($records[0] == $cond){
				echo "<option value='$records[0]' selected='selected'>{$records[0] . "</option>";
			} else {
				echo "<option value='$records[0]'>{$records[0] . "</option>";
			}
		}
		pg_close($dbconn);
		//END DROPDOWN
		echo "</select><br /><br />
		<b>Description:</b>&nbsp;<textarea cols='40' rows='5' id='description' name='description' style='vertical-align:text-top;' virtual />";
		echo $note;
		echo "</textarea><br /><br />
		<b>Available for loan?</b>&nbsp;<input type='checkbox' id='available' name='available' {$status} /><br /><br />
		<input type='submit' name='save' value='Save' style='margin-left:10px' />
		<input type='submit' name='cancel' value='Cancel' style='margin-left:10px' />";
	}
	function filledform(){
		global $owner_id, $bookex_id, $isbn10, $isbn13, $title, $course, $cond, $authorfirst, $authorlast, $note, $status, $DB_CONNECT_STRING;
		if($status == 'on')
			$status	= 'checked';
		echo "<p><form action='' id='book' name='book' method='POST'>
		<input type='hidden' value='{$bookex_id}' id='bookexid' name='bookexid' />
		<input type='hidden' value='{$owner_id}' id='ownerid' name='ownerid' />
		<b>Title:</b>&nbsp;{$title}<br />
		<input type='hidden' value='{$title}' id='title' name='title' />
		<b>Author Firstname</b>:&nbsp;{$authorfirst}<br /> 
		<input type='hidden' value='{$authorfirst}' id='authorfirst' name='authorfirst' />
		<b>Author Lastname</b>:&nbsp;{$authorlast}<br /> 
		<input type='hidden' value='{$authorlast}' id='authorlast' name='authorlast' />
		<b>ISBN-10:</b>&nbsp;{$isbn10}<br />
		<input type='hidden' value='{$isbn10}' id='isbn10' name='isbn10' />
		<b>ISBN-13:</b>&nbsp;{$isbn13}<br />
		<input type='hidden' value='{$isbn13}' id='isbn13' name='isbn13' />
		<b>Course:</b>&nbsp;{$course}<br />
		<input type='hidden' value='{$course}' id='course' name='course' />
		<b>Condition:</b>&nbsp;<select name='dropdown' disabled><option value='$cond' selected='selected'>{$cond}</option></select><br /><br />
		<input type='hidden' value='{$cond}' id='condition' name='condition' />
		<b>Description:</b>&nbsp;
		<textarea cols='40' rows='5' id='frame' name='frame' style='vertical-align:text-top;' virtual disabled />{$note}</textarea><br /><br />
		<input type='hidden' value='{$note}' id='description' name='description' />
		<b>Available for loan?</b>&nbsp;<input type='checkbox' id='box' name='box' {$status} disabled />
		<input type='hidden' id='available' name='available' value='{$status}' /><br /><br />";
		$user = $_SERVER['REMOTE_USER'];
		if($owner_id == $user){
			echo "<input type='submit' name='edit' value='Edit' style='margin-left:10px' />";
			echo "<input type='submit' name='delete' value='Delete Book' style='margin-left:10px' />";
		}
	}
	function getfromBookEx($bookexid){
		global $owner_id, $bookex_id, $isbn10, $isbn13, $title, $course, $cond, $authorfirst, $authorlast, $note, $status, $DB_CONNECT_STRING;
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$books = pg_query("SELECT * FROM bookdetails WHERE bookid='{$bookexid}'") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($books)) {
			$owner_id = $records[0];
			$bookex_id = $bookexid; 
			$title = $records[2];
			$authorfirst = $records[3];
			$authorlast = $records[4];
			$isbn10 = $records[5];
			$isbn13 = $records[6];
			$course = $records[7];
			$cond = $records[8];
			$note = $records[9];
			if($records[10] == 'Available'){
				$status	= 'checked';
			} else {
				$status = 'unchecked';
			}
		}
		pg_close($dbconn);
	}
	function updatebook(){
		global $bookex_id, $course, $cond, $note, $status, $DB_CONNECT_STRING;
		if($status == 'on'){
			$status = 'Available';
		} else {
			$status = 'Unavailable';
		}
		$user = $_SERVER['REMOTE_USER'];
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$books = pg_query("SELECT editbook('{$bookex_id}'::int,'{$user}'::varchar,'{$course}'::varchar,'{$cond}'::varchar,'{$note}'::text,'{$status}'::varchar)") or die('Query failed: ' . pg_last_error()); 
	
	}
	echo "<h1>Book Details</h1>";
	if($_SERVER['REQUEST_METHOD'] == 'GET'){ 
		if(isset($_GET['id'])){
			getfromBookEx($_GET['id']);
			filledform();
		}
	} elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['edit'])){
			editform();
		} elseif(isset($_POST['cancel'])){
			getfromBookEx($_GET['id']);
			filledform();
		} elseif(isset($_POST['save'])){
			updatebook();
			getfromBookEx($_GET['id']);
			filledform();
		} elseif(isset($_POST['delete'])){
			deletebook();
		} elseif(isset($_POST['confirmdelete'])){
		}
	}
	echo "</form></p>";
?>