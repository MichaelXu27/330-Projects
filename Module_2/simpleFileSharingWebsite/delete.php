<?php
session_start();

// Redirecting user if not signed in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$file = basename($_POST['file']);
$filePath = '/home/mxu/uploads/' . $username . '/' . $file;

if (file_exists($filePath)) {
    unlink($filePath);
    header("Location: home.php");
    exit;
} else {
    echo "File not found!";
}
?>
