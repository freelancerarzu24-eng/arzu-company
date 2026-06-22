<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['seo'] as $id => $data) {
        $stmt = $pdo->prepare("UPDATE seo_settings SET meta_title = ?, meta_description = ?, meta_keywords = ? WHERE id = ?");
        $stmt->execute([$data['title'], $data['desc'], $data['keys'], $id]);
    }
    header("Location: seo.php?msg=SEO Updated");
    exit();
}

$seo_pages = $pdo->query("SELECT * FROM seo_settings")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SEO Settings | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2>SEO Settings</h2>
        <form method="POST" class="mt-4">
            <?php foreach($seo_pages as $page): ?>
                <div class="card p-4 mb-4">
                    <h5 class="text-capitalize"><?php echo $page['page_name']; ?> Page SEO</h5>
                    <div class="mb-3"><label>Meta Title</label><input type="text" name="seo[<?php echo $page['id']; ?>][title]" class="form-control" value="<?php echo $page['meta_title']; ?>"></div>
                    <div class="mb-3"><label>Meta Description</label><textarea name="seo[<?php echo $page['id']; ?>][desc]" class="form-control"><?php echo $page['meta_description']; ?></textarea></div>
                    <div class="mb-3"><label>Meta Keywords</label><input type="text" name="seo[<?php echo $page['id']; ?>][keys]" class="form-control" value="<?php echo $page['meta_keywords']; ?>"></div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Save SEO Settings</button>
        </form>
    </div>
</body>
</html>
