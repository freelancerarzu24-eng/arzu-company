<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$page_title = "Dashboard";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Fetch Statistics
$total_customers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pending_orders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'")->fetchColumn();
$completed_orders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Completed'")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(amount) FROM orders WHERE status = 'Completed'")->fetchColumn() ?? 0;
$total_reviews = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
$total_meetings = $pdo->query("SELECT COUNT(*) FROM meetings")->fetchColumn();

// Fetch Latest Orders
$latest_orders = $pdo->query("SELECT o.*, s.title as service_title FROM orders o JOIN services s ON o.service_id = s.id ORDER BY o.created_at DESC LIMIT 5")->fetchAll();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard Overview</h1>
    </div>

    <!-- Stats Row -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-primary text-white p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Total Customers</h6>
                        <h3><?php echo $total_customers; ?></h3>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-success text-white p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Total Revenue</h6>
                        <h3><?php echo format_currency($total_revenue); ?></h3>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-warning text-white p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Pending Orders</h6>
                        <h3><?php echo $pending_orders; ?></h3>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-info text-white p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Total Orders</h6>
                        <h3><?php echo $total_orders; ?></h3>
                    </div>
                    <i class="fas fa-shopping-basket fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="card stat-card mt-4">
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
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($latest_orders as $order): ?>
                        <tr>
                            <td><?php echo $order['order_number']; ?></td>
                            <td><?php echo $order['service_title']; ?></td>
                            <td><?php echo format_currency($order['amount']); ?></td>
                            <td>
                                <span class="badge bg-<?php
                                    echo $order['status'] == 'Pending' ? 'warning' : ($order['status'] == 'Completed' ? 'success' : 'info');
                                ?>"><?php echo $order['status']; ?></span>
                            </td>
                            <td><?php echo date('d M, Y', strtotime($order['created_at'])); ?></td>
                            <td><a href="orders.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
