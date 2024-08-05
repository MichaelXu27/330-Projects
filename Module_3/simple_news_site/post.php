<?php
session_start();
require 'database.php';
//is user logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if(isset($_POST['cancel'])){
    header("Location: home.php");
    exit;
}
//did the user click the submit button
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $body = $_POST['body'];
    $link = $_POST['link'];

    $stmt = $mysqli->prepare("INSERT INTO posts (user_id, title, body, link) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('isss', $user_id, $title, $body, $link);
    $stmt->execute();
    $stmt->close();

    header("Location: home.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Submit Post</title>
</head>
<body>
    <h1>Submit Post</h1>
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <p>
            <label for="title">Title</label>
            <input type="text" name="title" id="title" required />
        </p>
        <p>
            <label for="body">Body</label>
            <textarea name="body" id="body" required></textarea>
        </p>
        <p>
            <label for="link">Link</label>
            <input type="text" name="link" id="link" />
        </p>
        <p>
            <input type="submit" value="Submit" />
        </p>
        
    </form>
    <form action = "post.php" method = "POST">
        <p>
            <input type="hidden" name="cancel" value="1">
            <input type="submit" value="Cancel">
        </p>
    </form>
</body>
</html>
