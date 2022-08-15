<?php
session_start();
$mysqli = mysqli_connect("localhost", "root", "", "tw");

if (!$mysqli) {
    die('Error connect to DataBase');
}

$userId = $_POST['user_id'];
$followerId = $_SESSION['user']['id'];

$query = "INSERT INTO followers VALUES ('$followerId', '$userId')";
mysqli_query($mysqli, $query);

header('Location: user.php');