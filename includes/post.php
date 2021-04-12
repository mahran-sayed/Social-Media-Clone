<?php
class Post
{
    public $user_obj;
    private $connection;
    public function __construct($connection, $user)
    {
        $this->connection = $connection;
        $this->user_obj = new User($connection, $user);
    }
    public function submitPost($body, $user_to)
    {
        $body = strip_tags($body);
        $body = mysqli_real_escape_string($this->connection, $body);
        $check_empty = preg_replace("/\s+/", "", $body);

        if ($check_empty != "") {
            $date_added = date("Y-m-d H:i:s");

            $added_by = $this->user_obj->getUsername();

            if ($user_to == $added_by) {
                $user_to = "none";
            }

            $query = mysqli_query($this->connection, "INSERT INTO posts VALUES ('','$body','$added_by','$user_to','$date_added','no','no','0','')");
            $returned_id = mysqli_insert_id($this->connection);

            //Insert Notification

            //Update post count for user
            $num_posts = $this->user_obj->getNumPosts();
            $num_posts++;
            $update_query = mysqli_query($this->connection, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

        }
    }
    public function loadPostsFriends($data, $limit)
    {
        $page = $data["page"];
        $logged_user = $this->user_obj->getUsername();
        if ($page == 1) {
            $start = 0; //number of loaded post, After loading there're no posts
        } else {
            $start = ($page - 1) * $limit; //number of all loaded posts
        }

        $str = "";
        $data_query = mysqli_query($this->connection, "SELECT * From posts WHERE deleted='no'  ORDER BY id DESC");
        if (mysqli_num_rows($data_query) > 0) {
            $num_iterations = 0; //num of all posts in database
            $count = 1; //loop iteration count

            while ($row = mysqli_fetch_array($data_query)) {
                $id = $row["id"];
                $body = $row["body"];
                $added_by = $row["add_by"];
                $date_time = $row["date_added"];

                if ($row["user_to"] == "none") {
                    $user_to = "";
                } else {
                    $user_to_obj = new User($this->connection, $row["user_to"]);
                    $user_to_name = $user_to_obj->getFirstAndLastName();
                    $user_to = "to <a href='" . $row['user_to'] . "'>" . $user_to_name . "</a>";
                }

                $added_by_obj = new User($this->connection, $added_by);
                if ($added_by_obj->isClosed()) {
                    continue;
                }
                if ($this->user_obj->isFriend($added_by)) {
                    if ($num_iterations++ < $start) {
                        continue;
                    }
                    if ($count > $limit) {
                        break;
                    } else {
                        $count++;
                    }
                    if($logged_user == $added_by){
                        $del_btn="<button class='del_btn' id='post$id' style='margin-left: auto;background-color: red;border: none;outline: none !important;padding: 0 10px;border-radius: 50%;color: #fff;'>x</button>";
                    }else{
                        $del_btn="";
                    }
                    $user_details_query = mysqli_query($this->connection, "SELECT first_name,last_name,profile_pic FROM users WHERE username='$added_by'");
                    $user_row = mysqli_fetch_array($user_details_query);
                    $profile_pic = $user_row["profile_pic"];
                    $first_name = $user_row["first_name"];
                    $last_name = $user_row["last_name"];

                    ?>

                    <script>
                    function toggle<?php echo $id; ?> (){
                        var target=$(event.target);
                        if (!target.is("a")){
                            var element= document.getElementById("toggleComment<?php echo $id; ?>");
                            if(element.style.display == "block"){
                                element.style.display = "none";
                            }else{
                                element.style.display = "block";
                            }

                        }

                    }
                    </script>
                    <?php
                    $comments_query=mysqli_query($this->connection,"SELECT * FROM comments WHERE post_id='$id'");
                    $num_comments=mysqli_num_rows($comments_query);
                    
                    ?>

                    <?php
                    $likes_query=mysqli_query($this->connection,"SELECT * FROM likes WHERE post_id='$id'");
                    $num_likes=mysqli_num_rows($likes_query);
                    ?>


                    <?php
                    $date_time_now = date("Y-m-d H:i:s");
                    $start_date = new DateTime($date_time);
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
                    $str .= "<div class='status_post' onclick='javascript: toggle$id()'>
                                <div class='main-info'>
                                <div class='post_profile_pic'>
                                <img src='$profile_pic' alt='' width='50'>
                                </div>
                                <div class='posted_by'>
                                <a href='$added_by'>$first_name $last_name</a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $time_message 
                                </div>
                                $del_btn
                                </div>
                                <hr>
                                <div class='post_body'>
                                $body
                                <br>
                                </div>
                                <div class='postOptions'>
                                <iframe src='likes-frame.php?post_id=$id' id='likes-iframe' frameborder='0'></iframe>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Comments($num_comments)
                                
                                </div>
                                <div class='post_comment' id='toggleComment$id' style='display:none;'>
                                <iframe src='comments-frame.php?post_id=$id' id='comment-iframe'>
                                </iframe>
                                </div>

                            </div>";
                }
                ?>
                <script>
                    $(document).ready(function(){
                        $("#post<?php echo $id ?>").click(function(){
                            bootbox.confirm("Are you sure you want to delete this post?",function(result){
                                $.post("handlers/delete_post.php?post_id=<?php echo $id;?>", {result:result});
                                if (result){
                                    location.reload();
                                }
                            })
                            
                        })
                    });
                </script>
                <?php
            }
            if ($count > $limit) {
                $str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
                        <input type='hidden' class='noMorePosts' value='false'>";
            } else {
                $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;' class='noMorePostsText text-white text-center'> No more posts to show! </p>";
            }

        }
        echo $str;

    }
    public function loadProfilePosts($data, $limit)
    {
        $page = $data["page"];
        $profile_user=$data["profile_user"];
        $logged_user=$this->user_obj->getUsername();
        if ($page == 1) {
            $start = 0; //number of loaded post, After loading there're no posts
        } else {
            $start = ($page - 1) * $limit; //number of all loaded posts
        }

        $str = "";
        $data_query = mysqli_query($this->connection, "SELECT * From posts WHERE deleted='no' AND ((add_by='$profile_user' AND user_to='none') OR user_to='$profile_user') ORDER BY id DESC");
        if (mysqli_num_rows($data_query) > 0) {
            $num_iterations = 0; //num of all posts in database
            $count = 1; //loop iteration count

            while ($row = mysqli_fetch_array($data_query)) {
                $id = $row["id"];
                $body = $row["body"];
                $added_by = $row["add_by"];
                $date_time = $row["date_added"];

                if ($row["user_to"] == "none") {
                    $user_to = "";
                } else {
                    $user_to_obj = new User($this->connection, $row["user_to"]);
                    $user_to_name = $user_to_obj->getFirstAndLastName();
                    $user_to = "to <a href='" . $row['user_to'] . "'>" . $user_to_name . "</a>";
                }

                $added_by_obj = new User($this->connection, $added_by);
                if ($added_by_obj->isClosed()) {
                    continue;
                }
                if ($this->user_obj->isFriend($added_by) ) {
                    if ($num_iterations++ < $start) {
                        continue;
                    }
                    if ($count > $limit) {
                        break;
                    } else {
                        $count++;
                    }
                    if($added_by == $logged_user){
                        $del_btn="<button class='del_btn' id='post$id' style='margin-left: auto;background-color: red;border: none;outline: none !important;padding: 0 10px;border-radius: 50%;color: #fff;'>x</button>";
                    }else{
                        $del_btn="";
                    }
                    $user_details_query = mysqli_query($this->connection, "SELECT first_name,last_name,profile_pic FROM users WHERE username='$added_by'");
                    $user_row = mysqli_fetch_array($user_details_query);
                    $profile_pic = $user_row["profile_pic"];
                    $first_name = $user_row["first_name"];
                    $last_name = $user_row["last_name"];

                    ?>

                    <script>
                    function toggle<?php echo $id; ?> (){
                        var target=$(event.target);
                        if (!target.is("a")){
                            var element= document.getElementById("toggleComment<?php echo $id; ?>");
                            if(element.style.display == "block"){
                                element.style.display = "none";
                            }else{
                                element.style.display = "block";
                            }

                        }

                    }
                    </script>
                    <?php
                    $comments_query=mysqli_query($this->connection,"SELECT * FROM comments WHERE post_id='$id'");
                    $num_comments=mysqli_num_rows($comments_query);
                    
                    ?>

                    <?php
                    $likes_query=mysqli_query($this->connection,"SELECT * FROM likes WHERE post_id='$id'");
                    $num_likes=mysqli_num_rows($likes_query);
                    ?>


                    <?php
                    $date_time_now = date("Y-m-d H:i:s");
                    $start_date = new DateTime($date_time);
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
                    $str .= "<div class='status_post' onclick='javascript: toggle$id()'>
                                <div class='main-info'>
                                <div class='post_profile_pic'>
                                <img src='$profile_pic' alt='' width='50'>
                                </div>
                                <div class='posted_by'>
                                <a href='$added_by'>$first_name $last_name</a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $time_message 
                                </div>
                                $del_btn
                                </div>
                                <hr>
                                <div class='post_body'>
                                $body
                                <br>
                                </div>
                                <div class='postOptions'>
                                <iframe src='likes-frame.php?post_id=$id' id='likes-iframe' frameborder='0'></iframe>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Comments($num_comments)
                                
                                </div>
                                <div class='post_comment' id='toggleComment$id' style='display:none;'>
                                <iframe src='comments-frame.php?post_id=$id' id='comment-iframe'>
                                </iframe>
                                </div>

                            </div>";
                }
                ?>
                <script>
                    $(document).ready(function(){
                        $("#post<?php echo $id ?>").click(function(){
                            bootbox.confirm("Are you sure you want to delete this post?",function(result){
                                $.post("handlers/delete_post.php?post_id=<?php echo $id;?>", {result:result});
                                if (result){
                                    location.reload();
                                }
                            })
                            
                        })
                    });
                </script>
                <?php
            }
            if ($count > $limit) {
                $str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
                        <input type='hidden' class='noMorePosts' value='false'>";
            } else {
                $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;' class='noMorePostsText text-white text-center'> No more posts to show! </p>";
            }

        }
        if($str == ""){
            echo "<h1 class='text-white text-center'>No posts yet</h1>";

        }else{
            echo $str;
        }

    }
    public function removePost($post_id){
        
    }
}
