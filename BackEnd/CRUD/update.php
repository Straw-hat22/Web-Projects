<?php
    require('db.php');
    $id= $_GET['id'];
    $query = 'SELECT * FROM users WHERE id = :id';
    $res = $conn->prepare($query);
    $res->execute(['id' => $id]);
    $user = $res->fetch(PDO::FETCH_ASSOC);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_GET['id'];
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $query = 'UPDATE users SET name = :name, gender = :gender, role = :role, password = :password';
        $res = $conn->prepare($query);
        $res->execute(['name' => $name, 'gender' => $gender, 'role' => $role, 'password' => $password]);
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit USER</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="update.php?id=<?= $user['id'];?>" method="POST">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" autocomplete="off" value="<?= $user['name'];?>">
        <label for="role">Role</label>
        <input type="text" id="role" name="role" autocomplete="off" value="<?= $user['role'];?>">
        <label for="gender">Gender</label>
        <input type="text" id="gender" name="gender" autocomplete="off" value="<?= $user['gender'];?>">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="off" value="<?= $user['password'];?>">
        <button type="submit">Submit</button>
    </form>
</body>
</html>