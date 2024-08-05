<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment = $_POST['comment'];
    $story_id = $_POST['story_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $mysqli->prepare("INSERT INTO comments (story_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $story_id, $user_id, $comment);
    $stmt->execute();
    $stmt->close();

    header("Location: home.php?id=$story_id");
}

?>