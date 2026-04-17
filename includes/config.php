<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'shoes_shop');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', '/ECOMM/');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');
define('RESEND_API_KEY', 're_9JZeLHZT_Js6VjxJLNDu6FQvtYWHuwWXF');
define('RESEND_FROM_EMAIL', 'onboarding@resend.dev');

require_once __DIR__ . '/image-helper.php';

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect($url)
{
    header("Location: " . BASE_URL . $url);
    exit();
}

function sanitize($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

function showMessage($message, $type = 'success')
{
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

function displayMessage()
{
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $_SESSION['message_type'] ?? 'success';
        echo "<div class='alert alert-{$type}'>{$message}</div>";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}

function formatPrice($price)
{
    return '₹' . number_format($price, 2);
}

function getAppBaseUrl()
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host . rtrim(BASE_URL, '/') . '/';
}

function sendResendEmail($to, $subject, $html, $attachments = [])
{
    if (empty(RESEND_API_KEY) || !function_exists('curl_init') || empty($to)) {
        return false;
    }

    $payload = [
        'from' => RESEND_FROM_EMAIL,
        'to' => [$to],
        'subject' => $subject,
        'html' => $html
    ];

    if (!empty($attachments)) {
        $payload['attachments'] = $attachments;
    }

    $ch = curl_init('https://api.resend.com/emails');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . RESEND_API_KEY,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $response !== false && $httpCode >= 200 && $httpCode < 300;
}

function buildOrderInvoiceHtml($customer, $order, $items)
{
    $rows = '';
    $index = 1;

    foreach ($items as $item) {
        $rows .= '<tr>'
            . '<td style="padding:8px;border:1px solid #ddd;">' . $index++ . '</td>'
            . '<td style="padding:8px;border:1px solid #ddd;">' . htmlspecialchars($item['name']) . '</td>'
            . '<td style="padding:8px;border:1px solid #ddd;text-align:center;">' . (int)$item['quantity'] . '</td>'
            . '<td style="padding:8px;border:1px solid #ddd;text-align:right;">' . formatPrice($item['price']) . '</td>'
            . '<td style="padding:8px;border:1px solid #ddd;text-align:right;">' . formatPrice($item['subtotal']) . '</td>'
            . '</tr>';
    }

    return '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Invoice #' . (int)$order['id'] . '</title></head><body style="font-family:Arial,sans-serif;color:#222;">'
        . '<h2 style="margin-bottom:8px;">The Shoe Vault - Invoice</h2>'
        . '<p style="margin:4px 0;"><strong>Order ID:</strong> #' . (int)$order['id'] . '</p>'
        . '<p style="margin:4px 0;"><strong>Date:</strong> ' . date('d M Y, h:i A', strtotime($order['created_at'])) . '</p>'
        . '<p style="margin:4px 0;"><strong>Customer:</strong> ' . htmlspecialchars($customer['name']) . '</p>'
        . '<p style="margin:4px 0;"><strong>Email:</strong> ' . htmlspecialchars($customer['email']) . '</p>'
        . '<p style="margin:4px 0;"><strong>Phone:</strong> ' . htmlspecialchars($order['phone']) . '</p>'
        . '<p style="margin:4px 0;"><strong>Shipping Address:</strong> ' . nl2br(htmlspecialchars($order['shipping_address'])) . '</p>'
        . '<p style="margin:4px 0;"><strong>Payment:</strong> ' . htmlspecialchars($order['payment_method']) . '</p>'
        . '<p style="margin:4px 0;"><strong>Transaction ID:</strong> ' . htmlspecialchars($order['transaction_id']) . '</p>'
        . '<table style="width:100%;border-collapse:collapse;margin-top:16px;">'
        . '<thead><tr>'
        . '<th style="padding:8px;border:1px solid #ddd;background:#f6f6f6;">#</th>'
        . '<th style="padding:8px;border:1px solid #ddd;background:#f6f6f6;text-align:left;">Product</th>'
        . '<th style="padding:8px;border:1px solid #ddd;background:#f6f6f6;">Qty</th>'
        . '<th style="padding:8px;border:1px solid #ddd;background:#f6f6f6;text-align:right;">Price</th>'
        . '<th style="padding:8px;border:1px solid #ddd;background:#f6f6f6;text-align:right;">Subtotal</th>'
        . '</tr></thead><tbody>' . $rows . '</tbody></table>'
        . '<h3 style="text-align:right;margin-top:14px;">Total: ' . formatPrice($order['total_amount']) . '</h3>'
        . '</body></html>';
}

function sendOrderConfirmationEmail($customer, $order, $items)
{
    if (empty($customer['email'])) {
        return false;
    }

    $invoiceHtml = buildOrderInvoiceHtml($customer, $order, $items);
    $invoiceUrl = getAppBaseUrl() . 'invoice.php?order_id=' . (int)$order['id'];

    $itemsHtml = '';
    foreach ($items as $item) {
        $itemsHtml .= '<tr>'
            . '<td style="padding:8px;border:1px solid #eee;">' . htmlspecialchars($item['name']) . '</td>'
            . '<td style="padding:8px;border:1px solid #eee;text-align:center;">' . (int)$item['quantity'] . '</td>'
            . '<td style="padding:8px;border:1px solid #eee;text-align:right;">' . formatPrice($item['price']) . '</td>'
            . '<td style="padding:8px;border:1px solid #eee;text-align:right;">' . formatPrice($item['subtotal']) . '</td>'
            . '</tr>';
    }

    $emailHtml = '<div style="font-family:Arial,sans-serif;color:#2c3e50;line-height:1.6;">'
        . '<h2 style="margin-bottom:8px;">Order Confirmation - The Shoe Vault</h2>'
        . '<p>Hello ' . htmlspecialchars($customer['name']) . ',</p>'
        . '<p>Your order has been placed successfully. Here are your order details:</p>'
        . '<p><strong>Order ID:</strong> #' . (int)$order['id'] . '<br>'
        . '<strong>Date:</strong> ' . date('d M Y, h:i A', strtotime($order['created_at'])) . '<br>'
        . '<strong>Status:</strong> ' . ucfirst(htmlspecialchars($order['status'])) . '<br>'
        . '<strong>Payment Method:</strong> ' . htmlspecialchars($order['payment_method']) . '<br>'
        . '<strong>Transaction ID:</strong> ' . htmlspecialchars($order['transaction_id']) . '<br>'
        . '<strong>Phone:</strong> ' . htmlspecialchars($order['phone']) . '</p>'
        . '<p><strong>Shipping Address:</strong><br>' . nl2br(htmlspecialchars($order['shipping_address'])) . '</p>'
        . '<table style="width:100%;border-collapse:collapse;margin-top:10px;">'
        . '<thead><tr>'
        . '<th style="padding:8px;border:1px solid #eee;background:#f8f9fa;text-align:left;">Product</th>'
        . '<th style="padding:8px;border:1px solid #eee;background:#f8f9fa;">Qty</th>'
        . '<th style="padding:8px;border:1px solid #eee;background:#f8f9fa;text-align:right;">Price</th>'
        . '<th style="padding:8px;border:1px solid #eee;background:#f8f9fa;text-align:right;">Subtotal</th>'
        . '</tr></thead><tbody>' . $itemsHtml . '</tbody></table>'
        . '<h3 style="text-align:right;margin-top:12px;">Total: ' . formatPrice($order['total_amount']) . '</h3>'
        . '<p style="margin-top:14px;">Invoice attached with this email. You can also view it online: '
        . '<a href="' . htmlspecialchars($invoiceUrl) . '">' . htmlspecialchars($invoiceUrl) . '</a></p>'
        . '<p>Thank you for shopping with us.</p>'
        . '</div>';

    $attachments = [[
        'filename' => 'invoice-order-' . (int)$order['id'] . '.html',
        'content' => base64_encode($invoiceHtml)
    ]];

    return sendResendEmail(
        $customer['email'],
        'Order Confirmation #' . (int)$order['id'] . ' - The Shoe Vault',
        $emailHtml,
        $attachments
    );
}
