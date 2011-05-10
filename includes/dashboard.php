<?php
	include 'menu.php';
	include "search.php";
	include 'greeting.php';
	
	$DATABASE = "larry_test";
	$DB_USER = "shanzha";
	$DB_PASSWORD = "lawrence";
	$DB_CONNECT_STRING = "host=vergil.u.washington.edu port=10450 dbname=" . $DATABASE . " user=" . $DB_USER . " password=" . $DB_PASSWORD;
	
	$user = $_SERVER['REMOTE_USER'];
	
	function comments(){
	#	.button-container form,
	#	.button-container form div {
	#	    display: inline;
	#	}

	#	.button-container button {
	#	    display: inline;
	#	    vertical-align: middle;
	#	}

	#	<div class="button-container">
	#	    <form action="confirm.php" method="post">
	#		<div>
	#			<button type="submit">Confirm</button>
	#		</div>
	#	    </form>

	#	    <form action="cancel.php" method="post">
	#		<div>
	#			<button type="submit">Cancel</button>
	#		</div>
	#	    </form>
	#	</div>
	}

	function createbutton($name, $label, $bookid){
		echo "<form action='' id='form_98' name='form_98' method='POST'>";
		echo "<input type='hidden' value='{$bookid}' id='transid' name='transid' />";
		echo "<input type='submit' id='{$name}' name='{$name}' value='{$label}' />";
		echo "</form>";	
	}
	function myrequests(){
		global $user;
		$firsttime = true;
		$yourequested = pg_query("SELECT * FROM detailedtransactions WHERE recipientid = '" . $user . "' AND transstatus = 'Requested'") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($yourequested)) {
			if ($firsttime){
				echo "<h3>Your Requests</h3>\n";
				echo "<p>You have requested to borrow \"{$records[3]}\" from {$records[6]}. ";
				createbutton('cancelrequest','Cancel',$records[0]);
				echo "</p>\n";
				$firsttime = false;
			} else {
				echo "<p>You have requested to borrow \"{$records[3]}\" from {$records[6]}. ";
				createbutton('cancelrequest','Cancel',$records[0]);
				echo "</p>\n";
			}
		}
	}
	function othersrequests(){
		global $user;
		$theyrequested = pg_query("SELECT * FROM detailedtransactions WHERE myid = '" . $user . "' AND transstatus = 'Requested'") 
			or die('Query failed: ' . pg_last_error()); 
		$firsttime = true;
		while($records = pg_fetch_array($theyrequested)) {
			if ($firsttime){
				echo "<h3>Others have Requested</h3>\n";
				echo "<p>{$records[5]} has requested to borrow \"{$records[3]}\" ";
				createbutton('accept','Accept',$records[0]);
				echo "&nbsp;";
				createbutton('deny','Deny',$records[0]);
				echo "</p>\n";
				$firsttime = false;
			} else {	
				echo "<p>{$records[5]} has requested to borrow \"{$records[3]}\" ";
				createbutton('acceptrequest','Accept',$records[0]);
				echo "&nbsp;";
				createbutton('deny','Deny',$records[0]);
				echo "</p>\n";
			}
		}
	}
	function deliveryconfirmations(){
		global $user;
		$awaiting = pg_query("SELECT * FROM detailedtransactions WHERE myid = '" . $user . "' AND transstatus = 'Awaiting Delivery'") 
		or die('Query failed: ' . pg_last_error()); 
		$firsttime = true;
		while($records = pg_fetch_array($awaiting)) {
			if ($firsttime) {
				echo "<h3>Delivery Confirmations</h3>\n";
				echo "<p>Have you delivered \"{$records[3]}\" to {$records[5]}? "; 
				createbutton('delivered','Delivered',$records[0]);
				echo "&nbsp;";
				createbutton('deny','Deny',$records[0]);
				echo "</p>\n";
				$firsttime = false;
			} else {
				echo "<p>Have you delivered \"{$records[3]}\" to {$records[5]}? "; 
				createbutton('delivered','Delivered',$records[0]);
				echo "&nbsp;";
				createbutton('deny','Deny',$records[0]);
				echo "</p>\n";
			}
		}
	}
	function receiptconfirmations(){
		global $user;
		$firsttime = true;
		$delivered = pg_query("SELECT * FROM detailedtransactions WHERE recipientid = '" . $user . "' AND transstatus = 'Delivered'") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($delivered)) {
			if ($firsttime) {
				echo "<h3>Receipt Confimation</h3>\n";
				echo "<p>Have you received \"{$records[3]}\" from {$records[6]}?";
				createbutton('confirmdelivery','Received',$records[0]);
				echo "</p>\n";
				$firsttime = false;
			} else {
				echo "<p>Have you received \"{$records[3]}\" from {$records[6]}?";
				createbutton('confirmdelivery','Received',$records[0]);
				echo "</p>\n";
			}
		}
	}
	function returnconfirmations(){
		global $user;
		$firsttime = true;
		$returned = pg_query("SELECT * FROM detailedtransactions WHERE myid = '" . $user . "' AND transstatus = 'Returned'") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($returned)) {
			if ($firsttime) {
				echo "<h3>Return Confirmations</h3>\n";
				echo "<p>Has {$records[5]} returned your \"{$records[3]}\" book?";
				createbutton('confirmreturnedbook','Returned',$records[0]);
				echo "</p>\n";
				$firsttime = false;
			} else {
				echo "<p>Has {$records[5]} returned your \"{$records[3]}\" book?";
				createbutton('confirmreturnedbook','Returned',$records[0]);
				echo "</p>\n";
			}
		}
	}
	function imborrowing(){
		global $user;
		$firsttime = true;	
		echo "<h3>Books I'm borrowing</h3>\n";
		$returned = pg_query("SELECT * FROM detailedtransactions WHERE recipientid = '" . $user . "' AND transstatus = 'Received'") 
			or die('Query failed: ' . pg_last_error()); 
		while($records = pg_fetch_array($returned)) {
			if ($firsttime) {
				echo "<table border='2px'>";
				echo "<th>Title</th><th>Lender</th><th>Due Date</th><th></th>";
				echo "<tr><td>{$records[3]}</td>";
				echo "<td>{$records[6]}</td>";
				echo "<td>".date("F j, Y")."</td><td>";
				createbutton('return','Return',$records[0]);
				echo "</td></tr>";
				$firsttime = false;
			} else {
				echo "<tr><td>{$records[3]}</td>";
				echo "<td>{$records[6]}</td>";
				echo "<td>".date("F j, Y")."</td><td>";
				createbutton('return','Return',$records[0]);
				echo "</td></tr>";
			}
		}
		if($firsttime)
			echo "<p><i>&nbsp;&nbsp;&nbsp;You are currently not borrwing and books.</i></p>"; 
		else
			echo "</table>";
	}
	function notifications(){
		global $user;
		#$awaiting = pg_query("SELECT * FROM detailedtransactions WHERE myid = '" . $user . "' AND transstatus = 'Awaiting Delivery'") 
		#or die('Query failed: ' . pg_last_error()); 
		#$firsttime = true;
		#while($records = pg_fetch_array($awaiting)) {
		#	if ($firsttime) {
				echo "<h3>Notifications</h3>\n";
				echo "<p style='color:red'><i>&nbsp;&nbsp;&nbsp;BookEx is currently under maintenance. Some features may be temporarily unavailable."; 
				echo "</i></p>\n";
		#		$firsttime = false;
		#	} else {
		#		echo "<p>Have you delivered \"{$records[3]}\" to {$records[5]}? "; 
		#		createbutton('delivered','Delivered',$records[0]);
		#		echo "</p>\n";
		#	}
	}
	$dbconn = pg_connect($DB_CONNECT_STRING)
    		or die('Could not connect: ' . pg_last_error());
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['accept'])){
				pg_query("SELECT acceptbookrequest('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
					or die('Query failed: ' . pg_last_error()); 
		} else if (isset($_POST['delivered'])){
				pg_query("SELECT deliverbook('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
					or die('Query failed: ' . pg_last_error()); 
		} else if (isset($_POST['confirmdelivery'])){
				pg_query("SELECT confirmdelivery('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
					or die('Query failed: ' . pg_last_error()); 
		} else if (isset($_POST['return'])){
				pg_query("SELECT returnbooktoowner('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
					or die('Query failed: ' . pg_last_error()); 
		} else if (isset($_POST['confirmreturnedbook'])){
				pg_query("SELECT confirmreturnedbook('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
					or die('Query failed: ' . pg_last_error()); 
		} else if (isset($_POST['cancelrequest'])){
				pg_query("SELECT cancelrequest('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
					or die('Query failed: ' . pg_last_error()); 
		} else if (isset($_POST['deny'])){
				pg_query("SELECT denybookrequest('{$_POST['transid']}'::integer,'" . $user . "'::varchar)") 
					or die('Query failed: ' . pg_last_error()); 
		} else {
		}
	}	
	myrequests();
	othersrequests();
	deliveryconfirmations();
	receiptconfirmations();
	returnconfirmations();
	notifications();
	imborrowing();
	
pg_close($dbconn);
?>
