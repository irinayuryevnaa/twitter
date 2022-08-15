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
<h1 class="h1p">Profile</h1>

<div>
    <h3 class="h1p"><?= $_SESSION['user']['firstname'] . ' ' . $_SESSION['user']['lastname']  ?></h3>

    <?php

    $mysqli = mysqli_connect("localhost", "root", "", "tw");
    $userId = $_SESSION['user']['id'];

    $selectFollowing = "SELECT count(user_id) as count FROM followers WHERE follower_id = '$userId'";
    $following = mysqli_fetch_assoc(mysqli_query($mysqli, $selectFollowing))['count'];

    $selectFollowers = "SELECT count(follower_id) as count FROM followers WHERE user_id = '$userId'";
    $followers = mysqli_fetch_assoc(mysqli_query($mysqli, $selectFollowers))['count'];

    echo
    '<div>
        <span>Following: <b>'.$following.'</b></span>
        <span>Follower: <b>'.$followers.'</b></span>
    </div>';


    $qry = "SELECT posts.text as post_text,
    posts.user_id as post_user_id,
    users.id as user_id,
    users.username as user_name,
    posts.date as post_date,
    posts.id as post_id,
    posts.retweet_for as retweet_id
    FROM posts LEFT JOIN users ON posts.user_id = users.id WHERE posts.user_id = '".$_SESSION['user']['id']."' ORDER BY posts.date DESC ";

    $res = mysqli_query($mysqli, $qry);

    while ($row = mysqli_fetch_assoc($res)) {
        if (!empty($row['retweet_id'])) {
            $retweetId = $row['retweet_id'];
            $selectRetweet = "
                SELECT posts.text as post_text,
                    posts.user_id as post_user_id,
                    users.id as user_id,
                    users.username as user_name,
                    posts.date as post_date,
                    posts.id as post_id
                FROM posts LEFT JOIN users ON posts.user_id = users.id WHERE posts.id = '$retweetId'
            ";

            $result = mysqli_query($mysqli, $selectRetweet);

            while ($retweet = mysqli_fetch_assoc($result)) {
                echo
                    '<div id="str">'
                        . '<a href="/profile.php?user=' . $row['user_name'] . '">'
                        . '@' . $row['user_name'] . '</a>' . " "
                        . '<b>' . $row['post_date'] . '</b>' . " "
                        . preg_replace("/\[\[(.*?)\]\]/", "<a href='/profile.php?user=$1'>@$1</a>", $row['post_text']);

                $query = "SELECT tag_id FROM post_tag WHERE post_id = '" . $row['post_id'] . "'";
                $postTags = mysqli_query($mysqli, $query);

                foreach ($postTags as $postTag) {
                    $query = "SELECT id, tag FROM tags where id = '" . $postTag['tag_id'] . "'";
                    $tags = mysqli_query($mysqli, $query);

                    foreach ($tags as $tag) {
                        echo ' <a href="/tags.php?tag_id=' . $tag['id'] . '"> #' . $tag['tag'] . '</a>' . ' ';
                    }
                }

                if ($row['user_id'] == $_SESSION['user']['id']) {
                    echo
                        '<form action="delete.php" method="post" style="display:inline">'
                        . '<input type="hidden" name="post_id" value="' . $row['post_id'] . '">'
                        . '<input type="hidden" name="redirect_id" value="index.php">'
                        . '<button id="button_area2" type="submit">Delete</button>'
                        . '</form>';

                }

                echo
                    '<div class="retweet"><a href="/profile.php?user=' . $retweet['user_name'] . '">'
                        . '@' . $retweet['user_name'] . '</a>' . " "
                        . '<b>' . $retweet['post_date'] . '</b>' . " "
                        . '<p class="text">'
                            . preg_replace("/\[\[(.*?)\]\]/", "<a href='/profile.php?user=$1'>@$1</a>", $retweet['post_text'])
                        . '</p>'
                    . '</div>';

                echo '<br/>' . '</div>';
            }
        } else {
            echo
                '<form action="delete.php" method="post"><p id="str">'
                . '<a href="/profile.php?user=' . $row['user_name'] . '">'
                . '@' . $row['user_name'] . '</a>' . " "
                . '<b>' . $row['post_date'] . '</b>' . " "
                . preg_replace("/\[\[(.*?)\]\]/", "<a href='/profile.php?user=$1'>@$1</a>", $row['post_text']);

            $query = "SELECT tag_id FROM post_tag WHERE post_id = '" . $row['post_id'] . "'";
            $postTags = mysqli_query($mysqli, $query);

            foreach ($postTags as $postTag) {
                $query = "SELECT id, tag FROM tags where id = '" . $postTag['tag_id'] . "'";
                $tags = mysqli_query($mysqli, $query);

                foreach ($tags as $tag) {
                    echo ' <a href="/tags.php?tag_id=' . $tag['id'] . '"> #' . $tag['tag'] . '</a>' . ' ';
                }
            }

            echo
                '<input type="hidden" name="post_id" value="' . $row['post_id'] . '">'
                . '<input type="hidden" name="redirect_id" value="user.php">'
                . '<button id="button_area2" type="submit">Delete</button>'
                . '<br>' . '</p></form>';
        }
    }
    ?>

</div>
</body>
</html>