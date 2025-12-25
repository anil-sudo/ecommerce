<?php
// Initialize variables
$name = $email = "";
$nameError = $emailError = $passwordError = "";

// Include database connection
include 'dbconnection.php';

if (isset($_POST['submit'])) {

    // Get values safely
    $name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $isValid = true;

    // Username validation (only letters and spaces)
    if (empty($name)) {
        $nameError = "Username is required";
        $isValid = false;
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $nameError = "Invalid name: only letters and spaces allowed";
        $isValid = false;
    }

    // Email validation
    if (empty($email)) {
        $emailError = "Email is required";
        $isValid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format";
        $isValid = false;
    }

    // Password validation
    if (empty($password) || empty($confirm_password)) {
        $passwordError = "Password fields are required";
        $isValid = false;
    } elseif ($password !== $confirm_password) {
        $passwordError = "Passwords do not match";
        $isValid = false;
    }

    // Check if email already exists
    if ($isValid) {
        $checkEmail = "SELECT id FROM register_user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $checkEmail);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $emailError = "Email already registered";
            $isValid = false;
        }

        mysqli_stmt_close($stmt);
    }

    // Insert into database if valid
    if ($isValid) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO register_user (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPassword);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Registration successful!'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Something went wrong');</script>" . $stmt->error;
        }

        mysqli_stmt_close($stmt);
    }
}

$conn->close();
?>
