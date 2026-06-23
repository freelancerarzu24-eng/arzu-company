<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$page_title = "Support Tickets";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$tickets = $pdo->query("SELECT t.*, u.full_name as customer_name FROM support_tickets t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <h2>Support Tickets</h2>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Subject</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tickets as $t): ?>
                        <tr>
                            <td>#<?php echo $t['id']; ?></td>
                            <td><?php echo e($t['customer_name']); ?></td>
                            <td><?php echo e($t['subject']); ?></td>
                            <td><span class="badge bg-<?php echo $t['priority'] == 'High' ? 'danger' : ($t['priority'] == 'Medium' ? 'warning' : 'info'); ?>"><?php echo $t['priority']; ?></span></td>
                            <td><span class="badge bg-<?php echo $t['status'] == 'Open' ? 'primary' : ($t['status'] == 'Closed' ? 'secondary' : 'warning'); ?>"><?php echo $t['status']; ?></span></td>
                            <td><?php echo date('d M, Y', strtotime($t['created_at'])); ?></td>
                            <td><a href="view_ticket.php?id=<?php echo $t['id']; ?>" class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
