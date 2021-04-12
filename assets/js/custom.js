var new_btn=document.getElementById("new_btn");
var member_btn=document.getElementById("member_btn");
var container=document.getElementsByClassName("container")[0];

new_btn.onclick= function(){
    container.classList.add("overlay-active");
}
member_btn.onclick= function(){
    container.classList.remove("overlay-active")
}
