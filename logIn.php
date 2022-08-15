<?php
session_start();

if ($_SESSION['user']) {
    header('Location: user.php');
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/signInUp.css">

    <title>Log In</title>
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

<body id="body" >
<form id="form" action="logIn.php" method="post">
    <label>Username</label>
    <input id="input" type="text" name="username" placeholder="Enter username">
    <span></span>
    <label>Password</label>
    <input id="input" type="password" name="password" placeholder="Enter password">
    <?php
    if ($_SESSION['message']) {
        echo '<p id="error">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }
    ?>
    <button id="button" type="submit">Sign In</button>
    <p id="p" >
        Don't have an account? - <a id="a" href="signUp.php">Sign Up!</a>
    </p>
</form>

</body>
</html>





<?php

// подключится к БД
$mysqli = mysqli_connect("localhost", "root", "", "tw");


$username = $_POST['username'];
$password = md5($_POST['password']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $query = "SELECT username, password, id, firstname, lastname FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'";

    $check_user = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($check_user) > 0) {
        $user = mysqli_fetch_assoc($check_user);

        $_SESSION['user'] = [
            "id" => $user['id'],
            "firstname" => $user['firstname'],
            "lastname" => $user['lastname'],
            "username" => $user['username']
        ];
        header('location: user.php');

    } else {
        $_SESSION['message'] = 'Wrong username or password.';
        header('location: logIn.php');
    }
}



