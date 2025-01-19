<?php
require('../includes/config.php');
require('../includes/header.php');

// Start the session and check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch tasks assigned to the logged in user with status filter
$user_id = $_SESSION['user_id'];
$status_filter = $_GET['status_filter'] ?? ''; // Get the status filter from the URL

// Base query to fetch tasks
$query = "SELECT * FROM tasks WHERE assigned_to = :user_id";

// add status filter to the query if a filter is selected
if ($status_filter) {
    $query .= " AND status = :status";
}


// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
if ($status_filter) {
    $stmt->bindParam(':status', $status_filter);
}
$stmt->bindParam(':limit', $tasks_per_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// handle status update 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $task_id = $_POST['task_id'];
    $new_status = $_POST['status'];

    // update the task status in the database
    $query = "UPDATE tasks SET status = :status WHERE id = :task_id AND assigned_to = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':status', $new_status);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
        // insert a notification for the admin
        $message = "User {$_SESSION['username']} updated task '{$task['title']}' to '$new_status'.";
        $query = "INSERT INTO notifications (message) VALUES (:message)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':message', $message);
        $stmt->execute();

        // Refresh the page to show the updated status
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Failed to update the task status. Please try again.";
    }
}

?>

<main>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <!-- status filter -->
    <h3>Your Tasks</h3>
    <form action="dashboard.php" method="GET">
        <label for="status_filter">Filter by Status:</label>
        <select name="status_filter" id="status_filter" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="in_progress" <?php echo $status_filter === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
            <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
        </select>
    </form>

    <!-- sidplay Tasks -->
    <?php if (empty($tasks)): ?>
        <p>No tasks found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td>
                            <?php
                            // display the task status 
                            $status = htmlspecialchars($task['status']);
                            $status_class = '';
                            switch ($status) {
                                case 'pending':
                                    $status_class = 'status-pending';
                                    break;
                                case 'in_progress':
                                    $status_class = 'status-in-progress';
                                    break;
                                case 'completed':
                                    $status_class = 'status-completed';
                                    break;
                            }
                            echo "<span class='status $status_class'>$status</span>";
                            ?>
                        </td>
                        <td>
                            <!-- status update -->
                            <form action="dashboard.php" method="POST" style="display: inline;">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" <?php echo $task['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="in_progress" <?php echo $task['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="completed" <?php echo $task['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php
require('../includes/footer.php');
?>