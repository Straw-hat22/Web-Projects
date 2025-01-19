<?php
require('../includes/config.php');
require('../includes/header.php');

// check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // if not redirect to the login page
    header('Location: login.php');
    exit();
}

// fetch unread notifications
$query = "SELECT * FROM notifications WHERE is_read = 0 ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// fetch all projects and tasks
$query = "SELECT * FROM projects";
$stmt = $conn->prepare($query);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT tasks.*, users.username AS assigned_to_username FROM tasks JOIN users ON tasks.assigned_to = users.id";
$stmt = $conn->prepare($query);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <h2>Welcome, Admin!</h2>

    <h3>Notifications</h3>
    <?php if (empty($notifications)): ?>
        <p>No new notifications.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($notifications as $notification): ?>
                <li>
                    <p><?php echo htmlspecialchars($notification['message']); ?></p>
                    <small><?php echo htmlspecialchars($notification['created_at']); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- clear notifications button -->
        <form action="clear_notifications.php" method="POST" style="margin-top: 10px;">
            <button type="submit" class="btn-clear">Clear Notifications</button>
        </form>
    <?php endif; ?>

    <!--create project form -->
    <h3>Create Project</h3>
    <form action="create_project.php" method="POST">
        <div>
            <label for="project_name">Project Name:</label>
            <input type="text" name="project_name" id="project_name" required>
        </div>
        <div>
            <label for="project_description">Description:</label>
            <textarea name="project_description" id="project_description" required></textarea>
        </div>
        <div>
            <button type="submit">Create Project</button>
        </div>
    </form>

    <!--assign task form-->
    <h3>Assign Task</h3>
    <form action="assign_task.php" method="POST">
        <div>
            <label for="task_title">Task Title:</label>
            <input type="text" name="task_title" id="task_title" required>
        </div>
        <div>
            <label for="task_description">Description:</label>
            <textarea name="task_description" id="task_description" required></textarea>
        </div>
        <div>
            <label for="project_id">Project:</label>
            <select name="project_id" id="project_id" required>
                <?php foreach ($projects as $project): ?>
                    <option value="<?php echo $project['id']; ?>"><?php echo htmlspecialchars($project['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="assigned_to">Assign To:</label>
            <select name="assigned_to" id="assigned_to" required>
                <?php
                $query = "SELECT id, username FROM users WHERE role = 'user'";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <button type="submit">Assign Task</button>
        </div>
    </form>

    <!-- display projects and tasks in a table -->
    <h3>Projects and Tasks</h3>
    <?php if (empty($projects)): ?>
        <p>No projects found.</p>
    <?php else: ?>
        <table class="projects-table">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Description</th>
                    <th>Tasks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($project['name']); ?></td>
                        <td><?php echo htmlspecialchars($project['description']); ?></td>
                        <td>
                            <table class="tasks-table">
                                <thead>
                                    <tr>
                                        <th>Task Title</th>
                                        <th>Description</th>
                                        <th>Assigned To</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tasks as $task): ?>
                                        <?php if ($task['project_id'] === $project['id']): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                                <td><?php echo htmlspecialchars($task['description']); ?></td>
                                                <td><?php echo htmlspecialchars($task['assigned_to_username']); ?></td>
                                                <td>
                                                    <?php
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
                                                    <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn-edit">Edit</a>
                                                    <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn-delete">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="btn-delete-project" onclick="return confirm('Are you sure you want to delete this project and all its tasks?');">Delete Project</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <br>
    <br>
    <br>
</main>

<?php
require('../includes/footer.php');
?>