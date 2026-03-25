<?php
include '../database/dbconnection.php';
include 'add_cart_adder.php';

$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    
</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="search-results">
    <h2 style="text-align:center; margin:40px;">
        Search results for "<?php echo htmlspecialchars($search); ?>"
    </h2>

    <div class="products-container">
        <?php
        if ($search != "") {
            $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? AND is_deleted = 0");
            $term = "%" . $search . "%";
            $stmt->bind_param("s", $term);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($product = $result->fetch_assoc()):
        ?>
                    <div class="product-card">
                        <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">

                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>

                        <div class="rating">★★★★★</div>

                        <div class="price-cart">
                            <p class="price">Rs.<?php echo number_format($product['price'], 2); ?></p>
                            <form method="post" action="">
                                <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                                <button type="submit" name="cart" class="btn-cart" title="Add to Cart"><i class="fa-solid fa-cart-shopping"></i></button>
                            </form>
                        </div>
                    </div>
        <?php
                endwhile;
            } else {
                echo "<p style='text-align:center;'>No products found.</p>";
            }
        } else {
            echo "<p style='text-align:center;'>Please enter a search term.</p>";
        }
        ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

</body>
</html>


