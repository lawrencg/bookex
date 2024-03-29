<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd" author and creator: Jessica Pardee>
<html>
	<head>
		<title>Agreement and Registration</title>
		<!-- <link rel="stylesheet" href="main.css"/> -->
		<link rel="stylesheet" href="style/main.css"/>
	
	</head>
	<body>
	<div id="pagecontainer">
		<!-- Top Div, this is included in every page.  Should be pulled out into a header file, use PHP include feature so we don't repeat code -->
		<div id="top">
			<div id="header">
				<div id="bookexlogo" class="frontpageonly">
					BookEX
				</div>
				<!--<div class=""><h1>Agreement and Registration</h1></div>-->
			</div>
		</div>	
		<!-- End Top Div -->
		
		<div id="page">
			<div id="registrationtitle" class="pageTitle">Agreement and Registration</div>
			<div id="maincontent">
				<div class="contentarea centerDiv">
					<div id="agreementinformation">
						<p class="introstatement">To use BookEX, you have to agree to the follwing terms.</p>
						<p>This document assumes that you have an English install of Notepad++. If you have installed it in another language, the commands and options will probably have been translated into your native language. In that case, find the command with the similar name, the logical grouping will still be the same. p>All the images used in this helpfile assume the default settings. If you change any of these settings, you'll have to look for the other image instead, this is most noticable with the toolbar (the position of images will always remain the same).</p>
						<p class="agreementconditions">
						You agree that:<br/>
							<ul>
								<li>You will return the book you borrow on time and in as good condition as when borrowed.</li>
								<li>You agree to pay for the damage or loss of the book that you borrow.</li>
								<li>You will not hack the system.</li>
								<li>Will not spam other members with your request.</li>
							</ul>
							<p>This document assumes that you have an English install of Notepad++. If you have installed it in another language, the commands and options will probably have been translated into your native language. In that case, find the command with the similar name, the logical grouping will still be the same. p>All the images used in this helpfile assume the default settings. If you change any of these settings, you'll have to look for the other image instead, this is most noticable with the toolbar (the position of images will always remain the same).</p>
							
							You will have to use your UW NetID to log in.
						</p>
					</div>
				</div>
				
				<div>
					<div id="uwnetiddisplayarea">
						<p>UW NetID:&nbsp;<b><?php echo $_SERVER['REMOTE_USER']; ?></b></p>
					</div>
					<div id="pleasenotemessage">
						
						<b>Please note:</b> All information in the box below is optional, 
						but by entering more information, you will make it easier for 
						others to find you, as well as allowing for people to see more 
						information about you, increasing your chances of being able to 
						borrow books.
						<br/>
	
					</div>
					<form action='dashboard.php' id='register' name='register' method='POST'>
						<div id="additionalinfotextareas" class="contentarea centerDiv">
							<div id="centeringtextarea">
									<div class="firstnametextarea">
										<label>First Name:</label>
										<input type="text" id="firstname" id="firstname" />
									</div>
									<div class="lastnametextarea">
										<label>Last Name:</label>
										<input type="text" id="lastname" id="lastname"/>
									</div>
									<div class="majortextarea">
										<label>Major:</label>
										<input type="text" id="major" id="major"/>
									</div>
									<div class="additionalemailtextarea">
										<label>Additional E-mail:</label>
										<input type="text" id="email" id="email"/>
									</div>	
							</div>
						</div>
						<div id="agreementbuttons" class="centerDiv">
							<input type="submit" id="register" name="register" value="I agree"/>
							<input type="submit" id="dontregister" name="dontregister" value="No thanks"> 
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<!-- Footer, this should be the same across every page.  Consider pulling out into an include -->
		<div id="footer">
			<ul id="footernavigation">
				<li id="privacy"><a href="/privacy">Privacy</a></li>
				<li id="terms"><a href="/terms">Terms</a></li>
				<li id="contact"><a href="/contact">Contact</a></li>
				<li id="help"><a href="/help">Help</a></li>
				<li id="ourstory"><a href="/ourstory">Our Story</a></li>
			</ul>
			<div id="copyrightstatement">
					&copy; 2011 All Rights Reserved by BookEX
			</div>
		</div>
	</div>	
		<!-- End Footer -->
	</body>
</html>