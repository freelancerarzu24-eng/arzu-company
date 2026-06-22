<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$orders = $pdo->query("SELECT o.*, s.title as service_title, u.full_name as customer_name FROM orders o JOIN services s ON o.service_id = s.id LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Management | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-md-block bg-dark sidebar min-vh-100 p-3">
                <a href="index.php" class="text-white text-decoration-none"><h5>Admin Panel</h5></a>
                <ul class="nav flex-column mt-4">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="categories.php">Categories</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="services.php">Services</a></li>
                    <li class="nav-item"><a class="nav-link text-primary" href="orders.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
                </ul>
            </nav>

            <main class="col-md-10 px-md-4 py-4">
                <h2>Manage Orders</h2>

                <div class="card mt-4">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['order_number']; ?></td>
                                    <td><?php echo $order['customer_name'] ?: 'Guest/WhatsApp'; ?></td>
                                    <td><?php echo $order['service_title']; ?></td>
                                    <td><?php echo format_currency($order['amount']); ?></td>
                                    <td><span class="badge bg-<?php
                                        echo $order['status'] == 'Pending' ? 'warning' : ($order['status'] == 'Completed' ? 'success' : 'info');
                                    ?>"><?php echo $order['status']; ?></span></td>
                                    <td><?php echo date('d M, Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary view-order" data-id="<?php echo $order['id']; ?>" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo $order['id']; ?>">Manage</button>
                                    </td>
                                </tr>

                                <!-- Order Detail Modal -->
                                <div class="modal fade" id="orderModal<?php echo $order['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <form class="updateOrderForm" enctype="multipart/form-data">
                                            <div class="modal-content text-dark">
                                                <div class="modal-header"><h5>Order #<?php echo $order['order_number']; ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Service:</strong> <?php echo $order['service_title']; ?> (<?php echo $order['package_type']; ?>)</p>
                                                            <p><strong>Country:</strong> <?php echo $order['country']; ?></p>
                                                            <p><strong>Requirements:</strong> <?php echo $order['requirements']; ?></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label>Update Status</label>
                                                                <select name="status" class="form-control">
                                                                    <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                                    <option value="Processing" <?php echo $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                                                    <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                                    <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Admin Notes</label>
                                                        <textarea name="admin_notes" class="form-control"><?php echo $order['admin_notes']; ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Upload Delivery Files</label>
                                                        <input type="file" name="delivery_files" class="form-control">
                                                        <?php if($order['delivery_files']): ?>
                                                            <small>Current file: <a href="../uploads/<?php echo $order['delivery_files']; ?>" target="_blank"><?php echo $order['delivery_files']; ?></a></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                </div>
                                                <div class="modal-footer"><button type="submit" class="btn btn-primary">Update Order</button></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.updateOrderForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('action', 'update_status');
                $.ajax({
                    url: '../ajax/admin_orders.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response);
                        location.reload();
                    }
                });
            });
        });
    </script>
</body>
</html>
