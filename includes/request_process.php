<?php
	function request_button($bookid){
		global $DB_CONNECT_STRING;
		$user = $_SERVER['REMOTE_USER'];
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$result = pg_query("SELECT verifystillavailable('{$bookid}'::integer)") or die('Query failed: ' . pg_last_error()); 
		$stillavailable = pg_fetch_array($result);
		$result2 = pg_query("SELECT alreadyrequested('{$user}'::varchar,'{$bookid}'::integer)") or die('Query failed: ' . pg_last_error()); 
		$alreadyrequested = pg_fetch_array($result2);
		#pg_close($dbconn);
		echo "<form action='' id='form_99' name='form_99' method='POST'>";
		if ($alreadyrequested[0] == t){
			echo "<input type='submit' id='notrequest' name='notrequest' value='Pending Request' disabled />\n";
		} elseif ($stillavailable[0] == f){
			echo "<input type='submit' id='notrequest' name='notrequest' value='Not Available' disabled />\n";
		}else {
			echo "<input type='hidden' value='{$bookid}' id='book_id' name='book_id' />\n";
			echo "<input type='submit' id='request' name='request' value='Request' />\n";
		}	 
		echo "</form>";	
	}
	function initiate_request($bookid){
		global $DB_CONNECT_STRING;
		$user = $_SERVER['REMOTE_USER'];
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$results = pg_query("SELECT requestbook('{$bookid}'::integer,'{$user}'::varchar)") 
			or die('Query failed: ' . pg_last_error()); 
		#pg_close($dbconn);
	}
	if(isset($_POST['request']) && $_POST['book_id'] != null)
		initiate_request($_POST['book_id']);

?>
