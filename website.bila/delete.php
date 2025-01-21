<?php
session_start();
require_once 'config/connection.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM photos WHERE id = '$id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$photo = mysqli_fetch_assoc($result);

if($photo) {
    unlink("uploads/" . $photo['image_path']);
    mysqli_query($conn, "DELETE FROM photos WHERE id = '$id'");
}

header("Location: gallery.php");
exit();
?> 