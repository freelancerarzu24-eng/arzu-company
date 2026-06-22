<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (is_admin_logged_in()) {
    header("Location: index.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Dmagancy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background-color: #343a40; }
        .login-card { max-width: 400px; margin-top: 100px; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="card login-card p-4">
                <h3 class="text-center mb-4">Admin Login</h3>
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-dark">Username or Email</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
