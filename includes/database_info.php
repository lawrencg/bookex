<?php
		# Author: Lawrence Gabriel
		# Email: shanzha@uw.edu
		# Date: May 11, 2011
		# Title: This is a single point of change for the BookEx web applicaton database.

		# Database connection parameters
		$DATABASE = "larry_test";
		$DB_USER = "shanzha";
		$DB_PASSWORD = "lawrence";
		$DB_CONNECT_STRING = "host=vergil.u.washington.edu port=10450 dbname=" . $DATABASE . " user=" . $DB_USER . " password=" . $DB_PASSWORD;
		
		# Connect to database
		$dbconn = pg_connect($DB_CONNECT_STRING) or die('Could not connect: ' . pg_last_error());
?>