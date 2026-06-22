<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_slot'])) {
    $day = $_POST['slot_day'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $stmt = $pdo->prepare("INSERT INTO meeting_slots (slot_day, start_time, end_time) VALUES (?, ?, ?)");
    $stmt->execute([$day, $start, $end]);
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM meeting_slots WHERE id = ?")->execute([$_GET['delete']]);
}

$slots = $pdo->query("SELECT * FROM meeting_slots ORDER BY FIELD(slot_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Slots | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2>Manage Available Time Slots</h2>

        <div class="card p-4 mb-4 shadow-sm">
            <form method="POST" class="row g-3 align-items-end">
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
            <table class="table">
                <thead><tr><th>Day</th><th>Start</th><th>End</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach($slots as $s): ?>
                    <tr>
                        <td><?php echo $s['slot_day']; ?></td>
                        <td><?php echo $s['start_time']; ?></td>
                        <td><?php echo $s['end_time']; ?></td>
                        <td><a href="?delete=<?php echo $s['id']; ?>" class="btn btn-sm btn-danger">Delete</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
