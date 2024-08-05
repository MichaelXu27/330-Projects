<?php
session_start();
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    //making sure the two passwords match
    if ($password == $confirm_password) {
        //hasing the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        //prepare
        $stmt = $mysqli->prepare("insert into user (username, password) values (?, ?)");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('ss', $username, $hashed_password);
        $stmt->execute();
        $stmt->close();

        echo "Register Successful" ;
        header("Location: login.php");
    } else {
        echo "Passwords do not match, please try again";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
</head>

<body>
    <h1>Login Screen</h1>
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <p>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required />

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required />

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required />
        </p>
        <p>
            <input type="submit" value="Register" />
        </p>
    </form>
    <h2>Already have an account?</h2>
    <form action = "login.php" method = "GET">
        <button type = "submit">Go to Login</button>
    </form>
    <h3>View news without logging in</h3>
    <form action = "home.php" method = "GET">
        <button type = "submit">Home</button>
    </form>

</body>

</html>