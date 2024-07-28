<?php
session_start();
//redirecting
if (isset($_SESSION['username']) && !isset($_FILES['uploadedfile'])) {
	header("Location: home.php");
	exit;
}

// Get the filename and make sure it is valid
$filename = basename($_FILES['uploadedfile']['name']);
if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
	echo "Invalid filename";
	exit;
}

// Get the username and make sure it is valid
$username = $_SESSION['username'];
if( !preg_match('/^[\w_\-]+$/', $username) ){
	echo "Invalid username";
	exit;
}
//"/Users/mxu/Documents/330-Projects/Module_2/simpleFileSharingWebsite/uploads/%s"
$user_dir = sprintf("/home/mxu/uploads/%s", $username);
if (!is_dir($user_dir)) {
    mkdir($user_dir, 0777, true);
}
//this is the full path of where the file is to be uploaded
$full_path = sprintf("%s/%s", $user_dir, $filename);

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path) ){
	header("Location: home.php");
	exit;
}else{
	header("Location: home.php");
	echo "File upload error";
	exit;
}
?>
