<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Handle Create Ticket
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_ticket'])) {
    $subject = trim($_POST['subject']);
    $priority = $_POST['priority'];
    $message = trim($_POST['message']);

    if (empty($subject) || empty($message)) {
        $error = "Subject and Message are required.";
    } else {
        $attachment = null;
        if (!empty($_FILES['attachment']['name'])) {
            $attachment = upload_image($_FILES['attachment'], '../uploads/');
        }

        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO support_tickets (user_id, subject, priority, status, attachment) VALUES (?, ?, ?, 'Open', ?)");
            $stmt->execute([$user_id, $subject, $priority, $attachment]);
            $ticket_id = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO ticket_replies (ticket_id, sender_type, message) VALUES (?, 'user', ?)");
            $stmt->execute([$ticket_id, $message]);

            $pdo->commit();
            $success = "Ticket created successfully!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Error: " . $e->getMessage();
        }
    }
}

$tickets = $pdo->prepare("SELECT * FROM support_tickets WHERE user_id = ? ORDER BY created_at DESC");
$tickets->execute([$user_id]);
$all_tickets = $tickets->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Tickets | Dmagancy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Dmagancy</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Dashboard</a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Support Tickets</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTicketModal">Create New Ticket</button>
        </div>

        <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($all_tickets as $t): ?>
                            <tr>
                                <td>#<?php echo $t['id']; ?></td>
                                <td><?php echo e($t['subject']); ?></td>
                                <td><span class="badge bg-<?php echo $t['priority'] == 'High' ? 'danger' : ($t['priority'] == 'Medium' ? 'warning' : 'info'); ?>"><?php echo $t['priority']; ?></span></td>
                                <td><span class="badge bg-<?php echo $t['status'] == 'Open' ? 'success' : ($t['status'] == 'Pending' ? 'info' : 'secondary'); ?>"><?php echo $t['status']; ?></span></td>
                                <td><?php echo date('d M, Y', strtotime($t['created_at'])); ?></td>
                                <td><a href="view_ticket.php?id=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- New Ticket Modal -->
    <div class="modal fade" id="newTicketModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header"><h5>Create New Ticket</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Priority</label>
                            <select name="priority" class="form-control">
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Message</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Attachment (Optional)</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="create_ticket" class="btn btn-primary">Submit Ticket</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
