<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: Add a book to the BookEx web application.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	include 'includes/session_track.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>BookEx Error</title>
		<link rel="stylesheet" href="styles/main.css"/>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	</head>
	<body>
	<div id="pagecontainer">
		<div id="top">
			<div id="header">
			</div>
		</div>	
		<div id="page">
			<div id="errortitle" class="pageTitle">Oops... Something is broken.</div>
			<div id="maincontent">
				<div class="contentarea centerDiv">
					<div id="agreementinformation">
						<div style="text-align:center;">
						<img src="images/bookex-logo-error.png" id="error-image" alt="BookEx is broken" />
						</div>
						<p>Sorry, It doesn't look like we don't have everything working yet. Hopefully we will get this 
						problem fixed today. Or maybe tomorrow. You can try to <a href="
						<?php echo $_SESSION['previouspage'][2]; ?>">
						go back</a> to where you were before this
						embarrassment happened. Please don't be too hard on us. Thanks.</p>
					</div>
				</div>
			</div>
		</div>
        <div id="footer">
                <br />
                <ul id="footernavigation">
                        <li id="privacy"><a href="#">Privacy</a></li>
                        <li id="terms"><a href="http://www.bookex.info/terms.html">Terms</a></li>
                        <li id="contact"><a href="#">Contact</a></li>
                        <li id="help"><a href="#">Help</a></li>
                        <li id="ourstory"><a href="http://www.bookex.info/ourstory.html">Our Story</a></li>
                </ul>
                <br />
                <div id="copyrightstatement">
						&copy; 2011 All Rights Reserved by <a href="http://www.bookex.info">BookEx</a>
					<div id="validator">
<p><a href="http://validator.w3.org/check?uri=http://bookex.info/error.php"><img src="http://www.w3.org/Icons/valid-xhtml11-blue" alt="Valid XHTML 1.1" style="border:0px;width:88px;height:31px;" /></a>
<a href="http://jigsaw.w3.org/css-validator/validator?uri=http://bookex.info/error.php"><img src="http://www.w3.org/Icons/valid-css2-blue" alt="Valid CSS!" style="border:0px;width:88px;height:31px;" /></a></p>
					</div>
                </div>
        </div>
</div>
</body>
</html>