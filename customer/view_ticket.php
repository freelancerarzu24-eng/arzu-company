<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$ticket_id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM support_tickets WHERE id = ? AND user_id = ?");
$stmt->execute([$ticket_id, $user_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header("Location: tickets.php");
    exit();
}

// Handle Reply
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_reply'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO ticket_replies (ticket_id, sender_type, message) VALUES (?, 'user', ?)");
        $stmt->execute([$ticket_id, $message]);

        // Set status to Open if it was Pending
        if ($ticket['status'] == 'Pending') {
            $pdo->prepare("UPDATE support_tickets SET status = 'Open' WHERE id = ?")->execute([$ticket_id]);
        }
        header("Location: view_ticket.php?id=" . $ticket_id);
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
    <title>View Ticket #<?php echo $ticket_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <a href="tickets.php" class="btn btn-sm btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Back to Tickets</a>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ticket #<?php echo $ticket_id; ?>: <?php echo e($ticket['subject']); ?></h5>
                <span class="badge bg-info"><?php echo $ticket['status']; ?></span>
            </div>
            <div class="card-body">
                <p><strong>Priority:</strong> <?php echo $ticket['priority']; ?></p>
                <?php if($ticket['attachment']): ?>
                    <p><strong>Attachment:</strong> <a href="../uploads/<?php echo $ticket['attachment']; ?>" target="_blank">View File</a></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="replies mb-4">
            <?php foreach($all_replies as $reply): ?>
                <div class="card mb-3 <?php echo $reply['sender_type'] == 'admin' ? 'bg-light ms-5' : 'me-5'; ?>">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <strong><?php echo $reply['sender_type'] == 'admin' ? 'Support Agent' : 'You'; ?></strong>
                            <small class="text-muted"><?php echo date('d M, Y H:i', strtotime($reply['created_at'])); ?></small>
                        </div>
                        <p class="mb-0"><?php echo nl2br(e($reply['message'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if($ticket['status'] != 'Closed'): ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Post a Reply</h6>
                <form method="POST">
                    <div class="mb-3">
                        <textarea name="message" class="form-control" rows="4" required placeholder="Type your message here..."></textarea>
                    </div>
                    <button type="submit" name="send_reply" class="btn btn-primary">Send Reply</button>
                </form>
            </div>
        </div>
        <?php else: ?>
            <div class="alert alert-secondary text-center">This ticket is closed. If you need more help, please open a new ticket.</div>
        <?php endif; ?>
    </div>
</body>
</html>
