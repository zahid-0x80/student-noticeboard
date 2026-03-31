<?php
include 'db.php';

$error = "";
$success = "";

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);
$notice = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM notices WHERE id = $id"));

if (!$notice) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $is_important = isset($_POST['is_important']) ? 1 : 0;
    $expiry_date = $_POST['expiry_date'] != '' ? "'" . $_POST['expiry_date'] . "'" : "NULL";

    if ($title == "" || $message == "") {
        $error = "Title and message cannot be empty.";
    } else {
        $query = "UPDATE notices SET title='$title', message='$message', category='$category', is_important=$is_important, expiry_date=$expiry_date WHERE id=$id";

        if (mysqli_query($conn, $query)) {
            $success = "Notice updated successfully!";
            $notice = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM notices WHERE id = $id"));
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
    <title>Edit Notice</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="uni-header">
        <h3>Daffodil International University</h3>
        <p>Department of Software Engineering</p>
    </div>
    <h1>Edit Notice</h1>

    <?php if ($error): ?>
        <p class="msg error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="msg success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form class="notice-form" method="POST">
        <label>Title</label>
        <input type="text" name="title" maxlength="255" value="<?php echo htmlspecialchars($notice['title']); ?>" required>
        <small class="char-count"><?php echo strlen($notice['title']); ?> / 255</small>

        <label>Message</label>
        <textarea name="message" maxlength="300" required><?php echo htmlspecialchars($notice['message']); ?></textarea>
        <small class="char-count"><?php echo strlen($notice['message']); ?> / 300</small>

        <label>Category</label>
        <select name="category">
            <option value="general" <?php echo $notice['category'] == 'general' ? 'selected' : ''; ?>>General</option>
            <option value="exam" <?php echo $notice['category'] == 'exam' ? 'selected' : ''; ?>>Exam</option>
            <option value="event" <?php echo $notice['category'] == 'event' ? 'selected' : ''; ?>>Event</option>
            <option value="result" <?php echo $notice['category'] == 'result' ? 'selected' : ''; ?>>Result</option>
        </select>

        <label>Expiry Date (optional)</label>
        <input type="date" name="expiry_date" value="<?php echo $notice['expiry_date']; ?>">

        <div class="checkbox-group">
            <input type="checkbox" name="is_important" id="is_important" <?php echo $notice['is_important'] ? 'checked' : ''; ?>>
            <label for="is_important">Mark as Important</label>
        </div>

        <button type="submit" class="btn">Update Notice</button>
        <a href="index.php" class="btn btn-secondary">Back to Board</a>
    </form>
</div>

<script src="js/main.js"></script>
</body>
</html>