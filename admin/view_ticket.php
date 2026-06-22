<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$ticket_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT t.*, u.full_name, u.email as user_email FROM support_tickets t JOIN users u ON t.user_id = u.id WHERE t.id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header("Location: tickets.php");
    exit();
}

// Handle Admin Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['send_reply'])) {
        $message = trim($_POST['message']);
        if (!empty($message)) {
            $stmt = $pdo->prepare("INSERT INTO ticket_replies (ticket_id, sender_type, message) VALUES (?, 'admin', ?)");
            $stmt->execute([$ticket_id, $message]);

            // Auto update status to Pending when admin replies
            $pdo->prepare("UPDATE support_tickets SET status = 'Pending' WHERE id = ?")->execute([$ticket_id]);

            header("Location: view_ticket.php?id=" . $ticket_id . "&msg=Reply Sent");
            exit();
        }
    }

    if (isset($_POST['update_ticket'])) {
        $status = $_POST['status'];
        $notes = $_POST['internal_notes'];
        $pdo->prepare("UPDATE support_tickets SET status = ?, internal_notes = ? WHERE id = ?")->execute([$status, $notes, $ticket_id]);
        header("Location: view_ticket.php?id=" . $ticket_id . "&msg=Ticket Updated");
        exit();
    }
}

$replies = $pdo->prepare("SELECT * FROM ticket_replies WHERE ticket_id = ? ORDER BY created_at ASC");
$replies->execute([$ticket_id]);
$all_replies = $replies->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Ticket #<?php echo $ticket_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <a href="tickets.php" class="btn btn-sm btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Back to List</a>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Ticket #<?php echo $ticket_id; ?>: <?php echo e($ticket['subject']); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Customer:</strong> <?php echo e($ticket['full_name']); ?> (<?php echo e($ticket['user_email']); ?>)</p>
                                <p><strong>Priority:</strong> <span class="badge bg-info"><?php echo $ticket['priority']; ?></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> <span class="badge bg-warning"><?php echo $ticket['status']; ?></span></p>
                                <?php if($ticket['attachment']): ?>
                                    <p><strong>Attachment:</strong> <a href="../uploads/<?php echo $ticket['attachment']; ?>" target="_blank">View File</a></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="replies mb-4">
                    <?php foreach($all_replies as $reply): ?>
                        <div class="card mb-3 <?php echo $reply['sender_type'] == 'user' ? 'bg-light me-5' : 'bg-primary text-white ms-5'; ?>">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong><?php echo $reply['sender_type'] == 'user' ? e($ticket['full_name']) : 'Admin Support'; ?></strong>
                                    <small><?php echo date('d M, Y H:i', strtotime($reply['created_at'])); ?></small>
                                </div>
                                <p class="mb-0"><?php echo nl2br(e($reply['message'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6>Reply to Customer</h6>
                        <form method="POST">
                            <div class="mb-3">
                                <textarea name="message" class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" name="send_reply" class="btn btn-primary">Send Reply</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white"><h5>Admin Control</h5></div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Open" <?php echo $ticket['status'] == 'Open' ? 'selected' : ''; ?>>Open</option>
                                    <option value="Pending" <?php echo $ticket['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Closed" <?php echo $ticket['status'] == 'Closed' ? 'selected' : ''; ?>>Closed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Internal Notes</label>
                                <textarea name="internal_notes" class="form-control" rows="5"><?php echo e($ticket['internal_notes']); ?></textarea>
                            </div>
                            <button type="submit" name="update_ticket" class="btn btn-dark w-100">Update Ticket</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
