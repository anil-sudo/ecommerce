<?php
session_start();
include '../database/dbconnection.php';

/* Cart items count */
$totalItems = 0;
$cartResult = $conn->query("SELECT SUM(quantity) AS total FROM cart");
if ($cartResult && $cartResult->num_rows > 0) {
    $cartRow = $cartResult->fetch_assoc();
    $totalItems = $cartRow['total'] ?? 0;
}
?>

<header class="header">
    <div class="navbar">

        <!-- Logo -->
        <div class="logo">
            <a href="../frontend/index.php">ShopEase</a>
        </div>

        <!-- Navigation -->
        <nav class="nav-links">
            <a href="../frontend/index.php">Home</a>
            <a href="../frontend/product.php">Products</a>
            <a href="../frontend/about.php">About Us</a>
        </nav>

        <!-- ================= SEARCH PART START ================= -->
        <!-- This form sends the search keyword to search-items.php -->
        <div class="search-box">
            <form action="../frontend/search-items.php" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search products..." 
                    autocomplete="off"
                    required
                >
                <button type="submit">Search</button>
            </form>
        </div>
        <!-- ================== SEARCH PART END ================== -->

        <!-- Right actions -->
        <div class="nav-actions">
            <?php if (isset($_SESSION['username'])): ?>
                <a href="../frontend/cart.php" class="cart">
                    🛒 Cart
                    <?php if ($totalItems > 0): ?>
                        <span class="cart-count"><?php echo $totalItems; ?></span>
                    <?php endif; ?>
                </a>

                <a href="../frontend/profile.php" class="profile">
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>

                <a href="../database/logout.php" class="profile">Logout</a>
            <?php else: ?>
                <a href="../frontend/login.php" class="sign">Sign in</a>
            <?php endif; ?>
        </div>

    </div>
</header>
