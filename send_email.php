<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    if(empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['status'] = "Please fill in all fields.";
        header('Location: index.html');
        exit;
    }

    // Email address where you want to receive the form submissions
    $to = 'inquire@ralphmatthewperez.com';

    // Email subject
    $email_subject = "New Form Submission: $subject";

    // Email message
    $email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email\n\nMessage:\n$message";

    // Email headers
    $headers = "From: noreply@ralphmatthewperez.com\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send email
    if(mail($to, $email_subject, $email_body, $headers)) {
        $_SESSION['status'] = "Your message has been sent. Thank you!";
    } else {
        $_SESSION['status'] = "Failed to send message. Please try again later.";
    }
    exit;
}
?>
