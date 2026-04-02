<?php
session_start();
include 'db.php';

$search = "";
$category = "";
$sort = "newest";

if (isset($_GET['search'])) $search = mysqli_real_escape_string($conn, $_GET['search']);
if (isset($_GET['category'])) $category = mysqli_real_escape_string($conn, $_GET['category']);
if (isset($_GET['sort']) && $_GET['sort'] == 'oldest') {
    $sort = 'ASC';
} else {
    $sort = 'DESC';
}

$query = "SELECT * FROM notices WHERE 1=1";
if ($search != "") $query .= " AND (title LIKE '%$search%' OR message LIKE '%$search%')";
if ($category != "") $query .= " AND category = '$category'";
$query .= " ORDER BY created_at $sort";

$result = mysqli_query($conn, $query);
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
<nav class="navbar">
    <a href="index.php" class="nav-brand">Student Noticeboard</a>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <?php if (isset($_SESSION['user'])): ?>
            <a href="post_notice.php">Post Notice</a>
        <?php endif; ?>
        <a href="contact.php">Contact</a>
        <a href="about.php">About</a>
        <?php if (isset($_SESSION['user'])): ?>
            <span style="color:white;">Hi, <?php echo $_SESSION['user']; ?></span>
            <a href="logout.php" style="color:white;">Logout</a>
        <?php else: ?>
            <a href="login.php" style="color:white;">Teacher Login</a>
        <?php endif; ?>
        <button onclick="toggleDarkMode()" id="darkModeBtn" class="dark-btn">Dark Mode</button>
    </div>
</nav>

<div class="container">
    <div class="uni-header">
        <h3>Daffodil International University</h3>
        <p>Department of Software Engineering</p>
    </div>
    <h1>Student Noticeboard</h1>
    <div class="top-bar">
        <?php if (isset($_SESSION['user'])): ?>
            <a href="post_notice.php" class="btn">Post a Notice</a>
        <?php endif; ?>
        <p class="notice-count">Total Notices: <?php echo $total; ?></p>
    </div>

    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search notices..." value="<?php echo htmlspecialchars($search); ?>">
        <select name="category">
            <option value="">All Categories</option>
            <option value="general" <?php echo (isset($_GET['category']) && $_GET['category'] == 'general') ? 'selected' : ''; ?>>General</option>
            <option value="exam" <?php echo (isset($_GET['category']) && $_GET['category'] == 'exam') ? 'selected' : ''; ?>>Exam</option>
            <option value="event" <?php echo (isset($_GET['category']) && $_GET['category'] == 'event') ? 'selected' : ''; ?>>Event</option>
            <option value="result" <?php echo (isset($_GET['category']) && $_GET['category'] == 'result') ? 'selected' : ''; ?>>Result</option>
        </select>
        <select name="sort">
            <option value="newest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : ''; ?>>Newest</option>
            <option value="oldest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'selected' : ''; ?>>Oldest</option>
        </select>
        <button type="submit" class="btn">Search</button>
        <a href="index.php" class="btn btn-secondary">Clear</a>
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
                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="edit_notice.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="delete_notice.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        <?php endif; ?>
                        <button onclick="copyNotice('<?php echo addslashes($row['title']); ?>', '<?php echo addslashes($row['message']); ?>')" class="action-btn">Copy</button>
                        <button onclick="printNotice('<?php echo addslashes($row['title']); ?>', '<?php echo addslashes($row['message']); ?>')" class="action-btn">Print</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<div class="chatbox">
    <div class="chat-header" onclick="toggleChat()">
        AI Assistant - Ask about notices
    </div>
    <div class="chat-body" id="chatBody">
        <div class="chat-messages" id="chatMessages">
            <div class="chat-msg bot">Hello! Ask me anything about the notices on this board.</div>
        </div>
        <div class="chat-input">
            <input type="text" id="chatInput" placeholder="Ask a question...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<script src="js/main.js"></script>
</body>
</html>