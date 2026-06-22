<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Dmagancy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .sidebar { min-height: 100vh; background: #212529; color: #fff; }
        .sidebar .nav-link { color: #adb5bd; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #343a40; }
        .stat-card { border-radius: 10px; border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0">
            <div class="position-sticky pt-3">
                <h5 class="text-center py-3">Dmagancy Admin</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="categories.php"><i class="fas fa-list me-2"></i> Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="services.php"><i class="fas fa-concierge-bell me-2"></i> Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="portfolio.php"><i class="fas fa-briefcase me-2"></i> Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link" href="blog.php"><i class="fas fa-blog me-2"></i> Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-shopping-cart me-2"></i> Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="meetings.php"><i class="fas fa-video me-2"></i> Meetings</a></li>
                    <li class="nav-item"><a class="nav-link" href="tickets.php"><i class="fas fa-headset me-2"></i> Support Tickets</a></li>
                    <li class="nav-item"><a class="nav-link" href="reviews.php"><i class="fas fa-star me-2"></i> Reviews</a></li>
                    <hr>
                    <li class="nav-item"><a class="nav-link" href="settings.php"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="payment_settings.php"><i class="fas fa-credit-card me-2"></i> Payments</a></li>
                    <li class="nav-item"><a class="nav-link" href="seo.php"><i class="fas fa-search me-2"></i> SEO</a></li>
                    <li class="nav-item mt-5"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
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

            <!-- More Stats -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-light-primary p-3 rounded-circle me-3">
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Reviews</h6>
                                <strong><?php echo $total_reviews; ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-light-primary p-3 rounded-circle me-3">
                                <i class="fas fa-video text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Meetings</h6>
                                <strong><?php echo $total_meetings; ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-light-primary p-3 rounded-circle me-3">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Completed Orders</h6>
                                <strong><?php echo $completed_orders; ?></strong>
                            </div>
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
