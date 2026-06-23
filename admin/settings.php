<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$settings = $pdo->query("SELECT * FROM settings LIMIT 1")->fetch();
$csrf_token = generate_csrf_token();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }

    $name = $_POST['website_name'];
    $email = $_POST['contact_email'];
    $phone = $_POST['contact_phone'];
    $wa = $_POST['whatsapp_number'];
    $address = $_POST['address'];
    $footer = $_POST['footer_content'];

    $logo = $settings['website_logo'];
    if (!empty($_FILES['website_logo']['name'])) {
        $logo = upload_image($_FILES['website_logo'], '../uploads/');
    }

    $fav = $settings['favicon'];
    if (!empty($_FILES['favicon']['name'])) {
        $fav = upload_image($_FILES['favicon'], '../uploads/');
    }

    $stmt = $pdo->prepare("UPDATE settings SET website_name = ?, website_logo = ?, favicon = ?, footer_content = ?, contact_email = ?, contact_phone = ?, whatsapp_number = ?, address = ? WHERE id = ?");
    $stmt->execute([$name, $logo, $fav, $footer, $email, $phone, $wa, $address, $settings['id']]);

    redirect('settings.php', 'Settings Updated');
}

$page_title = "Website Settings";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <h2>Website Settings</h2>
    <form method="POST" enctype="multipart/form-data" class="mt-4">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <div class="card p-4 mb-4 shadow-sm border-0">
            <div class="row">
                <div class="col-md-6 mb-3"><label>Website Name</label><input type="text" name="website_name" class="form-control" value="<?php echo e($settings['website_name']); ?>"></div>
                <div class="col-md-6 mb-3"><label>Contact Email</label><input type="email" name="contact_email" class="form-control" value="<?php echo e($settings['contact_email']); ?>"></div>
                <div class="col-md-6 mb-3"><label>Contact Phone</label><input type="text" name="contact_phone" class="form-control" value="<?php echo e($settings['contact_phone']); ?>"></div>
                <div class="col-md-6 mb-3"><label>WhatsApp Number</label><input type="text" name="whatsapp_number" class="form-control" value="<?php echo e($settings['whatsapp_number']); ?>"></div>
            </div>
            <div class="mb-3"><label>Address</label><textarea name="address" class="form-control"><?php echo e($settings['address']); ?></textarea></div>
            <div class="mb-3"><label>Footer Content</label><textarea name="footer_content" class="form-control"><?php echo e($settings['footer_content']); ?></textarea></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label>Website Logo</label><input type="file" name="website_logo" class="form-control"></div>
                <div class="col-md-6 mb-3"><label>Favicon</label><input type="file" name="favicon" class="form-control"></div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</main>

<?php require_once 'includes/footer.php'; ?>
