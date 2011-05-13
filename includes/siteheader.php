	<!-- Top Div, this is included in every page.  Should be pulled out into a header file, use PHP include feature so we don't repeat code -->
	<div id="top">
		<div id="header">
			<div id="bookexlogo">
				BookEX
			</div>
		</div>
		<div id="search">
			<form id="searchbox" method="POST" Action="searchResults.php">
				<select name="searchDropdown">
					<option>Book-Title</option>
					<option>Book-ISBN</option>
					<option>Book-Author First Name</option>
					<option>Book-Author Last Name</option>
					<option>Person-UW NetID</option>
					<option>Person-Email</option>
					<option>Person-Student Last Name</option>
					<option>Person-Student First Name</option>
				</select>
				<input type="text" name="searchTerm" size="40"/>
				<input type="submit" name="searchButton" value="Search"/>
			</form>	
		</div>
		<div id="navigation">
			<ul id="topnavigationlist">
				<li id="dashboard"><a href="dashboard.php">Dashboard</a></li>
				<li id="mybooks"><a href="mybooks.php">My Books</a></li>
				<li id="myprofile"><a href="myprofile.php">My Profile</a></li>
				<li id="logout"><a href="logout.php">Logout</a></li>
			</ul>
		</div>
	</div>	
	<!-- End Top Div -->