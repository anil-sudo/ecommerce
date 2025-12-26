<?php
include '../database/dbconnection.php';



// Fetch all products
$sql = "SELECT id, name, price, image FROM products ORDER BY id ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ShopEase | Products</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/product.css">
    
</head>
<body>

   <?php 
        include '../includes/header.php';
        include 'add_cart_adder.php'; 
     ?>

<section class="products-page">
    <div class="container">
        <h2 style="margin:20px 0; text-align:center;">All Products</h2>

        <div class="products-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $row): ?>
                    <a href="/e-commerce/frontend/product-detail.php?id=<?= $row['id'] ?>" style="text-decoration: none;">
                        <div class="product-card">
                            <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <div class="rating">★★★★★</div>
                            <div class="price-cart">
                                <p class="price">Rs.<?php echo number_format($row['price'], 2); ?></p>
                                <form method="post">
                                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
                                    <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                                    <input type="hidden" name="image" value="<?php echo htmlspecialchars($row['image']); ?>">
                                    <button type="submit" name="cart" class="btn-cart">Add to Cart</button>
                                </form>
                            </div>
                        </div>    
                    </a>
                <?php endforeach; ?>
            <?php else: ?>z
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include('../includes/footer.php'); ?>

</body>
</html>
