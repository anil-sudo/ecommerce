<?php
session_start();
include '../database/dbconnection.php';
include 'session_check.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Products</title>    
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php include 'aside.php'; ?>

<div class="main">

    <!-- Header + Add Button -->
    <div class="table-header" style="margin-bottom: .5rem;">
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
                    echo "<td><img src='../assets/images/" . htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8') . "' alt='product'></td>";
                    
                    $product_name_display = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                    if ($row['is_deleted'] == 1) {
                        $product_name_display .= " <span style='color:red; font-size:12px; font-weight:bold;'>(Deleted)</span>";
                    }
                    echo "<td>" . $product_name_display . "</td>";
                    echo "<td>Rs." . number_format($row['price'], 2) . "</td>";
                    echo "<td>
                            <a href='update_product.php?id=" . $row['id'] . "'>
                                <button class='btn btn-edit'>Update</button>
                            </a>";
                    if ($row['is_deleted'] == 0) {
                        echo "
                            <form action='delete_product.php' method='POST' style='display:inline;' onsubmit=\"return confirm('Are you sure you want to delete this product?');\">
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='submit' class='btn btn-delete'>Delete</button>
                            </form>";
                    }
                    echo "</td>";
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
