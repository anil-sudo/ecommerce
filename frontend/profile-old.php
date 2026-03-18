<?php
session_start();
include "../database/dbconnection.php";

/* ── Session Protection ── */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ── Fetch user info safely ── */
$stmt = $conn->prepare("SELECT username, email FROM register_user WHERE id = ?");
if (!$stmt) {
    session_destroy();
    header("Location: login.php");
    exit();
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* ── Try to get phone if column exists ── */
$user['phone'] = '';
$check_phone = $conn->query("SHOW COLUMNS FROM register_user LIKE 'phone'");
if ($check_phone && $check_phone->num_rows > 0) {
    $stmt2 = $conn->prepare("SELECT phone FROM register_user WHERE id = ?");
    if ($stmt2) {
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $phone_result = $stmt2->get_result()->fetch_assoc();
        $user['phone'] = $phone_result['phone'] ?? '';
    }
}

/* ── Guard: stale / invalid session ── */
if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

/* ── Fetch order statistics ── */
$order_stmt = $conn->prepare("SELECT COUNT(*) as order_count FROM orders WHERE register_user_id = ?");
if ($order_stmt) {
    $order_stmt->bind_param("i", $user_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result()->fetch_assoc();
    $total_orders = $order_result['order_count'] ?? 0;
} else {
    $total_orders = 0;
}

$role    = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'Member';
$initial = strtoupper(substr($user['username'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile | ShopEase</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
:root {
  --gold:        #c9a84c;
  --gold-light:  #e8c97a;
  --gold-dim:    rgba(201,168,76,0.13);
  --dark:        #09090e;
  --surface:     #101018;
  --card:        #14141e;
  --border:      rgba(201,168,76,0.18);
  --border-soft: rgba(255,255,255,0.05);
  --text:        #f0ece3;
  --text-soft:   #b0acA4;
  --muted:       #6a6876;
  --green:       #22c55e;
  --danger:      #ef4444;
}

*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

body {
  background: var(--dark);
  color: var(--text);
  font-family: 'DM Sans', sans-serif;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  padding: 64px 16px 80px;
  overflow-x: hidden;
}

/* ── Ambient glow ── */
body::before {
  content: '';
  position: fixed;
  top: -10%;
  left: 50%;
  transform: translateX(-50%);
  width: 800px;
  height: 600px;
  background: radial-gradient(ellipse at center,
    rgba(201,168,76,0.06) 0%,
    transparent 65%);
  pointer-events: none;
  z-index: 0;
}

/* ── Wrapper ── */
.card {
  position: relative;
  width: 100%;
  max-width: 460px;
  z-index: 1;
  animation: rise 0.85s cubic-bezier(0.22,1,0.36,1) both;
}

@keyframes rise {
  from { opacity: 0; transform: translateY(36px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Hero ── */
.hero {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 22px 22px 0 0;
  padding: 52px 32px 40px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

/* subtle diagonal hatching */
.hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: repeating-linear-gradient(
    -55deg,
    rgba(201,168,76,0.025) 0px,
    rgba(201,168,76,0.025) 1px,
    transparent 1px,
    transparent 32px
  );
  pointer-events: none;
}

.hero-separator {
  position: absolute;
  bottom: 0; left: 50%;
  transform: translateX(-50%);
  width: 55%;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--gold), transparent);
}

/* Avatar */
.avatar-wrap {
  position: relative;
  display: inline-block;
  margin-bottom: 22px;
}

.avatar {
  width: 96px;
  height: 96px;
  border-radius: 50%;
  background: linear-gradient(145deg, #1a1a28, #242436);
  border: 2px solid var(--gold);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: 38px;
  font-weight: 700;
  color: var(--gold);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
  cursor: default;
  user-select: none;
}

.avatar:hover {
  transform: scale(1.06);
  box-shadow: 0 0 36px rgba(201,168,76,0.3);
}

.avatar-ring {
  position: absolute;
  inset: -9px;
  border-radius: 50%;
  border: 1.5px dashed rgba(201,168,76,0.3);
  animation: spin 14s linear infinite;
  pointer-events: none;
}

@keyframes spin { to { transform: rotate(360deg); } }

.avatar-ring-2 {
  position: absolute;
  inset: -16px;
  border-radius: 50%;
  border: 1px dashed rgba(201,168,76,0.1);
  animation: spin 22s linear infinite reverse;
  pointer-events: none;
}

.status-dot {
  position: absolute;
  bottom: 5px; right: 5px;
  width: 13px; height: 13px;
  background: var(--green);
  border-radius: 50%;
  border: 2px solid var(--surface);
  box-shadow: 0 0 8px rgba(34,197,94,0.55);
  animation: blink 2.5s ease infinite;
}

@keyframes blink {
  0%,100% { opacity: 1; }
  50%      { opacity: 0.55; }
}

.username {
  font-family: 'Playfair Display', serif;
  font-size: 26px;
  font-weight: 700;
  letter-spacing: 0.01em;
  color: var(--text);
  margin-bottom: 6px;
}

.user-meta {
  font-size: 13.5px;
  color: var(--muted);
  letter-spacing: 0.025em;
  line-height: 1.8;
  margin-bottom: 18px;
}

.role-badge {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 5px 16px;
  border: 1px solid rgba(34,197,94,0.35);
  background: rgba(34,197,94,0.07);
  border-radius: 100px;
  font-size: 11.5px;
  font-weight: 600;
  color: #4ade80;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

.role-badge::before {
  content: '';
  width: 6px; height: 6px;
  background: var(--green);
  border-radius: 50%;
  box-shadow: 0 0 6px rgba(34,197,94,0.8);
}

/* ── Body ── */
.body {
  background: var(--card);
  border: 1px solid var(--border);
  border-top: none;
  border-radius: 0 0 22px 22px;
  padding: 6px 20px 28px;
}

/* Info rows */
.row {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 16px 6px;
  border-bottom: 1px solid var(--border-soft);
  animation: slideIn 0.55s ease both;
}

.row:last-of-type { border-bottom: none; }
.row:nth-child(1) { animation-delay: 0.08s; }
.row:nth-child(2) { animation-delay: 0.16s; }
.row:nth-child(3) { animation-delay: 0.24s; }

@keyframes slideIn {
  from { opacity: 0; transform: translateX(-12px); }
  to   { opacity: 1; transform: translateX(0); }
}

.row-icon {
  width: 40px; height: 40px;
  border-radius: 11px;
  background: var(--gold-dim);
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 17px;
  flex-shrink: 0;
  transition: background 0.3s, transform 0.3s;
}

.row:hover .row-icon,
a.row-link:hover .row-icon {
  background: rgba(201,168,76,0.22);
  transform: rotate(6deg);
}

.row-text { flex: 1; min-width: 0; }

.row-label {
  font-size: 10.5px;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: var(--muted);
  margin-bottom: 2px;
}

.row-value {
  font-size: 14.5px;
  font-weight: 500;
  color: var(--text);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Linked rows */
a.row-link {
  text-decoration: none;
  color: inherit;
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 16px 10px;
  margin-inline: -10px;
  border-bottom: 1px solid var(--border-soft);
  border-radius: 10px;
  transition: background 0.25s;
  animation: slideIn 0.55s ease 0.32s both;
}

a.row-link:hover {
  background: rgba(201,168,76,0.05);
}

.row-arrow {
  color: var(--muted);
  font-size: 20px;
  transition: transform 0.3s, color 0.3s;
  line-height: 1;
}

a.row-link:hover .row-arrow {
  transform: translateX(4px);
  color: var(--gold);
}

/* ── Divider ── */
.divider {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px 0 14px;
}

.divider-line {
  flex: 1;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--border), transparent);
}

.divider-label {
  font-size: 10px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--muted);
}

/* ── Button ── */
.btn-logout {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 9px;
  width: 100%;
  padding: 13px 24px;
  border-radius: 13px;
  border: 1px solid rgba(239,68,68,0.35);
  background: transparent;
  color: #f87171;
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  font-weight: 500;
  letter-spacing: 0.04em;
  cursor: pointer;
  text-decoration: none;
  transition: background 0.3s, border-color 0.3s, transform 0.3s, box-shadow 0.3s;
}

.btn-logout:hover {
  background: rgba(239,68,68,0.1);
  border-color: rgba(239,68,68,0.65);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(239,68,68,0.12);
}

/* ── Brand ── */
.brand {
  text-align: center;
  margin-top: 26px;
  font-size: 10.5px;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: rgba(201,168,76,0.28);
}

.brand em { color: var(--gold); font-style: normal; }

/* ── Enhanced Features ── */
.stats-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 12px;
  margin-bottom: 18px;
}

.stat-box {
  background: rgba(201,168,76,0.06);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 14px 16px;
  text-align: center;
  transition: all 0.35s ease;
  cursor: default;
}

.stat-box:hover {
  background: rgba(201,168,76,0.12);
  border-color: var(--gold);
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(201,168,76,0.15);
}

.stat-number {
  font-family: 'Playfair Display', serif;
  font-size: 22px;
  font-weight: 700;
  color: var(--gold);
  line-height: 1;
  margin-bottom: 4px;
}

.stat-label {
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--muted);
}

/* ── Edit Profile Button ── */
.btn-edit-profile {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 11px 20px;
  margin-bottom: 14px;
  border-radius: 11px;
  border: 1px solid rgba(201,168,76,0.35);
  background: linear-gradient(135deg, rgba(201,168,76,0.08), rgba(201,168,76,0.02));
  color: var(--gold);
  font-family: 'DM Sans', sans-serif;
  font-size: 13.5px;
  font-weight: 600;
  letter-spacing: 0.04em;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.3s ease;
}

.btn-edit-profile:hover {
  background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.08));
  border-color: var(--gold);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(201,168,76,0.2);
}

/* ── Member Level Badge ── */
.member-level {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  margin-top: 12px;
  padding: 8px 14px;
  background: linear-gradient(135deg, rgba(34,197,94,0.1), rgba(34,197,94,0.04));
  border: 1px solid rgba(34,197,94,0.3);
  border-radius: 8px;
  font-size: 12px;
  color: #4ade80;
  font-weight: 600;
}

.member-level::before {
  content: '⭐';
  font-size: 14px;
}

/* ── Section Title ── */
.section-title {
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  color: var(--muted);
  margin-top: 16px;
  margin-bottom: 10px;
  padding-left: 6px;
}

/* ── Quick Links ── */
.quick-links {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-top: 14px;
}

.quick-link {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  padding: 12px;
  border-radius: 10px;
  border: 1px solid var(--border-soft);
  background: rgba(255,255,255,0.02);
  text-decoration: none;
  color: var(--text-soft);
  text-align: center;
  font-size: 12px;
  transition: all 0.3s ease;
}

.quick-link:hover {
  background: rgba(201,168,76,0.08);
  border-color: var(--gold);
  color: var(--gold);
  transform: translateY(-2px);
}

.quick-link-icon {
  font-size: 20px;
}

/* ── Tooltip ── */
.tooltip {
  position: relative;
  display: inline-block;
  cursor: help;
}

.tooltip::after {
  content: attr(data-tooltip);
  position: absolute;
  bottom: 125%;
  left: 50%;
  transform: translateX(-50%);
  background: #1a1a1e;
  color: var(--gold);
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 11px;
  white-space: nowrap;
  border: 1px solid var(--border);
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s;
  z-index: 100;
}

.tooltip:hover::after {
  opacity: 1;
}

/* ── Premium Border Effect ── */
.premium-border {
  position: relative;
  background: var(--card);
  border-radius: 22px;
  overflow: hidden;
}

.premium-border::before {
  content: '';
  position: absolute;
  inset: -2px;
  background: linear-gradient(135deg, #c9a84c, #e8c97a, #c9a84c);
  border-radius: 22px;
  opacity: 0;
  transition: opacity 0.6s ease;
  pointer-events: none;
  z-index: -1;
}

.premium-border:hover::before {
  opacity: 0.15;
}

/* ── Responsive ── */
@media (max-width: 520px) {
  body { padding: 48px 12px 60px; }
  .hero { padding: 40px 24px 32px; }

}
</style>
</head>
<body>

<div class="card">

  <!-- HERO -->
  <div class="hero">
    <div class="avatar-wrap">
      <div class="avatar-ring-2"></div>
      <div class="avatar-ring"></div>
      <div class="avatar"><?= $initial ?></div>
      <div class="status-dot"></div>
    </div>

    <div class="username"><?= htmlspecialchars($user['username']) ?></div>

    <div class="user-meta">
      <?= htmlspecialchars($user['email']) ?>
      <?php if (!empty($user['phone'])): ?>
        <br><?= htmlspecialchars($user['phone']) ?>
      <?php endif; ?>
    </div>

    <div class="role-badge"><?= htmlspecialchars($role) ?></div>
    <div class="hero-separator"></div>
  </div>

  <!-- BODY -->
  <div class="body">

    <!-- Edit Profile Button -->
    <a href="edit-profile.php" class="btn-edit-profile">
      ✎ Edit Profile
    </a>

    <!-- Member Level -->
    <div style="text-align: center;">
      <div class="member-level">Gold Member</div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-box">
        <div class="stat-number"><?= $total_orders ?></div>
        <div class="stat-label">Orders</div>
      </div>
    </div>

    <!-- User Info -->
    <div class="row">
      <div class="row-icon">📧</div>
      <div class="row-text">
        <div class="row-label">Email Address</div>
        <div class="row-value"><?= htmlspecialchars($user['email']) ?></div>
      </div>
    </div>

    <?php if (!empty($user['phone'])): ?>
    <div class="row">
      <div class="row-icon">📱</div>
      <div class="row-text">
        <div class="row-label">Phone Number</div>
        <div class="row-value"><?= htmlspecialchars($user['phone']) ?></div>
      </div>
    </div>
    <?php endif; ?>

    <div class="row">
      <div class="row-icon">🕐</div>
      <div class="row-text">
        <div class="row-label">Last Login</div>
        <div class="row-value">Today</div>
      </div>
    </div>

    <div class="row">
      <div class="row-icon">✓</div>
      <div class="row-text">
        <div class="row-label">Account Status</div>
        <div class="row-value" style="color: #4ade80;">Active & Verified</div>
      </div>
    </div>

    <!-- Divider -->
    <div class="divider">
      <div class="divider-line"></div>
      <div class="divider-label">Shopping</div>
      <div class="divider-line"></div>
    </div>

    <!-- Quick Links Grid -->
    <div class="quick-links">
      <a href="../frontend/orders.php" class="quick-link">
        <div class="quick-link-icon">📦</div>
        <div>Orders</div>
      </a>
      <a href="../frontend/product.php" class="quick-link">
        <div class="quick-link-icon">🛍️</div>
        <div>Shop</div>
      </a>
      <a href="../frontend/cart.php" class="quick-link">
        <div class="quick-link-icon">🛒</div>
        <div>Cart</div>
      </a>
      <a href="../frontend/about.php" class="quick-link">
        <div class="quick-link-icon">ℹ️</div>
        <div>About</div>
      </a>
    </div>

    <!-- Actions -->
    <div style="margin-top: 16px;">
      <a href="../database/logout.php" class="btn-logout">
        <span>⎋</span> Sign Out
      </a>
    </div>

  </div>

  <div class="brand">Shop<em>Ease</em> — Premium Member Portal</div>

</div>

</body>
</html>
