<?php
require 'C:\XAMPP\htdocs\PetEssentials\Mail\PHPMailer\class.phpmailer.php';
require 'C:\XAMPP\htdocs\PetEssentials\Mail\PHPMailer\class.smtp.php';

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Recipient email (the same one used for sending OTP)
    $recipient_email = 'kanzaki.bernardo32@gmail.com'; // The email you want to receive the contact form submission
    
    // Create a new PHPMailer instance
    $mail = new PHPMailer;

    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'aera.montefalco33@gmail.com'; // Your Gmail address
    $mail->Password = 'cmcw pnwe fqxx meih'; // Your Gmail App password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Recipients
    $mail->setFrom($email, $name); // Sender's email
    $mail->addAddress($recipient_email); // Recipient's email

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Contact Form Submission from ' . $name;
    $mail->Body = "<strong>Name:</strong> $name<br><strong>Email:</strong> $email<br><strong>Message:</strong><br>$message";

    // Send email and return a response
    if ($mail->send()) {
        echo 'success'; // Return success response
    } else {
        echo 'error'; // Return error response
    }
}
?>
