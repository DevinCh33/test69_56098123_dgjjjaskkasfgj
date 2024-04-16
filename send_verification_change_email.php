<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load PHPMailer
require 'connection/connect.php'; // Load your database connection

function sendOtpEmail($userEmail, $userId, $db)
{
    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);

    // Optionally, store the OTP in your database for verification later
    $updateQuery = "UPDATE users SET email_token = ? WHERE u_id = ?";
    $stmt = $db->prepare($updateQuery);
    $stmt->bind_param("si", $otp, $userId);
    $stmt->execute();
    $stmt->close();

    // Initialize PHPMailer and set email content
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gjunyu99@gmail.com'; // Your SMTP username
        $mail->Password = 'vdkx mcja yusp rwsr'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@example.com', 'LFSC System');
        $mail->addAddress($userEmail); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Your OTP for Email Verification';
        $mail->Body = "Your OTP for email verification is: <strong>{$otp}</strong>. This OTP is valid for 10 minutes.";
        $mail->AltBody = 'Your OTP for email verification is: ' . $otp . '. This OTP is valid for 10 minutes.';

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        return false;
    }
}
