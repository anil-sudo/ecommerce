<?php
session_start();
include '../database/dbconnection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND status='active'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Check password (if stored as plain text for now)
        // For hashed passwords, use password_verify($password, $user['password'])
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: admin.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid email or user inactive.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Admin Panel</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>

        <?php if($error) echo "<p class='flash-message' style='background:#fee2e2;color:#b91c1c;'>$error</p>"; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>
    </div>
</body>
</html>
