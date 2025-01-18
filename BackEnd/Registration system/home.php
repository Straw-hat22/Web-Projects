<?php
session_start();

// check if the user is logged in 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require('db.php');

// extract user data
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $user_name; ?>!</h1>
        <p>You are now logged in.</p>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>