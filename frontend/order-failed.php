<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="margin:0; font-family:Arial, sans-serif;">

    <?php include '../includes/header.php'; ?>

    <div style="
        height:100vh;
        display:flex;
        justify-content:center;
        align-items:center;
        background:#fdecea;
    ">
        <div style="
            padding:40px 60px;
            background:#ffffff;
            border-left:6px solid #e74c3c;
            border-radius:10px;
            box-shadow:0 4px 15px rgba(0,0,0,0.08);
            text-align:center;
        ">
            <h2 style="color:#e74c3c; margin-bottom:15px; font-size:28px;">
                ✖ Payment Failed!
            </h2>
            <p style="color:#2c3e50; margin:0; font-size:16px;">
                Unfortunately, your payment could not be processed. Please try again.
            </p>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>