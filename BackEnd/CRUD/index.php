<?php
    require('db.php');
    $query = 'SELECT * FROM users';
    $res = $conn->query($query);
    $users = $res->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <ul>
        <a href="create.php">Add USER</a>
        <table>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Role</th>
                <th>Gender</th>
                <th>Password</th>
                <th>Operations</th>
            </tr>
            <?php foreach($users as $user):?>
                <tr>
                    <td><?= $user['id'];?></td>
                    <td><?= $user['name'];?></td>
                    <td><?= $user['role'];?></td>
                    <td><?= $user['gender'];?></td>
                    <td><?= $user['password'];?></td>
                    <td>
                        <a href="update.php?id=<?= $user['id'];?>">Edit</a>
                        <a href="delete.php?id=<?= $user['id'];?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach;?>
        </table>
    </ul>
    
</body>
</html>