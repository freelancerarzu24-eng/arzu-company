<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_meeting'])) {
    $topic = trim($_POST['topic']);
    $date = $_POST['meeting_date'];
    $time = $_POST['meeting_time'];
    $platform = $_POST['platform'];

    if (empty($topic) || empty($date) || empty($time)) {
        $error = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO meetings (user_id, topic, meeting_date, meeting_time, platform, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
        if ($stmt->execute([$user_id, $topic, $date, $time, $platform])) {
            $success = "Meeting request submitted successfully!";
        } else {
            $error = "Error submitting request.";
        }
    }
}

$meetings = $pdo->prepare("SELECT * FROM meetings WHERE user_id = ? ORDER BY meeting_date DESC");
$meetings->execute([$user_id]);
$all_meetings = $meetings->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meeting Scheduling | Dmagancy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>My Meetings</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestMeetingModal">Request Meeting</button>
        </div>

        <?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
        <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Topic</th>
                            <th>Date & Time</th>
                            <th>Platform</th>
                            <th>Status</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($all_meetings as $m): ?>
                        <tr>
                            <td><?php echo e($m['topic']); ?></td>
                            <td><?php echo date('d M, Y', strtotime($m['meeting_date'])) . ' at ' . date('H:i', strtotime($m['meeting_time'])); ?></td>
                            <td><?php echo $m['platform']; ?></td>
                            <td><span class="badge bg-<?php echo $m['status'] == 'Accepted' ? 'success' : ($m['status'] == 'Rejected' ? 'danger' : 'info'); ?>"><?php echo $m['status']; ?></span></td>
                            <td>
                                <?php if($m['meeting_link']): ?>
                                    <a href="<?php echo $m['meeting_link']; ?>" target="_blank" class="btn btn-sm btn-link">Join Meeting</a>
                                <?php else: ?>
                                    <span class="text-muted small">Not provided yet</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Request Meeting Modal -->
    <div class="modal fade" id="requestMeetingModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST">
                <div class="modal-content text-dark">
                    <div class="modal-header"><h5>Request Meeting</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Meeting Topic</label>
                            <input type="text" name="topic" class="form-control" placeholder="What should we talk about?" required>
                        </div>
                        <div class="mb-3">
                            <label>Preferred Date</label>
                            <input type="date" name="meeting_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3">
                            <label>Preferred Time</label>
                            <input type="time" name="meeting_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Meeting Platform</label>
                            <select name="platform" class="form-control">
                                <option value="Google Meet">Google Meet</option>
                                <option value="Zoom">Zoom</option>
                                <option value="WhatsApp Call">WhatsApp Call</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="request_meeting" class="btn btn-primary">Submit Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
