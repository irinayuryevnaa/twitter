<?php
session_start();

$mysqli = mysqli_connect("localhost", "root", "", "tw");

$name = $_GET['user'];

$query = "SELECT users.id as user_id,
                 users.username as user_username,
                 users.firstname as user_firstname,
                 users.lastname as user_lastname
          FROM users WHERE users.username = '" . $name . "'";

$result = mysqli_query($mysqli, $query);
$userId = null;

if (mysqli_num_rows($result) > 0){
    while ($row = mysqli_fetch_assoc($result)) {
        $profile_name =  "Welcome to page of " . $row['user_firstname'] . " " . $row['user_lastname'] . "!";

        if ($row['user_id'] != $_SESSION['user']['id']) {
            $userId = $row['user_id'];
        }
    }
}   else {
    header('HTTP/1.0 404 Not Found');
    echo 'Sorry! No user found!';
    die;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="/style/main.css">

</head>
<header>
    <div id="header" >
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
<body>
<h1>Profile</h1>
<?php echo $profile_name ?>

<?php if ($userId) {
    $currentUserId = $_SESSION['user']['id'];
    $selectFollowing = "SELECT count(user_id) as count FROM followers WHERE follower_id = '$userId'";
    $following = mysqli_fetch_assoc(mysqli_query($mysqli, $selectFollowing))['count'];

    $selectFollowers = "SELECT count(follower_id) as count FROM followers WHERE user_id = '$userId'";
    $followers = mysqli_fetch_assoc(mysqli_query($mysqli, $selectFollowers))['count'];

    echo
        '<div>
        <span>Following: <b>'.$following.'</b></span>
        <span>Follower: <b>'.$followers.'</b></span>
    </div>';

    $query = "SELECT count(user_id) as count FROM followers WHERE follower_id = '$currentUserId'";
    $isFollowing = mysqli_fetch_assoc(mysqli_query($mysqli, $selectFollowers))['count'];

    if ($isFollowing == 0) { ?>
        <form action="follow.php" method="post" >
            <input type="hidden" name="user_id" value="<?= $userId ?>">
            <button id="" type="submit">Follow</button>
        </form>
    <?php } else { ?>
        <form action="unfollow.php" method="post" >
            <input type="hidden" name="user_id" value="<?= $userId ?>">
            <button id="" type="submit">Unfollow</button>
        </form>
    <?php } ?>
<?php } ?>
</body>
</html>


