<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet prefetch" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900">
    <link rel="stylesheet prefetch" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" type="image/png" sizes="16x16" href="landing/logo.png">

    <style type="text/css">
        #buttn {
            color: #fff;
            background-color: #ff3300;
        }

        #countdownMessage {
            display: none;
            color: #ff3300; /* Choose your desired color */
        }
    </style>
</head>

<body>
    <?php
    session_start(); // temp session
    error_reporting(0); // hide undefined index errors
    include("connection/connect.php"); // connection to the database


    if (isset($_POST['submit'])) // if submit button was pressed
    {
        $email = $_POST['email']; // fetch records from login form

        if (!empty($_POST['email'])) // if records were not empty
        {
            // Check if the email exists in your database
            $check_email = mysqli_query($db, "SELECT email FROM users WHERE email='$email'");
            $row = mysqli_fetch_array($check_email);

            if (mysqli_num_rows($check_email) > 0) {
                // Output the message to be displayed on the page
                $countdown_message = "Email will be sent within";
                ?>
                <script>
                    $(document).ready(function () {
                        $('#buttn').on('click', function () {
                            $('#buttn').prop('disabled', true);
                            $('#buttn').css('background-color', '#ccc'); // Change button color to grey
                            $('#countdownMessage').css('display', 'block');
                            var countdown = 60;
                            var countdownInterval = setInterval(function () {
                                var minutes = Math.floor(countdown / 60);
                                var seconds = countdown % 60;
                                $('#countdown').html('Email will be sent within ' + minutes + ':' + (seconds < 10 ? '0' : '') + seconds + ' seconds');
                                countdown--;

                                if (countdown < 0) {
                                    clearInterval(countdownInterval);
                                    $('#countdownMessage').html('Please resend the email');
                                    $('#buttn').prop('disabled', false);
                                    $('#buttn').css('background-color', '#ff3300'); // Change button color back to original
                                }
                            }, 1000);
                        });
                    });
                </script>
                <?php
            
            
                // Generate a unique token for the password reset link
                $token = bin2hex(random_bytes(32));

                // Store the token and email in a temporary table (e.g., password_reset_tokens)
                $insert_token_query = "INSERT INTO password_reset_tokens (email, token) VALUES ('$email', '$token')";
                mysqli_query($db, $insert_token_query);

                // Send an email with the password reset link
                $reset_link = "forgot_password.php?token=$token";
                $subject = "Password Reset";
                $message = "Click on the following link to reset your password: $reset_link";
                $headers = "From: yiling0177@gmail.com"; // Replace with your email address

                mail($email, $subject, $message, $headers);

                $success_message = "Password reset link has been sent to your email. Please check your inbox.";

              
            } else {
                $message = "Invalid email!";
            }
        }else {
                $message = "Please enter your email!";
            }
    }
    ?>

    <!-- Form Mixin-->
    <!-- Input Mixin-->
    <!-- Button Mixin-->
    <!-- Pen Title-->
    <div class="pen-title">
        <h1>Reset Password Form</h1>
    </div>

    <!-- Form Module-->
    <div class="module form-module">
        <div class="toggle">
        </div>
        <div class="form">
            <h2>Please Enter Your E-mail</h2>
            <span style="color:red;"><?php echo $message; ?></span>
            <span style="color:green;"><?php echo $success_message; ?></span>
            <form action="" method="post">
                <input type="email" placeholder="amy@gmail.com" name="email" />
                <span id="countdownMessage"><span id="countdown"></span>.</span>
                <input type="submit" id="buttn" name="submit" value="Submit" />
            </form>
        </div>
    </div>

    <div class="cta">
        Not registered?<a href="registration.php" style="color:#f30;"> Create an account</a>
        or <a href="enter_email.php" style="color:#f30;">Forgot password?</a>
    </div>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#buttn').on('click', function () {
                $('#buttn').prop('disabled', true);
                $('#buttn').css('background-color', '#ccc'); // Change button color to grey
                $('#countdownMessage').css('display', 'block');
                var countdown = 60;
                var countdownInterval = setInterval(function () {
                    var minutes = Math.floor(countdown / 60);
                    var seconds = countdown % 60;
                    $('#countdown').html('Email will be sent within ' + minutes + ':' + (seconds < 10 ? '0' : '') + seconds + ' seconds');
                    countdown--;

                    if (countdown < 0) {
                        clearInterval(countdownInterval);
                        $('#countdownMessage').html('Please resend the email');
                        $('#buttn').prop('disabled', false);
                        $('#buttn').css('background-color', '#ff3300'); // Change button color back to original
                    }
                }, 1000);
            });
        });
    </script>

</body>

</html>
