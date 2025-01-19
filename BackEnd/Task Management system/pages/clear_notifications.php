<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
require('../includes/config.php');
$query = "UPDATE notifications SET is_read = 1 WHERE is_read = 0"; 

$stmt = $conn->prepare($query);
$stmt->execute();
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>