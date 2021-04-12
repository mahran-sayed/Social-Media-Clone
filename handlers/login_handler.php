<?php
$connection=mysqli_connect("localhost","mahran","0162310296","social");
if (mysqli_connect_error()){
    echo "Connection Error"."<br>";
}
if(isset($_POST["login"])){
    $log_em=$_POST["log_em"];
    $log_em=strip_tags($log_em);
    $log_em=ucfirst($log_em);
    $log_pw=md5($_POST["log_pw"]);
    $log_check= mysqli_query($connection,"SELECT * FROM users WHERE `email` ='$log_em' And `password`='$log_pw' ");
    if(mysqli_num_rows($log_check)>0){
        $row=mysqli_fetch_array($log_check);
        $username=$row["username"];
        $_SESSION['username'] = $username;
        $user_closed_status=mysqli_query($connection,"SELECT * FROM users WHERE email ='$log_em' AND user_closed ='yes'");
        if(mysqli_num_rows($user_closed_status) > 0) {
            $reopen_account = mysqli_query($connection, "UPDATE users SET user_closed='no' WHERE email='$log_em'");
        }
        header("Location: index.php");
    }else{
        array_push($error_array,"Email or Password is wrong!");
    }

}
?>