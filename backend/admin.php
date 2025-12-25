<?php
session_start();
include '../database/dbconnection.php';
include 'session_check.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | Products</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php include 'aside.php'; ?>

<div class="main">

    <!-- Flash Message -->
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p class='flash-message'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>

    <!-- Header + Add Button -->
    <div class="table-header">
        <h1>Product Management</h1>
        <a href="add_product.php">
            <button class="btn btn-add">+ Add Product</button>
        </a>
    </div>

    <!-- Dynamic Products Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price (Rs.)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM products ORDER BY id ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td><img src='../assets/images/" . $row['image'] . "' alt='product'></td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>Rs." . number_format($row['price'], 2) . "</td>";
                    echo "<td>
                            <a href='update_product.php?id=" . $row['id'] . "'>
                                <button class='btn btn-edit'>Update</button>
                            </a>
                            <a href='delete_product.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure?');\">
                                <button class='btn btn-delete'>Delete</button>
                            </a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>No products found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</div>

</body>
</html>
