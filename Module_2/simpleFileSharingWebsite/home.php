<?php
session_start();

//makes sure user is signed in correctly, if not redirects back to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

//gets the current username of the person signed in
$username = $_SESSION['username'];

// Define the directory where files are stored
$directory = '/home/mxu/uploads/' . $username;
if (!is_dir($directory)) {
    mkdir($directory, 0777, true);
}

// Fetch the list of files for the current user
$files = [];
$iterator = new DirectoryIterator($directory);
foreach ($iterator as $fileinfo) {
    if ($fileinfo->isDot()) {
        continue; // Skip '.' and '..'
    }
    $files[] = $fileinfo->getFilename();
} 
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
        <h1>Hello, <?php echo $username; ?></h1>
    </div>
    <div> <!--uploading docs here, the code that was given in the php page -->
        <h2>Upload Docs</h2>
        <form enctype="multipart/form-data" action="upload.php" method="POST">
            <p>
                <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
                <label for="uploadfile_input">Choose a file to upload:</label> <input name="uploadedfile" type="file" id="uploadfile_input" />
            </p>
            <p>
                <input type="submit" value="Upload File" />
            </p>
        </form>


    </div>
    <div>
        <h1>Current Files for <?php echo $username; ?></h1>
        <form method="post" action="delete.php">
            <ul>
                <?php foreach ($files as $file) : ?>
                    <li>
                        <input type="radio" name="file" value="<?php echo $file; ?>">
                        <a href="view.php?file=<?php echo urlencode($file); ?>"><?php echo $file; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="submit" name="action" value="delete">Delete Selected File</button>
        </form>


    </div>
    <div>
        <h3>Logout<h3>
            <!-- Logout button -->
            <form action="logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
    </div>


</body>

</html>