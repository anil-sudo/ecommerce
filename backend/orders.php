<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Orders | Admin Panel</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family: 'Arial', sans-serif; }
    body { background: #f4f6f8; color:#111827; transition: background 0.3s, color 0.3s; }

    /* Dark mode */
    body.dark-mode { background: #111827; color: #f4f6f8; }
    body.dark-mode .sidebar { background: #111827; }
    body.dark-mode table, body.dark-mode .filter-section { background: #1f2937; color:#f4f6f8; }
    body.dark-mode th { background:#2563eb; color:#fff; }
    body.dark-mode tr:nth-child(even) { background:#1a1f2b; }
    body.dark-mode tr:hover { background:#2563eb33; }
    body.dark-mode input, body.dark-mode select { background:#1f2937; color:#f4f6f8; border:1px solid #444; }

    /* Sidebar */
    .sidebar {
        width: 250px; height: 100vh; background: #1f2937; color: #fff;
        position: fixed; top: 0; left: 0; display: flex; flex-direction: column;
    }
    .sidebar h2 { text-align:center; margin:20px 0; font-size:1.5em; }
    .sidebar a {
        color:#fff; padding:15px 20px; text-decoration:none; display:block;
        transition:0.3s; border-left:4px solid transparent;
    }
    .sidebar a:hover { background:#111827; border-left:4px solid #3b82f6; }

    /* Main Content */
    .main { margin-left:250px; padding:20px; }
    h1 { margin-bottom:20px; }

    /* Top controls */
    .top-controls { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px; }
    .top-controls input, .top-controls select {
        padding:8px 12px; border-radius:5px; border:1px solid #ccc; transition: background 0.3s, color 0.3s, border 0.3s;
    }
    .btn { padding:8px 12px; border:none; border-radius:5px; cursor:pointer; color:#fff; transition:0.3s; }
    .btn-view { background:#3b82f6; }
    .btn-view:hover { background:#2563eb; }
    .btn-edit { background:#10b981; }
    .btn-edit:hover { background:#059669; }
    .btn-cancel { background:#ef4444; }
    .btn-cancel:hover { background:#b91c1c; }

    /* Orders Table */
    table { width:100%; border-collapse: collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 4px 6px rgba(0,0,0,0.1); }
    th, td { padding:12px; text-align:left; vertical-align: middle; }
    th { background:#3b82f6; color:#fff; }
    tr:nth-child(even) { background:#f9fafb; }
    tr:hover { background:#e0f2fe; }

    @media(max-width:768px){
        .sidebar { width:100%; height:auto; position:relative; }
        .main { margin-left:0; }
        .top-controls { flex-direction:column; gap:10px; }
    }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="#">Dashboard</a>
    <a href="#">Products</a>
    <a href="#">Orders</a>
    <a href="#">Users</a>
    <a href="#">Settings</a>
</div>

<div class="main">
    <h1>Orders</h1>

    <!-- Filters & Search -->
    <div class="top-controls">
        <input type="text" placeholder="Search orders...">
        <select>
            <option value="">Filter by Status</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="shipped">Shipped</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Products</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#101</td>
                <td>John Doe</td>
                <td>Smart Watch</td>
                <td>Completed</td>
                <td>$99.99</td>
                <td>
                    <button class="btn btn-view">View</button>
                    <button class="btn btn-edit">Edit</button>
                    <button class="btn btn-cancel">Cancel</button>
                </td>
            </tr>
            <tr>
                <td>#102</td>
                <td>Jane Smith</td>
                <td>Laptop Bag</td>
                <td>Pending</td>
                <td>$39.99</td>
                <td>
                    <button class="btn btn-view">View</button>
                    <button class="btn btn-edit">Edit</button>
                    <button class="btn btn-cancel">Cancel</button>
                </td>
            </tr>
            <tr>
                <td>#103</td>
                <td>Bob Johnson</td>
                <td>Bluetooth Headphones</td>
                <td>Shipped</td>
                <td>$59.99</td>
                <td>
                    <button class="btn btn-view">View</button>
                    <button class="btn btn-edit">Edit</button>
                    <button class="btn btn-cancel">Cancel</button>
                </td>
            </tr>
            <tr>
                <td>#104</td>
                <td>Alice Brown</td>
                <td>Gaming Mouse</td>
                <td>Cancelled</td>
                <td>$49.99</td>
                <td>
                    <button class="btn btn-view">View</button>
                    <button class="btn btn-edit">Edit</button>
                    <button class="btn btn-cancel">Cancel</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>
