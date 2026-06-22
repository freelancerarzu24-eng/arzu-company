<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    die("Unauthorized");
}

$action = $_POST['action'] ?? '';

if ($action == 'update_status') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $admin_notes = $_POST['admin_notes'];

    $delivery_files = null;
    if (!empty($_FILES['delivery_files']['name'])) {
        $delivery_files = upload_image($_FILES['delivery_files'], '../uploads/');
    }

    if ($delivery_files) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ?, admin_notes = ?, delivery_files = ? WHERE id = ?");
        $result = $stmt->execute([$status, $admin_notes, $delivery_files, $order_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE orders SET status = ?, admin_notes = ? WHERE id = ?");
        $result = $stmt->execute([$status, $admin_notes, $order_id]);
    }

    if ($result) {
        echo "Order updated successfully!";
    } else {
        echo "Error updating order.";
    }
}
?>
