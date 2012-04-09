<?php
// error handler function
function bookex_error_handler($errno, $errstr, $errfile, $errline)
{
	switch ($errno)
	{
		case E_USER_ERROR:
			echo "Sorry, a fatal error occurred. ";
			echo "The administrator will be notified.";
			$str="[$errono] Fatal error on line $errline in file $errfile\n";
			$str.= "$errstr";
			LogError ($str);
			email_error($str);
			exit(1);
			break;
		case E_USER_WARNING:
			LogError ("[$errno] WARNING $errstr<br />\n");
			email_error($str);
			break;
		case E_USER_NOTICE:
			LogError ("[$errno] NOTICE $errstr<br />\n");
			email_error($str);
			break;
		default:
			LogError ("Unknown error type: [$errno] $errstr<br />\n");
			email_error($str);
			break;
	}
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