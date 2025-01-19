<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
require('../includes/config.php');
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    // delete all tasks with the project
    $query = "DELETE FROM tasks WHERE project_id = :project_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->execute();

    // delete the project
    $query = "DELETE FROM projects WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $project_id);
    $stmt->execute();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    header('Location: admin.php');
    exit();
}
?>