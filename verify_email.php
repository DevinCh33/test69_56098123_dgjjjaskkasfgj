<?php
include('connection/connect.php'); 


$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];


    $query = "SELECT * FROM users WHERE email_token='$token' LIMIT 1";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        $updateQuery = "UPDATE users SET email_verified=1, email_token=NULL WHERE email_token='$token'";

        if (mysqli_query($db, $updateQuery)) {
            // Successfully updated the user's verification status
            // Redirect to the success verification page

            header('Location: success_verification.php');
            exit(); 
        } else {

            $message = "Failed to verify email. Please try the verification link again or contact support if the problem persists.";
        }
    } else {

        $message = "This verification link is invalid or expired. Please check your email for the correct link or register again.";
    }
} else {

    $message = "No verification token provided. Please check your email for the verification link.";
}


if ($message !== '') {
    echo "<div style='text-align: center; margin-top: 20px;'>$message</div>";
}
?>
