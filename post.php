<?php
session_start();

if (!$_SESSION['user']) {
    header('Location: logIn.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post</title>
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
<form id="form_area" action="post.php" method="post">
    <textarea id="textarea" name="post" placeholder="Enter your tweet"></textarea>
    <br/>
    <textarea id="hashtags" name="hashtags" placeholder="Enter your hashtags separated by space without #"></textarea>
    <button id="button_area" type="submit">Post!</button>
</form>

</body>
</html>




<?php
$mysqli = mysqli_connect("localhost", "root", "", "tw");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post = trim($_POST['post']);
    $query = "INSERT INTO posts (text, user_id) VALUES ('".$post."', '".$_SESSION['user']['id']."')";
    mysqli_query($mysqli, $query);

    if (!empty($_POST['hashtags'])) {
        $tags = explode(' ', $_POST['hashtags']);

        foreach ($tags as $tag) {
            $selectTag = "SELECT tag FROM tags WHERE tag = '$tag'";
            $result = mysqli_query($mysqli, $selectTag);

            if (empty(mysqli_fetch_assoc($result))) {
                $insertTag = "INSERT INTO tags (tag) VALUE ('".$tag."')";
                mysqli_query($mysqli, $insertTag);
            }

            $selectTagId = "SELECT id FROM tags WHERE tag = '$tag'";
            $tagId = mysqli_fetch_assoc(mysqli_query($mysqli, $selectTagId))['id'];

            $selectPostId = "SELECT id FROM posts ORDER BY id DESC LIMIT 1";
            $postId = mysqli_fetch_assoc(mysqli_query($mysqli, $selectPostId))['id'];

            if (!empty($tagId) && !empty($postId)) {
                $query = "INSERT INTO post_tag VALUES ('".$postId."', '".$tagId."')";
                mysqli_query($mysqli, $query);
            }
        }
    }


    header('Location: user.php');
}







