<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$page_title = "Order Management";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$orders = $pdo->query("SELECT o.*, s.title as service_title, u.full_name as customer_name FROM orders o JOIN services s ON o.service_id = s.id LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll();
$csrf_token = generate_csrf_token();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <h2>Manage Orders</h2>

    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
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
                            <td><?php echo e($order['order_number']); ?></td>
                            <td><?php echo e($order['customer_name'] ?: 'Guest/WhatsApp'); ?></td>
                            <td><?php echo e($order['service_title']); ?></td>
                            <td><?php echo format_currency($order['amount']); ?></td>
                            <td><span class="badge bg-<?php
                                echo $order['status'] == 'Pending' ? 'warning' : ($order['status'] == 'Completed' ? 'success' : 'info');
                            ?>"><?php echo e($order['status']); ?></span></td>
                            <td><?php echo date('d M, Y', strtotime($order['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary view-order" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo $order['id']; ?>">Manage</button>
                            </td>
                        </tr>

                        <!-- Order Detail Modal -->
                        <div class="modal fade" id="orderModal<?php echo $order['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <form class="updateOrderForm" enctype="multipart/form-data">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    <div class="modal-content text-dark">
                                        <div class="modal-header"><h5>Order #<?php echo e($order['order_number']); ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Service:</strong> <?php echo e($order['service_title']); ?> (<?php echo e($order['package_type']); ?>)</p>
                                                    <p><strong>Country:</strong> <?php echo e($order['country']); ?></p>
                                                    <p><strong>Requirements:</strong> <?php echo e($order['requirements']); ?></p>
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
                                                <textarea name="admin_notes" class="form-control"><?php echo e($order['admin_notes']); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label>Upload Delivery Files</label>
                                                <input type="file" name="delivery_files" class="form-control">
                                                <?php if($order['delivery_files']): ?>
                                                    <small>Current file: <a href="../uploads/<?php echo e($order['delivery_files']); ?>" target="_blank"><?php echo e($order['delivery_files']); ?></a></small>
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
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

<?php require_once 'includes/footer.php'; ?>
