
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | BCA Project</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

    <?php 
    include '../database/loginconn.php';
    ?>
    <div class="login-container">
        <form class="login-form" method="POST" action="">
            <h2>Login</h2>

            <?php if($error) { echo "<p style='color:red;'>$error</p>"; } ?>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button type="submit" name="login">Login</button>

            <p class="register-link">
                Not registered? <a href="register.php">Register here</a>
            </p>
        </form>
    </div>
</body>
</html>