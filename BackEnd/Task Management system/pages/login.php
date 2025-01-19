<?php
    // include the databse config file and the header
    require('../includes/config.php');
    require('../includes/header.php');

    $errors = [];//array to store the errors
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // get the form data
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        //validtae if all fields are filled
        if(empty($email) || empty($password)){
            $errors[] = 'Please fill all Fields.';
        }


        // if no errors insert the user into the database
        if(empty($errors)){
           // check if the email already exist
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                // put the user data into an array
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // if the email exists validate the password
                if(password_verify($password, $user['password'])){

                    // start a session and store user data
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    // redirect to the dashboard
                    if($user['role'] === 'user'){
                        header('Location: dashboard.php');
                    }else{
                        header('Location: admin.php');
                    }
                    exit();
                }else{
                    $errors[] = 'Invalid password.';
                }
            }else{
                $errors[] = 'Email not found.';
            }
        }
    }

?>

<main>
    <h2>Login</h2>
    <!-- put the errors here if there any -->
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</main>



<?php
    require('../includes/footer.php');
?>