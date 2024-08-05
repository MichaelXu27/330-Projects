<?php
session_start();
require 'database.php';

if (isset($_POST['comment_id']) && isset($_SESSION['user_id'])) {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['user_id'];

    //checks if the user is the author of the comment
    //if not will throw error
    $stmt = $mysqli->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('ii', $comment_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: home.php");
?>
