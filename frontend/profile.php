<?php
session_start();
include "../database/dbconnection.php";

/* Session Protection */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_SESSION['role'])) {
    $model = 'register_user';
} else {
    $model = ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'editor') ? 'register_user' : 'users';
}
    
$user_id = $_SESSION['user_id'];

/* Fetch user info */
if ($model === 'users') {
    $stmt = $conn->prepare("SELECT username, email FROM admins WHERE id = ?");
} else {
    $stmt = $conn->prepare("SELECT username, email, phone FROM register_user WHERE id = ?");
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile | ShopEase</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

<style>
:root {
  --gold: #c9a84c;
  --gold-light: #e8c97a;
  --gold-dim: rgba(201,168,76,0.15);
  --dark: #0a0a0f;
  --surface: #111118;
  --card: #16161f;
  --card-hover: #1c1c28;
  --border: rgba(201,168,76,0.2);
  --text: #f0ece3;
  --muted: #7a7880;
  --danger: #e05555;
}

* { margin:0; padding:0; box-sizing:border-box; }

body {
  background: var(--dark);
  color: var(--text);
  font-family: 'DM Sans', sans-serif;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 60px 16px 80px;
  overflow-x: hidden;
}

/* Ambient background glow */
body::before {
  content: '';
  position: fixed;
  top: -20%;
  left: 50%;
  transform: translateX(-50%);
  width: 700px;
  height: 500px;
  background: radial-gradient(ellipse, rgba(201,168,76,0.07) 0%, transparent 70%);
  pointer-events: none;
  z-index: 0;
}

/* ── CARD ── */
.profile-card {
  position: relative;
  max-width: 480px;
  width: 100%;
  z-index: 1;
  animation: rise 0.9s cubic-bezier(0.22,1,0.36,1) both;
}

@keyframes rise {
  from { opacity: 0; transform: translateY(40px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── HERO ── */
.hero {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 24px 24px 0 0;
  padding: 48px 32px 36px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    repeating-linear-gradient(
      45deg,
      rgba(201,168,76,0.03) 0px,
      rgba(201,168,76,0.03) 1px,
      transparent 1px,
      transparent 28px
    );
  pointer-events: none;
}

.hero-line {
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 60%;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--gold), transparent);
}

/* Avatar */
.avatar-wrap {
  position: relative;
  display: inline-block;
  margin-bottom: 24px;
}

.avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: linear-gradient(135deg, #1e1e2e, #2a2a3e);
  border: 2px solid var(--gold);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: 40px;
  font-weight: 700;
  color: var(--gold);
  position: relative;
  transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.avatar:hover {
  transform: scale(1.07);
  box-shadow: 0 0 40px rgba(201,168,76,0.35);
}

/* Spinning ring */
.avatar-ring {
  position: absolute;
  inset: -8px;
  border-radius: 50%;
  border: 1.5px dashed rgba(201,168,76,0.35);
  animation: spin 12s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.status-dot {
  position: absolute;
  bottom: 4px;
  right: 4px;
  width: 14px;
  height: 14px;
  background: #22c55e;
  border-radius: 50%;
  border: 2px solid var(--surface);
  box-shadow: 0 0 8px rgba(34,197,94,0.6);
}

.username {
  font-family: 'Playfair Display', serif;
  font-size: 28px;
  font-weight: 700;
  letter-spacing: 0.02em;
  color: var(--text);
  margin-bottom: 6px;
}

.user-email {
  font-size: 14px;
  color: var(--muted);
  letter-spacing: 0.03em;
  margin-bottom: 4px;
}

.user-phone {
  font-size: 14px;
  color: var(--muted);
  letter-spacing: 0.03em;
  margin-bottom: 16px;
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 14px;
  border: 1px solid rgba(34,197,94,0.4);
  background: rgba(34,197,94,0.08);
  border-radius: 100px;
  font-size: 12px;
  font-weight: 500;
  color: #4ade80;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.badge::before {
  content: '';
  width: 6px;
  height: 6px;
  background: #22c55e;
  border-radius: 50%;
  box-shadow: 0 0 6px rgba(34,197,94,0.8);
  animation: pulse 2s ease infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%       { opacity: 0.5; transform: scale(0.8); }
}

/* ── BODY ── */
.body {
  background: var(--card);
  border: 1px solid var(--border);
  border-top: none;
  border-radius: 0 0 24px 24px;
  padding: 8px 20px 28px;
}

/* Info rows */
.info-row {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 18px 0;
  border-bottom: 1px solid rgba(255,255,255,0.05);
  transition: all 0.3s ease;
  animation: slideIn 0.6s ease both;
}

.info-row:last-of-type { border-bottom: none; }
.info-row:nth-child(1) { animation-delay: 0.1s; }
.info-row:nth-child(2) { animation-delay: 0.2s; }
.info-row:nth-child(3) { animation-delay: 0.3s; }

@keyframes slideIn {
  from { opacity: 0; transform: translateX(-16px); }
  to   { opacity: 1; transform: translateX(0); }
}

.info-icon {
  width: 42px;
  height: 42px;
  border-radius: 12px;
  background: var(--gold-dim);
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  flex-shrink: 0;
  transition: all 0.3s ease;
}

.info-row:hover .info-icon {
  background: rgba(201,168,76,0.25);
  transform: rotate(5deg);
}

.info-text {
  flex: 1;
}

.info-label {
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: var(--muted);
  margin-bottom: 3px;
}

.info-value {
  font-size: 15px;
  font-weight: 500;
  color: var(--text);
}

.info-arrow {
  color: var(--muted);
  font-size: 18px;
  transition: transform 0.3s ease, color 0.3s ease;
}

a.info-row-link {
  text-decoration: none;
  color: inherit;
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 18px 0;
  border-bottom: 1px solid rgba(255,255,255,0.05);
  transition: all 0.3s ease;
  animation: slideIn 0.6s ease 0.3s both;
  border-radius: 8px;
  padding-inline: 8px;
  margin-inline: -8px;
}

a.info-row-link:hover {
  background: rgba(201,168,76,0.06);
}

a.info-row-link:hover .info-arrow {
  transform: translateX(4px);
  color: var(--gold);
}

a.info-row-link:hover .info-icon {
  background: rgba(201,168,76,0.25);
  transform: rotate(5deg);
}

/* ── DIVIDER ── */
.divider {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 0 16px;
}
.divider-line {
  flex: 1;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--border), transparent);
}
.divider-label {
  font-size: 11px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--muted);
}

/* ── ACTIONS ── */
.actions {
  display: grid;
  grid-template-columns: 1fr;
  gap: 12px;
  padding-top: 4px;
}

.btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 14px 24px;
  border-radius: 14px;
  border: none;
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  font-weight: 500;
  letter-spacing: 0.04em;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
}

.btn-logout {
  background: transparent;
  border: 1px solid rgba(224, 85, 85, 0.4);
  color: #f87171;
}
.btn-logout:hover {
  background: rgba(224,85,85,0.12);
  border-color: rgba(224,85,85,0.7);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(224,85,85,0.15);
}

/* ── BRAND STAMP ── */
.brand {
  text-align: center;
  margin-top: 28px;
  font-size: 11px;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: rgba(201,168,76,0.35);
}

.brand span {
  color: var(--gold);
}
</style>
</head>
<body>

<div class="profile-card">

  <!-- HERO -->
  <div class="hero">
    <div class="avatar-wrap">
      <div class="avatar-ring"></div>
      <div class="avatar"><?= strtoupper(substr($user['username'], 0, 1)) ?></div>
      <div class="status-dot"></div>
    </div>

    <div class="username"><?= htmlspecialchars($user['username']) ?></div>
    <div class="user-email"><?= htmlspecialchars($user['email']) ?></div>

    <?php if (isset($user['phone'])): ?>
      <div class="user-phone"><?= htmlspecialchars($user['phone']) ?></div>
    <?php endif; ?>

    <div class="badge">Online</div>
    <div class="hero-line"></div>
  </div>

  <!-- BODY -->
  <div class="body">

    <div class="info-row">
      <div class="info-icon">🕐</div>
      <div class="info-text">
        <div class="info-label">Last Login</div>
        <div class="info-value">Today</div>
      </div>
    </div>

    <div class="info-row">
      <div class="info-icon">✦</div>
      <div class="info-text">
        <div class="info-label">Account Status</div>
        <div class="info-value">Active</div>
      </div>
    </div>

    <a href="../frontend/orders.php" class="info-row-link">
      <div class="info-icon">📦</div>
      <div class="info-text">
        <div class="info-label">Order History</div>
        <div class="info-value">View your orders</div>
      </div>
      <div class="info-arrow">›</div>
    </a>

    <!-- Divider -->
    <div class="divider">
      <div class="divider-line"></div>
      <div class="divider-label">Account</div>
      <div class="divider-line"></div>
    </div>

    <!-- Actions -->
    <div class="actions">
      <a href="../database/logout.php" class="btn btn-logout">
        <span>⎋</span> Sign Out
      </a>
    </div>

  </div>

  <div class="brand">Shop<span>Ease</span> — Member Portal</div>
</div>


</body>
</html>