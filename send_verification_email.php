<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendVerificationEmail($userEmail, $token)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gjunyu99@gmail.com';
        $mail->Password = 'vdkx mcja yusp rwsr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@example.com', 'LFSC System');
        $mail->addAddress($userEmail);     // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Confirm Your Email Address';

        $mail->Body = <<<EMAILBODY
<html>
<head>
  <title>Email Verification</title>
</head>
<body>
  <div style="font-family: Arial, sans-serif; color: #333;">
    <h2>Welcome to LFSC System!</h2>
    <p>Thank you for registering with us. To complete your registration and to verify your email address, please click the link below:</p>
    <p><a href="http://localhost/LFSC/verify_email.php?token={$token}" style="background-color: #0046be; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Confirm Email Address</a></p>
    <p>If you're having trouble clicking the "Confirm Email Address" button, copy and paste the URL below into your web browser:</p>
    <p><a href="http://localhost/LFSC/verify_email.php?token={$token}" style="color: #0046be;">http://localhost/LFSC/verify_email.php?token={$token}</a></p>
    <p>If you did not create an account using this address, please ignore this email.</p>
    <p>Best Regards,<br>LFSC System Team</p>
  </div>
</body>
</html>
EMAILBODY;

        // Prepare the plain text version for email clients that don't support HTML
        $mail->AltBody = "Welcome to Example App!\n\n"
            . "Thank you for registering with us. To complete your registration and to verify your email address, please copy and paste the URL below into your web browser:\n"
            . "http://localhost/LFSC/verify_email.php?token={$token}\n\n"
            . "If you did not create an account using this address, please ignore this email.\n\n"
            . "Best Regards,\n"
            . "LFSC System Team";

        $mail->send();
        return true;
    } catch (Exception $e) {

        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;

        return false;
    }
}
?>