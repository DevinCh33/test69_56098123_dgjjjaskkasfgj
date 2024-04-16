<?php

include('connection/connect.php');
// Include the script where the sendOtpEmail function is defined
require 'send_verification_change_email.php';

session_start(); // Ensure session_start() is called at the beginning

$user_id = $_SESSION['user_id'] ?? null;
$otp_sent = false;
$otp_error = '';

if (!$user_id) {
    header('Location: login.php');
    exit;
}

// Function to fetch the user's email by user ID
function getUserEmailById($userId, $db)
{
    $query = "SELECT email FROM users WHERE u_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();
    return $email;
}

// Check if the OTP request form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_otp'])) {
    // Generate a random OTP
    $otp = rand(100000, 999999);

    // Fetch the user's current email address from the database
    $userEmail = getUserEmailById($user_id, $db);

    // Send the OTP to the user's current email address
    if (sendOtpEmail($userEmail, $user_id, $db)) {
        // Store the OTP in the session with an expiry time
        $_SESSION['email_change_otp'] = $otp;
        $_SESSION['email_change_otp_expiry'] = time() + 300; // OTP valid for 5 minutes
        $otp_sent = true; // Indicate that the OTP has been sent
    } else {
        $otp_error = "Failed to send OTP. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Verify Your Identity</title>
    <link rel="stylesheet" href="landing/style.css" />
</head>
<body>

    <div class="profile-management-container">
        <h1>Account Security Verification</h1>
        <p>To protect your account security, please verify your identity with the methods below.</p>
        <div class="form-fields-container">
            <?php if (!$otp_sent): ?>
                <p>Verify by Email OTP</p>
                <form method="POST" action="change_email.php">
                    <button type="submit" name="request_otp" class="order-button">Send OTP</button>
                </form>
            <?php else: ?>
            <form method="POST" action="verify_change_email.php">
                <label for="otp">OTP</label>
                <input type="text" id="otp" name="otp" placeholder="Enter OTP" class="profile-management-container input input-otp" />
                <button type="submit" name="verify_otp" class="order-button">Verify OTP</button>
            </form>
            <?php endif; ?>
        </div>

        <?php if ($otp_error): ?>
            <p class="error"><?php echo $otp_error; ?></p>
        <?php endif; ?>
    </div>

</body>
</html>
