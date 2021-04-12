<?php
include "handlers/register_handler.php";
include "handlers/login_handler.php";
if (isset($_SESSION["username"])) {
    $logged_user=$_SESSION["username"];
    $user_details=mysqli_query($connection,"SELECT * FROM users WHERE username='$logged_user'");
    $user=mysqli_fetch_array($user_details);
}else{
    header("location: register.php");
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
        <a class="navbar-brand ml-5" href="#"><i class="fas fa-infinity"></i></a>
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
            </ul>
        </div>
    </nav>









    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
</body>

</html>