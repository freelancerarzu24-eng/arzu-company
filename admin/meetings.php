<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$page_title = "Meeting Management";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$meetings = $pdo->query("SELECT m.*, u.full_name as customer_name FROM meetings m JOIN users u ON m.user_id = u.id ORDER BY created_at DESC")->fetchAll();
$csrf_token = generate_csrf_token();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_meeting'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }
    $id = $_POST['meeting_id'];
    $status = $_POST['status'];
    $link = $_POST['meeting_link'];
    $notes = $_POST['admin_notes'];

    $stmt = $pdo->prepare("UPDATE meetings SET status = ?, meeting_link = ?, admin_notes = ? WHERE id = ?");
    $stmt->execute([$status, $link, $notes, $id]);
    redirect('meetings.php', 'Meeting updated');
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Meetings</h2>
        <a href="available_slots.php" class="btn btn-primary">Manage Slots</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Topic</th>
                            <th>Date/Time</th>
                            <th>Platform</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($meetings as $m): ?>
                        <tr>
                            <td><?php echo e($m['customer_name']); ?></td>
                            <td><?php echo e($m['topic']); ?></td>
                            <td><?php echo $m['meeting_date'] . ' ' . $m['meeting_time']; ?></td>
                            <td><?php echo $m['platform']; ?></td>
                            <td><span class="badge bg-<?php echo $m['status'] == 'Accepted' ? 'success' : ($m['status'] == 'Rejected' ? 'danger' : 'warning'); ?>"><?php echo $m['status']; ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#meetingModal<?php echo $m['id']; ?>">Manage</button>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="meetingModal<?php echo $m['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    <input type="hidden" name="meeting_id" value="<?php echo $m['id']; ?>">
                                    <div class="modal-content">
                                        <div class="modal-header"><h5>Manage Meeting</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body text-dark">
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="Pending" <?php echo $m['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="Accepted" <?php echo $m['status'] == 'Accepted' ? 'selected' : ''; ?>>Accepted</option>
                                                    <option value="Rejected" <?php echo $m['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                    <option value="Rescheduled" <?php echo $m['status'] == 'Rescheduled' ? 'selected' : ''; ?>>Rescheduled</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Meeting Link (Zoom/Meet)</label>
                                                <input type="url" name="meeting_link" class="form-control" value="<?php echo e($m['meeting_link']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label>Admin Notes</label>
                                                <textarea name="admin_notes" class="form-control"><?php echo e($m['admin_notes']); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="submit" name="update_meeting" class="btn btn-primary">Update</button></div>
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

<?php require_once 'includes/footer.php'; ?>
