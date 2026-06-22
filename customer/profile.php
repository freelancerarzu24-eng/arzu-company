<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?");
    if ($stmt->execute([$full_name, $phone, $address, $user_id])) {
        redirect('profile.php', 'Profile updated successfully.');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | Dmagancy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4 shadow-sm">
                    <h4>Edit Profile</h4>
                    <form method="POST">
                        <div class="mb-3"><label>Full Name</label><input type="text" name="full_name" class="form-control" value="<?php echo e($user['full_name']); ?>"></div>
                        <div class="mb-3"><label>Email</label><input type="email" class="form-control" value="<?php echo e($user['email']); ?>" disabled></div>
                        <div class="mb-3"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?php echo e($user['phone']); ?>"></div>
                        <div class="mb-3"><label>Address</label><textarea name="address" class="form-control"><?php echo e($user['address']); ?></textarea></div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
