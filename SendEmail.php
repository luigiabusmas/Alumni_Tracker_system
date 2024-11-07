<?php
// Load Composer's autoloader
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
    $mail->SMTPAuth   = true; 
    $mail->Username   = 'alumnitracker2024@gmail.com'; // SMTP username
    $mail->Password   = 'czeu ishy akdn eeik'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port       = 587; // TCP port to connect to

    // Enable debugging
    $mail->SMTPDebug = 2;  // Enable debugging (output communication with the SMTP server)

    // Recipients
    $mail->setFrom('alumnitracker2024@gmail.com', 'TEST SENDER'); // Sender's email and name
    $mail->addAddress('luigiabusmas@gmail.com', 'Recipient Name'); // Add a recipient

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Account access';
    $mail->Body    = 'Your username is: $email; Password is: $password';
    $mail->AltBody = 'Congratulations! Your account has been verified.'; // Non-HTML version for mail clients

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
