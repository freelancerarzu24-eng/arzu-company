<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    die("Unauthorized");
}

$action = $_POST['action'] ?? '';

if ($action == 'add') {
    $title = $_POST['title'];
    $slug = slugify($title);
    $category = $_POST['category'];
    $content = $_POST['content'];
    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];

    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $image = upload_image($_FILES['image'], '../uploads/');
    }

    $stmt = $pdo->prepare("INSERT INTO blogs (title, slug, featured_image, content, category, meta_title, meta_description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$title, $slug, $image, $content, $category, $meta_title, $meta_description])) {
        echo "Blog post added successfully!";
    } else {
        echo "Error adding blog post.";
    }
}

if ($action == 'delete') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo "Blog post deleted successfully!";
    } else {
        echo "Error deleting blog post.";
    }
}
?>
