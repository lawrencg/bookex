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
// error handler function

function bookex_error_handler($errno, $errstr, $errfile, $errline)
{
	$locations = "\nCurrent Page:\n" . $_SESSION['previouspage'][0]; 
	$locations .= "\nPrevious Page:\n" . $_SESSION['previouspage'][1];
	switch ($errno)
	{
		case E_USER_ERROR:
			$str="[$errono] Fatal error on line $errline in file $errfile\n";
			$str.= "$errstr";
			LogError ($str);
			$str .= $locations;
			email_error($str);
			exit(1);
			break;
		case E_USER_WARNING:
			$str = "[$errno] WARNING $errstr\n";
			LogError ($str);
			$str .= $locations;
			email_error($str);
			break;
		case E_USER_NOTICE:
			$str = "[$errno] NOTICE $errstr\n";
			LogError ($str);
			$str .= $locations;
			email_error($str);
			break;
		default:
			$str = "Unknown error type: [$errno] $errstr\n";
			LogError ($str);
			$str .= $locations;
			email_error($str);
			break;
	}
	include '../error.php';
	/* Don't execute PHP internal error handler */
	return true;
}
function email_error($error_message){
	$system_message = "BookEx Error\n\n";
	$from = 'bookex@u.washington.edu';
	$message = $system_message . $error_message;
	$subject = 'BookEx Error';
	$to = 'bookex@u.washington.edu';
	$headers = 'From: BookEx<' . $from . '>' . "\r\n" .
		'Reply-To: BookEx<bookex@u.washington.edu>' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();		
	mail($to,$subject,$message,$headers);
}
// set to the user defined error handler
$old_error_handler = set_error_handler("bookex_error_handler");
?>