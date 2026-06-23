<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$page_title = "Payment Settings";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$settings = $pdo->query("SELECT * FROM settings LIMIT 1")->fetch();
$csrf_token = generate_csrf_token();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }
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

    redirect('payment_settings.php', 'Payment Settings Updated');
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <h2>Payment Settings</h2>
    <form method="POST" enctype="multipart/form-data" class="mt-4">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <div class="card p-4 mb-4 shadow-sm border-0">
            <h5>Bangladesh Payment Methods</h5>
            <div class="row">
                <div class="col-md-4 mb-3"><label>bKash Number</label><input type="text" name="bkash_number" class="form-control" value="<?php echo e($settings['bkash_number']); ?>"></div>
                <div class="col-md-4 mb-3"><label>Nagad Number</label><input type="text" name="nagad_number" class="form-control" value="<?php echo e($settings['nagad_number']); ?>"></div>
                <div class="col-md-4 mb-3"><label>Rocket Number</label><input type="text" name="rocket_number" class="form-control" value="<?php echo e($settings['rocket_number']); ?>"></div>
            </div>
            <div class="mb-3"><label>Bank Details</label><textarea name="bank_details" class="form-control" rows="3"><?php echo e($settings['bank_details']); ?></textarea></div>
            <div class="mb-3">
                <label>Payment QR Code</label>
                <input type="file" name="payment_qr_code" class="form-control">
                <?php if($settings['payment_qr_code']): ?>
                    <div class="mt-2"><img src="../uploads/<?php echo $settings['payment_qr_code']; ?>" width="100"></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card p-4 mb-4 shadow-sm border-0">
            <h5>International Payment Methods</h5>
            <div class="row">
                <div class="col-md-6 mb-3"><label>PayPal Link</label><input type="text" name="paypal_link" class="form-control" value="<?php echo e($settings['paypal_link']); ?>"></div>
                <div class="col-md-6 mb-3"><label>Wise Link</label><input type="text" name="wise_link" class="form-control" value="<?php echo e($settings['wise_link']); ?>"></div>
            </div>
        </div>

        <div class="card p-4 mb-4 shadow-sm border-0">
            <h5>Payment Instructions</h5>
            <div class="mb-3"><textarea name="payment_instructions" class="form-control" rows="5"><?php echo e($settings['payment_instructions']); ?></textarea></div>
        </div>

        <button type="submit" class="btn btn-primary">Save Payment Settings</button>
    </form>
</main>

<?php require_once 'includes/footer.php'; ?>
