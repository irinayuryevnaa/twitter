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
    <title>Twitter</title>
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

<?php
$mysqli = mysqli_connect("localhost", "root", "", "tw");
$tagId = $_GET['tag_id'];
$query = "SELECT post_id FROM post_tag WHERE tag_id ='$tagId'";
$postIds = mysqli_query($mysqli, $query);

foreach ($postIds as $postId) {
    $postId = $postId['post_id'];
    $selectPost = "SELECT posts.text as post_text,
                posts.user_id as post_user_id,
                users.id as user_id,
                users.username as user_name,
                posts.date as post_date,
                posts.id as post_id
        FROM posts LEFT JOIN users ON posts.user_id = users.id WHERE posts.id ='$postId' ORDER BY posts.date DESC";

    $posts = mysqli_query($mysqli, $selectPost);

    while ($row = mysqli_fetch_assoc($posts)) {
        echo
            '<div id="str">'
            . '<a href="/profile.php?user=' . $row['user_name'] . '">'
            . '@' . $row['user_name'] . '</a>' . " "
            . '<b>' . $row['post_date'] . '</b>' . " "
            . preg_replace("/\[\[(.*?)\]\]/", "<a href='/profile.php?user=$1'>@$1</a>", $row['post_text']);

        $query = "SELECT tag_id FROM post_tag WHERE post_id = '".$row['post_id']."'";
        $postTags = mysqli_query($mysqli, $query);

        foreach ($postTags as $postTag) {
            $query = "SELECT id, tag FROM tags where id = '".$postTag['tag_id']."'";
            $tags = mysqli_query($mysqli, $query);

            foreach ($tags as $tag) {
                echo ' <a href="/tags.php?tag_id=' . $tag['id'] . '"> #' . $tag['tag'] .  '</a>' . ' ';
            }
        }

        if ($row['user_id'] == $_SESSION['user']['id']) {
            echo
                '<form action="delete.php" method="post" style="display:inline">'
                . '<input type="hidden" name="post_id" value="'.$row['post_id'].'">'
                . '<input type="hidden" name="redirect_id" value="index.php">'
                . '<button id="button_area2" type="submit">Delete</button>'
                . '</form>';
        }
        echo '<br/>' . '</div>';
    }
}
?>
</div>
</body>
</html>
