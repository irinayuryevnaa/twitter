<?php
session_start();

if (!$_SESSION['user']) {
    header('Location: logIn.php');
}

$mysqli = mysqli_connect("localhost", "root", "", "tw");
$postId = $_GET['post_id'];
$selectPost = "SELECT posts.text as post_text,
                posts.user_id as post_user_id,
                users.id as user_id,
                users.username as user_name,
                posts.date as post_date,
                posts.id as post_id
        FROM posts LEFT JOIN users ON posts.user_id = users.id WHERE posts.id ='$postId'";

$post = mysqli_query($mysqli, $selectPost);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="/style/main.css">


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
            echo '<form action="post.php">
            <input type="image" id="post" src="/style/post" alt="Twitter">
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
<div>
    <form id="form_area" action="retweet.php" method="post">
        <textarea id="textarea" name="retweet" placeholder="Enter your retweet text"></textarea>
        <input type="hidden" name="post_id" value="<?= $postId ?>">'
        <input type="hidden" name="redirect_id" value="index.php">
        <br/>
        <?php
            while ($row = mysqli_fetch_assoc($post)) {
                echo
                    '<div id="str">'
                    . '<a href="/profile.php?user=' . $row['user_name'] . '">'
                    . '@' . $row['user_name'] . '</a>' . " "
                    . '<b>' . $row['post_date'] . '</b>' . " "
                    . preg_replace("/\[\[(.*?)\]\]/", "<a href='/profile.php?user=$1'>@$1</a>", $row['post_text']);
            }
        ?>
        <button id="button_area" type="submit">Post!</button>
    </form>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST['post_id'];
    $redirectId = $_POST['redirect_id'];
    $userId = $_SESSION['user']['id'];
    $retweet = trim($_POST['retweet']);
    $query = "INSERT INTO posts (text, user_id, retweet_for) VALUES ('".$retweet."', '".$userId."', '$postId')";
    mysqli_query($mysqli, $query);
    header('Location: index.php');

}
?>
</div>
</body>
</html>
