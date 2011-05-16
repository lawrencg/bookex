<?php
	# Author: Lawrence Gabriel
	# Email: shanzha@uw.edu
	# Date: May 11, 2011
	# Title: Display a users information. Will be displayed differently if the user is looking at their own profile.
	
	# Session tracking for the bug submission form. Needs to be before ANY HTML.
	require 'includes/session_track.php';
	# Database connection parameters
	require 'includes/database_info.php';
	include 'includes/valid_user.php';
	include 'includes/request_process.php';
	
	

	
	$myinfoNetID = pg_escape_string($_POST['myinfoNetID']);
	$myinfoFirstName = pg_escape_string($_POST['myinfoFirstName']);
	$myinfoLastName = pg_escape_string($_POST['myinfoLastName']);
	$myEmail = pg_escape_string($_POST['myEmail']);
	$myMajor = pg_escape_string($_POST['myMajor']);
	$person = pg_escape_string($_POST['person']);
	$errormessage;
	$person = $_GET['id'];
	$user = $_SERVER['REMOTE_USER'];
	if($person == null){
		$person = $user;
	}
	
	
	
	function filledProfile() {
		global $myinfoNetID, $myinfoFirstName, $myinfoLastName, $myEmail, $myMajor, $person, $user;
	
		$myinfoResult2 = pg_query("SELECT * FROM getmyinfo('{$person}') AS results(id varchar, fname varchar, lname varchar, email varchar, major varchar)");
		if (!$myinfoResult2) {
			die("Error in SQL query: " . pg_last_error());
		}
		while ($row = pg_fetch_array($myinfoResult2)) {
			$myinfoNetID = $row[0];
			$myinfoFirstName = $row[1];
			$myinfoLastName = $row[2];
			$myEmail = $row[3];
			$myMajor = $row[4];
		}
		$picURL = pictureurl();
		echo '							<form id="defaultform" action="" name="form_99" method="POST" >';
		echo"
			<input type='hidden' value='{$person}' id='person' name='person' />
			<input type='hidden' value='{$myinfoFirstName}' id='myinfoFirstName' name='myinfoFirstName' />
			<input type='hidden' value='{$myinfoLastName}' id='myinfoLastName' name='myinfoLastName' />
			<input type='hidden' value='{$myinfoNetID}' id='myinfoNetID' name='myinfoNetID' />
			<input type='hidden' value='{$myEmail}' id='myEmail' name='myEmail' />
			<input type='hidden' value='{$myMajor}' id='myMajor' name='myMajor' />";
		echo '								<div><label>First Name:</label><div>'. $myinfoFirstName . '</div></div>';
		echo '								<div><label>Last Name:</label><div>' . $myinfoLastName . '</div></div>';
		echo '								<div><label>UW NetID:</label><div>' . $myinfoNetID . '</div></div>';
		echo '								<div><label>E-mail:</label><div>' . $myEmail . '</div></div>';
		echo '								<div><label>Major:</label><div>' . $myMajor . '</div></div>';

		if($user == $person){
			echo '								<input type="submit" name="edit" value="Edit My Profile" />';
		}
		echo '							</form>';
		
		if($person != $_SERVER['REMOTE_USER']){
			userbooks();
		}
	}
	function editProfile() {
		# Global variables
		global $myinfoNetID, $myinfoFirstName, $myinfoLastName, $myEmail, $myMajor, $user;
		echo "<form action='' id='defaultform' name='profile' method='POST' enctype='multipart/form-data'>";
		/*
		echo"
			First Name: <span style='font-weight:normal;'>&nbsp;<input type='text' value='" . $myinfoFirstName . "' id='myinfoFirstName' name='myinfoFirstName' size='40' /><br /><br />
			Last Name: <span style='font-weight:normal;'>&nbsp;<input type='text' value='" . $myinfoLastName . "' id='myinfoLastName' name='myinfoLastName' size='40' /><br />	<br />		 
			UW NetID: <span style='font-weight:normal;'>&nbsp;" . $myinfoNetID . " <id='myinfoNetID' name='myinfoNetID' /><br /><br />
			E-mail: <span style='font-weight:normal;'>&nbsp;<input type='text' value='" . $myEmail . "' id='myEmail' name='myEmail' size='40' /><br /><br />
			Major: <span style='font-weight:normal;'>&nbsp;<input type='text' value='" . $myMajor . "' id='myMajor' name='myMajor' size='40' /><br /><br />
			Upload Photo: <input type='file' name='image'>";
		*/	
		echo "								<div><label>First Name:</label><div><input type='text' value='" . $myinfoFirstName . "' id='myinfoFirstName' name='myinfoFirstName' size='40' /></div></div>";
		echo "								<div><label>Last Name:</label><div><input type='text' value='" . $myinfoLastName . "' id='myinfoLastName' name='myinfoLastName' size='40' /></div></div>";
		echo "								<div><label>UW NetID:</label><div>" . $myinfoNetID . "</div></div>";
		echo "								<div><label>E-mail:</label><div><input type='text' value='" . $myEmail . "' id='myEmail' name='myEmail' size='40' /></div></div>";
		echo "								<div><label>Major:</label><div><input type='text' value='" . $myMajor . "' id='myMajor' name='myMajor' size='40' /></div></div>";
			
			
		echo "<br/><br/><input type='submit' name='saveID' value='Save Changes' />";
		echo "</form>";
		/*		 echo '<form name="newad" method="post" enctype="multipart/form-data"  
				 action=""><table><tr><td><input type="file" name="image"></td></tr><tr><td><input name="Submit" type="submit" value="Upload image"></td></tr></table></form>';
				 */
	}
	function savemyinfo(){
		global $user, $myinfoFirstName, $myinfoLastName, $myEmail, $myMajor, $errormessage;
		pg_query("SELECT savemyinfo('{$myinfoFirstName}'::varchar,'{$myinfoLastName}'::varchar, '{$myEmail}'::varchar, '{$myMajor}'::varchar, '{$user}'::varchar)")
			or die('Query failed: ' . pg_last_error());
		
		$image=$_FILES['image']['name'];
		// if it is not empty
		if ($image)
			uploadimage();
			
		$errormessage = "Your profile has been updated.";	
		
	}
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
				$thumb_h=$new_h;
				//$thumb_h=$old_y/$ratio1;
			}
			else	{
				$thumb_w=$new_w;
				$thumb_h=$new_h;
				//$thumb_w=$old_x/$ratio2;
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
	function getExtension($str) {
			$i = strrpos($str,".");
			if (!$i) { return ""; }
			$l = strlen($str) - $i;
			$ext = substr($str,$i+1,$l);
			return $ext;
		}
	function uploadimage(){
		global $user, $errormessage;
		define ("MAX_SIZE","1000");
		// define the width and height for the thumbnail
		// note that theese dimmensions are considered the maximum dimmension and are not fixed,
		// because we have to keep the image ratio intact or it will be deformed
				define ("WIDTH","130");
				define ("HEIGHT","180");
				// get the original name of the file from the clients machine
				$filename = stripslashes($_FILES['image']['name']);
	
				// get the extension of the file in a lower case format
				$extension = getExtension($filename);
				$extension = strtolower($extension);
				// if it is not a known extension, we will suppose it is an error, print an error message
				// and will not upload the file, otherwise we continue
				if (($extension != "jpg")  && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
				{
					$errormessage = 'Unsupported file type.';
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
						$errormessage = 'File size too big.';
					}
	
					//we will give an unique name, for example the time in unix time format
					$image_name=time().'.'.$extension;
					//the new name will be containing the full path where will be stored (profile-pics folder)
					$newname='images/profiles/tmp/'.$image_name;
					$copied = copy($_FILES['image']['tmp_name'], $newname);
					//we verify if the image has been uploaded, and print error instead
					if (!$copied)
					{
						$errormessage = 'Copy unsuccessfull.';
					}
					else
					{
						// the new thumbnail image will be placed in profile-pics/thumbs/ folder
						$thumb_name='images/profiles/'.$image_name;
						$thumb_file=$image_name;
						// call the function that will create the thumbnail. The function will get as parameters
						// the image name, the thumbnail name and the width and height desired for the thumbnail
						$thumb=make_thumb($newname,$thumb_name,WIDTH,HEIGHT);
						unlink($newname);
					}
				}	
				//If no errors registred, print the success message and show the thumbnail image created
				 if($errormessage == '')
				 {
				 	$errormessage = 'Image uploaded Successfully';
				 	$imageInsertSQL = "UPDATE users SET profile_pic='$thumb_file' WHERE id ='{$user}'";
				 	$imageInsertResult = pg_query($imageInsertSQL);
				 	if (!$imageInsertResult) {
				 		die("Error in SQL query: " . pg_last_error());
				 	}
				 }
			}
	function pictureurl(){
			global $person;	
	
			$imageURL = "SELECT users.profile_pic FROM users WHERE id = '{$person}'";
			$imageURLResult = pg_query($imageURL);
			if (!$imageURLResult) {
				die("Error in SQL query: " . pg_last_error());
			}
	
			while ($row = pg_fetch_array($imageURLResult)) {
				$currentPictureURL = $row[0];
			}
			return $currentPictureURL;
	
	}
	function userbooks(){	
			global $person;
			$result = pg_query("SELECT getbookexname('{$person}'::varchar)") 
					or die('Query failed: ' . pg_last_error());
			$row = pg_fetch_array($result);
			$real_name = $row[0];
			$available = pg_query("SELECT * FROM availablebooksfromuser('{$person}'::varchar)
					VALUES( book_id int, title varchar, isbn10 numeric, isnb13 numeric, author text)") 
					or die('Query failed: ' . pg_last_error());
			echo '						</div>';
			echo '						<div class="clear"></div>';
			echo '					</div>';
			echo '					<div id="maincontent">';
			echo '						<div id="searchresultsarea" class="contentarea">';
			echo '							<div id="booksearchresults">';
			echo '								<div class="pageSubTitle">Books List for ' . $real_name . '</div>';
			echo '								<table id="booksearchresultstable">';
			echo '									<thead>';
			echo '										<tr>';
			echo '											<td class="header">Title</td>';
			echo '											<td class="header">Author</td>';
			echo '											<td class="header">ISBN</td>';
			echo '											<td class="header"></td>';
			echo '										</tr>';
			echo '									</thead>';
			echo '									<tbody>	';

			$rows = pg_num_rows($available);
			while($records = pg_fetch_array($available)) {
				echo '										<tr>';
				echo '											<td class="booktitle"><a href="bookdetails.php?id=' .$records[0]. '">' .$records[1]. '</a></td>';
				echo '											<td class="bookauthor">' . $records[4] .'</td>';
				echo '											<td class="bookisbn">'; 
				if ($records[2] != ''){
					echo $records[2];
				} else {
					echo $records[3];
				}
				echo '</td>';
				echo '											<td class="requestbutton">';
				request_button($records[0]);
				echo '</td>';
				echo '										</tr>';			
				while($records = pg_fetch_array($available)){
					echo '										<tr>';
					echo '											<td class="booktitle"><a href="bookdetail.php?id=' .$records[0]. '">' .$records[1]. '</a></td>';
					echo '											<td class="bookauthor">' . $records[4] .'</td>';
					echo '											<td class="bookisbn">' . $records[2] . '</td>';
					echo '											<td class="requestbutton">';
					request_button($records[0]);
					echo '</td>';
					echo '										</tr>';
				}

			}
			echo '									</tbody>';
			echo '								</table>';
			echo '							</div>';
	}
	
	if (isset($_POST['saveID'])){
		savemyinfo();
	}
	
	include 'includes/profile_0_header.php';
	include 'includes/siteheader.php';
	
	echo '		<div id="page">' . "\n";
	echo '			<div id="maincontent">' . "\n";
	echo '				<div class="pageTitle">Profile</div>' . "\n";
	if($errormessage != '')
		echo '				<div id="notification" class="show">' . $errormessage . '</div>' . "\n";
	
	include 'includes/profile_1b_contentarea.php';	

	$picURL = pictureurl();
	echo "<img src='images/profiles/" . $picURL . "' ></img>";
	include 'includes/profile_2_contentarea.php';
	
	if (isset($_POST['edit'])){
		editProfile();
	} elseif (isset($_POST['saveID'])){	
		savemyinfo();
		filledProfile();
	}elseif($_SERVER['REQUEST_METHOD'] == 'GET'){
		filledProfile();
	} else {
		filledProfile();
	}

	include 'includes/profile_3_contentarea.php';
	include 'includes/sitefooter.php';
	
	
	

?>