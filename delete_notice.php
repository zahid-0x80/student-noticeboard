<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM notices WHERE id = $id";
    mysqli_query($conn, $query);
}

header('Location: index.php');
exit;
?>