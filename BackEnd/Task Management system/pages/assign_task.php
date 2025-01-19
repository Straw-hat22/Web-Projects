<?php
require('../includes/config.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$task_title = trim($_POST['task_title']);
$task_description = trim($_POST['task_description']);
$project_id = $_POST['project_id'];
$assigned_to = $_POST['assigned_to'];

$query = "INSERT INTO tasks (title, description, project_id, assigned_to, status) VALUES (:title, :description, :project_id, :assigned_to, 'pending')";
$stmt = $conn->prepare($query);
$stmt->bindParam(':title', $task_title);
$stmt->bindParam(':description', $task_description);
$stmt->bindParam(':project_id', $project_id);
$stmt->bindParam(':assigned_to', $assigned_to);

if ($stmt->execute()) {
    header('Location: admin.php');
    exit();
} else {
    // Handle errors
    echo "Failed to assign the task. Please try again.";
}
?>