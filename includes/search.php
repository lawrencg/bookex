<?php

	echo "<p><form action='searchResults.php' id='search' name='search' method='POST'>";
	echo "<select name='searchDropdown'>";
	echo "<option value='searchTitle'>Search by Book Title</option>";
	echo "<option value='searchISBN'>Search by Book ISBN</option>";
	echo "<option value='searchAuthor'>Search by Book Author</option>";
	echo "<option value='searchStudentName'>Search by Student Name</option>";
	echo "<option value='searchNetID'>Search by Student UW NetID</option>";
	echo "<option value='searchEmail'>Search by Student Email</option>";
	echo "</select>";
	echo "<input type='text' value='' id='searchTerm' name='searchTerm' size='25' /></span>";
	echo "<input type='submit' name='searchID' value='Search'/>";	
	echo "</form>";

	//if (pg_escape_string($_POST['searchID'])){
	//	
	//}
	
	
	
	
	
	
	
?>
