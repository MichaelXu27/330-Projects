<?php
session_start();
require 'database.php';

$stmt = $mysqli->prepare("SELECT posts.id, posts.title, posts.body, posts.link, user.username, posts.user_id FROM posts JOIN user ON posts.user_id = user.id");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->execute();
$stmt->bind_result($post_id, $title, $body, $link, $username, $post_user_id);
$posts = [];
while ($stmt->fetch()) {
    $posts[] = ['id' => $post_id, 'title' => $title, 'body' => $body, 'link' => $link, 'username' => $username, 'user_id' => $post_user_id];
}

$comments = [];
foreach ($posts as $post) {
    $stmt = $mysqli->prepare("SELECT comments.id, comments.comment, comments.user_id, user.username FROM comments JOIN user ON comments.user_id = user.id WHERE comments.story_id = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('i', $post['id']);
    $stmt->execute();
    $stmt->bind_result($comment_id, $comment, $user_id, $comment_username);
    while ($stmt->fetch()) {
        $comments[$post['id']][] = ['id' => $comment_id, 'comment' => $comment, 'user_id' => $user_id, 'username' => $comment_username];
    }
    //'created_at' => $created_at
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>News</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
    <div class="navbar">
        <div class = "logo">
            <a href="home.php">Home</a>
        </div>
        <div class = "links">
            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<a href="post.php">Post</a>';
                echo '<a href="logout.php">Logout</a>';
            } else {
                echo '<a href="login.php">Login</a>';
                echo '<a href="register.php">Register</a>';
            }
        ?>
        </div>
    </div>
    <div class="container">
        <div class="posts">
            <?php
            // Loop through each post and display its details
            foreach ($posts as $post) {
                echo '<div class="post">';
                echo '<h2><a href="' .htmlentities($post['link']) . '">' . htmlentities($post['title']) . '</a></h2>';
                echo '<p>' .htmlentities($post['body']) . '</p>';
                echo '<p>Posted by: ' .htmlentities($post['username']) . '</p>';

                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']) {
                    echo '<form action="delete_post.php" method="POST" >';
                    echo '<input type="hidden" name="post_id" value="' . htmlentities($post['id']) . '">';
                    echo '<button type="submit">Delete Post</button>';
                    echo '</form>';
                    echo '<form action="edit_post.php" method="POST" >';
                    echo '<input type="hidden" name="post_id" value="' . htmlentities($post['id']) . '">';
                    echo '<button type="submit">Edit Post</button>';
                    echo '</form>';
                }

                if (isset($comments[$post['id']])) {
                    echo '<div class="comments">';
                    echo 'Comments:';
                    foreach ($comments[$post['id']] as $comment) {
                        echo '<div class="comment">';
                        echo '<p>' . htmlentities($comment['comment']) . '</p>';
                        echo '<p> Commented by: ' . htmlentities($comment['username']) . '</p>';

                        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']) {
                            echo '<form method="POST" action="delete_comment.php">';
                            echo '<input type="hidden" name="comment_id" value="' . htmlentities($comment['id']) . '">';
                            echo '<button type="submit">Delete Comment</button>';
                            echo '</form>';

                            echo '<form method="POST" action="edit_comment.php">';
                            echo '<input type="hidden" name="comment_id" value="' . htmlentities($comment['id']) . '">';
                            echo '<button type="submit">Edit Comment</button>';
                            echo '</form>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                }

                //comments
                
                echo '<form method="POST" action="comments.php">';
                echo '<textarea name="comment" required></textarea>';
                echo '<input type="hidden" name="story_id" value="' . htmlentities($post['id']) . '">';
                echo '<button type="submit">Comment</button>';
                echo '</form>';

                echo '</div>';

            }
            ?>
        </div>
    </div>


</body>

</html>