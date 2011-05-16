<?php

	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	include 'includes/session_track.php';
	# Database connection parameters
	require 'includes/database_info.php';
	# Force non-registered users to register or leave
	# Pages would break if a UW NetID accessed a page directly
	# without being a user
	require 'includes/valid_user.php';

	include 'includes/request_process.php';
	include 'includes/searchresults_0_header.php';
	include 'includes/siteheader.php';
	include 'includes/searchresults_1_contentarea.php';
	
	$user = $_SERVER['REMOTE_USER'];
	$searchTerm = $_POST['searchTerm'];
	$searchOption = $_POST['searchDropdown'];
	
	function remove_non_numeric($string) {
		return preg_replace('/\D/', '', $string);
	}
	
	function displaybookresults($results, $type){
		global $searchTerm;
		$rows = pg_num_rows($results);
		echo "<div class=\"pageSubTitle\">Found {$rows} books with the {$type}&nbsp;<font color='green'><i>\"" . $searchTerm . "\"</i></font></div>";	
		while ($row = pg_fetch_array($results)) {

			echo "<table id='booksearchresultstable'>";
			echo "<thead><tr><td class=\"header\">Book Title</td><td class=\"header\">Author</td><td class=\"header\">ISBN-13</td><td class=\"header\">Owner</td><td class=\"header\"></td></tr></thead><tbody>";
			echo "<tr><td class=\"booktitle\"><a href='bookdetails.php?id={$row[6]}'>" . htmlspecialchars($row[0]) . "</a></td><td class=\"bookauthor\">" . htmlspecialchars($row[1]) . " " . htmlspecialchars($row[2]) . "</td><td class=\"bookisbn\">" . htmlspecialchars($row[4]) . "&nbsp;&nbsp;</td><td class=\"bookowner\">" . htmlspecialchars($row[5]) . "</td><td class=\"requestbutton\">";
			request_button($row[6]);
			echo "</td></tr>";
			while ($row = pg_fetch_array($results)) {
				echo "<tr><td class=\"booktitle\"><a href='bookdetails.php?id={$row[6]}'>" . htmlspecialchars($row[0]) . "</a></td><td class=\"bookauthor\">" . htmlspecialchars($row[1]) . " " . htmlspecialchars($row[2]) . "</td><td class=\"bookisbn\">" . htmlspecialchars($row[4]) . "&nbsp;&nbsp;&nbsp;&nbsp;</td><td class=\"bookowner\">" . htmlspecialchars($row[5]) . "</td><td class=\"requestbutton\">";
				request_button($row[6]);
				echo "</td></tr>";
			} 
			echo "</tbody></table>";
		}
	}
	function displayuserresults($results, $type){
		global $searchTerm;
		$rows = pg_num_rows($results);
		echo "<div class=\"pageSubTitle\">Found {$rows} users with their {$type} containing&nbsp;<font color='green'><i>\"" . $searchTerm . "\"</i></font></div>";	
		while ($row = pg_fetch_array($results)) {

			echo "<table id='booksearchresultstable'>";
			echo "<thead><tr><td class=\"header\">Name</td><td class=\"header\">UW NetID</td><td class=\"header\">Email</td><td class=\"header\">Available Books</td></tr></thead><tbody>";
				echo "<tr><td class=\"personsname\"><a href='profile.php?id={$row[3]}'>" . htmlspecialchars($row[1]) . "</a></td><td class=\"personsnetid\">" . htmlspecialchars($row[3]) . "  </td><td class=\"personsemail\">";
				if(htmlspecialchars($row[2]) == ''){
					echo htmlspecialchars($row[3]) . "@uw.edu";
				} else {
					echo htmlspecialchars($row[2]);
				}				
				echo "</td><td class=\"personsbooknumber\">" . htmlspecialchars($row[0]) . "</td></tr>";
			while ($row = pg_fetch_array($results)) {
				echo "<tr><td class=\"personsname\"><a href='profile.php?id={$row[3]}'>" . htmlspecialchars($row[1]) . "</a></td><td class=\"personsnetid\">" . htmlspecialchars($row[3]) . "  </td><td class=\"personsemail\">";
				if(htmlspecialchars($row[2]) == ''){
					echo htmlspecialchars($row[3]) . "@uw.edu";
				} else {
					echo htmlspecialchars($row[2]);
				}				
				echo "</td><td class=\"personsbooknumber\">" . htmlspecialchars($row[0]) . "</td></tr>";
			} 
			echo "</tbody></table>";
		}
	}
	switch ($searchOption){
		case "searchTitle":
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
				$searchTitleSQL = "SELECT * FROM searchbytitle('" . $searchTerm . "') AS results(title varchar, author_first_name varchar, author_last_name varchar, isbn10 numeric, isbn13 numeric, owner_name varchar, book_id integer)";
				$results = pg_query($searchTitleSQL);
				if (!$results) {
					die("Error in SQL query: " . pg_last_error());
				}
				displaybookresults($results, 'title containing');
			}
		break;
		case "searchISBN":
			$searchTerm = remove_non_numeric($searchTerm);
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
			$searchTitleSQL2 = "SELECT * FROM searchbyisbn('" . $searchTerm . "') AS results(title varchar, author_first_name varchar, author_last_name varchar, isbn10 numeric, isbn13 numeric, owner_name varchar, book_id int)";
			$results = pg_query($searchTitleSQL2);
			if (!$results) {
				die("Error in SQL query: " . pg_last_error());
			}
				displaybookresults($results, 'ISBN');
			}
		break;
		case "searchAuthor":
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
			$searchAuthorSQL = "SELECT * FROM searchbyauthorname('" . $searchTerm . "') AS results(title varchar, author_first_name varchar, author_last_name varchar, isbn10 numeric, isbn13 numeric, owner_name varchar, book_id int)";
			$results = pg_query($searchAuthorSQL);
			if (!$results) {
				die("Error in SQL query: " . pg_last_error());
			}
				displaybookresults($results, 'author name containing');
			}
		break;
		
		
		case "searchStudentName":
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
			$searchStudentNameSQL = "SELECT * FROM searchbyname('" . $searchTerm . "') AS results(numberOfBooks bigint, ownerName varchar, email varchar, userid varchar)";
			$results = pg_query($searchStudentNameSQL);	
			if (!$results) {
				die("Error in SQL query: " . pg_last_error());
			}
				displayuserresults($results, 'name');
			}
		break;
		case "searchNetID":
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
			$searchNetidSQL2 = "SELECT * FROM searchbyuwnetid('" . $searchTerm . "') AS results(numberOfBooks bigint, ownerName varchar, email varchar, userid varchar)";
			$results = pg_query($searchNetidSQL2);
			if (!$results) {
				die("Error in SQL query: " . pg_last_error());
			}
				displayuserresults($results, 'UW NetID');
			}
		break;
		case "searchEmail":
			if (trim($searchTerm) == "") {
				echo "<div class=\"pageSubTitle\">You didn&#39;t enter a search term.</div>";	
			} else {
			$searchStudentEmailSQL = "SELECT * FROM searchbyemail('" . $searchTerm . "') AS results(numberOfBooks bigint, ownerName varchar, email varchar, userid varchar)";
			$results = pg_query($searchStudentEmailSQL);
			if (!$results) {
				die("Error in SQL query: " . pg_last_error());
			}
				displayuserresults($results, 'email address');
			}
		break;
		}
		
	include 'includes/searchresults_2_contentarea.php';		
	include 'includes/sitefooter.php';		
?>