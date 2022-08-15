<?php
session_start();

if ($_SESSION['user']) {
    header('Location: user.php');
}


//нужно подключиться к БД
$mysqli = mysqli_connect("localhost", "root", "", "tw");


$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$password2 = $_POST['password2'];

//if ($password === $password2) {
//    //con..
//} else {
//    $_SESSION['message'] = 'Passwords don\'t match! Try again.';
//    header('Location: signUp.php');
//}


$firstname = $lastname = $username = $password = $password2 = "";
$firstname_error = $lastname_error = $username_error = $password_error = $password2_error ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate firstname
    if (empty(trim($_POST['firstname']))) {
        $firstname_error = 'Please enter a firstname.';
    } elseif (strlen(trim($_POST['firstname'])) < 2) {
        $firstname_error = 'Firstname must have atleast 2 characters.';
    } else {
        $firstname = trim($_POST['firstname']);
    }

    // Validate lastname
    if (empty(trim($_POST['lastname']))) {
        $lastname_error = 'Please enter a lastname';
    } elseif (strlen(trim($_POST['lastname'])) < 2) {
        $lastname_error = 'Lastname must have atleast 2 characters';
    } else {
        $lastname = trim($_POST['lastname']);
    }

    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_error = 'Please enter a username';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST['username']))) {
        $username_error = 'Username can only contain letters, numbers and underscores.';
    } else {
        $param_username = trim($_POST['username']);

        $query = "SELECT username FROM users WHERE username = '" . $username . "'";
        $param_username = mysqli_query($mysqli, $query);

        if (mysqli_num_rows($param_username) > 0) {
            $username_error = 'This username is already taken.';
        } else {
            $username = trim($_POST['username']);
        }
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_error = 'Please enter a password';
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_error = 'Password must have atleast 6 characters.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate password2
    if (empty(trim($_POST['password2']))) {
        $password2_error = 'Please confirm password';
    } else {
        $password2 = trim($_POST['password2']);
        if ($password != $password2) {
            $password2_error = 'Password did not match.';
        }
    }

    if (empty($username_error) && empty($firstname_error) && empty($lastname_error) && empty($password_error) && empty($password2_error)) {

        $password = md5($password);

        $query = "INSERT INTO users (username, firstname, lastname, email, password) 
               VALUES ('". $username. "', '". $firstname ."', '". $lastname ."', '". $email ."', '". $password ."')";
        mysqli_query($mysqli, $query);
        header('Location: logIn.php');
    }

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/signInUp.css">

    <title>Sign Up</title>
</head>

<header>
    <div id="header">
        <?php

        if (isset($_SESSION['user'])) {
            echo '<form action="logOut.php">
        <button id="signup">LogOut</button>
    </form>';
            echo '<form action="user.php">
        <button id="login">'.$_SESSION['user']['username'].'</button>
    </form>';
        } else {
            echo '<form action = "signUp.php" >
        <button id = "signup" > SignUp</button >
    </form >';
            echo '<form action = "logIn.php" >
        <button id = "login" > LogIn</button >
    </form >';
        }
        ?>
        <form action="index.php">
            <input type="image" id="logo" src="/style/iconTw.svg" alt="Twitter">
        </form>
    </div>

</header>

<body id="body">

<form id="form" action="signUp.php" method="post">
    <label>First name</label>
    <input id="input" type="text" name="firstname" placeholder="Enter your firstname">
    <span id="error"><?= $firstname_error?></span>
    <label>Last name</label>
    <input id="input" type="text" name="lastname" placeholder="Enter your lastname">
    <span id="error"><?= $lastname_error?></span>
    <label>Username</label>
    <input id="input" type="text" name="username" placeholder="Enter your username">
    <span id="error"><?= $username_error?></span>
    <label>Email</label>
    <input id="input" type="email" name="email" placeholder="Enter your email address">
    <label>Password</label>
    <input id="input" type="password" name="password" placeholder="Enter your password">
    <span id="error"><?= $password_error?></span>
    <label>Confirm the password</label>
    <input id="input" type="password" name="password2" placeholder="Enter your password">
    <span id="error"><?= $password2_error?></span>
    <button id="button" type="submit">Sign Up</button>
    <p id="p">
        Do you already have an account? - <a id="a" href="logIn.php">Log In!</a>
    </p>

</form>

</body>
</html>



