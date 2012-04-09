<?php
        /* Once this file is changed you need to copy and paste it to the static pages.
         * index.html
         * terms.html
         * ourstory.html
         */
?>
        <div id="footer">
                <br />
                <ul id="footernavigation">
                        <li id="privacy"><a href="#">Privacy</a></li>
                        <li id="terms"><a href="http://www.bookex.info/terms.html">Terms</a></li>
                        <li id="contact"><a href="contact.php">Contact</a></li>
                        <li id="help"><a href="#">Help</a></li>
                        <li id="ourstory"><a href="http://www.bookex.info/ourstory.html">Our Story</a></li>
                </ul>
                <br />
                <div id="copyrightstatement">
						&copy; 2011 All Rights Reserved by <a href="http://www.bookex.info">BookEx</a>
					<div id="validator">
						<?php
							global $current;
							echo '<p><a href="http://validator.w3.org/check?uri='.htmlspecialchars($current).'"><img src="http://www.w3.org/Icons/valid-xhtml11-blue" alt="Valid XHTML 1.1" style="border:0px;width:88px;height:31px;" /></a>';
							echo '<a href="http://jigsaw.w3.org/css-validator/validator?uri='.htmlspecialchars($current).'"><img src="http://www.w3.org/Icons/valid-css2-blue" alt="Valid CSS!" style="border:0px;width:88px;height:31px;" /></a></p>';
						?>
					</div>
                </div>
        </div>
</div>
</body>
</html>
