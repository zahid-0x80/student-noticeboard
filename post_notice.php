<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';
include 'send_notification.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $is_important = isset($_POST['is_important']) ? 1 : 0;
    $expiry_date = $_POST['expiry_date'] != '' ? "'" . $_POST['expiry_date'] . "'" : "NULL";

    if ($title == "" || $message == "") {
        $error = "Title and message cannot be empty.";
    } else {
        $query = "INSERT INTO notices (title, message, category, is_important, expiry_date) 
                  VALUES ('$title', '$message', '$category', $is_important, $expiry_date)";
        
        if (mysqli_query($conn, $query)) {
            $success = "Notice posted successfully!";
            sendNotificationEmails($title, $message, $category);
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
    <title>Post a Notice</title>
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
    <span style="color:white;">Hi, <?php echo $_SESSION['user']; ?></span>
    <a href="logout.php" style="color:white;">Logout</a>
    <button onclick="toggleDarkMode()" id="darkModeBtn" class="dark-btn">Dark Mode</button>
</div>
</nav>

<div class="container">
    <div class="uni-header">
        <h3>Daffodil International University</h3>
        <p>Department of Software Engineering</p>
    </div>
    <h1>Post a Notice</h1>

    <?php if ($error): ?>
        <p class="msg error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="msg success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form class="notice-form" method="POST">
        <label>Title</label>
        <input type="text" name="title" maxlength="255" placeholder="Enter notice title" required>
        <small class="char-count">0 / 255</small>

        <label>Message</label>
        <textarea name="message" maxlength="300" placeholder="Enter notice message" required></textarea>
        <small class="char-count">0 / 300</small>

        <label>Category</label>
        <select name="category">
            <option value="general">General</option>
            <option value="exam">Exam</option>
            <option value="event">Event</option>
            <option value="result">Result</option>
        </select>

        <label>Expiry Date (optional)</label>
        <input type="date" name="expiry_date">

        <div class="checkbox-group">
            <input type="checkbox" name="is_important" id="is_important">
            <label for="is_important">Mark as Important</label>
        </div>

        <button type="submit" class="btn">Post Notice</button>
        <a href="index.php" class="btn btn-secondary">Back to Board</a>
    </form>
</div>

<script src="js/main.js"></script>
</body>
</html>