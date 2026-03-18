<?php
session_start();
include "../database/dbconnection.php";

/* ── Session Protection ── */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = '';
$error_msg = '';

/* ── Fetch current user info safely ── */
$stmt = $conn->prepare("SELECT username, email FROM register_user WHERE id = ?");
if (!$stmt) {
    session_destroy();
    header("Location: login.php");
    exit();
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

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

/* ── Handle Form Submission ── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    /* ── Validation ── */
    if (empty($username)) {
        $error_msg = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $error_msg = "Username must be at least 3 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email address.";
    } elseif (!empty($phone) && !preg_match('/^[0-9\+\-\(\)\s]{7,}$/', $phone)) {
        $error_msg = "Invalid phone number format.";
    } else {
        /* ── Check if email already exists (excluding current user) ── */
        $check_stmt = $conn->prepare("SELECT id FROM register_user WHERE email = ? AND id != ?");
        $check_stmt->bind_param("si", $email, $user_id);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows > 0) {
            $error_msg = "Email already registered.";
        } else {
            /* ── Check if phone column exists before updating ── */
            $phone_col_exists = $conn->query("SHOW COLUMNS FROM register_user LIKE 'phone'")->num_rows > 0;
            
            if ($phone_col_exists) {
                /* ── Update with phone ── */
                $update_stmt = $conn->prepare("UPDATE register_user SET username = ?, email = ?, phone = ? WHERE id = ?");
                $update_stmt->bind_param("sssi", $username, $email, $phone, $user_id);
            } else {
                /* ── Update without phone ── */
                $update_stmt = $conn->prepare("UPDATE register_user SET username = ?, email = ? WHERE id = ?");
                $update_stmt->bind_param("ssi", $username, $email, $user_id);
            }
            
            if ($update_stmt->execute()) {
                $success_msg = "Profile updated successfully!";
                // Refresh user data
                $user['username'] = $username;
                $user['email'] = $email;
                if ($phone_col_exists) {
                    $user['phone'] = $phone;
                }
                // Update session with new username
                $_SESSION['username'] = $username;
            } else {
                $error_msg = "Error updating profile. Please try again.";
            }
        }
    }
}

$initial = strtoupper(substr($user['username'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile | ShopEase</title>
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
  padding: 60px 16px 60px;
}

body::before {
  content: '';
  position: fixed;
  top: -10%;
  left: 50%;
  transform: translateX(-50%);
  width: 800px;
  height: 600px;
  background: radial-gradient(ellipse at center, rgba(201,168,76,0.06) 0%, transparent 65%);
  pointer-events: none;
  z-index: 0;
}

.container {
  position: relative;
  width: 100%;
  max-width: 500px;
  margin: 0 auto;
  z-index: 1;
  animation: rise 0.85s cubic-bezier(0.22,1,0.36,1) both;
}

@keyframes rise {
  from { opacity: 0; transform: translateY(36px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Header ── */
.header {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 28px;
  padding-bottom: 20px;
  border-bottom: 1px solid var(--border);
}

.header-avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: linear-gradient(145deg, #1a1a28, #242436);
  border: 2px solid var(--gold);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  font-weight: 700;
  color: var(--gold);
  flex-shrink: 0;
}

.header-text h1 {
  font-family: 'Playfair Display', serif;
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 4px;
}

.header-text p {
  font-size: 13px;
  color: var(--muted);
}

/* ── Back Button ── */
.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  margin-bottom: 20px;
  border: 1px solid var(--border);
  background: transparent;
  color: var(--text-soft);
  border-radius: 8px;
  text-decoration: none;
  font-size: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-back:hover {
  border-color: var(--gold);
  color: var(--gold);
}

/* ── Alert Messages ── */
.alert {
  padding: 14px 16px;
  border-radius: 10px;
  margin-bottom: 18px;
  font-size: 13.5px;
  animation: slideDown 0.4s ease;
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-10px); }
  to   { opacity: 1; transform: translateY(0); }
}

.alert-success {
  background: rgba(34,197,94,0.1);
  border: 1px solid rgba(34,197,94,0.35);
  color: #4ade80;
}

.alert-error {
  background: rgba(239,68,68,0.1);
  border: 1px solid rgba(239,68,68,0.35);
  color: #fca5a5;
}

/* ── Form Card ── */
.card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 28px;
  animation: fadeIn 0.6s ease 0.1s both;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to   { opacity: 1; }
}

/* ── Form Group ── */
.form-group {
  margin-bottom: 18px;
  animation: slideIn 0.55s ease both;
}

.form-group:nth-child(1) { animation-delay: 0.1s; }
.form-group:nth-child(2) { animation-delay: 0.15s; }
.form-group:nth-child(3) { animation-delay: 0.2s; }

@keyframes slideIn {
  from { opacity: 0; transform: translateX(-12px); }
  to   { opacity: 1; transform: translateX(0); }
}

.form-group label {
  display: block;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--muted);
  margin-bottom: 8px;
}

.form-group input {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid var(--border);
  background: rgba(255,255,255,0.02);
  border-radius: 10px;
  color: var(--text);
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  transition: all 0.3s ease;
}

.form-group input:focus {
  outline: none;
  border-color: var(--gold);
  background: rgba(201,168,76,0.05);
  box-shadow: 0 0 20px rgba(201,168,76,0.1);
}

.form-group input::placeholder {
  color: var(--muted);
}

.form-hint {
  font-size: 11px;
  color: var(--muted);
  margin-top: 6px;
}

/* ── Buttons ── */
.form-actions {
  display: flex;
  gap: 12px;
  margin-top: 24px;
  animation: slideIn 0.55s ease 0.25s both;
}

.btn {
  flex: 1;
  padding: 12px 16px;
  border-radius: 10px;
  border: 1px solid var(--border);
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.btn-primary {
  background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.08));
  border-color: rgba(201,168,76,0.35);
  color: var(--gold);
}

.btn-primary:hover {
  background: linear-gradient(135deg, rgba(201,168,76,0.2), rgba(201,168,76,0.12));
  border-color: var(--gold);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(201,168,76,0.2);
}

.btn-secondary {
  background: transparent;
  border-color: var(--border);
  color: var(--text-soft);
}

.btn-secondary:hover {
  border-color: var(--text-soft);
  background: rgba(255,255,255,0.03);
}

/* ── Responsive ── */
@media (max-width: 520px) {
  body { padding: 40px 12px; }
  .card { padding: 20px; }
  .form-actions { flex-direction: column; }
  .header { gap: 12px; }
  .header-avatar { width: 48px; height: 48px; font-size: 18px; }
}
    </style>
</head>
<body>

<div class="container">
    <!-- Back Button -->
    <a href="profile.php" class="btn-back">← Back to Profile</a>

    <!-- Header -->
    <div class="header">
        <div class="header-avatar"><?= $initial ?></div>
        <div class="header-text">
            <h1>Edit Profile</h1>
            <p>Update your account information</p>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if ($success_msg): ?>
        <div class="alert alert-success">✓ <?= htmlspecialchars($success_msg) ?></div>
    <?php endif; ?>
    
    <?php if ($error_msg): ?>
        <div class="alert alert-error">✗ <?= htmlspecialchars($error_msg) ?></div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="card">
        <form method="POST" action="">
            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="<?= htmlspecialchars($user['username']) ?>" 
                    required 
                    minlength="3"
                    placeholder="Enter your username"
                >
                <div class="form-hint">Minimum 3 characters</div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?= htmlspecialchars($user['email']) ?>" 
                    required 
                    placeholder="Enter your email"
                >
                <div class="form-hint">Must be a valid email address</div>
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                    placeholder="Enter your phone number (optional)"
                >
                <div class="form-hint">Optional - Include country code if needed</div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="profile.php" class="btn btn-secondary">↶ GO BACK </a>
                <button type="submit" class="btn btn-primary">✓ Save Changes</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
