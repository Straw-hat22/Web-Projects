<?php
    // include the databse config file and the header
    require('../includes/config.php');
    require('../includes/header.php');

    $errors = [];//array to store the errors
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // get the form data
        $username = trim(htmlspecialchars($_POST['username']));
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        //validtae if all fields are filled
        if(empty($username) || empty($email) || empty($password) || empty($confirm_password)){
            $errors[] = 'Please fill all Fields.';
        }

        // validate the email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = 'Invalid Email Address.';
        } else {
            // check if the email already exist
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $errors[] = 'Email already exists.';
            }

            $query = "SELECT * FROM users WHERE username = :username";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $errors[] = 'Username already exists.';
            }
        }

        // validate the password
        if(strlen($password) < 8){
            $errors[] = "password must be at least 8 characters long.";
        }elseif($password !== $confirm_password){
            $errors[] = "passwords do not match.";
        }

        // if no errors insert the user into the database
        if(empty($errors)){
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            if ($stmt->execute()) {
                echo "<p>Registration successful! Redirecting to login page...</p>";
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 3000); // Redirect after 3 seconds
                </script>";
            }else{
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }

?>

<main>
    <h2>Register</h2>
    <!-- put the errors here if there any -->
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="register.php" method="POST">
    <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <div>
            <button type="submit">Register</button>
        </div>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</main>



<?php
    require('../includes/footer.php');
?>