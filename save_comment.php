<?php
session_start();
include("connection/connect.php");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input
    $comment = trim($_POST["comment"]);
    $comment = mysqli_real_escape_string($db, $comment);

    // Check if comment is not empty
    if (!empty($comment)) {
        // Check if the comment length exceeds 300 characters
        if (strlen($comment) > 300) {
            echo "Comment length exceeds the limit of 300 characters.";
        } else {
            // Check if res_id is provided in the URL and is a valid integer
            if (isset($_GET["res_id"]) && filter_var($_GET["res_id"], FILTER_VALIDATE_INT)) {
                // Insert the comment into the database
                $user_id = $_SESSION["user_id"]; // Assuming you have user authentication
                $res_id = $_GET["res_id"]; // Assuming you pass the restaurant ID via GET parameter
                $sql = "INSERT INTO restaurant (user_id, res_id, comment) VALUES ('$user_id', '$res_id', '$comment')";
                if (mysqli_query($db, $sql)) {
                    echo "Comment saved successfully.";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($db);
                }
            } else {
                echo "Invalid restaurant ID.";
            }
        }
    } else {
        echo "Comment cannot be empty.";
    }
} else {
    // Handle the case where the form is not submitted via POST
    echo "Form submission method is not POST.";
}

