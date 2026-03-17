<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "e-commerce");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch registered users
$sql = "SELECT username, email, created_at 
        FROM register_user 
        ORDER BY created_at DESC";
$result = $conn->query($sql);
if(!$result) die("Query Failed: " . $conn->error);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - Users</title>
    
    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        *{ margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', sans-serif; }
         body{ min-height:100vh; background:#f4f7fc; overflow-x:hidden; }


        /* Collapse Button */
        .collapse-btn {
            position:absolute; bottom:20px; left:50%; transform:translateX(-50%);
            background:#2563eb; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer;
            transition:0.3s;
        }
        .collapse-btn:hover { background:#3b82f6; }

        /* ---------------- Main Content ---------------- */
        .main-content{
            margin-left:250px; 
            padding:30px;
            flex:1;
            transition: margin-left 0.3s;
        }
        .sidebar.collapsed ~ .main-content { margin-left:70px; }

        .main-content h2{ margin-bottom:20px; color:#111827; }

        .card{
            background:white;
            padding:20px;
            border-radius:12px;
            box-shadow:0 4px 15px rgba(0,0,0,0.1);
            width:100%;
            overflow-x:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
            min-width:600px;
        }

        th{
            background:#3b82f6;
            color:white;
            padding:12px;
        }

        td{
            padding:12px;
            text-align:center;
            border-bottom:1px solid #eee;
        }

        tr:hover{
            background:#f1f5f9;
            transition:0.3s;
        }

        .badge{
            background:#10b981;
            color:white;
            padding:5px 10px;
            border-radius:20px;
            font-size:12px;
        }


    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include 'aside.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Registered Users</h2>
        <div class="card">
            <table>
                <tr>
                    <th>S.N</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Joined On</th>
                    <th>Status</th>
                </tr>

                <?php 
                $sn = 1;
                while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                        <td><span class="badge">Active</span></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>


    

</body>
</html>