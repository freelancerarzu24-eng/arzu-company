<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    die("Unauthorized");
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    die("CSRF token validation failed");
}

$action = $_POST['action'] ?? '';

if ($action == 'update_status') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $notes = $_POST['admin_notes'];

    $delivery_file = null;
    if (!empty($_FILES['delivery_files']['name'])) {
        $delivery_file = upload_image($_FILES['delivery_files'], '../uploads/');
    }

    if ($delivery_file) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ?, admin_notes = ?, delivery_files = ? WHERE id = ?");
        $stmt->execute([$status, $notes, $delivery_file, $order_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE orders SET status = ?, admin_notes = ? WHERE id = ?");
        $stmt->execute([$status, $notes, $order_id]);
    }
    echo "Order updated successfully!";
}
?>
