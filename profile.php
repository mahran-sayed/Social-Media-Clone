<?php
include "handlers/register_handler.php";
include "includes/user.php";
include "includes/post.php";

if (isset($_SESSION['username'])) {
	$logged_user = $_SESSION["username"];
    $user_details = mysqli_query($connection, "SELECT * FROM users WHERE username='$logged_user'");
    $user = mysqli_fetch_array($user_details);
}
else {
	header("Location: register.php");
}
if(isset($_GET['profile_username'])) {
    $username = $_GET['profile_username'];
    $user_query = mysqli_query($connection,"SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($user_query)>0){
        $user_array = mysqli_fetch_array($user_query);
	    $num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
    }else{
        header("Location: user_closed.php");
    }
	
}
if(isset($_POST['remove_friend'])) {
	$user = new User($connection, $logged_user);
	$user->removeFriend($username);
}
if(isset($_POST['add_friend'])) {
	$user = new User($connection, $logged_user);
	$user->addFriend($user_array['username']);
}




?>

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
    <link rel="stylesheet" href="assets/css/profile.css">

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
                    <a class="nav-link circle-shape" href="#"><i class="material-icons">person_add</i></a>
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

    <div class="user-details">
        <!-- Profile Image -->
        <a href="<?php echo $username; ?>"><img src="<?php echo $user_array["profile_pic"]; ?>" alt=""></a>
        <!-- Name -->
        <div class="text-center">
            <h1><?php echo $user_array["first_name"] . " " . $user_array["last_name"]; ?></h1>
            <h6>username: <a href="<?php echo $username; ?>"><?php echo $user_array["username"]; ?></a></h6>

            <h5>Posts: <?php echo $user_array["num_posts"]; ?></h5>
            <h5>Likes: <?php echo $user_array["num_likes"]; ?></h5>
            <h5>Friends: <?php echo $num_friends ?></h5>
            
        </div>
        <form action="<?php echo $username;?>" id="none_personal_page" method="POST">
            <?php
            $profile_user_obj = new User($connection,$username);
            if($profile_user_obj->isClosed()){
                header("Location: user_closed.php");
            }
            $logged_in_user_obj = new User($connection,$logged_user);

            if($logged_user != $username){
                if($logged_in_user_obj->isFriend($username)){
                    echo "<input type='submit' value='Remove Friend' name='remove_friend' class='remove_friend'>";
                }elseif($logged_in_user_obj->didSendRequest($username)){
                    echo "<input type='submit' value='Request Sent' name='remove_request' class='request_sent'>";
                }elseif($logged_in_user_obj->didReceiveRequest($username)){
                    echo "<input type='submit' value='Respond Request' name='respond_request' class='respond_request'>";

                }else{
                    echo "<input type='submit' value='Add Friend' name='add_friend' class='add_friend'>";
                }
                
            }
            

            ?>
        </form>

        <input type="submit" data-target="#post-modal" data-toggle="modal" class="btn btn-primary d-block mx-auto my-2" value="Post Something">
        <div id="post-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="post-modal-title"
            aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="post-modal-title">Post something!</h4>
                        <button class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>This post will apear in your profile and the newsfeed of your friends, too</p>
                        <form action="" class="profile_post" method="POST" name="profile_post">
                            <div class="form-group">
                                <textarea class="form-control" name="post_body"></textarea>
                                <input type="hidden" name="user_from" value="<?php echo $logged_user;?>" >
                                <input type="hidden" name="user_to" value="<?php echo $username;?>">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="post_btn"
                            id="submit_profile_post">Post</button>

                        <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            if($logged_user != $username){
                $mutualFriends= $logged_in_user_obj->getMutualFriends($username);
                echo "<h5>Mutual Friends: $mutualFriends</h5>";

            }
            ?>





    </div>
    <div class="content">
        <div class="posts_profile_area">

        </div>
        <img src="assets/imgs/loading.gif" id="loading" alt="">

    </div>

    <div class="messenger">
        <h1 class="text-center">Contacts</h1>
    </div>








    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>

    <script src="assets/js/social.js"></script>
    <script>
    var user = '<?php echo $username ?>';
    var logged_user='<?php echo $logged_user ?>'
    $(document).ready(function() {

        $("#loading").show();

        $.ajax({
            url: "includes/ajax_load_profile_posts.php",
            type: "POST",
            data: "page=1&profile_user=" + user + "&logged_user=" + logged_user,
            cache: false,

            success: function(data) {
                $('#loading').hide();
                $('.posts_profile_area').html(data);
            }
        });

        $(window).scroll(function() {
            var height = $('.posts_profile_area').height(); 
            var scroll_top = $(this).scrollTop();
            var page = $('.posts_profile_area').find('.nextPage').val();
            var noMorePosts = $('.posts_profile_area').find('.noMorePosts').val();
            if ((document.documentElement.scrollHeight == Math.round(document.documentElement.scrollTop + window.innerHeight)) && noMorePosts == 'false') {
                $('#loading').show();

                var ajaxReq = $.ajax({
                    url: "includes/ajax_load_profile_posts.php",
                    type: "POST",
                    data: "page=1&profile_user=" + user + "&logged_user=" + logged_user,
                    cache: false,

                    success: function(response) {
                        $('.posts_profile_area').find('.nextPage').remove(); 
                        $('.posts_profile_area').find('.noMorePosts').remove(); 
                        $('.posts_profile_area').find('.noMorePostsText').remove(); 
                        $('#loading').hide();
                        $('.posts_profile_area').append(response);
                    }
                });

            } 

            return false;

        }); //End (window).scroll(function())
    });
    </script>
</body>

</html>