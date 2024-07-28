<?php
session_start();

// Get the username and make sure that it is alphanumeric with limited other characters.
// You shouldn't allow usernames with unusual characters anyway, but it's always best to perform a sanity check
// since we will be concatenating the string to load files from the filesystem.
$username = $_SESSION['username'];
if (!preg_match('/^[\w_\-]+$/', $username)) {
	echo "Invalid username";
	exit;
}
$filename = basename($_GET['file']);
//below is code for testing on local machine
///Users/mxu/Documents/330-Projects/Module_2/simpleFileSharingWebsite/uploads/
$user_dir = sprintf("/home/mxu/uploads/%s", $username);

$full_path = sprintf("%s/%s", $user_dir, $filename);

// Now we need to get the MIME type (e.g., image/jpeg).  PHP provides a neat little interface to do this called finfo.
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($full_path);

// Finally, set the Content-Type header to the MIME type of the file, and display the file.
header("Content-Type: " . $mime);
header('content-disposition: inline; filename="' . $filename . '";');
readfile($full_path);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Filescreen</title>
</head>

<body>
	<div>
		<h1>Currently viewing "<?php echo $filename; ?>"</h1>
	</div>
	<div> <!--return to home! -->
		<form action="home.php" method="get">
			<button type="submit">Return to Home</button>
		</form>


	</div>

</body>

</html>