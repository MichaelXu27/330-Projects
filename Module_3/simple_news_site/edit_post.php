<?php
session_start();
require 'database.php';
//if the user chooses to cancel then cancel
if(isset($_POST['cancel'])){
    header("Location: home.php");
    exit;
} elseif (isset($_POST['post_id']) && isset($_POST['update_post'])) { //gets the post_id and sees if the post is to be updated
    $post_id = $_POST['post_id'];
    $title = $_POST['title'];
    $body = $_POST['body'];
    $link = $_POST['link'];

    // updating the post
    $stmt = $mysqli->prepare("UPDATE posts SET title = ?, body = ?, link = ? WHERE id = ? AND user_id = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('sssii', $title, $body, $link, $post_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();

    header("Location: home.php");
    exit;
} elseif (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    $stmt = $mysqli->prepare("SELECT title, body, link FROM posts WHERE id = ? AND user_id = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('ii', $post_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($title, $body, $link);
    $stmt->fetch();
    $stmt->close();
} else {
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Post</title>
</head>
<body>
    <h1>Edit Post</h1>
    <form action="edit_post.php" method="POST">
        <p>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlentities($title); ?>" required>
        </p>
        <p>
            <label for="body">Body:</label>
            <textarea name="body" id="body" required><?php echo htmlentities($body); ?></textarea>
        </p>
        <p>
            <label for="link">Link:</label>
            <input type="text" name="link" id="link" value="<?php echo htmlentities($link); ?>">
        </p>
        <p>
            <input type="hidden" name="post_id" value="<?php echo htmlentities($post_id); ?>">
            <input type="hidden" name="update_post" value="1">
            <input type="submit" value="Update Post">
        </p>
    </form>
    <form action="edit_post.php" method="POST">
        <p>
            <input type="hidden" name="cancel" value="1">
            <input type="submit" value="Cancel">
        </p>
    </form>
</body>
</html>
