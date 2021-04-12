

<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.css'>
    <link rel="stylesheet" href="assets/css/home.css">

</head>

<body>
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


    if (isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];
    }
    
    if (isset($_POST["postLike".$post_id])) {
        $like = $_POST["postLike".$post_id];
        $query = mysqli_query($connection, "INSERT INTO likes VALUES('','$logged_user','$post_id')");
        $user_total_likes_query=mysqli_query($connection,"SELECT * FROM likes Where user_name='$logged_user'");
        $user_total_likes=mysqli_num_rows($user_total_likes_query);
        $user_num_likes=mysqli_query($connection,"UPDATE users SET num_likes='$user_total_likes' WHERE username='$logged_user'");
    }
    if (isset($_POST["postUnlike".$post_id])) {
        $like = $_POST["postUnlike".$post_id];
        $date_time_now = date("Y-m-d H:i:s");
        $query = mysqli_query($connection, "DELETE FROM likes WHERE `user_name`='$logged_user' AND `post_id`='$post_id'");
        $user_total_likes_query=mysqli_query($connection,"SELECT * FROM likes Where user_name='$logged_user'");
        $user_total_likes=mysqli_num_rows($user_total_likes_query);
        $user_num_likes=mysqli_query($connection,"UPDATE users SET num_likes='$user_total_likes' WHERE username='$logged_user'");

    }
    
    $get_likes=mysqli_query($connection,"SELECT * FROM likes WHERE post_id='$post_id' AND user_name='$logged_user'");
    $get_likes_nums=mysqli_num_rows($get_likes);
    $all_likes=mysqli_query($connection,"SELECT * FROM likes WHERE post_id='$post_id'");
    $total_likes=mysqli_num_rows($all_likes);
    if($get_likes_nums > 0){
        echo "<form action='likes-frame.php?post_id=$post_id' id='likes-form' name='postUnlike$post_id' method='POST'>
        <button type='submit'  name='postUnlike$post_id' id='like_btn'>
        <i class ='fas fa-heart redHeart'></i>
        <div class='like_value'>
					Likes($total_likes)
				</div>
        </button>
    
        </form>";
        

    }else{
        echo "<form action='likes-frame.php?post_id=$post_id' id='likes-form' name='postLike$post_id' method='POST'>
        <button type='submit'  name='postLike$post_id' id='like_btn'>
        <i class ='far fa-heart'></i>
        <div class='like_value' style='color:blue;'>
					Likes($total_likes)
				</div>
        </button>
        </form>";

        
    }
    
    ?>
    


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


</body>

</html>
