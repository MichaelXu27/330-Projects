<?php
session_start();
require 'database.php';
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    //test for validity of the CSRF token on the server side
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //prepare prevents sql injection attacks
    $stmt = $mysqli->prepare("SELECT id, password FROM user WHERE username = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();
    

    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        header("Location: home.php");
        exit;
    } else {
        echo "Invalid username or password.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login Screen</h1>
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
        <p>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required />

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required />
        </p>
        <p>
            <input type="submit" value="Login" />
        </p>
    </form>
    <h2>Need to Register?</h2>
    <form action = "register.php" method = "GET">
        <button type = "submit">Register</button>
    </form>
    <h3>View news without logging in</h3>
    <form action = "home.php" method = "GET">
        <button type = "submit">Home</button>
    </form>
</body>

</html>