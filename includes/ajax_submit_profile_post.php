<?php
include "../handlers/register_handler.php";
include "../includes/user.php";
include "../includes/post.php";
$connection=mysqli_connect("localhost","mahran","0162310296","social");
if (mysqli_connect_error()){
    echo "Connection Error"."<br>";
}


if(isset($_POST["post_body"])) {
	$post = new Post($connection, $_POST['user_from']);
    $post->submitPost($_POST['post_body'], $_POST['user_to']);
    
}
?>