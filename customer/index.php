<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$user_id]);
$user_data = $user->fetch();

// Stats
$order_count = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$order_count->execute([$user_id]);
$total_orders = $order_count->fetchColumn();

$pending_orders = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = 'Pending'");
$pending_orders->execute([$user_id]);
$total_pending = $pending_orders->fetchColumn();

$latest_orders = $pdo->prepare("SELECT o.*, s.title as service_title FROM orders o JOIN services s ON o.service_id = s.id WHERE o.user_id = ? ORDER BY o.created_at DESC LIMIT 5");
$latest_orders->execute([$user_id]);
$orders = $latest_orders->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard | Dmagancy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="../index.php">Dmagancy</a>
        <div class="ms-auto text-white">Welcome, <?php echo e($user_data['full_name']); ?></div>
    </div>
</nav>

<div class="container my-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="index.php" class="list-group-item list-group-item-action active">Dashboard</a>
                        <a href="orders.php" class="list-group-item list-group-item-action">My Orders</a>
                        <a href="profile.php" class="list-group-item list-group-item-action">Profile</a>
                        <a href="tickets.php" class="list-group-item list-group-item-action">Support Tickets</a>
                        <a href="meetings.php" class="list-group-item list-group-item-action">Meetings</a>
                        <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white p-3 shadow-sm border-0">
                        <h6>Total Orders</h6>
                        <h3><?php echo $total_orders; ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-dark p-3 shadow-sm border-0">
                        <h6>Pending Orders</h6>
                        <h3><?php echo $total_pending; ?></h3>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['order_number']; ?></td>
                                    <td><?php echo $order['service_title']; ?></td>
                                    <td><span class="badge bg-<?php echo $order['status'] == 'Pending' ? 'warning' : ($order['status'] == 'Completed' ? 'success' : 'info'); ?>"><?php echo $order['status']; ?></span></td>
                                    <td><?php echo date('d M, Y', strtotime($order['created_at'])); ?></td>
                                    <td><a href="orders.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
