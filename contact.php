<?php
include 'db.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if ($name == "" || $email == "" || $message == "") {
        $error = "All fields are required.";
    } else {
        $query = "INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')";
        if (mysqli_query($conn, $query)) {
            $success = "Your message has been sent successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="index.php" class="nav-brand">Student Noticeboard</a>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="post_notice.php">Post Notice</a>
        <a href="contact.php">Contact</a>
        <a href="about.php">About</a>
    </div>
</nav>
<div class="container">
    <div class="uni-header">
        <h3>Daffodil International University</h3>
        <p>Department of Software Engineering</p>
    </div>
    <h1>Contact Us</h1>

    <?php if ($error): ?>
        <p class="msg error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="msg success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form class="notice-form" method="POST">
        <label>Your Name</label>
        <input type="text" name="name" placeholder="Enter your name" required>

        <label>Your Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Message</label>
        <textarea name="message" placeholder="Write your message here" required></textarea>

        <button type="submit" class="btn">Send Message</button>
        <a href="index.php" class="btn btn-secondary">Back to Board</a>
    </form>
</div>

</body>
</html>