<?php
include '../database/dbconnection.php';

// Get first 8 products ordered by ID (ascending)
$sql = "SELECT * FROM products ORDER BY id ASC LIMIT 8";
$result = $conn->query($sql);

$products = [];
if($result && $result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $products[] = $row;
    }
}

// Separate Featured and Best Sellers
$featuredProducts = array_slice($products, 0, 4);
$bestSellProducts = array_slice($products, 4, 4);
?>



<?php
include '../database/dbconnection.php';

// Handle Add to Cart
include 'add_cart_adder.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ecommerce</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <!-- hero-slider -->
    <section class="hero">
        <div class="slider">
            <div class="slide active">
                <img src="../assets/images/a.jpg" alt="Big Sale">
                <div class="caption">
                    <h1>Big Sale 50% Off</h1>
                    <p>Best deals on top products</p>
                    <a href="product.php" class="btn">Shop Now</a>
                </div>
            </div>
            <div class="slide">
                <img src="../assets/images/b.jpg" alt="New Arrivals">
                <div class="caption">
                    <h1>New Arrivals</h1>
                    <p>Latest trends just for you</p>
                    <a href="product.php" class="btn">Explore</a>
                </div>
            </div>
            <div class="slide">
                <img src="../assets/images/d.jpg" alt="Fast Delivery">
                <div class="caption">
                    <h1>Fast & Secure Delivery</h1>
                    <p>Across Nepal & Worldwide</p>
                    <a href="product.php" class="btn">Order Now</a>
                </div>
            </div>
        </div>
    </section>

    <script>
        let slides = document.querySelectorAll(".slide");
        let index = 0;
        function showSlide() {
            slides.forEach(slide => slide.classList.remove("active"));
            slides[index].classList.add("active");
            index = (index + 1) % slides.length;
        }
        setInterval(showSlide, 3000);
    </script>

    <!-- featured-products -->
</section>

<!-- Featured Products -->
<section class="featured-products">
    <h2 class="section-title" style="text-align:center; margin:40px;">Featured Products</h2>
    <div class="products-container">
        <?php foreach($featuredProducts as $product): ?>
        <div class="product-card">
            <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <div class="rating">★★★★★</div>
            <div class="price-cart">
                <p class="price">Rs.<?php echo number_format($product['price'], 2); ?></p>
                <form method="post">
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                    <button type="submit" name="cart" class="btn-cart">Add to Cart</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

    <!-- promo-banner -->
    <section class="promo-banner">
        <div class="promo-content">
            <h2>Up to 70% OFF</h2>
            <p>Grab the best deals now!</p>
            <a href="product.php" class="btn">Shop Now</a>
        </div>
    </section>

<!-- Best Sellers -->
<section class="best-sellers">
    <h2 class="section-title" style="text-align:center; margin:40px;">Best Sellers</h2>
    <div class="best-sellers-container">
        <?php foreach($bestSellProducts as $product): ?>
        <div class="product-card">
            <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <div class="rating">★★★★★</div>
            <div class="price-cart">
                <p class="price">Rs.<?php echo number_format($product['price'], 2); ?></p>
                <form method="post">
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                    <button type="submit" name="cart" class="btn-cart">Add to Cart</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>




    <?php include '../includes/footer.php'; ?>
</body>
</html>
