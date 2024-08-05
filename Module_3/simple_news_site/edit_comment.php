<?php
session_start();
require 'database.php';

if(isset($_POST['cancel'])){
    header("Location: home.php");
    exit;
}elseif (isset($_POST['comment_id']) && isset($_POST['update_comment'])) {
    $comment_id = $_POST['comment_id'];
    $comment = $_POST['comment'];

    //updating the comment
    $stmt = $mysqli->prepare("UPDATE comments SET comment = ? WHERE id = ? AND user_id = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('sii', $comment, $comment_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();

    header("Location: home.php");
    exit;
} elseif (isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];

    $stmt = $mysqli->prepare("SELECT comment FROM comments WHERE id = ? AND user_id = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('ii', $comment_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($comment);
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
    <title>Edit Comment</title>
</head>
<body>
    <h1>Edit Comment</h1>
    <form action="edit_comment.php" method="POST">
        <p>
            <label for="comment">Comment:</label>
            <textarea name="comment" id="comment" required><?php echo htmlentities($comment); ?></textarea>
        </p>
        <p>
            <input type="hidden" name="comment_id" value="<?php echo htmlentities($comment_id); ?>">
            <input type="hidden" name="update_comment" value="1">
            <input type="submit" value="Update Comment">
        </p>
    </form>
    <form action="edit_comment.php" method="POST">
        <p>
            <input type="hidden" name="cancel" value="1">
            <input type="submit" value="Cancel">
        </p>
    </form>
    
</body>
</html>
