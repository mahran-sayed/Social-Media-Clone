<?php
include "post.php";
include "user.php";
$connection=mysqli_connect("localhost","mahran","0162310296","social");
if (mysqli_connect_error()){
    echo "Connection Error"."<br>";
}


$limit= 10;
$posts = new Post($connection,$_REQUEST["logged_user"]);
$posts->loadProfilePosts($_REQUEST, $limit);

?>
