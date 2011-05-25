<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 19, 2011
	# Title: Displays the results from a search in the main navigation area.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	require 'includes/session_track.php';
	# Database connection parameters
	require 'includes/database_info.php';
	require 'includes/valid_user.php';

	include 'includes/request_process.php';
	include 'includes/searchresults_0_header.php';
	include 'includes/siteheader.php';
	include 'includes/searchresults_1_contentarea.php';
	
	$user = $_SERVER['REMOTE_USER'];
	$searchTerm = trim(pg_escape_string($_GET['value']));
	$searchOption = pg_escape_string($_GET['type']);
	$errormessage;
	$rbook;
	$rperson;
	
	
	if(isset($_POST['request'])){
		requested();
		$errormessage = "You have requested <b><i>$rbook</i></b> from <b><i>$rperson</i></b>.";
	}
	
	function requested(){
		global $user, $rbook, $rperson;
		$yourequested = pg_query("SELECT * FROM detailedtransactions WHERE recipientid = 'cheungm' AND transstatus = 'Requested' ORDER BY transid DESC LIMIT 1");
		while($records = pg_fetch_array($yourequested)) {
			$rbook = $records[3];
			$rperson = $records[6];
		}
	}
	
	function remove_non_numeric($string) {
		return preg_replace('/\D/', '', $string);
	}
	
	function displaybookresults($results, $type, $owner){
		global $searchTerm;
		$rows = pg_num_rows($results);
		echo "<div class=\"pageSubTitle\">Found {$rows} books with the {$type}&nbsp;<span id='searchterm'><i>\"" . $searchTerm . "\"</i></span></div>";	
		while ($row = pg_fetch_array($results)) {
			echo "<table id='booksearchresultstable'>";
			echo "<thead><tr><td class=\"header\">Book Title</td><td class=\"header\">Author</td><td class=\"header\">ISBN</td><td class=\"header\">Owner</td><td class=\"header\"></td></tr></thead><tbody>";
			echo "<tr><td class=\"booktitle\" id=\"bookresultsbooktitle\"><div><a href='bookdetails.php?id={$row[6]}'>" . htmlspecialchars($row[0]) . "</a></div></td><td class=\"bookauthor\" id=\"bookresultsauthorlastname\"><div>" . htmlspecialchars($row[2]) . "</div></td><td class=\"bookisbn\" id=\"bookresultsbookisbn\"><div>";
				if ($row[4] != ''){
					echo htmlspecialchars($row[4]);
				} else {
					echo htmlspecialchars($row[3]);
				}
				echo "</div></td><td class=\"bookowner\" id=\"bookresultsbookowner\"><div><a href=\"profile.php?id=".$row[7]."\">".htmlspecialchars($row[5])."</a></div></td><td class=\"requestbutton\">";
			if( $owner != $row[7]){
				request_button($row[6]);
			}else{
				echo "&nbsp;";
			}
			echo "</td></tr>";
			while ($row = pg_fetch_array($results)) {
			echo "<tr><td class=\"booktitle\" id=\"bookresultsbooktitle\"><div><a href='bookdetails.php?id={$row[6]}'>" . htmlspecialchars($row[0]) . "</a></div></td><td class=\"bookauthor\" id=\"bookresultsauthorlastname\"><div>" . htmlspecialchars($row[2]) . "</div></td><td class=\"bookisbn\" id=\"bookresultsbookisbn\"><div>";
				if ($row[4] != ''){
					echo htmlspecialchars($row[4]);
				} else {
					echo htmlspecialchars($row[3]);
				}
				echo "</div></td><td class=\"bookowner\" id=\"bookresultsbookowner\"><div><a href=\"profile.php?id=".$row[7]."\">".htmlspecialchars($row[5])."</a></div></td><td class=\"requestbutton\">";
			if( $owner != $row[7]){
				request_button($row[6]);
			}else{
				echo "&nbsp;";
			}
				echo "</td></tr>";
			} 
			echo "</tbody></table>";
		}
	}
	function displayuserresults($results, $type){
		global $searchTerm;
		$rows = pg_num_rows($results);
		echo "<div class=\"pageSubTitle\">Found {$rows} users with their {$type} containing&nbsp;<span id='searchterm'><i>\"" . $searchTerm . "\"</i></span></div>";	
		while ($row = pg_fetch_array($results)) {

			echo "<table id='booksearchresultstable'>";
			echo "<thead><tr><td class=\"header\">Name</td><td class=\"header\">UW NetID</td><td class=\"header\">Email</td><td class=\"header\">Books</td></tr></thead><tbody>";
				echo "<tr><td class=\"personsname\"><a href='profile.php?id={$row[3]}'>" . htmlspecialchars($row[1]) . "</a></td><td class=\"personsnetid\">" . htmlspecialchars($row[3]) . "  </td><td class=\"personsemail\">";
				if(htmlspecialchars($row[2]) == ''){
					echo htmlspecialchars($row[3]) . "@uw.edu";
				} else {
					echo htmlspecialchars($row[2]);
				}				
				echo "</td><td class=\"personsbooknumber\" id=\"personsbooknumber\">" . htmlspecialchars($row[0]) . "</td></tr>";
			while ($row = pg_fetch_array($results)) {
				echo "<tr><td class=\"personsname\"><a href='profile.php?id={$row[3]}'>" . htmlspecialchars($row[1]) . "</a></td><td class=\"personsnetid\">" . htmlspecialchars($row[3]) . "  </td><td class=\"personsemail\">";
				if(htmlspecialchars($row[2]) == ''){
					echo htmlspecialchars($row[3]) . "@uw.edu";
				} else {
					echo htmlspecialchars($row[2]);
				}				
				echo "</td><td class=\"personsbooknumber\" id=\"personsbooknumber\">" . htmlspecialchars($row[0]) . "</td></tr>";
			} 
			echo "</tbody></table>";
		}
	}
	//$temp = pg_query("SELECT getbookexname('" . $user . "')") or die('Query failed: ' . pg_last_error()); 
	//$bookexname = pg_fetch_array($temp);
	//$name = $bookexname[0];
	$name = $user;
	
	switch ($searchOption){
		case "title":
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
				$searchTitleSQL = "SELECT * FROM searchbytitle('" . $searchTerm . "') AS results(title varchar, author_first_name varchar, author_last_name varchar, isbn10 numeric, isbn13 numeric, owner_name varchar, book_id integer, netid varchar)";
				$results = pg_query($searchTitleSQL);
				if (!$results) {
					//die("Error in SQL query: " . pg_last_error());
				}
				if($errormessage != '') {
					echo '<div id="notification" class="show">' . $errormessage . '</div>' . "\n";
					}
				displaybookresults($results, 'title containing', $name);
			}
		break;
		case "isbn":
			$searchTerm = remove_non_numeric($searchTerm);
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
			$searchTitleSQL2 = "SELECT * FROM searchbyisbn('" . $searchTerm . "') AS results(title varchar, author_first_name varchar, author_last_name varchar, isbn10 numeric, isbn13 numeric, owner_name varchar, book_id int, netid varchar)";
			$results = pg_query($searchTitleSQL2);
			if (!$results) {
				//die("Error in SQL query: " . pg_last_error());
			}
				displaybookresults($results, 'ISBN', $name);
			}
		break;
		case "author":
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
			$searchAuthorSQL = "SELECT * FROM searchbyauthorname('" . $searchTerm . "') AS results(title varchar, author_first_name varchar, author_last_name varchar, isbn10 numeric, isbn13 numeric, owner_name varchar, book_id int, netid varchar)";
			$results = pg_query($searchAuthorSQL);
			if (!$results) {
				//die("Error in SQL query: " . pg_last_error());
			}
				displaybookresults($results, 'author name containing', $name);
			}
		break;
		
		
		case "name":
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
			$searchStudentNameSQL = "SELECT * FROM searchbyname('" . $searchTerm . "') AS results(numberOfBooks bigint, ownerName varchar, email varchar, userid varchar)";
			$results = pg_query($searchStudentNameSQL);	
			if (!$results) {
				//die("Error in SQL query: " . pg_last_error());
			}
				displayuserresults($results, 'name');
			}
		break;
		case "netid":
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
			$searchNetidSQL2 = "SELECT * FROM searchbyuwnetid('" . $searchTerm . "') AS results(numberOfBooks bigint, ownerName varchar, email varchar, userid varchar)";
			$results = pg_query($searchNetidSQL2);
			if (!$results) {
				//die("Error in SQL query: " . pg_last_error());
			}
				displayuserresults($results, 'UW NetID');
			}
		break;
		case "email":
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
			$searchStudentEmailSQL = "SELECT * FROM searchbyemail('" . $searchTerm . "') AS results(numberOfBooks bigint, ownerName varchar, email varchar, userid varchar)";
			$results = pg_query($searchStudentEmailSQL);
			if (!$results) {
				//die("Error in SQL query: " . pg_last_error());
			}
				displayuserresults($results, 'email address');
			}
		break;
		}
		
	include 'includes/searchresults_2_contentarea.php';		
	include 'includes/sitefooter.php';		
?>