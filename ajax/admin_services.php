<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    die("Unauthorized");
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    die("CSRF token validation failed");
}

$action = $_POST['action'] ?? '';

if ($action == 'add') {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $slug = slugify($title);
    $short_description = $_POST['short_description'];
    $description = $_POST['description'];
    $basic_price = $_POST['basic_price'];
    $standard_price = $_POST['standard_price'] ?: null;
    $premium_price = $_POST['premium_price'] ?: null;
    $status = $_POST['status'];
    $featured = $_POST['featured'];

    $stmt = $pdo->prepare("INSERT INTO services (category_id, title, slug, short_description, description, basic_price, standard_price, premium_price, status, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$category_id, $title, $slug, $short_description, $description, $basic_price, $standard_price, $premium_price, $status, $featured])) {
        $service_id = $pdo->lastInsertId();

        // Handle Image Gallery
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $file = [
                    'name' => $_FILES['images']['name'][$key],
                    'tmp_name' => $_FILES['images']['tmp_name'][$key],
                    'size' => $_FILES['images']['size'][$key],
                    'error' => $_FILES['images']['error'][$key]
                ];
                $uploaded_image = upload_image($file, '../uploads/');
                if ($uploaded_image) {
                    $pdo->prepare("INSERT INTO service_images (service_id, image) VALUES (?, ?)")->execute([$service_id, $uploaded_image]);
                }
            }
        }
        echo "Service added successfully!";
    } else {
        echo "Error adding service.";
    }
}

if ($action == 'delete') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo "Service deleted successfully!";
    } else {
        echo "Error deleting service.";
    }
}
?>
