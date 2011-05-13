<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: Display a users information. Will be displayed differently if the user is looking at their own profile.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	include 'session_track.php';
	# Database connection parameters
	include 'database_info.php';
	include 'menu.php';
	include 'search.php';
	include 'greeting.php';

	$DATABASE = "larry_test";
	$DB_USER = "shanzha"; 
	$DB_PASSWORD = "lawrence";
	$DB_CONNECT_STRING = "host=vergil.u.washington.edu port=10450 dbname=" . $DATABASE . " user=" . $DB_USER . " password=" . $DB_PASSWORD;
	
	$user = $_SERVER['REMOTE_USER'];
	# GLOBAL VARIABLES
	# BookEx book id
	$myinfoNetID = pg_escape_string($_POST['myinfoNetID']);
	$myinfoFirstName = pg_escape_string($_POST['myinfoFirstName']);
	$myinfoLastName = pg_escape_string($_POST['myinfoLastName']);
	$myEmail = pg_escape_string($_POST['myEmail']);
	$myMajor = pg_escape_string($_POST['myMajor']);	
	
	include 'request_process.php';

	
	# ---------------------------------------- #
	#                                          #
	# ----------FUNCTIONS BEGIN HERE---------- #
	#                                          #
	# ---------------------------------------- #
	
		function getinfofromBookEx($currentId){
		# Global variables
		//global $myinfoNetID, $myinfoFirstName, $myinfoLastName, $myEmail, $myMajor, $DB_CONNECT_STRING;
		global $DB_CONNECT_STRING;
		# Connect to the database
		$dbconn = pg_connect($DB_CONNECT_STRING)
			or die('Could not connect: ' . pg_last_error());
		$myinfo2 = "SELECT * FROM getmyinfo('" . $currentId . "') AS results(id varchar, fname varchar, lname varchar, email varchar, major varchar)";
		$myinfoResult2 = pg_query($dbconn, $myinfo2); 			
		if (!$myinfoResult2) {
			die("Error in SQL query: " . pg_last_error());
		}

/////// No output generated from this query here
		while ($row = pg_fetch_array($myinfoResult2)) {
			$myinfoNetID = $row[0];
			$myinfoFirstName = $row[1];
			$myinfoLastName = $row[2];
			$myEmail = $row[3];
			$myMajor = $row[4];

			echo"
			<input type='hidden' value='{$myinfoFirstName}' id='myinfoFirstName' name='myinfoFirstName' />
			<b>First Name: <span style='font-weight:normal;'></b>&nbsp;" . $myinfoFirstName . "<br />
			<input type='hidden' value='{$myinfoLastName}' id='myinfoLastName' name='myinfoLastName' />
			<b>Last Name: <span style='font-weight:normal;'></b>&nbsp;" . $myinfoLastName . "<br / >
			<input type='hidden' value='{$myinfoNetID}' id='myinfoNetID' name='myinfoNetID' />
			<b>UW NetID: <span style='font-weight:normal;'></b>&nbsp;" . $myinfoNetID . "<br / >			
			<input type='hidden' value='{$myEmail}' id='myEmail' name='myEmail' />
			<b>E-mail: <span style='font-weight:normal;'></b>&nbsp;" . $myEmail . "<br / >			
			<input type='hidden' value='{$myMajor}' id='myMajor' name='myMajor' />
			<b>Major: <span style='font-weight:normal;'></b>&nbsp;" . $myMajor . "<br / >";				
			
		}
		# Close the database
		pg_close($dbconn);
		}
		
		
		// HTML used to edit a form populated with profile information.
		function editProfile() {
		# Global variables
		global $myinfoNetID, $myinfoFirstName, $myinfoLastName, $myEmail, $myMajor, $DB_CONNECT_STRING;		
		echo"
		<input type='hidden' value='{$myinfoFirstName}' id='myinfoFirstName' name='myinfoFirstName' />
		<b>First Name: <span style='font-weight:normal;'></b>&nbsp;<input type='text' value='" . $myinfoFirstName . "' id='myinfoFirstName' name='myinfoFirstName' size='40' /><br />
		<input type='hidden' value='{$myinfoLastName}' id='myinfoLastName' name='myinfoLastName' />
		<b>Last Name: <span style='font-weight:normal;'></b>&nbsp;<input type='text' value='" . $myinfoLastName . "' id='myinfoLastName' name='myinfoLastName' size='40' /><br />			 
		<input type='hidden' value='{$myinfoNetID}' id='myinfoNetID' name='myinfoNetID' />
		<b>UW NetID: <span style='font-weight:normal;'></b>&nbsp;<input type='text' value='" . $myinfoNetID . "' id='myinfoNetID' name='myinfoNetID' size='40' /><br />
		<input type='hidden' value='{$myEmail}' id='myEmail' name='myEmail' />
		<b>E-mail: <span style='font-weight:normal;'></b>&nbsp;<input type='text' value='" . $myEmail . "' id='myEmail' name='myEmail' size='40' /><br />
		<input type='hidden' value='{$myMajor}' id='myMajor' name='myMajor' />
		<b>Major: <span style='font-weight:normal;'></b>&nbsp;<input type='text' value='" . $myMajor . "' id='myMajor' name='myMajor' size='40' /><br />";
		
		pg_close($dbconn);
		
		echo "<input type='submit' name='saveID' value='Save Changes' />";
		
		}
		 
		 
		function filledProfile() {
		global $myinfoNetID, $myinfoFirstName, $myinfoLastName, $myEmail, $myMajor, $DB_CONNECT_STRING;
		echo"
		<input type='hidden' value='{$myinfoFirstName}' id='myinfoFirstName' name='myinfoFirstName' />
		<b>First Name: <span style='font-weight:normal;'></b>&nbsp;" . $myinfoFirstName . "<br />
		<input type='hidden' value='{$myinfoLastName}' id='myinfoLastName' name='myinfoLastName' />
		<b>Last Name: <span style='font-weight:normal;'></b>&nbsp;" . $myinfoLastName . "<br / >
		<input type='hidden' value='{$myinfoNetID}' id='myinfoNetID' name='myinfoNetID' />
		<b>UW NetID: <span style='font-weight:normal;'></b>&nbsp;" . $myinfoNetID . "<br / >			
		<input type='hidden' value='{$myEmail}' id='myEmail' name='myEmail' />
		<b>E-mail: <span style='font-weight:normal;'></b>&nbsp;" . $myEmail . "<br / >			
		<input type='hidden' value='{$myMajor}' id='myMajor' name='myMajor' />
		<b>Major: <span style='font-weight:normal;'></b>&nbsp;" . $myMajor . "<br / >";	
		
		//pg_close($dbconn);
		
		echo "<input type='submit' name='edit' value='Edit Information' />";	
		
		}
		
		function savemyinfo(){
			global $myinfoFirstName, $myinfoLastName, $myEmail;
			//global $dbconn;
			//$dbconn2 = pg_connect($dbconn)
			//    or die('Could not connect: ' . pg_last_error());
			$user2 = $_SERVER['REMOTE_USER'];
			pg_query("SELECT savemyinfo('{$myinfoFirstName}'::varchar,'{$myinfoLastName}'::varchar, '{$myEmail}'::varchar, '{$user2}'::varchar)") 
				or die('Query failed: ' . pg_last_error()); 
		}
	
	# ---------------------------------------- #
	#                                          #
	# -----------FUNCTIONS END HERE----------- #
	#                                          #
	# ---------------------------------------- #	
	
	
	
	
	if(!isset($_GET['id'])){
		echo "No user choosen.";
	} else {
		$dbconn = pg_connect($DB_CONNECT_STRING)
		    or die('Could not connect: ' . pg_last_error());
		$currentId = $_GET['id'];
		// Case if profile is another user's profile.
		if ($currentId != $user) {
		
			$imageURL = "SELECT users.profile_pic FROM users WHERE id = '$currentId'";
			$imageURLResult = pg_query($dbconn, $imageURL);
			if (!$imageURLResult) {
				die("Error in SQL query: " . pg_last_error());
			}

			while ($row = pg_fetch_array($imageURLResult)) {
				 $currentPictureURL = $row[0];
			}

			echo '<img src="profile-pics/thumbs/'.$currentPictureURL.'"><br/>';
			
			
			$myinfo = "SELECT * FROM getmyinfo('" . $currentId . "') AS results(id varchar, first_name varchar, last_name varchar, email varchar, major varchar)";
			$myinfoResult = pg_query($dbconn, $myinfo); 			
			if (!$myinfoResult) {
				die("Error in SQL query: " . pg_last_error());
			}
			 
			while ($row = pg_fetch_array($myinfoResult)) {
				$myinfoNetID = $row[0];
				$myinfoFirstName = $row[1];
				$myinfoLastName = $row[2];
				$myEmail = $row[3];
				echo "First Name: <span style='font-weight:normal;'>" . $myinfoFirstName . "</span><br/>";
				echo "Last Name: <span style='font-weight:normal;'>" . $myinfoLastName . "</span><br/>";
				echo "UW NetID: <span style='font-weight:normal;'>" . $myinfoNetID . "</span><br/>";
				echo "E-mail: <span style='font-weight:normal;'>" . $myEmail . "</span><br/>";	
				echo "Major: <br/>";		
			}
			
		
			$available = pg_query("SELECT * FROM availablebooksfromuser('{$_GET['id']}'::varchar) 
				VALUES( book_id int, title varchar, isbn10 numeric, isnb13 numeric, author text)") 
				or die('Query failed: ' . pg_last_error()); 
			$firsttime = true;
			while($records = pg_fetch_array($available)) {
				if ($firsttime) {
					echo "<h3>Book List for {$_GET['id']}</h3>\n";
					echo "<table border='2px'><th width='250px'>Title</th><th width='200px'>Author</th>
					<th width='100px'>ISBN-10</th><th width='100px'>ISBN-13</th><th width='300px'></th>";
					echo "<tr>";
					echo "<td><a href='bookdetail.php?id={$records[0]}'>{$records[1]}</a></td>
					<td>{$records[4]}</td><td>{$records[2]}</td><td>{$records[3]}</td><td>";
					request_button($records[0]); 
					echo "</td>\n";
					echo "</tr>";
					$firsttime = false;
				} else {
					echo "<tr>";
					echo "<td><a href='bookdetail.php?id={$records[0]}'>{$records[1]}</a></td>
					<td>{$records[4]}</td><td>{$records[2]}</td><td>{$records[3]}</td><td>"; 
					request_button($records[0]);
					echo "</td>\n";
					echo "</tr>";
				}
			}
			// Case if profile is the current user's profile.
		} else {
			echo "<h1>My Profile</h1>";
			
			# ---------------------------------------- #
			#                                          #
			# ---UPLOAD PICTURE PROCESS STARTS HERE--- #
			#                                          #
			# ---------------------------------------- #
			
			 //define a maxim size for the uploaded images
			 define ("MAX_SIZE","1000"); 
			 // define the width and height for the thumbnail
			 // note that theese dimmensions are considered the maximum dimmension and are not fixed, 
			 // because we have to keep the image ratio intact or it will be deformed
			 define ("WIDTH","150"); 
			 define ("HEIGHT","100"); 

			  // this is the function that will create the thumbnail image from the uploaded image
			 // the resize will be done considering the width and height defined, but without deforming the image
			 function make_thumb($img_name,$filename,$new_w,$new_h)
			 {
				//get image extension.
				$ext=getExtension($img_name);
				//creates the new image using the appropriate function from gd library
				if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext))
					$src_img=imagecreatefromjpeg($img_name);

				if(!strcmp("png",$ext))
					$src_img=imagecreatefrompng($img_name);

				if(!strcmp("gif",$ext))
					$src_img=imagecreatefromgif($img_name);	
					
					//gets the dimmensions of the image
				$old_x=imageSX($src_img);
				$old_y=imageSY($src_img);

				 // next we will calculate the new dimmensions for the thumbnail image
				// the next steps will be taken: 
				// 	1. calculate the ratio by dividing the old dimmensions with the new ones
				//	2. if the ratio for the width is higher, the width will remain the one define in WIDTH variable
				//		and the height will be calculated so the image ratio will not change
				//	3. otherwise we will use the height ratio for the image
				// as a result, only one of the dimmensions will be from the fixed ones
				$ratio1=$old_x/$new_w;
				$ratio2=$old_y/$new_h;
				if($ratio1>$ratio2)	{
					$thumb_w=$new_w;
					$thumb_h=$old_y/$ratio1;
				}
				else	{
					$thumb_h=$new_h;
					$thumb_w=$old_x/$ratio2;
				}

				// we create a new image with the new dimmensions
				$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);

				// resize the big image to the new created one
				imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 

				// output the created image to the file. Now we will have the thumbnail into the file named by $filename
				if(!strcmp("png",$ext))
					imagepng($dst_img,$filename); 
				else
					imagejpeg($dst_img,$filename); 

				//destroys source and destination images. 
				imagedestroy($dst_img); 
				imagedestroy($src_img); 
			 }

			 // This function reads the extension of the file. 
			 // It is used to determine if the file is an image by checking the extension. 
			 function getExtension($str) {
					 $i = strrpos($str,".");
					 if (!$i) { return ""; }
					 $l = strlen($str) - $i;
					 $ext = substr($str,$i+1,$l);
					 return $ext;
			 }
			 
			 // This variable is used as a flag. The value is initialized with 0 (meaning no error found) 
			 // and it will be changed to 1 if an errro occures. If the error occures the file will not be uploaded.
			 $errors=0;
			 // checks if the form has been submitted
			 if(isset($_POST['Submit']))
			 {
			 //reads the name of the file the user submitted for uploading
				$image=$_FILES['image']['name'];
				// if it is not empty
				if ($image) 
				{
					// get the original name of the file from the clients machine
					$filename = stripslashes($_FILES['image']['name']);
					
					// get the extension of the file in a lower case format
					$extension = getExtension($filename);
					$extension = strtolower($extension);
					// if it is not a known extension, we will suppose it is an error, print an error message 
					// and will not upload the file, otherwise we continue
					if (($extension != "jpg")  && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
					{
						echo '<h1>Unknown extension!</h1>';
						$errors=1;
					}
					else
					{
						// get the size of the image in bytes
						// $_FILES[\'image\'][\'tmp_name\'] is the temporary filename of the file in which 
						//the uploaded file was stored on the server
						$size=getimagesize($_FILES['image']['tmp_name']);
						$sizekb=filesize($_FILES['image']['tmp_name']);

						//compare the size with the maxim size we defined and print error if bigger
						if ($sizekb > MAX_SIZE*1024)
						{
							echo '<h1>You have exceeded the size limit!</h1>';
							$errors=1;
						}

						//we will give an unique name, for example the time in unix time format
						$image_name=time().'.'.$extension;
						//the new name will be containing the full path where will be stored (profile-pics folder)
						$newname="profile-pics/".$image_name;
						$copied = copy($_FILES['image']['tmp_name'], $newname);
						//we verify if the image has been uploaded, and print error instead
						if (!$copied) 
						{
							echo '<h1>Copy unsuccessfull!</h1>';
							$errors=1;
						}
						else
						{
							// the new thumbnail image will be placed in profile-pics/thumbs/ folder
							$thumb_name='profile-pics/thumbs/thumb_'.$image_name;
							$thumb_file='thumb_'.$image_name;
							// call the function that will create the thumbnail. The function will get as parameters 
							// the image name, the thumbnail name and the width and height desired for the thumbnail
							$thumb=make_thumb($newname,$thumb_name,WIDTH,HEIGHT);
						}}	}}

			  //If no errors registred, print the success message and show the thumbnail image created
			 if(isset($_POST['Submit']) && !$errors) 
			 {
				echo "<h1>Image uploaded Successfully!</h1>";
				$imageInsertSQL = "UPDATE users SET profile_pic='$thumb_file' WHERE id ='$user'";
				$imageInsertResult = pg_query($dbconn, $imageInsertSQL);
				if (!$imageInsertResult) {
					die("Error in SQL query: " . pg_last_error());
				}
			 }
			 
			# -------------------------------------- #
			#                                        #
			# ---UPLOAD PICTURE PROCESS ENDS HERE--- #
			#                                        #
			# -------------------------------------- #
			
			

			
			 
			$imageURL = "SELECT users.profile_pic FROM users WHERE id = '$user'";
			$imageURLResult = pg_query($dbconn, $imageURL);
			if (!$imageURLResult) {
				die("Error in SQL query: " . pg_last_error());
			}

			while ($row = pg_fetch_array($imageURLResult)) {
				 $currentPictureURL = $row[0];
			}

			echo "<h2>Current Picture:<br/>";
			echo '<img src="profile-pics/thumbs/'.$currentPictureURL.'">';
				
			echo '<form name="newad" method="post" enctype="multipart/form-data"  action=""><table><tr><td><input type="file" name="image"></td></tr><tr><td><input name="Submit" type="submit" value="Upload image"></td></tr></table></form>';
			 
			/*
			$myinfo = "SELECT * FROM getmyinfo('" . $user . "') AS results(id varchar, first_name varchar, last_name varchar, email varchar, major integer, pic varchar)";
			$myinfoResult = pg_query($dbconn, $myinfo); 			
			if (!$myinfoResult) {
				die("Error in SQL query: " . pg_last_error());
			}
			 
			while ($row = pg_fetch_array($myinfoResult)) {
				$myinfoNetID = $row[0];
				$myinfoFirstName = $row[1];
				$myinfoLastName = $row[2];
				$myEmail = $row[3];
				echo "First Name: <span style='font-weight:normal;'>" . $myinfoFirstName . "</span><br/>";
				echo "Last Name: <span style='font-weight:normal;'>" . $myinfoLastName . "</span><br/>";
				echo "UW NetID: <span style='font-weight:normal;'>" . $myinfoNetID . "</span><br/>";
				echo "E-mail: <span style='font-weight:normal;'>" . $myEmail . "</span><br/>";	
				echo "Major: <br/>";		
			}
			*/
				
			
			getinfofromBookEx($user);
			//filledProfile();	
			
			//echo "<a href='editprofile.php'>-->Edit My Information</a>\n";
			echo "<input type='submit' name='edit' value='Edit Information' />";
			echo "<input type='submit' name='saveID' value='Save' />";	
			
			//if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
			//	if(isset($_POST['edit'])) {
			//		editProfile();
			//	}
			//}
			
			if (pg_escape_string($_POST['edit'])){
				echo "test";
			}
			
			if (pg_escape_string($_POST['saveID'])){
				echo "save works";
			}
			
			
			//if (pg_escape_string($_POST['saveID'])){
			//	savemyinfo();
			//	echo '<META HTTP-EQUIV="refresh" content="0;URL=https://students.washington.edu/shanzha/myprofile.php">';
			//	exit;
			//}
			
			
			}
				//pg_close($dbconn);
					echo "</table>";	
		}
		
		
?>
