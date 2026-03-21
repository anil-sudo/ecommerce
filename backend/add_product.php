<?php
session_start();
include '../database/dbconnection.php';
include 'session_check.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);
    $price = $_POST['price'];

    /* -------- Product Name Validation -------- */
    if (empty($name) || strlen($name) < 3) {
    $error = "Product name must be at least 3 characters.";
} 
elseif (!preg_match("/[a-zA-Z]/", $name)) {
    $error = "Product name must contain letters.";
}

    /* -------- Price Validation -------- */
    elseif (!is_numeric($price) || $price <= 0) {
        $error = "Price must be a positive number.";
    } 
    elseif ($price > 1000000) {
        $error = "Price is too large.";
    }

    /* -------- Image Validation -------- */
    elseif (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
        $error = "Please select an image.";
    }

    else {

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($file_extension, $allowed_extensions) || !in_array($_FILES['image']['type'], $allowed_mime_types)) {
            $error = "Only JPG, PNG, or WEBP images are allowed.";
        } else {

            $image_name = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['image']['name']);
            $upload_dir = "../assets/images/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $target = $upload_dir . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {

                $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
                $stmt->bind_param("sds", $name, $price, $image_name);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Product added successfully!";
                    header("Location: admin.php");
                    exit();
                } else {
                    $error = "Database error: " . $stmt->error;
                }

            } else {
                $error = "Failed to upload image.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product | Admin Panel</title>
<link rel="stylesheet" href="css/add.css">
</head>

<body>

<?php include 'aside.php'; ?>

<div class="main">
<h1>Add New Product</h1>

<?php
if (isset($error)) {
    echo "<p class='flash-message'>$error</p>";
}

if (isset($_SESSION['message'])) {
    echo "<p class='flash-message flash-success' style='text-align:center;'>".$_SESSION['message']."</p>";
    unset($_SESSION['message']);
}
?>

<form action="" method="POST" enctype="multipart/form-data">

<div class="form-group">
<label>Product Name</label>
<input type="text" name="name" required>
</div>

<div class="form-group">
<label>Price (Rs.)</label>
<input type="number" name="price" step="0.01" min="5000" max="1000000" required>
</div>

<div class="form-group">
<label>Product Image</label>
<input type="file" name="image" accept="image/*" required>
</div>

<button type="submit" class="btn btn-submit">Add Product</button>

</form>
</div>

</body>
</html>