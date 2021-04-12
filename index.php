<?php
include "handlers/register_handler.php";
include "handlers/login_handler.php";
include "includes/user.php";
include "includes/post.php";
if (isset($_SESSION["username"])) {
    $logged_user = $_SESSION["username"];
    $user_details = mysqli_query($connection, "SELECT * FROM users WHERE username='$logged_user'");
    $user = mysqli_fetch_array($user_details);
} else {
    header("location: register.php");
}

if (isset($_POST["add_post"])) {
    $post = new Post($connection, $logged_user);
    $post->submitPost($_POST["post_body"], "none");
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
    <link rel="stylesheet" href="assets/css/home.css">

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




    <div class="user-details">
        <!-- Profile Image -->
        <a href="<?php echo $logged_user; ?>"><img src="<?php echo $user["profile_pic"]; ?>" alt=""></a>
        <!-- Name -->
        <div class="text-center">
            <h1><?php echo $user["first_name"] . " " . $user["last_name"]; ?></h1>
            <h6>username: <a href="<?php echo $logged_user; ?>"><?php echo $user["username"]; ?></a></h6>

            <h5>Posts: <?php echo $user["num_posts"]; ?></h5>
            <h5>Likes: <?php echo $user["num_likes"]; ?></h5>
        </div>




    </div>
    <div class="content">
        <form class="post-form" action="index.php" method="POST" enctype="multipart/form-data">
            <textarea name="post_body" placeholder="What do you think about?"></textarea>
            <div class="post-buttons">
                <button><i class="material-icons">add_photo_alternate</i></button>
                <button><i class="material-icons">attach_file</i></button>
                <button><i class="material-icons">emoji_emotions</i></button>
                <input name='add_post' type="submit" value="post">

            </div>
        </form>

        <div class="posts_area">

        </div>
        <img src="assets/imgs/loading.gif" id="loading" alt="">



    </div>

    <div class="messenger">
        <h1 class="text-center">Contacts</h1>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script>
    var logged_user = '<?php echo $logged_user; ?>';
    $(document).ready(function() {

        $("#loading").show();

        $.ajax({
            url: "includes/ajax_load_posts.php",
            type: "POST",
            data: "page=1&logged_user=" + logged_user,
            cache: false,

            success: function(data) {
                $('#loading').hide();
                $('.posts_area').html(data);
            }
        });

        $(window).scroll(function() {
            var height = $('.posts_area').height(); 
            var scroll_top = $(this).scrollTop();
            var page = $('.posts_area').find('.nextPage').val();
            var noMorePosts = $('.posts_area').find('.noMorePosts').val();
            if ((document.documentElement.scrollHeight == Math.round(document.documentElement.scrollTop + window.innerHeight)) && noMorePosts == 'false') {
                $('#loading').show();

                var ajaxReq = $.ajax({
                    url: "includes/ajax_load_posts.php",
                    type: "POST",
                    data: "page=" + page + "&logged_user=" + logged_user,
                    cache: false,

                    success: function(response) {
                        $('.posts_area').find('.nextPage').remove(); 
                        $('.posts_area').find('.noMorePosts').remove(); 
                        $('.posts_area').find('.noMorePostsText').remove(); 
                        $('#loading').hide();
                        $('.posts_area').append(response);
                    }
                });

            } 

            return false;

        }); //End (window).scroll(function())


    });
    </script>


</body>

</html>