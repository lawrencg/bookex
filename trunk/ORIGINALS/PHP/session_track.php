<?php
        # Author: Lawrence Gabriel
        # Email: shanzha@uw.edu
        # Date: May 11, 2011
        # Title: Used to track the previous page visited on the site. Used for the bug submission form so
        #        we can hopefully get information on where the user was when they encountered a problem.

        session_start();
        $current= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        # Might not work properly if they visit the submitbug.php page directly.
        $_SESSION['previouspage'][1]=$_SESSION['previouspage'][0];
        $_SESSION['previouspage'][0]=$current;
?>