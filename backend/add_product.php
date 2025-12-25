<?php
session_start();
include '../database/dbconnection.php';
include 'session_check.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . "_" . $_FILES['image']['name'];
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
    } else {
        $error = "Please select an image.";
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
    // Display error message
    if (isset($error)) {
        echo "<p class='flash-message'>$error</p>";
    }

    // Display success message (from redirect, optional)
    if (isset($_SESSION['message'])) {
       echo "<p class='flash-message flash-success' style='text-align:center;'>" . $_SESSION['message'] . "</p>";

        unset($_SESSION['message']);
    }
    ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Price ($)</label>
            <input type="number" step="0.01" name="price" required>
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
