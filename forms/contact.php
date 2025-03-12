<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1); 
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Make sure to include PHPMailer's autoload file

// Your email (sender's email)
$sender_email = 'drastiparikh23@gmail.com';
$receiving_email_address = 'drastiparikh23@gmail.com';  // Email to receive messages

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim form inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = nl2br(htmlspecialchars(trim($_POST['message'])));

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format');
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    $log = '';  // Variable to store the log

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Use your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'drastiparikh23@gmail.com'; // Your Gmail username
        $mail->Password = 'hrir agjx mtmu qmwm'; // Your Gmail app password (not actual password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Enable verbose SMTP debug output to capture process logs
        $mail->SMTPDebug = 2; // Change this value to 3 for even more detailed output
        $mail->Debugoutput = function($str, $level) use (&$log) { $log .= "$str\n"; };

        // Recipient
        $mail->setFrom($sender_email, $name);
        $mail->addAddress($receiving_email_address);  // Recipient address

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "
            <html>
            <head>
                <title>Contact Form Submission</title>
            </head>
            <body>
                <h2>Contact Form Submission</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Subject:</strong> $subject</p>
                <p><strong>Message:</strong></p>
                <p>$message</p>
            </body>
            </html>";

        // Send the email
        if ($mail->send()) {
          // Custom CSS for success message
          echo "<div style='background-color: #d4edda; color: #155724; padding: 20px; border: 1px solid #c3e6cb; border-radius: 5px;'>
                  <strong>Success!</strong> Your message has been sent successfully.
                </div><br><br>";
      } else {
          // This block will not be triggered as PHPMailer is already throwing errors if the send fails
          echo "<div style='background-color: #f8d7da; color: #721c24; padding: 20px; border: 1px solid #f5c6cb; border-radius: 5px;'>
                  <strong>Error!</strong> There was an error sending your message. Please try again.
                </div><br><br>";
      }
  } catch (Exception $e) {
      // If an exception is caught, handle the error and show the error message
      echo "<div style='background-color: #f8d7da; color: #721c24; padding: 20px; border: 1px solid #f5c6cb; border-radius: 5px;'>
              <strong>Error!</strong> Message could not be sent. Mailer Error: {$mail->ErrorInfo}
            </div><br><br>";
  }
} else {
  die("Invalid request method.");
}
?>
