<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_slot'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }
    $day = $_POST['slot_day'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $stmt = $pdo->prepare("INSERT INTO meeting_slots (slot_day, start_time, end_time) VALUES (?, ?, ?)");
    $stmt->execute([$day, $start, $end]);
    redirect('available_slots.php', 'Slot added successfully');
}

if (isset($_POST['delete_slot'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }
    $pdo->prepare("DELETE FROM meeting_slots WHERE id = ?")->execute([$_POST['id']]);
    redirect('available_slots.php', 'Slot deleted successfully');
}

$page_title = "Available Slots";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$slots = $pdo->query("SELECT * FROM meeting_slots ORDER BY FIELD(slot_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time")->fetchAll();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Available Time Slots</h2>
        <a href="meetings.php" class="btn btn-outline-secondary">Back to Meetings</a>
    </div>

    <div class="card p-4 mb-4 shadow-sm">
        <form method="POST" class="row g-3 align-items-end">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <div class="col-md-3">
                <label>Day</label>
                <select name="slot_day" class="form-control">
                    <option>Monday</option><option>Tuesday</option><option>Wednesday</option><option>Thursday</option><option>Friday</option><option>Saturday</option><option>Sunday</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Start Time</label>
                <input type="time" name="start_time" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>End Time</label>
                <input type="time" name="end_time" class="form-control" required>
            </div>
            <div class="col-md-3">
                <button type="submit" name="add_slot" class="btn btn-primary w-100">Add Slot</button>
            </div>
        </form>
    </div>

    <div class="card p-3 shadow-sm">
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Day</th><th>Start</th><th>End</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach($slots as $s): ?>
                    <tr>
                        <td><?php echo e($s['slot_day']); ?></td>
                        <td><?php echo e($s['start_time']); ?></td>
                        <td><?php echo e($s['end_time']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                <button type="submit" name="delete_slot" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
