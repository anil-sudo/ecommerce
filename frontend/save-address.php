<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['delivery_street']   = trim($_POST['street']   ?? '');
    $_SESSION['delivery_city']     = trim($_POST['city']     ?? '');
    $_SESSION['delivery_state']    = trim($_POST['state']    ?? '');
    $_SESSION['delivery_zip_code'] = trim($_POST['zip_code'] ?? '');
    $_SESSION['delivery_country']  = trim($_POST['country']  ?? 'Nepal');

    echo json_encode(['success' => true]);
}
?>