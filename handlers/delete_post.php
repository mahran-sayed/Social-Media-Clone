<?php
require "login_handler.php";
include "../includes/user.php";
include "../includes/post.php";
if (isset($_REQUEST["post_id"])){
    $post_id=$_REQUEST["post_id"];    
}
if(isset($_POST["result"])){
    if($_POST["result"]=="true"){
        $query = mysqli_query($connection,"UPDATE posts SET deleted='yes' WHERE id = '$post_id'");
    }
}


?>