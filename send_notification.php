<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'db.php';
include 'config.php';

function sendNotificationEmails($title, $message, $category) {
    global $conn;

    $result = mysqli_query($conn, "SELECT email FROM student_emails");

    if (mysqli_num_rows($result) == 0) {
        return;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = GMAIL_ADDRESS;
            $mail->Password = GMAIL_APP_PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom(GMAIL_ADDRESS, 'Student Noticeboard');
            $mail->addAddress($row['email']);

            $mail->Subject = 'New Notice: ' . $title;
            $mail->Body = "Hello,\n\nA new notice has been posted on the Student Noticeboard.\n\nTitle: $title\nCategory: $category\nMessage: $message\n\nVisit the noticeboard to read more.\n\nDaffodil International University\nDepartment of Software Engineering";

            $mail->send();
        } catch (Exception $e) {
            // silently fail if email not sent yayayayayayayyaay '_____'
        }
    }
}
?>