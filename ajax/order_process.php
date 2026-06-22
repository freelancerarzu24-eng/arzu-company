<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $service_id = $_POST['service_id'];
    $package_type = $_POST['package_type'];
    $amount = $_POST['amount'];
    $country = $_POST['country'];
    $requirements = $_POST['requirements'];
    $payment_method = $_POST['payment_method'];
    $transaction_id = $_POST['transaction_id'];

    $order_number = 'ORD-' . strtoupper(bin2hex(random_bytes(4)));

    try {
        $pdo->beginTransaction();

        // 1. Create Order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, service_id, order_number, package_type, amount, country, requirements, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $service_id, $order_number, $package_type, $amount, $country, $requirements, 'Pending']);
        $order_id = $pdo->lastInsertId();

        // 2. Handle Payment
        $screenshot = '';
        if (!empty($_FILES['payment_screenshot']['name'])) {
            $screenshot = upload_image($_FILES['payment_screenshot'], '../uploads/payments/');
        }

        $stmt = $pdo->prepare("INSERT INTO payments (order_id, payment_method, transaction_id, payment_screenshot, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $payment_method, $transaction_id, $screenshot, 'Pending']);

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Order placed successfully! Please wait for admin verification.']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error processing order: ' . $e->getMessage()]);
    }
}
?>
