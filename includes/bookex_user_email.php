<?php
	function request_email($book_id, $person){
		$results = pg_query("SELECT * FROM bookdetails WHERE bookid='{$book_id}'");
			//or die('Query failed: ' . pg_last_error()); 
		$records = pg_fetch_array($results)) {
		$owner_id = $records[0];
		$title = $records[2];
		
		$results = pg_query("SELECT * FROM getemailinfo('{$owner_id}')");
		$records = pg_fetch_array($results) 
		$name = $records[0];
		$email_address = $records[1];
	
		$results = pg_query("SELECT getbookexname('" . $user . "')") or die('Query failed: ' . pg_last_error()); 
		$records = pg_fetch_array($results);
		$person = $records[0];
		$message = "Hello {$name},\n\n{$person} has requested to borrow {$title} from you. Please login to http://www.bookex.info/dashboard.php to accept or deny the request.\n\n";
		$message .= "BookEx Team";
		
		send_email('Book Request from BookEx.info',$message,$email_address,null);
		
	}
	function send_email($subject,$message,$to,$from){
		$from = 'bookex@u.washington.edu';
		//$to = 'bookex@u.washington.edu';
		$headers = 'From: BookEx<' . $from . '>' . "\r\n" .
		'Reply-To: BookEx<bookex@u.washington.edu>' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();		
		mail($to,$subject,$message,$headers);
	}
?>