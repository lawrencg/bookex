<?php

	echo "<p><form action='searchResults.php' id='search' name='search' method='POST'>";
	echo "<select name='searchDropdown'>";
	echo "<option value='searchTitle'>Search by Title</option>";
	echo "<option value='searchISBN'>Search by ISBN</option>";
	echo "<option value='searchNetID'>Search by UW NetID</option>";
	echo "</select>";
	echo "<input type='text' value='' id='searchTerm' name='searchTerm' size='25' /></span>";
	echo "<input type='submit' name='searchID' value='Search'/>";	
	echo "</form>";

	if (pg_escape_string($_POST['searchID'])){
		
	}
	
	
	
	
	
	
	
?>
