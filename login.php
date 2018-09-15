<?php ob_start(); session_start();
if (isset($_SESSION["logged_user"])) {
    unset($_SESSION["logged_user"]);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Image Album - Login</title>
    </head>
    <body>
        <header>
            <h1>User Login</h1>
        </header>
        
        <!-- navigation bar -->
        <?php
        if (isset($_SESSION['logged_user'])) {
            //include 'php/headersession.php';
            echo "you're in";
        } else {
            //include 'php/header.php';
            echo "not logged in yet";
        };
        ?>
        
        <main>
        <?php 
        require_once 'functions.php';
        $db   = 'base1';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $pdo = new PDO($dsn, $user, $pass, $opt);
        
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

            
        if (empty($username) || empty($password)) {
            //show form
            ?>
            <form name="loginForm" action="login.php" method="post">
                Username: <input id="username" type="text" name="username"><br>
                Password: <input id="password" type="password" name="password"><br>
                <input type="submit" name="submit" value="Login">
            </form>
            <?php
        } else {
            $pdo->exec("
            CREATE TABLE IF NOT EXISTS `users` (
            `userID` int(11) NOT NULL AUTO_INCREMENT, `hashpassword` varchar(255) NOT NULL, `username` varchar(50) NOT NULL,
            `name` varchar(50),
            PRIMARY KEY (`userID`),
            UNIQUE KEY `idx_unique_username` (`username`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;");
            
            $result = $pdo->query("SELECT * FROM users WHERE username = '$username'");
            if($result && $result->rowCount() == 1) {
                $row = $result->fetch_assoc();
                $hashpassword = $row['hashpassword'];
                if(password_verify($password, $hashpassword)) {
                    echo "<p>Login Success! Redirecting to Home page</p>";
                    $_SESSION['logged_user'] = $username;
                    header('Refresh: 2;index.php');
                } else {
                    echo '<p>Login unsuccessful. Please <a href="login.php">login</a>.</p>';
                }
            }
        }
        ?>
        </main> 
    </body>