<?php
include('connection/connect.php');

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

$u_id = $_SESSION['user_id'];
$username = '';
$fullName = '';
$email = '';
$phone = '';
$gender = '';
$dob = '';
$updateSuccess = false;

// Check if the form is submitted
if (isset($_POST['update_profile'])) {
    // Sanitize and prepare input data
    $fullName = $db->real_escape_string($_POST['name']);
    $phone = $db->real_escape_string($_POST['phone_number']);
    $gender = $db->real_escape_string($_POST['gender']);
    $dob = $db->real_escape_string($_POST['dob']);

    // Prepare the UPDATE statement
    $stmt = $db->prepare("UPDATE users SET fullName=?, phone=?, gender=?, dob=? WHERE u_id=?");
    $stmt->bind_param("ssssi", $fullName, $phone, $gender, $dob, $u_id);
    if ($stmt->execute()) {
        $updateSuccess = true;
    }
    $stmt->close();
}

$stmt = $db->prepare("SELECT username, fullName, email, phone, gender, dob FROM users WHERE u_id = ?");
$stmt->bind_param("i", $u_id);
$stmt->execute();
$stmt->bind_result($username, $fullName, $email, $phone, $gender, $dob);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Account</title>
    <link rel="stylesheet" href="landing/style.css" />
</head>
<body>

    <?php include('includes/header.php'); ?>

    <div class="profile-management-container">
        <h1>My Profile</h1>
        <p>Manage and protect your account</p>
        <div class="profile-management-layout">
            <div class="form-fields-container">
                <form method="POST" enctype="multipart/form-data">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" disabled />

                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($fullName); ?>" />

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" disabled />
                    <a href="change_email.php">Change</a>

                    <label for="phone_number">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone); ?>" />

                    <div class="gender-row">
                        <label>Gender</label>
                        <input type="radio" id="male" name="gender" value="male" <?php echo ($gender == 'male') ? 'checked' : ''; ?> />
                        <label for="male">Male</label>
                        <input type="radio" id="female" name="gender" value="female" <?php echo ($gender == 'female') ? 'checked' : ''; ?> />
                        <label for="female">Female</label>
                        <input type="radio" id="other" name="gender" value="other" <?php echo ($gender == 'other') ? 'checked' : ''; ?> />
                        <label for="other">Other</label>
                    </div>

                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" />

                    <button type="submit" name="update_profile" style="margin-top: 20px;">Save</button>
                </form>
            </div>
        </div>
    </div>

    <?php if ($updateSuccess): ?>
        <script>        alert('Profile Updated');</script>
    <?php endif; ?>

    <footer></footer>
</body>
</html>