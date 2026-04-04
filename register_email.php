<?php
include 'db.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if ($email == "") {
        $error = "Email cannot be empty.";
    } else {
        $check = mysqli_query($conn, "SELECT * FROM student_emails WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "This email is already registered.";
        } else {
            $query = "INSERT INTO student_emails (email) VALUES ('$email')";
            if (mysqli_query($conn, $query)) {
                $success = "You have successfully registered for notifications!";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Notifications</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="nav-brand">Student Noticeboard</a>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="contact.php">Contact</a>
        <a href="about.php">About</a>
        <button onclick="toggleDarkMode()" id="darkModeBtn" class="dark-btn">Dark Mode</button>
    </div>
</nav>

<div class="container">
    <div class="uni-header">
        <h3>Daffodil International University</h3>
        <p>Department of Software Engineering</p>
    </div>
    <h1>Get Notice Notifications</h1>
    <p style="text-align:center; color:#888; margin-bottom:20px;">Register your email to get notified when a new notice is posted.</p>

    <?php if ($error): ?>
        <p class="msg error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="msg success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form class="notice-form" method="POST">
        <label>Your Email</label>
        <input type="email" name="email" placeholder="Enter your student email" required>
        <button type="submit" class="btn">Register for Notifications</button>
        <a href="index.php" class="btn btn-secondary">Back to Board</a>
    </form>
</div>

<script src="js/main.js"></script>
</body>
</html>