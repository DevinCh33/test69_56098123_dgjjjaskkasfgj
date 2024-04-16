document.addEventListener("DOMContentLoaded", function() {
    // Function to handle changing comments per page
    function changeCommentsPerPage() {
        var selectedValue = document.getElementById("commentsPerPage").value;
        var resId = "<?php echo isset($_GET['res_id']) ? $_GET['res_id'] : 'null'; ?>";
        if (resId !== 'null') {
            // Redirect to all_comments.php with updated per_page parameter
            window.location.href = "all_comments.php?res_id=" + resId + "&per_page=" + selectedValue;
        }
    }

    // Call changeCommentsPerPage() when the comments per page selection changes
    document.getElementById("commentsPerPage").addEventListener("change", changeCommentsPerPage);

    // Function to check for swear words in comments
    function checkSwearWords(comment) {
        // Define the regex pattern for swear words
        var swearPattern = /\b(fuck|nigger|shit|fucker|faggot|babi|cibai)\b/i; 

        // Check if the comment contains swear words
        if (swearPattern.test(comment)) {
            // Display an alert or message to the user
            alert("Your comment contains inappropriate language. Please revise your comment.");
            return false; // Return false to prevent form submission
        }

        return true; // Return true if no swear words are detected
    }

    // Call checkSwearWords() when the comment form is submitted
    document.getElementById("commentForm").addEventListener("submit", function(event) {
        var commentText = document.getElementById("comment").value;
        if (!checkSwearWords(commentText)) {
            event.preventDefault(); // Prevent form submission if swear words are detected
        }
    });
});
