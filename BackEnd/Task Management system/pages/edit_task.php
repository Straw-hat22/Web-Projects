<?php
require('../includes/config.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    $query = "SELECT * FROM tasks WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $task_id);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        echo "Task not found.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $query = "UPDATE tasks SET title = :title, description = :description, status = :status WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $task_id);
    $stmt->execute();

    header('Location: admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main>
        <h2>Edit Task</h2>
        <form method="POST">
            <div>
                <label for="title">Task Title:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea name="description" id="description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>
            <div>
                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="pending" <?php echo $task['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="in_progress" <?php echo $task['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="completed" <?php echo $task['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <div>
                <button type="submit">Update Task</button>
            </div>
        </form>
    </main>
</body>
</html>