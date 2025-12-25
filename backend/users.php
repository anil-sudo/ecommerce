<?php
session_start();
include 'session_check.php';
include '../database/dbconnection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Users | Admin Panel</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family: 'Arial', sans-serif; }
    body { background: #f4f6f8; color:#111827; transition: background 0.3s, color 0.3s; }
    body.dark-mode { background: #111827; color: #f4f6f8; }
    body.dark-mode .sidebar { background: #111827; }
    body.dark-mode table, body.dark-mode .form-section { background: #1f2937; color:#f4f6f8; }
    body.dark-mode th { background:#2563eb; color:#fff; }
    body.dark-mode tr:nth-child(even) { background:#1a1f2b; }
    body.dark-mode tr:hover { background:#2563eb33; }
    body.dark-mode input { background:#1f2937; color:#f4f6f8; border:1px solid #444; }

    .main { margin-left:250px; padding:20px; }
    .main h1 { margin-bottom:20px; color:#111827; }

    table { width:100%; border-collapse: collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 4px 6px rgba(0,0,0,0.1); }
    th, td { padding:15px; text-align:left; vertical-align: middle; }
    th { background:#3b82f6; color:#fff; }
    tr:nth-child(even) { background:#f9fafb; }
    tr:hover { background:#e0f2fe; }

    .btn { padding:8px 12px; border:none; border-radius:5px; cursor:pointer; color:#fff; transition:0.3s; }
    .btn-edit { background:#3b82f6; }
    .btn-edit:hover { background:#2563eb; }
    .btn-delete { background:#ef4444; }
    .btn-delete:hover { background:#b91c1c; }

    @media(max-width:768px){
        .sidebar { width:100%; height:auto; position:relative; }
        .main { margin-left:0; }
    }
</style>
</head>
<body>

<?php include 'aside.php'; ?>

<div class="main">
    <h1>Users</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM users ORDER BY id ASC";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['role']}</td>
                            <td>{$row['status']}</td>

                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>No users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
