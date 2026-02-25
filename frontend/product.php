<?php
include '../database/dbconnection.php';

// Pagination settings
$limit = 8;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total number of products
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalRow = $totalResult->fetch_assoc();
$totalProducts = $totalRow['total'];
$totalPages = ceil($totalProducts / $limit);

// Fetch products with limit
$sql = "SELECT id, name, price, image FROM products ORDER BY id ASC LIMIT $limit OFFSET $offset";
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

        <!-- Pagination -->
        <div class="pagination" style="text-align:center; margin:30px 0;">
            
            <!-- Previous Button -->
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1; ?>" class="btn-page">Previous</a>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i; ?>" 
                class="btn-page <?= ($i == $page) ? 'active-page' : ''; ?>">
                <?= $i; ?>
                </a>
            <?php endfor; ?>

            <!-- Next Button -->
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1; ?>" class="btn-page">Next</a>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include('../includes/footer.php'); ?>

</body>
</html>
