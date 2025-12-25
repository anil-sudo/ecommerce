

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | BCA Project</title>
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
 <?php
 include '../database/registrationdb.php';
 ?>

    <div class="register-container">

        <!-- Register Form -->
        <form class="register-form"  method = "post" action = "register.php">
            <h2>Create Account</h2>
          <?php
          include '../database/registrationdb.php';
          ?>
         
         
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Choose a username" value ="<?php echo htmlspecialchars($name);?>">
            <span style="color:red"><?php echo $nameError; ?></span><br>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" value = "<?php echo htmlspecialchars($email); ?>">
             <span style="color:red"><?php echo $emailError; ?></span><br>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password">
            
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password">
            <span style="color:red"><?php echo $passwordError; ?></span><br>

            <button type="submit" name ="submit">Register</button>

            <p class="login-link">
                Already registered? <a href="login.php">Login here</a>
            </p>
        </form>
    </div>
    
</body>
</html>
