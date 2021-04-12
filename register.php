<?php
require "handlers/register_handler.php";
require "handlers/login_handler.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Ankh!</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    
    <div class="container">
        <div id="login_form">
            <form action="" method="post">
                <input type="text" placeholder="Enter Your Email" value="<?php
    if(isset($_SESSION["log_em"])){
        echo $_SESSION["log_em"];
    }
    
?>" required name="log_em"><br>
                <input type="password" placeholder="Enter Your Password" name="log_pw"><br>
                <input type="submit" value="Login" name="login">

            </form>
            <?php
if(in_array("Email or Password is wrong!",$error_array)){
    echo "Email or Password is wrong!";
}
?>
        </div>

        <div id="register_form">
            <form action="register.php" method="post">
                <input type="text" placeholder="First Name" name="reg_fname" value="<?php
    if(isset($_SESSION["reg_fname"])){
        echo $_SESSION["reg_fname"];
    }
    
    ?>" required>
                <br>
                <?php
    if (in_array("You must enter your first name",$error_array)){
        echo "You must enter your first name"."<br>";
    } elseif(in_array("First Name must be between 4 and 25 character",$error_array)){
        echo "First Name must be between 4 and 25 character"."<br>";
    }
    ?>
                <input type="text" placeholder="Last Name" name="reg_lname" value="<?php
    if(isset($_SESSION["reg_lname"])){
        echo $_SESSION["reg_lname"];
    }
    
    ?>" required>
                <br>
                <?php
    if (in_array("You must enter your last name",$error_array)){
        echo "You must enter your last name"."<br>";
    } elseif(in_array("Last Name must be between 4 and 25 character",$error_array)){
        echo "Last Name must be between 4 and 25 character"."<br>";
    }
    ?>
                <input type="text" placeholder="Email" name="reg_email1" value="<?php
    if(isset($_SESSION["reg_email1"])){
        echo $_SESSION["reg_email1"];
    }
    
    ?>" required>
                <?php
    if (in_array("Invalid Email Format",$error_array)){
        echo "Invalid Email Format"."<br>";
    }elseif (in_array("Email already in use",$error_array)){
        echo "Email already in use"."<br>";
    }elseif(in_array("Emails don't match",$error_array)){
        echo "Emails don't match"."<br>";
    }
    
    ?>
                <br>
                <input type="text" placeholder="Confirm Email" name="reg_email2" value="<?php
    if(isset($_SESSION["reg_email2"])){
        echo $_SESSION["reg_email2"];
    }
    
    ?>" required>

                <br>
                <input type="password" placeholder="Password" name="reg_pw1" required>
                <br>
                <input type="password" placeholder="Confirm Password" name="reg_pw2" required>
                <br>
                <?php
    if(in_array("Passwords don't match",$error_array)){
        echo "Passwords don't match"."<br>";
    }elseif(in_array("Your password can only contain english characters or numbers",$error_array)){
        echo "Your password can only contain english characters or numbers"."<br>";

    }
    ?>
                <input type="radio" name="gender" value="Male">
                <label for="Male">Male</label><br>
                <input type="radio" name="gender" value="Female">
                <label for="Female">Female</label><br>
                <input type="date" placeholder="Birthday" name="reg_birthday">
                <br>
                <input type="submit" value="Register" name="reg_button">
            </form>

            <?php
    if(in_array("<span style='color:green;'>All set! You're a member now </span>",$error_array)){
        echo "<span style='color:green;'>All set! You're a member now </span>"."<br>";
    }
    ?>
        </div>
        <div class="form-overlay">
            <div class="_overlay overlay-left">
                <h1>Welcome back to your world!</h1>
                <h4>Always keep in touch</h4>
                <button id="member_btn">Login!</button>



            </div>
            <div class="_overlay overlay-right">
                <h1>Join Our World, It's free!</h1>
                <h4>The Place You Deserve</h4>
                <button id="new_btn">Register Now!</button>

            </div>



        </div>
    </div>
    <?php
    if(isset($_POST["reg_button"])){
        echo '
        <script>
        var new_btn=document.getElementById("new_btn");
    var member_btn=document.getElementById("member_btn");
    var container=document.getElementsByClassName("container")[0];
    container.classList.add("overlay-active");
        </script>
        ';
    }
    ?>

    <script src="assets/js/custom.js"></script>
</body>

</html>