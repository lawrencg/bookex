<?php

$dbconn = pg_connect("host=vergil.u.washington.edu port=10450 dbname=larry_test user=shanzha password=lawrence");
if (!$dbconn) {
	die("Error in connection: " . pg_last_error());
}
$user = $_SERVER['REMOTE_USER'];

/*echo "<p>\n";
echo "\t<a href='dashboard.php'>Dashboard</a>\n";
echo "\t&nbsp;|&nbsp;<a href='mybooks.php'>My Books</a>\n";
echo "\t&nbsp;|&nbsp;My Profile\n";
echo "\t&nbsp;|&nbsp;<a href='https://weblogin.washington.edu/logout/'>Logout</a>\n";
echo "</p>\n";
//include 'includes/search.php';
*/
$searchTerm = $_POST['searchTerm'];
$searchOption = $_POST['searchDropdown'];
/*
echo "searchTerm = " . $searchTerm . "<br/>";
echo "searchOption = ". $searchOption . "<br/>";
if ($searchOption = "searchTitle") {
echo "searchOption = searchTitle";
}
*/
include 'includes/request_process.php';
include 'includes/searchresults_0_header.php';
include 'includes/siteheader.php';
include 'includes/searchresults_1_contentarea.php';


switch ($searchOption)
{
case "searchTitle":
	if (trim($searchTerm) == "") {
		$errormessage = "You didn't enter a search term.";
		} else {
	$searchTitleSQL = "SELECT * FROM searchbytitle('" . $searchTerm . "') AS results(title varchar, author_first_name varchar, author_last_name varchar, isbn10 numeric, isbn13 numeric, owner_name varchar, book_id int)";
	$results = pg_query($dbconn, $searchTitleSQL);
	if (!$results) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows = pg_num_rows($results);
	if ($rows != 0) {
	echo "<div class=\"pageSubTitle\">Search Results for <font color='green'><i>" . $searchTerm . "</i></font></div>";
	echo "<table id='booksearchresultstable'>";
	echo "<thead><tr><td class=\"header\">Book Title</td><td class=\"header\">Author</td><td class=\"header\">ISBN-13</td><td class=\"header\">Owner</td></tr></thead>";
	while ($row = pg_fetch_array($results)) {
		echo "<tr><td class=\"booktitle\">" . htmlspecialchars($row[0]) . "</td><td class=\"bookauthor\">" . htmlspecialchars($row[1]) . " " . htmlspecialchars($row[2]) . "</td><td class=\"bookisbn\">" . htmlspecialchars($row[4]) . "</td><td class=\"bookowner\">" . htmlspecialchars($row[5]) . "</td><td class=\"requestbutton\">" . request_button($row[6]) . "</td></tr>"; 
		}
	echo "</table>";
	} else {
		$errormessage = "Cannot find any books with the title";
		}
	}
break;
case "searchNetID":
	if (trim($searchTerm) == "") {
		$errormessage = "You didn't enter a search term.";
		} else {
	$searchNetidSQL2 = "SELECT * FROM searchbyuwnetid('" . $searchTerm . "') AS results(numberOfBooks bigint, ownerName varchar, email varchar, userid varchar)";
	$results = pg_query($dbconn, $searchNetidSQL2);
	if (!$results) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows = pg_num_rows($results);
	if ($rows != 0) {
	echo "<div class=\"pageSubTitle\">Search Results for <font color='green'><i>" . $searchTerm . "</i></font></div>";
	echo "<table id='peoplesearchresults'>";
	echo "<thead><tr><td class=\"header\">Name</td><td>E-mail</td><td>Number of books</td></tr></thead>";
	while ($row = pg_fetch_array($results)) {
		echo "<tr><td class=\"personsname\"><a href='profile.php?id={$row[3]}'>" . htmlspecialchars($row[1]) . "</a></td><td class=\"personsemail\">" . htmlspecialchars($row[2]) . "</td><td class=\"personsbooknumber\">" . htmlspecialchars($row[0]) . "</td></tr>"; 
		}
		echo "</table>";
	} else {
		$errormessage = "Cannot find any people with the UW NetID";
		}
	}
break;
case "searchISBN":
	if (trim($searchTerm) == "") {
		$errormessage = "You didn't enter a search term.";
		} else {
	$searchTitleSQL2 = "SELECT * FROM searchbyisbn('" . $searchTerm . "') AS results(title varchar, author_first_name varchar, author_last_name varchar, isbn10 numeric, isbn13 numeric, owner_name varchar, book_id int)";
	$results = pg_query($dbconn, $searchTitleSQL2);
	if (!$results) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows = pg_num_rows($results);
	if ($rows != 0) {
	echo "<div class=\"pageSubTitle\">Search Results for <font color='green'><i>" . $searchTerm . "</i></font></div>";
	echo "<table id='peoplesearchresults'>";
	echo "<thead><tr><td class=\"header\">Name</td><td>E-mail</td><td>Number of books</td></tr></thead>";
	while ($row = pg_fetch_array($results)) {
		echo "<tr><td class=\"personsname\"><a href='profile.php?id={$row[3]}'>" . htmlspecialchars($row[1]) . "</a></td><td class=\"personsemail\">" . htmlspecialchars($row[2]) . "</td><td class=\"personsbooknumber\">" . htmlspecialchars($row[0]) . "</td></tr>"; 
		}
		echo "</table>";
	} else {
		$errormessage = "Cannot find any books with the ISBN";
		}
	}
break;
case "searchStudentName":
	if (trim($searchTerm) == "") {
		$errormessage = "You didn't enter a search term.";
	} else {
	$searchStudentNameSQL = "SELECT * FROM searchbyname('" . $searchTerm . "') AS results(numberOfBooks bigint, ownerName varchar, email varchar, userid varchar)";
	$results = pg_query($dbconn, $searchStudentNameSQL);	
	if (!$results) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows = pg_num_rows($results);
	if ($rows != 0) {
	echo "<div class=\"pageSubTitle\">Search Results for <font color='green'><i>" . $searchTerm . "</i></font></div>";
	echo "<table id='peoplesearchresults'>";
	echo "<thead><tr><td class=\"header\">Name</td><td>E-mail</td><td>Number of books</td></tr></thead>";
	while ($row = pg_fetch_array($results)) {
		echo "<tr><td class=\"personsname\"><a href='profile.php?id={$row[3]}'>" . htmlspecialchars($row[1]) . "</a></td><td class=\"personsemail\">" . htmlspecialchars($row[2]) . "</td><td class=\"personsbooknumber\">" . htmlspecialchars($row[0]) . "</td></tr>"; 
		}
		echo "</table>";
	} else {
		$errormessage = "Cannot find any people with the name";
		}
	}
break;
case "searchEmail":
	if (trim($searchTerm) == "") {
		$errormessage = "You didn't enter a search term.";
	} else {
	$searchStudentEmailSQL = "SELECT * FROM searchbyemail('" . $searchTerm . "') AS results(numberOfBooks bigint, ownerName varchar, email varchar, userid varchar)";
	$results = pg_query($dbconn, $searchStudentEmailSQL);
	if (!$results) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows = pg_num_rows($results);
	if ($rows != 0) {
	echo "<div class=\"pageSubTitle\">Search Results for <font color='green'><i>" . $searchTerm . "</i></font></div>";
	echo "<table id='peoplesearchresults'>";
	echo "<thead><tr><td class=\"header\">Name</td><td>E-mail</td><td>Number of books</td></tr></thead>";
	while ($row = pg_fetch_array($results)) {
		echo "<tr><td class=\"personsname\"><a href='profile.php?id={$row[3]}'>" . htmlspecialchars($row[1]) . "</a></td><td class=\"personsemail\">" . htmlspecialchars($row[2]) . "</td><td class=\"personsbooknumber\">" . htmlspecialchars($row[0]) . "</td></tr>"; 
		}
		echo "</table>";
	} else {
		$errormessage = "Cannot find any people with the email";
		}
	}
break;
case "searchAuthor":
	if (trim($searchTerm) == "") {
		$errormessage = "You didn't enter a search term.";
	} else {
	$searchAuthorSQL = "SELECT * FROM searchbyauthorname('" . $searchTerm . "') AS results(title varchar, author_first_name varchar, author_last_name varchar, isbn10 numeric, isbn13 numeric, owner_name varchar, book_id int)";
	$results = pg_query($dbconn, $searchAuthorSQL);
	if (!$results) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows = pg_num_rows($results);
	if ($rows != 0) {
	echo "<div class=\"pageSubTitle\">Search Results for <font color='green'><i>" . $searchTerm . "</i></font></div>";
	echo "<table id='peoplesearchresults'>";
	echo "<thead><tr><td class=\"header\">Name</td><td>E-mail</td><td>Number of books</td></tr></thead>";
	while ($row = pg_fetch_array($results)) {
		echo "<tr><td class=\"personsname\"><a href='profile.php?id={$row[3]}'>" . htmlspecialchars($row[1]) . "</a></td><td class=\"personsemail\">" . htmlspecialchars($row[2]) . "</td><td class=\"personsbooknumber\">" . htmlspecialchars($row[0]) . "</td></tr>"; 
		}
		echo "</table>";
	} else {
		$errormessage = "Cannot find any books with the author";
		}
	}
break;
}
include 'includes/searchresults_2_contentarea.php';		
include 'includes/sitefooter.php';		
?>