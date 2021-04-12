<?php
ob_start();
session_start();
$connection=mysqli_connect("localhost","mahran","0162310296","social");
if (mysqli_connect_error()){
    echo "Connection Error"."<br>";
}

$fname=$lname=$em1=$em2=$birthday=$pw1=$pw2=$date="";
$error_array= array();
if (isset($_POST["reg_button"])){

    if (empty($_POST["reg_fname"])){
        array_push($error_array,"You must enter your first name");
    }elseif(strlen($_POST["reg_fname"])<4 ||strlen($_POST["reg_fname"])>25){
        array_push($error_array,"First Name must be between 4 and 25 character");
    }else{
        $fname=$_POST["reg_fname"];
        $fname=goTrim($fname);
        $_SESSION["reg_fname"]=$fname;
    }


    if (empty($_POST["reg_lname"])){
        array_push($error_array,"You must enter your last name");
    }elseif(strlen($_POST["reg_lname"])<4 || strlen($_POST["reg_lname"])>25){
        array_push($error_array,"Last Name must be between 4 and 25 character");
    }else{
        $lname=$_POST["reg_lname"];
        $lname=goTrim($lname);
        $_SESSION["reg_lname"]=$lname;
    }

    if ($_POST["reg_email1"] == $_POST["reg_email2"]){
        if(!filter_var($_POST["reg_email1"],FILTER_VALIDATE_EMAIL) === true){
            array_push($error_array,"Invalid Email Format");
        }else{
            $em1=$_POST["reg_email1"];
            $em1=filter_var($em1,FILTER_VALIDATE_EMAIL);
            $em1 = goTrim($em1);
            $em2=$_POST["reg_email2"];
            $em2=filter_var($em2,FILTER_VALIDATE_EMAIL);
            $em2 = goTrim($em2);
            $_SESSION["reg_email1"]=$em1;
            $_SESSION["reg_email2"]=$em2;
            $e_check=mysqli_query($connection,"SELECT email FROM `users` WHERE `email`='$em1'");
            $num_rows=mysqli_num_rows($e_check);
            if ($num_rows>0){
                array_push($error_array,"Email already in use");
            }
        
        }

    }else{
        array_push($error_array,"Emails don't match");
    }
    if(preg_match('/[A-Za-z0-9]/',$_POST["reg_pw1"]) && preg_match('/[A-Za-z0-9]/',$_POST["reg_pw2"])){
        if($_POST["reg_pw1"] == $_POST["reg_pw2"]){
            $pw1=$_POST["reg_pw1"];

        }else{
            array_push($error_array,"Passwords don't match");
        }
    }else{
        array_push($error_array,"Your password can only contain english characters or numbers");
    }
    $birthday=$_POST["reg_birthday"];
    if (empty($error_array)){
        $pw1=md5($pw1);
        $date = date("Y-m-d");
        $username=strtolower($fname."_".$lname);
        $username_check=mysqli_query($connection,"SELECT username FROM users WHERE username='$username'");
        $i=0;
        while(mysqli_num_rows($username_check) !=0){
            $i++;
            $username=strtolower($fname."_".$lname);
            $username = $username."_".$i;
            $username_check=mysqli_query($connection,"SELECT username FROM users WHERE username='$username'");
        }
        if($_POST["gender"] == "Male"){
            $profile_pic="assets/imgs/male.jpg";
            $_SESSION['profile_pic']=$profile_pic;

        }else{
            $profile_pic="assets/imgs/female.png";
            $_SESSION['profile_pic']=$profile_pic;

        }
        $sql= mysqli_query($connection,"INSERT INTO users VALUES ('','$fname','$lname','$username','$em1','$pw1','$date','$profile_pic','0','0','no',',','$birthday')");
        array_push($error_array,"<span style='color:green;'>All set! You're a member now </span>");


        $_SESSION['reg_fname'] = "";
		$_SESSION['reg_lname'] = "";
		$_SESSION['reg_email1'] = "";
        $_SESSION['reg_email2'] = "";
        session_destroy();

    }

}

function goTrim($data){
    $data=strip_tags($data);
    $data=str_replace(" ","",$data);
    $data=ucfirst($data);
    return $data;

}


?>