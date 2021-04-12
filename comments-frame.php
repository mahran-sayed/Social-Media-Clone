<?php
include "handlers/login_handler.php";
include "includes/user.php";
include "includes/post.php";
ob_start();
session_start();
$connection = mysqli_connect("localhost", "mahran", "0162310296", "social");
if (mysqli_connect_error()) {
    echo "Connection Error" . "<br>";
}
if (isset($_SESSION["username"])) {
    $logged_user = $_SESSION["username"];
    $user_details = mysqli_query($connection, "SELECT * FROM users WHERE username='$logged_user'");
    $user = mysqli_fetch_array($user_details);
} else {
    header("location: register.php");
}

?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/home.css">
</head>

<body>






    <script>
    function toggle(){
        var element= document.getElementById("comment_section");
        if(element.style.display == "block"){
            element.style.display = "none"
        }else{
            element.style.display = "block"
        }

    }
    </script>

    <?php
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
}
$query = mysqli_query($connection, "SELECT add_by, user_to FROM posts WHERE id='$post_id'");
$row = mysqli_fetch_array($query);

$posted_to = $row['add_by'];
if (isset($_POST['postComment' . $post_id])) {
    $post_body = $_POST["post_body"];
    $post_body = mysqli_escape_string($connection, $post_body);
    $date_time_now = date("Y-m-d H:i:s");
    $insert_post_comments = mysqli_query($connection, "INSERT INTO comments VALUES ('','$post_body','$logged_user','$posted_to','$date_time_now','no','$post_id')");
    echo "<p>Your comment is posted!:)<p>";

}

?>

    <form action="comments-frame.php?post_id=<?php echo $post_id; ?>" id="comment-form" name="postComment<?php echo $post_id; ?>" method="POST">
    <textarea name="post_body" id="post_body" placeholder="Write a comment here!" style="outline: none;border-radius: 20px;padding: 20px;"></textarea>
    <button type="submit" name="postComment<?php echo $post_id; ?>" class="icon-search icon-large"><span class='material-icons'>send</span></button>

    </form>



    <?php
$get_comments = mysqli_query($connection, "SELECT * FROM comments WHERE post_id = '$post_id' ORDER BY id ASC");
$rows = mysqli_num_rows($get_comments);
if ($rows != 0) {
    while ($comment = mysqli_fetch_array($get_comments)) {
        $comment_body = $comment["post_body"];
        $added_by = $comment["added_by"];
        $posted_to = $comment["posted_to"];
        $date_added = $comment["date_added"];
        $removed = $comment["removed"];

        $date_time_now = date("Y-m-d H:i:s");
        $start_date = new DateTime($date_added);
        $end_date = new DateTime($date_time_now);
        $interval = $start_date->diff($end_date);
        if ($interval->y >= 1) {
            if ($interval == 1) {
                $time_message = $interval->y . " " . "year ago";
            } else {
                $time_message = $interval->y . " " . "years ago";
            }
        } elseif ($interval->m >= 1) {
            if ($interval->d == 0) {
                $days = " ago";
            } elseif ($interval->d == 1) {
                $days = " day ago";
            } else {
                $days = $interval->d . " days ago";
            }
            if ($interval->m == 1) {
                $months = " month ago";
            } else {
                $months = $interval->m . " month ago";
            }
            $time_message = $months . $days;
        } elseif ($interval->d >= 1) {
            if ($interval->d == 1) {
                $time_message = "Yesterday";
            } else {
                $time_message = $interval->d . " days ago";
            }
        } elseif ($interval->h >= 1) {
            if ($interval->h == 1) {
                $time_message = "An hour ago";
            } else {
                $time_message = $interval->h . " hours ago";
            }
        } elseif ($interval->i >= 1) {
            if ($interval->i == 1) {
                $time_message = "A minute ago";
            } else {
                $time_message = $interval->i . " minutes ago";
            }
        } else {
            if ($interval->s < 30) {
                $time_message = "Just now";
            } else {
                $time_message = $interval->s . " seconds ago";
            }
        }
        $user_obj =new User($connection,$added_by);
        ?>
        <div class="comment_section">
        <a href="<?php echo $added_by?>" target="_parent"><img src="<?php echo $user_obj->getProfilePic(); ?>" height="30" style="float:left;" title="<?php echo $added_by?>"></a>
        <a href="<?php echo $added_by?>" target="_parent"><b><?php echo $user_obj->getFirstAndLastName(); ?></b></a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php echo $time_message."<br>".$comment_body."<hr>";?>
        <?php

    }
}else{
    echo "<br><br><center>No comments to show!<center>";
}
?>



</div>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
</body>

</html>