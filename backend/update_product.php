<?php
session_start();
include '../database/dbconnection.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID!");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) die("Product not found!");

$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image_name = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . "_" . $_FILES['image']['name'];
        $upload_dir = "../assets/images/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $target = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $old_image = $upload_dir . $product['image'];
            if (file_exists($old_image)) unlink($old_image);
        } else {
            $error = "Failed to upload new image.";
        }
    }

    $stmt_update = $conn->prepare("UPDATE products SET name=?, price=?, image=? WHERE id=?");
    $stmt_update->bind_param("sdsi", $name, $price, $image_name, $id);

    if ($stmt_update->execute()) {
        $_SESSION['message'] = "Product updated successfully!";
        header("Location: admin.php");
        exit();
    } else {
        $error = "Database error: " . $stmt_update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product | Admin Panel</title>
    <link rel="stylesheet" href="css/add.css">
</head>
<body>

<?php include 'aside.php'; 
include 'session_check.php';?>

<div class="main">
    <h1>Update Product</h1>

    <?php if(isset($error)) echo "<p class='flash-message' style='background:#fee2e2;color:#b91c1c;'>$error</p>"; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Price (Rs.)</label>
            <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
        </div>

        <div class="form-group">
            <label>Current Image</label><br>
            <img src="../assets/images/<?php echo $product['image']; ?>" alt="product" style="width:80px;height:80px;object-fit:cover;border-radius:6px;margin-bottom:10px;">
        </div>

        <div class="form-group">
            <label>Change Image (optional)</label>
            <input type="file" name="image" accept="image/*">
        </div>

        <button type="submit" class="btn btn-submit">Update Product</button>
    </form>
</div>
</body>
</html>
