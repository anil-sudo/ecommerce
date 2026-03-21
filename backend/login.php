<?php
session_start();
include '../database/dbconnection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check prepare
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?"); // remove status if it doesn't exist
    if(!$stmt){
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Check if plain text password matches or if the hashed password matches
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            // If they signed in with plain-text, forcefully update to hashed
            if ($password === $user['password'] && !password_get_info($user['password'])['algo']) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $hash, $user['id']);
                $update_stmt->execute();
            }

            session_regenerate_id(true);
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: admin.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid email.";
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
