<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Ankh!</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/requests.css">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-dark fixed-top">
        <a class="navbar-brand ml-5" href="index.php"><i class="fas fa-infinity"></i></a>
        <form class="ml-5 w-50 ">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-search"><i class="material-icons">search</i></span>
                </div>
                <input class="form-control shadow-none text-center" id="main_search" type="text" name="main_search"
                    placeholder="Search whatever you want on Ankh">
            </div>
        </form>
        <button class="navbar-toggler" data-target="#my-nav" data-toggle="collapse" aria-controls="my-nav"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="my-nav" class="collapse navbar-collapse justify-content-around">
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <a class="nav-link circle-shape" href="#"><i class="material-icons" style="color:#fff;">home</i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link circle-shape" href="#"><i class="material-icons" style="color:#fff;">home</i></a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link circle-shape" href="#"><i class="material-icons">message</i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link circle-shape" href="#"><i class="material-icons">notifications_none</i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link circle-shape" href="requests.php"><i class="material-icons">person_add</i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link circle-shape" href="#"><i class="material-icons">settings</i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link circle-shape" href="handlers/logout.php"><i class="material-icons">login</i></a>
                </li>

            </ul>
        </div>
    </nav>

    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>

</html>
<?php
include "handlers/register_handler.php";
include "includes/user.php";
include "includes/post.php";

if (isset($_SESSION['username'])) {
	$logged_user = $_SESSION["username"];
    $user_details = mysqli_query($connection, "SELECT * FROM users WHERE username='$logged_user'");
    $user = mysqli_fetch_array($user_details);
}
?>

<div class="friend_requests">
    <h1>
        Friend Requests
    </h1>
    <?php
    $query = mysqli_query($connection,"SELECT * FROM friend_requests WHERE user_to='$logged_user'");
    if(mysqli_num_rows($query) > 0){
        while($row=mysqli_fetch_array($query)){
            $user_from=$row["user_from"];
            $user_from_obj = new User($connection,$user_from);
            $pic=$user_from_obj->getProfilePic();
            $name=$user_from_obj->getFirstAndLastName();
            if (isset($_POST['accept_request'.$user_from])){
                $add_friend_query=mysqli_query($connection,"UPDATE users SET friend_array=CONCAT(friend_array,'$user_from,') WHERE username='$logged_user'");
                $add_friend_query=mysqli_query($connection,"UPDATE users SET friend_array=CONCAT(friend_array,'$logged_user,') WHERE username='$user_from'");
                $user_requests_query= mysqli_query($connection,"DELETE FROM friend_requests WHERE user_from='$user_from' AND user_to='$logged_user' ");
                echo "You are now friends";

                
            }
            if (isset($_POST['remove_request'.$user_from])){
                $user_requests_query= mysqli_query($connection,"DELETE FROM friend_requests WHERE user_from='$user_from' AND user_to='$logged_user' ");
                echo "Request Removed";
                header("Location:requests.php");


                
            }

            ?>
            <form action="requests.php" method="POST" id="friend_requests_form">
            <?php echo "<img src='$pic' height='20'>"."<a href='$user_from'>$name</a>"."<p>sent you a friend request!</p>"; ?>
            <button type="submit" name="accept_request<?php echo $user_from; ?>" id="accept_request">Accept</button>
            <button type="submit" name="remove_request<?php echo $user_from; ?>" id="remove_request">X</button>
            </form>
            <?php
        }
        
    }else{
        echo "You have no friend requests";
    }
    ?>

</div>