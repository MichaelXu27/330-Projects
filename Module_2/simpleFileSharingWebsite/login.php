<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
</head>

<body>
    <h1>Login Screen</h1>
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <p>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required />
        </p>
        <p>
            <input type="submit" value="Login" />
        </p>
    </form>

    <?php
    session_start();
    //this section focuses on redirecting the user if they are already signed in and havent signed out
    if (isset($_SESSION['username'])) {
        header("Location: home.php");
        exit;
    }
    //checks if the form was submitted
    if (isset($_POST['username'])) {
        $username = trim($_POST['username']);
        //opening the file that has the users
        $h = fopen("/var/www/secure/users.txt", "r");

        $users = array();
        while (!feof($h)) { //reads the file line by line, feof($h) returns true when it reaches the end of the file
            $curr = fgets($h); //reading line from file
            if ($curr !== false) {
                $users[] = trim($curr); //appending user to array
            }

        }
        fclose($h); //closes the file
        
        //checks if the user exists in the file
        if (in_array($username, $users)) {
            $_SESSION['username'] = $username;
            header("Location: home.php");
            exit;
        } else {
            echo '<p>Invalid username. Please try again.</p>';
        }
    }

    ?>
</body>

</html>