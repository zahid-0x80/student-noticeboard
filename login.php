<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db.php';
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = MD5($_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Wrong username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    <h1>Teacher Login</h1>

    <?php if ($error): ?>
        <p class="msg error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form class="notice-form" method="POST">
        <label>Username</label>
        <input type="text" name="username" placeholder="Enter username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required>

        <button type="submit" class="btn">Login</button>
    </form>
</div>

<script src="js/main.js"></script>
</body>
</html>