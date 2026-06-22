<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    die("Unauthorized");
}

$action = $_POST['action'] ?? '';

if ($action == 'add') {
    $name = $_POST['name'];
    $slug = slugify($name);
    $status = $_POST['status'];
    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];

    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $image = upload_image($_FILES['image'], '../uploads/');
    }

    $stmt = $pdo->prepare("INSERT INTO categories (name, slug, image, status, meta_title, meta_description) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $slug, $image, $status, $meta_title, $meta_description])) {
        echo "Category added successfully!";
    } else {
        echo "Error adding category.";
    }
}

if ($action == 'delete') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo "Category deleted successfully!";
    } else {
        echo "Error deleting category.";
    }
}
?>
