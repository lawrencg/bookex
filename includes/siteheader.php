	<link type="text/css" rel="stylesheet" href="styles/main.css" />
	<meta name="author" content="Jessica Pardee" />
	<meta name="author" content="Sopheap Kun" />
	<meta name="author" content="Michael Cheung" />
	<meta name="author" content="Lawrence Gabriel" />
	<meta name="affiliation" content="University of Washington Information Schoool" />
	<meta name="revised" content="<?php echo date ("F d, Y G:i:s \(T\)", filemtime('/nfs/giovanni11/dw21/d77'.$_SERVER['REQUEST_URI'])); ?>" />
	<meta name="description" content="BookEx Project, Capstone 2011" />
	<meta name="keywords" content="exchange, books, textbooks, University of Washington, Information Schoool, BookEx, students" />
	
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
	<div id="pagecontainer">
	<div id="top">
		<div id="header">
			<div id="bookexlogo">
				<img src="images/bookex-logo-small.png" alt="bookex-logo" />
			</div>
		</div>
		<div id="search">
			<form id="searchbox" method="post" action="searchresults.php">
				<div>
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
				</div>
			</form>
		</div>
		<div id="navigation">
			<ul id="topnavigationlist">
				<li id="dashboard"><a href="dashboard.php">Dashboard</a></li>
				<li id="mybooks"><a href="mybooks.php">My Books</a></li>
				<li id="myprofile"><a href="profile.php">My Profile</a></li>
				<li id="logout"><a href="https://weblogin.washington.edu/logout/">Logout</a></li>
				<li id="submitbug"><a href="submitbug.php">Submit a Bug</a></li>
			</ul>
		</div>
	</div>