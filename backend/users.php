<?php
session_start();
include 'session_check.php';
include '../database/dbconnection.php';

$current_admin_id = $_SESSION['user_id'];

/* =========================
   HANDLE ADD ADMIN
========================= */
if (isset($_POST['add_admin'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // PASSWORD VALIDATION
    if(strlen($password) < 6){
        $add_error = "Password must be at least 6 characters.";
    } else {
        // Check duplicate email
        $check = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $add_error = "Email already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO admins (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password, $role);
            $stmt->execute();
            $add_success = "Admin added successfully.";
        }
    }
}

/* =========================
   HANDLE DELETE
========================= */
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];

    if ($delete_id == $current_admin_id) {
        $delete_error = "You cannot delete your own account.";
    } else {
        $stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $delete_success = "Admin deleted successfully.";
    }
}

/* =========================
   HANDLE UPDATE
========================= */
if (isset($_POST['update_admin'])) {
    $id = (int)$_POST['id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    $stmt = $conn->prepare("UPDATE admins SET username=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);
    $stmt->execute();
    $update_success = "Admin updated successfully.";
}

/* =========================
   FETCH ALL ADMINS
========================= */
$result = $conn->query("SELECT * FROM admins ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users | Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }


 

        body {
            background: #f4f6f8;
            color: #111827;
            transition: 0.3s;
        }

  

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #3b82f6;
            color: #fff;
        }

        tr:nth-child(even) {
            background: #f9fafb;
        }

        tr:hover {
            background: #e0f2fe;
        }



        /* Layout */
        .main {
            margin-left: 250px;
            padding: 20px;
        }

        .main h1 {
            margin-bottom: 20px;
            color: #111827;
        }

        /* Forms */
        input, select {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            transition: 0.3s;
        }

        .btn-add { background: #22c55e; }
        .btn-add:hover { background: #16a34a; }
        .btn-edit { background: #3b82f6; }
        .btn-edit:hover { background: #2563eb; }
        .btn-delete { background: #ef4444; }
        .btn-delete:hover { background: #b91c1c; }

        form.inline-form {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        form.inline-form input,
        form.inline-form select {
            margin: 0;
        }

        form.inline-form button {
            margin: 0;
        }

        /* Flash messages */
        .flash-message {
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: 500;
        }

        .flash-message.success {
            background-color: #22c55e;
            color: #fff;
        }

        .flash-message.error {
            background-color: #ef4444;
            color: #fff;
        }
    </style>
</head>

<body>

<?php include 'aside.php'; ?>

<div class="main">
<h1>Admin Users</h1>

<!-- Flash Messages -->
<?php
if(isset($add_error)) echo "<div class='flash-message error'>$add_error</div>";
if(isset($add_success)) echo "<div class='flash-message success'>$add_success</div>";

if(isset($delete_error)) echo "<div class='flash-message error' style='background-color:red;'>$delete_error</div>";if(isset($delete_success)) echo "<div class='flash-message success'>$delete_success</div>";

if(isset($update_success)) echo "<div class='flash-message success'>$update_success</div>";
?>

<!-- ADD ADMIN -->
<h2>Add New Admin</h2>
<form method="POST" class="inline-form" style="margin-bottom:20px;">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="password" placeholder="Password" required>
    <select name="role" required>
        <option value="admin">Admin</option>
        <option value="editor">Editor</option>
    </select>
    <button type="submit" name="add_admin" class="btn-add">Add</button>
</form>

<!-- ADMINS TABLE -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <form method="POST" class="inline-form">
                    <td data-label="ID"><?= $row['id'] ?></td>
                    <td data-label="Username">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="text" name="username" value="<?= htmlspecialchars($row['username']) ?>">
                    </td>
                    <td data-label="Email">
                        <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>">
                    </td>
                    <td data-label="Role">
                        <select name="role">
                            <option <?= $row['role']=='admin'?'selected':'' ?>>admin</option>
                            <option <?= $row['role']=='editor'?'selected':'' ?>>editor</option>
                            <option <?= $row['role']=='manager'?'selected':'' ?>>manager</option>
                        </select>
                    </td>
                    <td data-label="Actions">
                        <button type="submit" name="update_admin" class="btn-edit">Update</button>
                        <?php if($row['id'] != $current_admin_id): ?>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this admin?')">
                            <button type="button" class="btn-delete">Delete</button>
                        </a>
                        <?php endif; ?>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No admins found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</div>
</body>
</html>