<?php
session_start();

$mysqli = mysqli_connect("localhost", "root", "", "tw");

$post_id = $_POST['post_id'];
$user_id = $_SESSION['user']['id'];
$redirect_id = $_POST['redirect_id'];
$query = "DELETE FROM posts WHERE id = '".$post_id."' AND user_id = '".$user_id."'";

$result = mysqli_query($mysqli, $query);

header('Location: ' . $redirect_id);