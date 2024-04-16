<?php
include('connection\connect.php');

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

$otpMessage = "";

if (isset($_POST['bindTelegram'])) {
    // Generate a unique OTP
    $otp = bin2hex(random_bytes(4));
    $expiration = new DateTime('+15 minutes');
    $userId = $_SESSION['user_id'];

    // Insert OTP into the tg_verification table
    $query = "INSERT INTO tg_verification (userId, code, expiration) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iss", $userId, $otp, $expiration->format('Y-m-d H:i:s'));
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $otpMessage = "Please send the following code to our Telegram bot <a href='https://t.me/lfscbot' target='_blank'>@lfscbot</a>: <strong>$otp</strong>";
        } else {
            $otpMessage = "An error occurred. Please try again or contact support.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $otpMessage = "An error occurred preparing the database. Please try again or contact support.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Account</title>
</head>
<body>
    <?php if ($otpMessage == ""): ?>
        <form method="POST">
            <button type="submit" name="bindTelegram">Bind Telegram</button>
        </form>
    <?php else: ?>
        <p><?php echo $otpMessage; ?></p>
    <?php endif; ?>

</body>
</html>