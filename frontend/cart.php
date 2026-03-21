<?php
include '../includes/header.php'; 

include '../database/dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$register_user_id = $_SESSION['user_id'];
$totalAmount      = 0;
$cartItems        = [];
$cart_id          = null;

// ── Get user cart ──────────────────────────────────────────────────────────────
$cartStmt = $conn->prepare("SELECT id FROM carts WHERE register_user_id = ?");
$cartStmt->bind_param("i", $register_user_id);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();

if ($cartResult->num_rows > 0) {
    $cart    = $cartResult->fetch_assoc();
    $cart_id = $cart['id'];
}

// ── CSRF Protection ────────────────────────────────────────────────────────────
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ── Handle quantity update ─────────────────────────────────────────────────────
if (isset($_POST['update']) && $cart_id) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF Token Verification Failed.");
    }
    $item_id  = intval($_POST['item_id']);
    $quantity = max(1, intval($_POST['quantity']));

    $updateStmt = $conn->prepare("
        UPDATE cart_items SET quantity = ?
        WHERE id = ? AND cart_id = ?
    ");
    $updateStmt->bind_param("iii", $quantity, $item_id, $cart_id);
    $updateStmt->execute();

    header("Location: cart.php");
    exit;
}

// ── Handle remove ──────────────────────────────────────────────────────────────
if (isset($_POST['remove']) && $cart_id) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF Token Verification Failed.");
    }
    $item_id = intval($_POST['item_id']);

    $deleteStmt = $conn->prepare("
        DELETE FROM cart_items
        WHERE id = ? AND cart_id = ?
    ");
    $deleteStmt->bind_param("ii", $item_id, $cart_id);
    $deleteStmt->execute();

    header("Location: cart.php");
    exit;
}

// ── Fetch cart items ───────────────────────────────────────────────────────────
if ($cart_id) {
    $itemsStmt = $conn->prepare("
        SELECT ci.id, ci.quantity, ci.price,
               p.name, p.image
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.cart_id = ?
    ");
    $itemsStmt->bind_param("i", $cart_id);
    $itemsStmt->execute();
    $itemsResult = $itemsStmt->get_result();

    while ($row = $itemsResult->fetch_assoc()) {
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $totalAmount    += $row['subtotal'];
        $cartItems[]     = $row;
    }
}

// ── Generate UUID + eSewa signature ───────────────────────────────────────────
function generateUUIDv4() {
    $data    = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$uuid      = generateUUIDv4();
$message   = "total_amount=" . $totalAmount . ",transaction_uuid=" . $uuid . ",product_code=EPAYTEST";
$signature = hash_hmac('sha256', $message, '8gBm/:&EnhH.1/q', true);

$taxAmount    = round(($totalAmount * 13) / 100, 2);
$amountNoTax  = round($totalAmount - $taxAmount, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | ShopEase</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* ── Cart Layout ── */
        .cart-section {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .cart-section h2 {
            font-size: 26px;
            font-weight: 700;
            color: #111;
            margin-bottom: 24px;
        }

        .empty-cart {
            text-align: center;
            padding: 80px 20px;
            color: #888;
        }

        .empty-cart p {
            font-size: 18px;
            margin-bottom: 16px;
        }

        .empty-cart a {
            display: inline-block;
            padding: 12px 28px;
            background: #4f46e5;
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        /* ── Table ── */
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .cart-table thead {
            background: #f9fafb;
        }

        .cart-table th {
            padding: 14px 18px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            border-bottom: 1px solid #eee;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .cart-table td {
            padding: 16px 18px;
            border-bottom: 1px solid #f5f5f5;
            font-size: 14px;
            color: #333;
            vertical-align: middle;
        }

        .cart-table tbody tr:last-child td {
            border-bottom: none;
        }

        .cart-table tbody tr:hover {
            background: #fafafa;
        }

        /* ── Product cell ── */
        .product-cell {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .product-cell img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
            flex-shrink: 0;
        }

        .product-name {
            font-weight: 500;
            color: #111;
        }

        /* ── Quantity form ── */
        .qty-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .qty-form input[type="number"] {
            width: 64px;
            padding: 8px 10px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            outline: none;
            transition: border-color .2s;
        }

        .qty-form input[type="number"]:focus {
            border-color: #4f46e5;
        }

        .btn-update {
            padding: 8px 14px;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: background .2s;
        }

        .btn-update:hover { background: #4338ca; }

        /* ── Remove button ── */
        .btn-remove {
            padding: 8px 14px;
            background: transparent;
            color: #e53e3e;
            border: 1.5px solid #e53e3e;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-remove:hover {
            background: #e53e3e;
            color: #fff;
        }

        /* ── Cart Footer ── */
        .cart-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 20px;
            margin-top: 24px;
            padding: 20px 24px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .cart-total {
            font-size: 18px;
            font-weight: 700;
            color: #111;
        }

        .cart-total span {
            color: #4f46e5;
        }

        .btn-checkout {
            padding: 13px 28px;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .2s;
        }

        .btn-checkout:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }

        /* ── Modal ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0,0,0,0.55);
            justify-content: center;
            align-items: center;
            padding: 16px;
        }

        .modal-box {
            background: #fff;
            border-radius: 16px;
            padding: 32px 28px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.2);
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-close {
            position: absolute;
            top: 14px;
            right: 18px;
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #aaa;
            line-height: 1;
            transition: color .2s;
        }

        .modal-close:hover { color: #333; }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #111;
            margin-bottom: 4px;
        }

        .modal-subtitle {
            font-size: 13px;
            color: #999;
            margin-bottom: 24px;
        }

        /* ── Form fields ── */
        .field-group {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .field label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #555;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .field label .req { color: #e53e3e; }

        .field input,
        .field select {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color .2s;
            box-sizing: border-box;
            background: #fff;
        }

        .field input:focus,
        .field select:focus {
            border-color: #4f46e5;
        }

        .field input.readonly {
            background: #f7f7f7;
            color: #999;
            cursor: not-allowed;
            border-color: #ececec;
        }

        .field .field-error {
            display: none;
            color: #e53e3e;
            font-size: 12px;
            margin-top: 4px;
        }

        /* ── Global error banner ── */
        .global-error {
            display: none;
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 8px;
            padding: 10px 14px;
            color: #c53030;
            font-size: 13px;
        }

        /* ── Payment buttons ── */
        .payment-btns {
            display: flex;
            gap: 12px;
            margin-top: 4px;
        }

        .payment-btns form { flex: 1; }

        .btn-esewa {
            width: 100%;
            padding: 13px;
            background: #377948;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }

        .btn-esewa:hover { background: #2d6139; }

        .btn-cod {
            width: 100%;
            padding: 13px;
            background: #f5f5f5;
            color: #333;
            border: 1.5px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }

        .btn-cod:hover { background: #ebebeb; }
    </style>
</head>
<body>


<section class="cart-section">
    <h2>Your Shopping Cart</h2>

    <?php if (empty($cartItems)): ?>

        <div class="empty-cart">
            <p>Your cart is empty.</p>
            <a href="index.php">Continue Shopping</a>
        </div>

    <?php else: ?>

        <!-- ── Cart Table ── -->
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                <tr>
                    <!-- Product -->
                    <td>
                        <div class="product-cell">
                            <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>"
                                 alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <span class="product-name"><?php echo htmlspecialchars($item['name']); ?></span>
                        </div>
                    </td>

                    <!-- Price -->
                    <td>Rs. <?php echo number_format($item['price'], 2); ?></td>

                    <!-- Quantity -->
                    <td>
                        <form method="POST" class="qty-form">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity"
                                   value="<?php echo $item['quantity']; ?>"
                                   min="1" max="99">
                            <button type="submit" name="update" class="btn-update">Update</button>
                        </form>
                    </td>

                    <!-- Subtotal -->
                    <td><strong>Rs. <?php echo number_format($item['subtotal'], 2); ?></strong></td>

                    <td>
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="remove" class="btn-remove">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- ── Cart Footer ── -->
        <div class="cart-footer">
            <div class="cart-total">
                Total: <span>Rs. <?php echo number_format($totalAmount, 2); ?></span>
            </div>
            <button class="btn-checkout"
                    onclick="document.getElementById('addressModal').style.display='flex'">
                Proceed to Checkout
            </button>
        </div>

        <!-- ══════════════════════════════════════════════
             ADDRESS MODAL
        ══════════════════════════════════════════════ -->
        <div id="addressModal" class="modal-overlay">
            <div class="modal-box">

                <button class="modal-close" onclick="closeModal()">✕</button>

                <p class="modal-title">Delivery Address</p>
                <p class="modal-subtitle">Where should we deliver your order?</p>

                <div class="field-group">

                    <!-- Street -->
                    <div class="field">
                        <label>Street / Tole <span class="req">*</span></label>
                        <input type="text" id="f_street"
                               placeholder="e.g. New Baneshwor, Tole 4"
                               oninput="clearError('f_street')">
                        <p class="field-error" id="err_street">⚠ Street is required.</p>
                    </div>

                    <!-- City + Province -->
                    <div class="field-row">
                        <div class="field">
                            <label>City <span class="req">*</span></label>
                            <input type="text" id="f_city"
                                   placeholder="e.g. Kathmandu"
                                   oninput="clearError('f_city')">
                            <p class="field-error" id="err_city">⚠ City is required.</p>
                        </div>
                        <div class="field">
                            <label>Province</label>
                            <select id="f_state">
                                <option value="">Select</option>
                                <option>Koshi</option>
                                <option>Madhesh</option>
                                <option selected>Bagmati</option>
                                <option>Gandaki</option>
                                <option>Lumbini</option>
                                <option>Karnali</option>
                                <option>Sudurpashchim</option>
                            </select>
                        </div>
                    </div>

                    <!-- ZIP + Country -->
                    <div class="field-row">
                        <div class="field">
                            <label>ZIP Code</label>
                            <input type="text" id="f_zip"
                                   placeholder="e.g. 44600"
                                   maxlength="10"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'');
                                            clearError('f_zip')">
                            <p class="field-error" id="err_zip">⚠ Enter a valid ZIP (numbers only).</p>
                        </div>
                        <div class="field">
                            <label>Country</label>
                            <input type="text" id="f_country"
                                   value="Nepal" readonly class="readonly">
                        </div>
                    </div>

                    <!-- Global error -->
                    <div class="global-error" id="addr-error-global">
                        ⚠ Please fill in all required fields correctly before proceeding.
                    </div>

                    <!-- Payment buttons -->
                    <div class="payment-btns">

                        <!-- eSewa form -->
                        <form id="esewaForm"
                              action="https://rc-epay.esewa.com.np/api/epay/main/v2/form"
                              method="POST">
                            <input type="hidden" name="amount"                  value="<?php echo $amountNoTax; ?>">
                            <input type="hidden" name="tax_amount"              value="<?php echo $taxAmount; ?>">
                            <input type="hidden" name="total_amount"            value="<?php echo $totalAmount; ?>">
                            <input type="hidden" name="transaction_uuid"        value="<?php echo $uuid; ?>">
                            <input type="hidden" name="product_code"            value="EPAYTEST">
                            <input type="hidden" name="product_service_charge"  value="0">
                            <input type="hidden" name="product_delivery_charge" value="0">
                            <input type="hidden" name="success_url"             value="http://localhost/e-commerce/frontend/order-callback.php">
                            <input type="hidden" name="failure_url"             value="http://localhost/e-commerce/frontend/order-failed.php">
                            <input type="hidden" name="signed_field_names"      value="total_amount,transaction_uuid,product_code">
                            <input type="hidden" name="signature"               value="<?php echo base64_encode($signature); ?>">
                            <button type="submit"
                                    onclick="return validateAndFill('esewa')"
                                    class="btn-esewa">
                                Pay with eSewa
                            </button>
                        </form>

                        <!-- COD form -->
                        <form id="codForm"
                              action="http://localhost/e-commerce/frontend/order-callback.php"
                              method="POST">
                            <input type="hidden" name="payment"  value="cod">
                            <input type="hidden" name="street"   id="cod_street">
                            <input type="hidden" name="city"     id="cod_city">
                            <input type="hidden" name="state"    id="cod_state">
                            <input type="hidden" name="zip_code" id="cod_zip">
                            <input type="hidden" name="country"  id="cod_country">
                            <button type="submit"
                                    onclick="return validateAndFill('cod')"
                                    class="btn-cod">
                                Cash on Delivery
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>

<script>
function closeModal() {
    document.getElementById('addressModal').style.display = 'none';
}

// Close on outside click
document.getElementById('addressModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

function clearError(fieldId) {
    const errMap = {
        'f_street' : 'err_street',
        'f_city'   : 'err_city',
        'f_zip'    : 'err_zip'
    };
    const errEl = document.getElementById(errMap[fieldId]);
    if (errEl) errEl.style.display = 'none';
    document.getElementById('addr-error-global').style.display = 'none';
    const field = document.getElementById(fieldId);
    if (field) field.style.borderColor = '#e0e0e0';
}

function setFieldError(fieldId, errId, message = null) {
    const field = document.getElementById(fieldId);
    const err   = document.getElementById(errId);
    field.style.borderColor = '#e53e3e';
    if (message) err.textContent = '⚠ ' + message;
    err.style.display = 'block';
    field.focus();
}

function runValidation() {
    const street = document.getElementById('f_street').value.trim();
    const city   = document.getElementById('f_city').value.trim();
    const zip    = document.getElementById('f_zip').value.trim();

    // Reset all
    ['err_street','err_city','err_zip'].forEach(id => {
        document.getElementById(id).style.display = 'none';
    });
    ['f_street','f_city','f_zip'].forEach(id => {
        document.getElementById(id).style.borderColor = '#e0e0e0';
    });
    document.getElementById('addr-error-global').style.display = 'none';

    let isValid = true;

    if (street === '') {
        setFieldError('f_street', 'err_street');
        isValid = false;
    }

    if (city === '') {
        setFieldError('f_city', 'err_city');
        isValid = false;
    } else if (!/^[a-zA-Z\s\-]+$/.test(city)) {
        setFieldError('f_city', 'err_city', 'City should contain letters only.');
        isValid = false;
    }

    if (zip !== '' && !/^\d{4,10}$/.test(zip)) {
        setFieldError('f_zip', 'err_zip');
        isValid = false;
    }

    if (!isValid) {
        document.getElementById('addr-error-global').style.display = 'block';
    }

    return isValid;
}

function validateAndFill(type) {
    if (!runValidation()) return false;

    const street  = document.getElementById('f_street').value.trim();
    const city    = document.getElementById('f_city').value.trim();
    const state   = document.getElementById('f_state').value.trim();
    const zip     = document.getElementById('f_zip').value.trim();
    const country = document.getElementById('f_country').value.trim();

    if (type === 'cod') {
        document.getElementById('cod_street').value  = street;
        document.getElementById('cod_city').value    = city;
        document.getElementById('cod_state').value   = state;
        document.getElementById('cod_zip').value     = zip;
        document.getElementById('cod_country').value = country;
        return true;
    }

    if (type === 'esewa') {
        // Save address to session via AJAX, then submit eSewa form
        fetch('save-address.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                street   : street,
                city     : city,
                state    : state,
                zip_code : zip,
                country  : country
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('esewaForm').submit();
            }
        })
        .catch(() => {
            // Fallback — submit anyway
            document.getElementById('esewaForm').submit();
        });

        return false; // prevent default — AJAX handles submit
    }
}
</script>

</body>
</html>