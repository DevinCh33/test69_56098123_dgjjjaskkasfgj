document.addEventListener("DOMContentLoaded", function() {
    $(document).ready(function() {
        // Fetch and display recent comments
        fetchComments();

        // Submit comment form
        $("#commentForm").submit(function(event) {
            event.preventDefault();
            var comment = $("#comment").val();
            var user_id = $("#user_id").val(); // Assuming you have a hidden input field for user_id
            var res_id = $("#res_id").val(); // Assuming you have a hidden input field for res_id
    
            $.ajax({
                type: "POST",
                url: "comment.php",
                data: {
                    comment: comment,
                    user_id: user_id,
                    res_id: res_id
                },
                success: function(response) {
                    // Parse JSON response
                    var data = JSON.parse(response);
                    // Clear comment textarea
                    $("#comment").val("");
                    // Append the new comment to the recent comments list
                    $("#recentComments").append("<li>" + data.comment + "</li>");
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        });
    


                    // Fetch and display recent comments
        function fetchComments() {
            $.ajax({
                type: "GET",
                url: "comment.php", // Assuming the PHP script is named comment.php
                data: { res_id: $("#res_id").val() }, // Send restaurant ID as a parameter
                success: function(response) {
                    var comments = JSON.parse(response);
                    var commentsList = $("#recentComments");
                    commentsList.empty();
                    comments.forEach(function(comment) {
                        commentsList.append("<li>" + comment.comment + "</li>");
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }
    });
});
