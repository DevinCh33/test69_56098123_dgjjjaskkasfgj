<?php

include('connection/connect.php'); // Ensure the correct path to your connection script

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if the form was submitted
if (isset($_POST['update_email']) && !empty($_POST['new_email'])) {
    $newEmail = $db->real_escape_string($_POST['new_email']);
    $userId = $_SESSION['user_id'];

    // Update the user's email
    $stmt = $db->prepare("UPDATE users SET email = ? WHERE u_id = ?");
    $stmt->bind_param("si", $newEmail, $userId);
    if ($stmt->execute()) {
        // Set success message to display on the current page
        $message = "<span style='color: #FF6B35;'>Email updated successfully. You will be redirected in a few seconds.</span>";
        // Use a meta-refresh to redirect after 3 seconds
        echo "<meta http-equiv='refresh' content='3;url=your_account.php'>";
    } else {
        // Set error message to display on the current page
        $message = 'Failed to update email. Please try again.';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Update Email Address</title>
    <link rel="stylesheet" href="landing/style.css" />
</head>
<body>
    <div class="profile-management-container">
        <h1>Update Your Email Address</h1>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="new_email">New Email Address:</label>
            <input type="email" id="new_email" name="new_email" required />
            <button type="submit" name="update_email" class="btn">Update Email</button>
        </form>
    </div>
</body>
</html>
