<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5;url=login.php"> 
    <title>Email Verification Successful</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .message {
            background-color: #f4f4f4;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="message">
    <h1>Email Verification Successful!</h1>
    <p>Your email address has been successfully verified. You will be redirected to the login page shortly.</p>
    <p>If you are not redirected, <a href="login.php">click here to log in</a>.</p>
</div>

<script>

    setTimeout(function() {
        window.location.href = "login.php";
    }, 5000); 
</script>

</body>
</html>
