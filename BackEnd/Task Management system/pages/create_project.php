<?php
require('../includes/config.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$project_name = trim($_POST['project_name']);
$project_description = trim($_POST['project_description']);
$created_by = $_SESSION['user_id'];

$query = "INSERT INTO projects (name, description, created_by) VALUES (:name, :description, :created_by)";
$stmt = $conn->prepare($query);
$stmt->bindParam(':name', $project_name);
$stmt->bindParam(':description', $project_description);
$stmt->bindParam(':created_by', $created_by);

if ($stmt->execute()) {
    header('Location: admin.php');
    exit();
} else {
    echo "Failed to create the project. Please try again.";
}
?>