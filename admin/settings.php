<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$settings = $pdo->query("SELECT * FROM settings LIMIT 1")->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $website_name = $_POST['website_name'];
    $footer_content = $_POST['footer_content'];
    $contact_email = $_POST['contact_email'];
    $contact_phone = $_POST['contact_phone'];
    $whatsapp_number = $_POST['whatsapp_number'];
    $address = $_POST['address'];

    $logo = $settings['website_logo'];
    if (!empty($_FILES['website_logo']['name'])) {
        $logo = upload_image($_FILES['website_logo'], '../uploads/');
    }

    $favicon = $settings['favicon'];
    if (!empty($_FILES['favicon']['name'])) {
        $favicon = upload_image($_FILES['favicon'], '../uploads/');
    }

    $stmt = $pdo->prepare("UPDATE settings SET website_name = ?, footer_content = ?, contact_email = ?, contact_phone = ?, whatsapp_number = ?, address = ?, website_logo = ?, favicon = ? WHERE id = ?");
    $stmt->execute([$website_name, $footer_content, $contact_email, $contact_phone, $whatsapp_number, $address, $logo, $favicon, $settings['id']]);

    // Social Links
    foreach ($_POST['social'] as $platform => $url) {
        $stmt = $pdo->prepare("UPDATE social_links SET url = ? WHERE platform = ?");
        $stmt->execute([$url, $platform]);
    }

    header("Location: settings.php?msg=Settings Updated");
    exit();
}

$social_links = $pdo->query("SELECT * FROM social_links")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Website Settings | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2>Website Settings</h2>
        <form method="POST" enctype="multipart/form-data" class="mt-4">
            <div class="card p-4 mb-4">
                <h5>General Info</h5>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Website Name</label><input type="text" name="website_name" class="form-control" value="<?php echo $settings['website_name']; ?>"></div>
                    <div class="col-md-6 mb-3"><label>Contact Email</label><input type="email" name="contact_email" class="form-control" value="<?php echo $settings['contact_email']; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Website Logo</label><input type="file" name="website_logo" class="form-control"></div>
                    <div class="col-md-6 mb-3"><label>Favicon</label><input type="file" name="favicon" class="form-control"></div>
                </div>
                <div class="mb-3"><label>Footer Content</label><textarea name="footer_content" class="form-control"><?php echo $settings['footer_content']; ?></textarea></div>
            </div>

            <div class="card p-4 mb-4">
                <h5>Social Links</h5>
                <div class="row">
                    <?php foreach($social_links as $social): ?>
                        <div class="col-md-4 mb-3">
                            <label class="text-capitalize"><?php echo $social['platform']; ?></label>
                            <input type="text" name="social[<?php echo $social['platform']; ?>]" class="form-control" value="<?php echo $social['url']; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</body>
</html>
