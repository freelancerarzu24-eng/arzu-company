<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$meetings = $pdo->query("SELECT m.*, u.full_name FROM meetings m JOIN users u ON m.user_id = u.id ORDER BY m.created_at DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_meeting'])) {
    $id = $_POST['meeting_id'];
    $status = $_POST['status'];
    $link = $_POST['meeting_link'];
    $notes = $_POST['admin_notes'];
    $date = $_POST['meeting_date'];
    $time = $_POST['meeting_time'];

    $stmt = $pdo->prepare("UPDATE meetings SET status = ?, meeting_link = ?, admin_notes = ?, meeting_date = ?, meeting_time = ? WHERE id = ?");
    $stmt->execute([$status, $link, $notes, $date, $time, $id]);
    header("Location: meetings.php?msg=Updated");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meeting Management | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Meeting Requests</h2>
            <a href="available_slots.php" class="btn btn-outline-primary">Manage Slots</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Topic</th>
                            <th>Date & Time</th>
                            <th>Platform</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($meetings as $m): ?>
                        <tr>
                            <td><?php echo e($m['full_name']); ?></td>
                            <td><?php echo e($m['topic']); ?></td>
                            <td><?php echo $m['meeting_date'] . ' ' . $m['meeting_time']; ?></td>
                            <td><?php echo $m['platform']; ?></td>
                            <td><span class="badge bg-info"><?php echo $m['status']; ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#mModal<?php echo $m['id']; ?>">Manage</button>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="mModal<?php echo $m['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST">
                                    <div class="modal-content">
                                        <div class="modal-header"><h5>Meeting: <?php echo e($m['topic']); ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="Pending" <?php echo $m['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="Accepted" <?php echo $m['status'] == 'Accepted' ? 'selected' : ''; ?>>Accepted</option>
                                                    <option value="Rejected" <?php echo $m['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                    <option value="Rescheduled" <?php echo $m['status'] == 'Rescheduled' ? 'selected' : ''; ?>>Rescheduled</option>
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Date</label>
                                                    <input type="date" name="meeting_date" class="form-control" value="<?php echo $m['meeting_date']; ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Time</label>
                                                    <input type="time" name="meeting_time" class="form-control" value="<?php echo $m['meeting_time']; ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label>Meeting Link</label>
                                                <input type="text" name="meeting_link" class="form-control" value="<?php echo $m['meeting_link']; ?>" placeholder="https://zoom.us/j/...">
                                            </div>
                                            <div class="mb-3">
                                                <label>Admin Notes</label>
                                                <textarea name="admin_notes" class="form-control" rows="3"><?php echo e($m['admin_notes']); ?></textarea>
                                            </div>
                                            <input type="hidden" name="meeting_id" value="<?php echo $m['id']; ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="update_meeting" class="btn btn-primary">Save Changes</button>
                                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
