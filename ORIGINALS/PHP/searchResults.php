<?php

# Database connection parameters
include 'database_info.php';
$dbconn = pg_connect($DB_CONNECT_STRING);
if (!$dbconn) {
	die("Error in connection: " . pg_last_error());
}
$user = $_SERVER['REMOTE_USER'];

echo "<p>\n";
echo "\t<a href='dashboard.php'>Dashboard</a>\n";
echo "\t&nbsp;|&nbsp;<a href='mybooks.php'>My Books</a>\n";
echo "\t&nbsp;|&nbsp;My Profile\n";
echo "\t&nbsp;|&nbsp;<a href='https://weblogin.washington.edu/logout/'>Logout</a>\n";
echo "</p>\n";
include "search.php";

$searchTerm = $_POST['searchTerm'];
$searchOption = $_POST['searchDropdown'];
/*
echo "searchTerm = " . $searchTerm . "<br/>";
echo "searchOption = ". $searchOption . "<br/>";
if ($searchOption = "searchTitle") {
echo "searchOption = searchTitle";
}
*/

switch ($searchOption)
{
case "searchTitle":
	if (trim($searchTerm) == "") {
		echo "You didn't enter a search term.";
	} else {
	$searchTitleSQL = "SELECT * FROM searchbytitle('" . $searchTerm . "') AS results(title varchar, author_first_name varchar, author_last_name varchar, isbn10 numeric, isbn13 numeric, ownerName varchar)";
	$searchTitleSQLResult = pg_query($dbconn, $searchTitleSQL);
	if (!$searchTitleSQLResult) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows = pg_num_rows($searchTitleSQLResult);
	if ($rows != 0) {
	echo "<h2>Search Results for <font color='green'><i>" . $searchTerm . "</i></font></h2>";
	echo "<table border='1'>";
	echo "<tr><th>Book Title<th>Author<th>ISBN-10<th>ISBN-13<th>Owner</tr>";
	while ($row = pg_fetch_array($searchTitleSQLResult)) {
		echo "<tr><td>" . htmlspecialchars($row[0]) . "</td><td>" . htmlspecialchars($row[1]) . " " . htmlspecialchars($row[2]) . "</td><td>" . htmlspecialchars($row[3]) . "</td><td>" . htmlspecialchars($row[4]) . "</td><td>" . htmlspecialchars($row[5]) . "</td></tr>"; 
		}
	} else {
		echo "<i>Cannot find any books with the title <b>" . $searchTerm . "</b>";
		}
	}
break;
case "searchNetID":
	if (trim($searchTerm) == "") {
		echo "You didn't enter a search term.";
	} else {
	$searchNetidSQL = "SELECT * FROM searchbyuwnetid('" . $searchTerm . "') AS results(numberOfBooks bigint, ownerName varchar, email varchar, userid varchar)";
	$searchNetidSQLResult = pg_query($dbconn, $searchNetidSQL);
	if (!$searchNetidSQLResult) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows2 = pg_num_rows($searchNetidSQLResult);
	if ($rows2 != 0) {
	echo "<h2>Search Results for <font color='green'><i>" . $searchTerm . "</i></font></h2>";
	echo "<table border='1'>";
	echo "<tr><th>Name<th>E-mail<th>Number of books</tr>";
	while ($row = pg_fetch_array($searchNetidSQLResult)) {
		echo "<tr><td><a href='profile.php?id={$row[3]}'>" . htmlspecialchars($row[1]) . "</a></td><td>" . htmlspecialchars($row[2]) . "</td><td>" . htmlspecialchars($row[0]) . "</td></tr>"; 
		}
	} else {
		echo "<i>Cannot find any people with the UW NetID <b>"	. $searchTerm . "</b>";
		}
	}
break;
case "searchISBN":
	if (trim($searchTerm) == "") {
		echo "You didn't enter a search term.";
	} else {
	$searchisbnSQL = "SELECT * FROM searchbyisbn('" . $searchTerm . "') AS results(booktitle varchar, authorFname varchar, authorLname varchar, isbn10 numeric, isbn13 numeric, ownerName varchar)";
	$searchisbnSQLResult = pg_query($dbconn, $searchisbnSQL);
	if (!$searchisbnSQLResult) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows2 = pg_num_rows($searchisbnSQLResult);
	if ($rows2 != 0) {
	echo "<h2>Search Results for <font color='green'><i>" . $searchTerm . "</i></font></h2>";
	echo "<table border='1'>";
	echo "<tr><th>Book Title<th>Author<th>ISBN-10<th>ISBN-13<th>Owner</tr>";
	while ($row = pg_fetch_array($searchisbnSQLResult)) {
		echo "<tr><td>" . htmlspecialchars($row[0]) . "</td><td>" . htmlspecialchars($row[1]) . " " . htmlspecialchars($row[2]) . "</td><td>" . htmlspecialchars($row[3]) . "</td><td>" . htmlspecialchars($row[4]) . "</td><td>" . htmlspecialchars($row[5]) . "</td></tr>"; 
		}
	} else {
		echo "<i>Cannot find any people that own the book having ISBN <b>"	. $searchTerm . "</b>";
		}
	}
break;
case "searchStudentName":
	if (trim($searchTerm) == "") {
		echo "You didn't enter a search term.";
	} else {
	$searchStudentNameSQL = "SELECT * FROM searchbyname('" . $searchTerm . "') AS results(numberOfBooks bigint, studentFirstName varchar, studentLastName varchar, email varchar)";
	$searchStudentNameSQLResult = pg_query($dbconn, $searchStudentNameSQL);
	if (!$searchStudentNameSQLResult) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows2 = pg_num_rows($searchStudentNameSQLResult);
	if ($rows2 != 0) {
	echo "<h2>Search Results for <font color='green'><i>" . $searchTerm . "</i></font></h2>";
	echo "<table border='1'>";
	echo "<tr><th>Name<th>E-mail<th>Number of books</tr>";
	while ($row = pg_fetch_array($searchStudentNameSQLResult)) {
		echo "<tr><td>" . htmlspecialchars($row[1]) . " " . htmlspecialchars($row[2]) . "</td><td>" . htmlspecialchars($row[3]) . "</td><td>" . htmlspecialchars($row[0]) . "</td></tr>"; 
		}
	} else {
		echo "<i>Cannot find any people with the name containing <b>"	. $searchTerm . "</b>";
		}
	}
break;
case "searchEmail":
	if (trim($searchTerm) == "") {
		echo "You didn't enter a search term.";
	} else {
	$searchStudentEmailSQL = "SELECT * FROM searchbyemail('" . $searchTerm . "') AS results(numberOfBooks bigint, ownerName varchar, email varchar)";
	$searchStudentEmailSQLResult = pg_query($dbconn, $searchStudentEmailSQL);
	if (!$searchStudentEmailSQLResult) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows2 = pg_num_rows($searchStudentEmailSQLResult);
	if ($rows2 != 0) {
	echo "<h2>Search Results for <font color='green'><i>" . $searchTerm . "</i></font></h2>";
	echo "<table border='1'>";
	echo "<tr><th>Name<th>E-mail<th>Number of books</tr>";
	while ($row = pg_fetch_array($searchStudentEmailSQLResult)) {
		echo "<tr><td>" . htmlspecialchars($row[1]) . "</td><td>" . htmlspecialchars($row[2]) . "</td><td>" . htmlspecialchars($row[0]) . "</td></tr>"; 
		}
	} else {
		echo "<i>Cannot find any people with the email <b>"	. $searchTerm . "</b>";
		}
	}
break;
case "searchAuthor":
	if (trim($searchTerm) == "") {
		echo "You didn't enter a search term.";
	} else {
	$searchAuthorSQL = "SELECT * FROM searchbyauthorname('" . $searchTerm . "') AS results(booktitle varchar, authorFname varchar, authorLname varchar, isbn10 numeric, isbn13 numeric, ownerName varchar)";
	$searchAuthorSQLResult = pg_query($dbconn, $searchAuthorSQL);
	if (!$searchAuthorSQLResult) {
		die("Error in SQL query: " . pg_last_error());
	}
	$rows2 = pg_num_rows($searchAuthorSQLResult);
	if ($rows2 != 0) {
	echo "<h2>Search Results for <font color='green'><i>" . $searchTerm . "</i></font></h2>";
	echo "<table border='1'>";
	echo "<tr><th>Book Title<th>Author<th>ISBN-10<th>ISBN-13<th>Owner</tr>";
	while ($row = pg_fetch_array($searchAuthorSQLResult)) {
		echo "<tr><td>" . htmlspecialchars($row[0]) . "</td><td>" . htmlspecialchars($row[1]) . " " . htmlspecialchars($row[2]) . "</td><td>" . htmlspecialchars($row[3]) . "</td><td>" . htmlspecialchars($row[4]) . "</td><td>" . htmlspecialchars($row[5]) . "</td></tr>"; 
		}
	} else {
		echo "<i>Cannot find any book authors with the name containing <b>"	. $searchTerm . "</b>";
		}
	}
break;
}
		
		
?>