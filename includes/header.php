<?php
include "includes/user.php";
include "includes/post.php";
$connection=mysqli_connect("localhost","mahran","0162310296","social");
if (mysqli_connect_error()){
    echo "Connection Error"."<br>";
}
if (isset($_SESSION["username"])) {
    $logged_user = $_SESSION["username"];
    $user_details = mysqli_query($connection, "SELECT * FROM users WHERE username='$logged_user'");
    $user = mysqli_fetch_array($user_details);
} else {
    header("location: register.php");
}
?>