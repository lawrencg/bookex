	<!-- Top Div, this is included in every page.  Should be pulled out into a header file, use PHP include feature so we don't repeat code -->
	<div id="top">
		<div id="header">
			<div id="bookexlogo">
				BookEX
			</div>
		</div>
		<div id="search">
			<form id="searchbox" method="POST" Action="includes/searchResults.php">
				<select name="searchDropdown">		
					<option value='searchTitle'>Title</option>
					<option value='searchISBN'>ISBN</option>
					<option value='searchAuthor'>Author</option>
					<option value='searchStudentName'>Real Name</option>
					<option value='searchNetID'>UW NetID</option>
					<option value='searchEmail'>Email</option>
				</select>
				<input type="text" name="searchTerm" size="40"/>
				<input type="submit" name="searchButton" value="Search"/>
			</form>	
		</div>
		<div id="navigation">
			<ul id="topnavigationlist">
				<li id="dashboard"><a href="dashboard.php">Dashboard</a></li>
				<li id="mybooks"><a href="mybooks.php">My Books</a></li>
				<li id="myprofile"><a href="profile.php">My Profile</a></li>
				<li id="logout"><a href="logout.php">Logout</a></li>
			</ul>
		</div>
	</div>	
	<!-- End Top Div -->