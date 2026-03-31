<?php
include 'db.php';

$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if ($search != "") {
    $result = mysqli_query($conn, "SELECT * FROM notices WHERE title LIKE '%$search%' OR message LIKE '%$search%' ORDER BY created_at DESC");
} else {
    $result = mysqli_query($conn, "SELECT * FROM notices ORDER BY created_at DESC");
}

$total = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Noticeboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="uni-header">
    <h3>Daffodil International University</h3>
    <p>Department of Software Engineering</p>
</div>
<h1>Student Noticeboard</h1>
    <div class="top-bar">
    <a href="post_notice.php" class="btn">Post a Notice</a>
    <p class="notice-count">Total Notices: <?php echo $total; ?></p>
</div>

<form method="GET" class="search-form">
    <input type="text" name="search" placeholder="Search notices..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit" class="btn">Search</button>
    <?php if ($search != ""): ?>
        <a href="index.php" class="btn btn-secondary">Clear</a>
    <?php endif; ?>
</form>

    <div class="notices">
        <?php if (mysqli_num_rows($result) == 0): ?>
            <p class="empty">No notices yet!</p>
        <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="notice-card <?php echo $row['is_important'] ? 'important' : ''; ?>">
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p><?php echo htmlspecialchars($row['message']); ?></p>
                    <small>Category: <?php echo $row['category']; ?></small>
                    <small>Posted: <?php echo $row['created_at']; ?></small>
                    <div class="actions">
                        <a href="edit_notice.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete_notice.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<script src="js/main.js"></script>
</body>
</html>