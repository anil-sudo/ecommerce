<?php
include '../database/dbconnection.php';

$totalItems = 0;
// Get current page for nav highlighting
$currentPage = basename($_SERVER['PHP_SELF']);

if (isset($_SESSION['user_id'])) {
    $register_user_id = $_SESSION['user_id'];
    $cartStmt = $conn->prepare("SELECT id FROM carts WHERE register_user_id = ?");
    $cartStmt->bind_param("i", $register_user_id);
    $cartStmt->execute();
    $cartResult = $cartStmt->get_result();

    if ($cartResult->num_rows > 0) {
        $cart = $cartResult->fetch_assoc();
        $cart_id = $cart['id'];
        $countStmt = $conn->prepare("SELECT SUM(quantity) AS total FROM cart_items WHERE cart_id = ?");
        $countStmt->bind_param("i", $cart_id);
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        if ($countResult->num_rows > 0) {
            $row = $countResult->fetch_assoc();
            $totalItems = $row['total'] ?? 0;
        }
    }
}
?>
<!-- Font Awesome 6 Free -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<header class="header">
    <div class="navbar">

        <!-- Logo -->
        <div class="logo">
            <a href="../frontend/index.php">ShopEase<span>.</span></a>
        </div>

        <!-- Navigation Links (Doubles as Mobile Menu) -->
        <nav class="nav-links">
            <?php if (isset($_SESSION['username'])): ?>
                <a href="../frontend/profile.php" class="mobile-only">Profile</a>
            <?php else: ?>
                <a href="../frontend/login.php" class="mobile-only">Sign In</a>
            <?php endif; ?>
            
            <a href="../frontend/index.php" class="<?= ($currentPage == 'index.php') ? 'active' : '' ?>">Home</a>
            <a href="../frontend/product.php" class="<?= ($currentPage == 'product.php') ? 'active' : '' ?>">Products</a>
            <a href="../frontend/about.php" class="<?= ($currentPage == 'about.php') ? 'active' : '' ?>">About Us</a>
            
            <?php if (isset($_SESSION['username'])): ?>
                <a href="../database/logout.php" class="mobile-only" style="color: #e53e3e;">Logout</a>
            <?php endif; ?>
        </nav>

        <!-- Search Box -->
        <div class="search-box">
            <form action="../frontend/search-items.php" method="GET" style="display:flex; align-items:center; margin:0; width:100%;">
                <input type="text" name="search" placeholder="Search..." autocomplete="off" required>
                <button type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>

        <!-- Right actions -->
        <div class="nav-actions">
            <?php if (isset($_SESSION['username'])): ?>
                <a href="../frontend/cart.php" class="action-btn cart-btn">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <?php if ($totalItems > 0): ?>
                        <span class="cart-badge"><?= $totalItems ?></span>
                    <?php endif; ?>
                </a>

                <a href="../frontend/profile.php" class="action-btn profile-btn desktop-only" title="Profile">
                    <i class="fa-solid fa-user"></i>
                </a>
                

            <?php else: ?>
                <a href="../frontend/login.php" style="text-decoration:none;" class="login-btn desktop-only">Sign In</a>
            <?php endif; ?>

            <!-- Mobile Toggle -->
            <button class="mobile-toggle" id="mobile-toggle" aria-label="Menu">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const mobileToggle = document.getElementById('mobile-toggle');
    const navbar = document.querySelector('.navbar');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            navbar.classList.toggle('active');
            
            const icon = mobileToggle.querySelector('i');
            if (navbar.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');
            } else {
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        });
    }
});
</script>