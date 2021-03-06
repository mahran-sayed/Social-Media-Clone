<?php
class User{
    private $user;
    private $connection;
    public function __construct($connection,$user){
        $this->connection= $connection;
        $user_details_query=mysqli_query($connection,"SELECT * FROM users WHERE username='$user'");
        $this->user=mysqli_fetch_array($user_details_query);
    }
    public function getUsername(){
        return $this->user["username"];
    }
    public function getNumPosts(){
        $username = $this->user["username"];
        $query =mysqli_query($this->connection,"SELECT num_posts FROM users WHERE username='$username'");
        $row= mysqli_fetch_array($query);
        return $row['num_posts'];
    }
    public function getFirstAndLastName(){
        $username = $this->user["username"];
        $query = mysqli_query($this->connection,"SELECT first_name, last_name FROM users WHERE username='$username' ");
        $row = mysqli_fetch_array($query);
        return $row["first_name"]." ".$row["last_name"];
    }
    public function getProfilePic(){
        $username = $this->user["username"];
        $query = mysqli_query($this->connection,"SELECT profile_pic FROM users WHERE username='$username' ");
        $row = mysqli_fetch_array($query);
        return $row["profile_pic"];
    }
    public function isClosed(){
        $username = $this->user["username"];
        $query = mysqli_query($this->connection,"SELECT user_closed FROM users WHERE username='$username'");
        $row = mysqli_fetch_array($query);
        if ($row["user_closed"] == "yes"){
            return true;
        }else{
            return false;
        }

    }
    public function isFriend($username_to_check){
        $usernameComma="," . $username_to_check . ",";
        if(strstr($this->user["friend_array"],$usernameComma) || $username_to_check == $this->user["username"]){
            return true;
        }else{
            return false;
        }

    }
    public function didReceiveRequest($user_from){
		$user_to = $this->user['username'];
		$check_request_query = mysqli_query($this->connection, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'");
		if(mysqli_num_rows($check_request_query) > 0) {
			return true;
		}
		else {
			return false;
		}


    }
	public function didSendRequest($user_to) {
		$user_from = $this->user['username'];
		$check_request_query = mysqli_query($this->connection, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'");
		if(mysqli_num_rows($check_request_query) > 0) {
			return true;
		}
		else {
			return false;
		}
    }
    public function removeFriend($user_to_remove){
        $logged_in_user=$this->user['username'];
        $friends_query =mysqli_query($this->connection,"SELECT friend_array FROM users WHERE username='$user_to_remove'");
        $row=mysqli_fetch_array($friends_query);
        $friend_array=$row['friend_array'];

        $new_friend_array= str_replace($user_to_remove.",","",$this->user['friend_array']);
        $remove_friend=mysqli_query($this->connection,"UPDATE users SET friend_array='$new_friend_array' WHERE username='$logged_in_user'");
        
        $new_friend_array= str_replace($logged_in_user.",","",$friend_array);
        $remove_friend=mysqli_query($this->connection,"UPDATE users SET friend_array='$new_friend_array' WHERE username='$user_to_remove'");



    }
    public function addFriend($user_to_add){
        $user_from=$this->user['username'];
        $add_query= mysqli_query($this->connection,"INSERT INTO friend_requests VALUES('','$user_to_add','$user_from')");

    }
    public function getMutualFriends($user_to_check){
        $user_friends=$this->user["friend_array"];
        $user_friend_array=explode(",",$user_friends);

        $user_to_check_friends=mysqli_query($this->connection,"SELECT friend_array FROM users WHERE username='$user_to_check'");
        $row=mysqli_fetch_array($user_to_check_friends);
        $user_to_check_friends_array=explode(",",$row['friend_array']);

        $mutualFriends=0;

        foreach($user_friend_array as $i){
            foreach($user_to_check_friends_array as $j){
                if ($i == $j && $i !=""){
                    $mutualFriends++;
                }
            }
        }
        
        return $mutualFriends;

    }
    public function respondRequest($username_to_respond){

    }

}


?>