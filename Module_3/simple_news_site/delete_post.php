<?php
session_start();
require 'database.php';

if (isset($_POST['post_id']) && isset($_SESSION['user_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    //checks if the user is the author of the post
    //if the user is not the author of the post then there will be a query prep failed error
    $stmt = $mysqli->prepare("SELECT user_id FROM posts WHERE id = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $stmt->bind_result($post_user_id);
    $stmt->fetch();
    $stmt->close();
    
    if ($post_user_id == $user_id) {
        //deleting all comments under the post
        $stmt = $mysqli->prepare("DELETE FROM comments WHERE story_id = ?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $post_id);
        $stmt->execute();
        $stmt->close();

        //deleting the post
        $stmt = $mysqli->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('ii', $post_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: home.php");
?>
