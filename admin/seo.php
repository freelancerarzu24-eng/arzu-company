<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$csrf_token = generate_csrf_token();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }
    foreach ($_POST['seo'] as $id => $data) {
        $stmt = $pdo->prepare("UPDATE seo_settings SET meta_title = ?, meta_description = ?, meta_keywords = ? WHERE id = ?");
        $stmt->execute([$data['title'], $data['desc'], $data['keys'], $id]);
    }
    redirect('seo.php', 'SEO Settings Updated');
}

$page_title = "SEO Settings";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$seo_pages = $pdo->query("SELECT * FROM seo_settings")->fetchAll();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <h2>SEO Settings</h2>
    <form method="POST" class="mt-4">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <?php foreach($seo_pages as $page): ?>
            <div class="card p-4 mb-4 shadow-sm border-0">
                <h5 class="text-capitalize"><?php echo e($page['page_name']); ?> Page SEO</h5>
                <div class="mb-3"><label>Meta Title</label><input type="text" name="seo[<?php echo $page['id']; ?>][title]" class="form-control" value="<?php echo e($page['meta_title']); ?>"></div>
                <div class="mb-3"><label>Meta Description</label><textarea name="seo[<?php echo $page['id']; ?>][desc]" class="form-control"><?php echo e($page['meta_description']); ?></textarea></div>
                <div class="mb-3"><label>Meta Keywords</label><input type="text" name="seo[<?php echo $page['id']; ?>][keys]" class="form-control" value="<?php echo e($page['meta_keywords']); ?>"></div>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Save SEO Settings</button>
    </form>
</main>

<?php require_once 'includes/footer.php'; ?>
