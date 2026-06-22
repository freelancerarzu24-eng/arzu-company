<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

$query = "SELECT t.*, u.full_name FROM support_tickets t JOIN users u ON t.user_id = u.id WHERE 1=1";
$params = [];

if ($status_filter) {
    $query .= " AND t.status = ?";
    $params[] = $status_filter;
}

if ($search) {
    $query .= " AND (t.subject LIKE ? OR u.full_name LIKE ? OR t.id LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query .= " ORDER BY t.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tickets | Admin</title>
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
                    <li class="nav-item"><a class="nav-link text-primary" href="tickets.php">Tickets</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="meetings.php">Meetings</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
                </ul>
            </nav>

            <main class="col-md-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Support Tickets</h2>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by ID, Subject, Customer" value="<?php echo e($search); ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="Open" <?php echo $status_filter == 'Open' ? 'selected' : ''; ?>>Open</option>
                                    <option value="Pending" <?php echo $status_filter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Closed" <?php echo $status_filter == 'Closed' ? 'selected' : ''; ?>>Closed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($tickets as $t): ?>
                                <tr>
                                    <td>#<?php echo $t['id']; ?></td>
                                    <td><?php echo e($t['full_name']); ?></td>
                                    <td><?php echo e($t['subject']); ?></td>
                                    <td><span class="badge bg-<?php echo $t['priority'] == 'High' ? 'danger' : ($t['priority'] == 'Medium' ? 'warning' : 'info'); ?>"><?php echo $t['priority']; ?></span></td>
                                    <td><span class="badge bg-<?php echo $t['status'] == 'Open' ? 'success' : ($t['status'] == 'Pending' ? 'info' : 'secondary'); ?>"><?php echo $t['status']; ?></span></td>
                                    <td><?php echo date('d M, Y', strtotime($t['created_at'])); ?></td>
                                    <td><a href="view_ticket.php?id=<?php echo $t['id']; ?>" class="btn btn-sm btn-primary">Manage</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
