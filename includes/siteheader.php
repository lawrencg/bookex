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
	<?php
	// Extract actual filename with PHP_SELF, then use this as the reference point 
	// to set up the active navigation.
	$path = $_SERVER['PHP_SELF'];
	$page = basename($path);
	$page = basename($path, '.php');
	?>
	<div id="pagecontainer">
	<div id="top">
		<div id="header">
			<div id="bookexlogo">
				<img src="images/bookex-logo-small.png" alt="bookex-logo" />
			</div>
		</div>
		<div id="search">
			<form id="searchbox" method="get" action="searchresults.php">
				<div>
				<select name="type">
				<?php 
						$options = pg_query("SELECT * FROM searchoptions ORDER BY rank") ;
						//or die('Query failed: ' . pg_last_error()); 
						while($records = pg_fetch_array($options)) {
							# The default value for books being added to BookEx are assumed to be "Used"
							if(isset($_GET['type']) && $records[0] == $_GET['type']){
								echo '<option value="' . $records[0] . '" selected="selected">' . $records[1] . '</option>';
							} else {
								echo '<option value="' . $records[0] . '">' . $records[1] . '</option>';
							}
						}
						echo "</select>";
						if(isset($_GET['value'])){
							echo '<input type="text" name="value" value="'.$_GET['value'].'" size="40" />';
						}else{
							echo '<input type="text" name="value" size="40" />';
						}
				?>
				<input type="submit" name="submit" value="Search"/>
				</div>
			</form>
		</div>
		<div id="navigation">
			<ul id="topnavigationlist">
				<li id="dashboard"><a <?php if($page == 'dashboard'): ?> id="activenavmenu"<?php else: ?> id="dashboardhover"<?php endif ?> href="dashboard.php">Dashboard</a></li>
				<li id="mybooks"><a <?php if($page == 'mybooks' || $page == 'addbook'): ?> id="activenavmenu"<?php else: ?> id="mybookshover"<?php endif ?> href="mybooks.php">My Books</a></li>
				<li id="profile"><a <?php if($page == 'profile' && $person == $user): ?> id="activenavmenu"<?php else: ?> id="profilehover"<?php endif ?> href="profile.php">My Profile</a></li>
				<li id="logout"><a href="https://weblogin.washington.edu/logout/">Logout</a></li>
				<li id="submitbug"><a <?php if($page == 'submitbug'): ?> id="activenavmenu"<?php else: ?> id="bughover"<?php endif ?> href="submitbug.php">Submit a Bug</a></li>
			</ul>
		</div>
	</div>