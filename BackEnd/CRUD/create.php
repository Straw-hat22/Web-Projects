<?php
    require('db.php');
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $query = "INSERT INTO users (name, gender, role, password) VALUES (:name, :gender, :role, :password)";
        $res = $conn->prepare($query);
        $res->execute(['name'=> $name, 'gender' => $gender, 'role' => $role, 'password' => $password]);
        header('Location: index.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add USER</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="create.php" method="POST">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" autocomplete="off">
        <label for="role">Role</label>
        <input type="text" id="role" name="role" autocomplete="off">
        <label for="gender">Gender</label>
        <input type="text" id="gender" name="gender" autocomplete="off">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="off">
        <button type="submit">Submit</button>
    </form>
</body>
</html>