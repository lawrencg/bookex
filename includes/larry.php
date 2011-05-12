<?php
$from = 'bookex@washington.edu';
$message = 'This is a test message.';
$subject = 'Welcome to BookEx!';
$to = 'shanzha@washington.edu';
$headers = 'From: BookEx<' . $from . '>' . "\r\n" .
'Reply-To: No-Reply<bookex@bookex.ischool.uw.edu>' . "\r\n" .
'X-Mailer: PHP/' . phpversion();
mail($to,$subject,$message,$headers);
?>       