<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$settings = $pdo->query("SELECT * FROM settings LIMIT 1")->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bkash = $_POST['bkash_number'];
    $nagad = $_POST['nagad_number'];
    $rocket = $_POST['rocket_number'];
    $bank = $_POST['bank_details'];
    $paypal = $_POST['paypal_link'];
    $wise = $_POST['wise_link'];
    $instructions = $_POST['payment_instructions'];

    $qr = $settings['payment_qr_code'];
    if (!empty($_FILES['payment_qr_code']['name'])) {
        $qr = upload_image($_FILES['payment_qr_code'], '../uploads/');
    }

    $stmt = $pdo->prepare("UPDATE settings SET bkash_number = ?, nagad_number = ?, rocket_number = ?, bank_details = ?, paypal_link = ?, wise_link = ?, payment_instructions = ?, payment_qr_code = ? WHERE id = ?");
    $stmt->execute([$bkash, $nagad, $rocket, $bank, $paypal, $wise, $instructions, $qr, $settings['id']]);

    header("Location: payment_settings.php?msg=Payment Settings Updated");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Settings | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2>Payment Settings</h2>
        <form method="POST" enctype="multipart/form-data" class="mt-4">
            <div class="card p-4 mb-4">
                <h5>Bangladesh Payment Methods</h5>
                <div class="row">
                    <div class="col-md-4 mb-3"><label>bKash Number</label><input type="text" name="bkash_number" class="form-control" value="<?php echo $settings['bkash_number']; ?>"></div>
                    <div class="col-md-4 mb-3"><label>Nagad Number</label><input type="text" name="nagad_number" class="form-control" value="<?php echo $settings['nagad_number']; ?>"></div>
                    <div class="col-md-4 mb-3"><label>Rocket Number</label><input type="text" name="rocket_number" class="form-control" value="<?php echo $settings['rocket_number']; ?>"></div>
                </div>
                <div class="mb-3"><label>Bank Details</label><textarea name="bank_details" class="form-control"><?php echo $settings['bank_details']; ?></textarea></div>
                <div class="mb-3"><label>Payment QR Code</label><input type="file" name="payment_qr_code" class="form-control"></div>
            </div>

            <div class="card p-4 mb-4">
                <h5>International Payment Methods</h5>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>PayPal Link</label><input type="text" name="paypal_link" class="form-control" value="<?php echo $settings['paypal_link']; ?>"></div>
                    <div class="col-md-6 mb-3"><label>Wise Link</label><input type="text" name="wise_link" class="form-control" value="<?php echo $settings['wise_link']; ?>"></div>
                </div>
            </div>

            <div class="card p-4 mb-4">
                <h5>Payment Instructions</h5>
                <div class="mb-3"><textarea name="payment_instructions" class="form-control" rows="5"><?php echo $settings['payment_instructions']; ?></textarea></div>
            </div>

            <button type="submit" class="btn btn-primary">Save Payment Settings</button>
        </form>
    </div>
</body>
</html>
