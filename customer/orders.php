<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$orders = $pdo->prepare("SELECT o.*, s.title as service_title FROM orders o JOIN services s ON o.service_id = s.id WHERE o.user_id = ? ORDER BY o.created_at DESC");
$orders->execute([$user_id]);
$all_orders = $orders->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | Dmagancy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h3 class="mb-4">My Orders</h3>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr><th>Order #</th><th>Service</th><th>Amount</th><th>Status</th><th>Delivery</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($all_orders as $order): ?>
                        <tr>
                            <td><?php echo $order['order_number']; ?></td>
                            <td><?php echo $order['service_title']; ?></td>
                            <td><?php echo format_currency($order['amount']); ?></td>
                            <td><span class="badge bg-info"><?php echo $order['status']; ?></span></td>
                            <td>
                                <?php if($order['delivery_files']): ?>
                                    <a href="../uploads/<?php echo $order['delivery_files']; ?>" class="btn btn-sm btn-success" download>Download Files</a>
                                <?php else: ?>
                                    <span class="text-muted">Not yet delivered</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
