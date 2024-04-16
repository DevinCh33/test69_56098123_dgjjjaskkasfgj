<?php
include('connection/connect.php'); // Ensure this points to your actual connection script
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if the form was submitted
if (isset($_POST['verify_otp'])) {
    $inputOtp = $_POST['otp']; // The OTP input by the user
    $userId = $_SESSION['user_id']; // The user's ID from the session

    // Prepare a statement to fetch the stored OTP
    $stmt = $db->prepare("SELECT email_token FROM users WHERE u_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($storedOtp);
    if (!$stmt->fetch()) {
        $stmt->close();
        // Handle error or redirect as appropriate
        echo "An error occurred. Please try again later.";
        exit;
    }
    $stmt->close();

    // Compare the input OTP with the stored OTP
    if ($inputOtp == $storedOtp) {
        // OTP matches, proceed to clear the email_token column
        $stmt = $db->prepare("UPDATE users SET email_token = NULL WHERE u_id = ?");
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            // OTP was verified successfully, redirect to update_email.php
            header('Location: update_email.php');
            exit; // Ensure no further code is executed after redirection
        } else {
            // Handle errors, e.g., if the database update fails
            echo "An error occurred. Please try again.";
        }
        $stmt->close();
    } else {
        // OTP does not match
        // Redirect back or display an error
        // Here, for simplicity, we're just showing a message
        echo "The OTP entered is incorrect. Please try again.";
        // In a real application, consider redirecting back with an error message
    }
} else {
    // If the form wasn't submitted, redirect to the form or show an error
    echo "Invalid request.";
    // Or redirect to the OTP request page
    // header('Location: change_email.php');
    // exit;
}
?>
