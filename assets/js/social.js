$(document).ready(function(){
    $("#submit_profile_post").click(function(){
        $.ajax({
            url: "includes/ajax_submit_profile_post.php",
            type: "POST",
            data: $("form.profile_post").serialize(),
            cache: false,

            success: function(data) {
                $("#post_modal").modal('hide');
				location.reload();
            },
            error: function(){
                alert('Failed');
            }
		});
    });
});